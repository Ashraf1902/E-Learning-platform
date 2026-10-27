<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            // Store just the video ID, not the full youtube/vimeo URL,
            // so we never expose the raw source link to the browser/student.
            $table->string('video_provider')->default('youtube')->after('video_url'); // youtube | vimeo
            $table->string('video_id')->nullable()->after('video_provider');
        });
    }

    public function down(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->dropColumn(['video_provider', 'video_id']);
        });
    }
};