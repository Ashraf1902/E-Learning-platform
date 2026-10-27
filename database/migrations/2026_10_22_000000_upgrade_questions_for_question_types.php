<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('type', 20)->default('mcq')->after('quiz_id');
            $table->json('options')->nullable()->after('option_d');
            $table->json('data')->nullable()->after('options');
            $table->unsignedInteger('position')->default(0)->after('points');
        });

        // correct_answer was enum('a','b','c','d') — now it must also hold
        // 'true'/'false', free-text (fill-in-the-blank) answers, and be
        // nullable for essay/matching/ordering questions.
        // NOTE: the legacy option_a..option_d columns stay in place untouched
        // so existing production questions keep working as-is.
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE questions MODIFY correct_answer VARCHAR(1000) NULL');
        } else {
            Schema::table('questions', function (Blueprint $table) {
                $table->string('correct_answer', 1000)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE questions MODIFY correct_answer ENUM('a','b','c','d') NOT NULL");
        }

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['type', 'options', 'data', 'position']);
        });
    }
};
