<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('lecture_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('pass_score')->default(60); // percentage
            $table->enum('status', ['published', 'draft'])->default('draft');
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c')->nullable();
            $table->string('option_d')->nullable();
            $table->enum('correct_answer', ['a', 'b', 'c', 'd']);
            $table->integer('points')->default(1);
            $table->timestamps();
        });

        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->json('answers'); // {"question_id": "answer"}
            $table->integer('score')->default(0);
            $table->integer('total_points')->default(0);
            $table->boolean('passed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quizzes');
    }
};
