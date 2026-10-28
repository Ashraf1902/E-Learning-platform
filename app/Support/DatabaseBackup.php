<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class DatabaseBackup
{
    /**
     * Write a gzipped SQL dump of the current database connection.
     */
    public function writeTo(string $path): void
    {
        $handle = gzopen($path, 'wb9');

        if ($handle === false) {
            throw new RuntimeException('Unable to open the backup file for writing.');
        }

        try {
            $this->writeDump($handle);
        } finally {
            gzclose($handle);
        }
    }

    /**
     * @param resource $handle
     */
    private function writeDump($handle): void
    {
        $pdo = DB::connection()->getPdo();
        $database = DB::connection()->getDatabaseName();

        $this->write($handle, "-- Database backup\n");
        $this->write($handle, '-- Database: ' . $database . "\n");
        $this->write($handle, '-- Generated: ' . date('c') . "\n\n");
        $this->write($handle, "SET NAMES utf8mb4;\n");
        $this->write($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

        $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $quotedTable = '`' . str_replace('`', '``', $table) . '`';
            $create = $pdo->query('SHOW CREATE TABLE ' . $quotedTable)->fetch(\PDO::FETCH_NUM);

            if (!is_array($create) || !isset($create[1])) {
                continue;
            }

            $this->write($handle, 'DROP TABLE IF EXISTS ' . $quotedTable . ";\n");
            $this->write($handle, rtrim($create[1], ';') . ";\n\n");

            $rows = $pdo->query('SELECT * FROM ' . $quotedTable);
            $values = [];

            while ($row = $rows->fetch(\PDO::FETCH_ASSOC)) {
                $values[] = '(' . implode(', ', array_map(
                    fn ($value) => $value === null ? 'NULL' : $pdo->quote((string) $value),
                    $row
                )) . ')';

                if (count($values) >= 50) {
                    $this->writeInserts($handle, $quotedTable, $values);
                    $values = [];
                }
            }

            if ($values !== []) {
                $this->writeInserts($handle, $quotedTable, $values);
            }
        }

        $this->write($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
    }

    /**
     * @param resource $handle
     * @param array<int, string> $values
     */
    private function writeInserts($handle, string $table, array $values): void
    {
        $this->write(
            $handle,
            'INSERT INTO ' . $table . " VALUES\n" . implode(",\n", $values) . ";\n"
        );
    }

    /**
     * @param resource $handle
     */
    private function write($handle, string $contents): void
    {
        if (gzwrite($handle, $contents) === false) {
            throw new RuntimeException('Unable to write to the database backup.');
        }
    }
}
