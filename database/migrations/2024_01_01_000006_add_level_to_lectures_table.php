<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->enum('level', ['all', 'first', 'second', 'third'])->default('all')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
