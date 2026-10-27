<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            // Hidden from students until the admin turns it on.
            $table->boolean('show_leaderboard')->default(false)->after('level');
            // Max students shown on this lecture's leaderboard (NULL = everyone).
            $table->unsignedSmallInteger('leaderboard_limit')->nullable()->after('show_leaderboard');
        });
    }

    public function down(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->dropColumn(['show_leaderboard', 'leaderboard_limit']);
        });
    }
};
