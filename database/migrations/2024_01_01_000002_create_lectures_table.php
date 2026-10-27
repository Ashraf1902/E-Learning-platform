<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->string('category')->default('General');
            $table->enum('status', ['published', 'draft'])->default('draft');
            $table->integer('duration_minutes')->nullable(); // e.g. 45
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        
        Schema::create('lecture_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
