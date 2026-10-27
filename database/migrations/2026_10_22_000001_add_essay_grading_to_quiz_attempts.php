<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            // {question_id: points_awarded} for essay questions, filled in by the admin
            $table->json('manual_scores')->nullable()->after('answers');
            $table->boolean('needs_grading')->default(false)->after('passed');
            $table->timestamp('graded_at')->nullable()->after('needs_grading');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn(['manual_scores', 'needs_grading', 'graded_at']);
        });
    }
};
