<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('enrolled_at')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Pivot: student can be enrolled in many lectures
        // Schema::create('lecture_student', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('lecture_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('student_id')->constrained()->onDelete('cascade');
        //     $table->boolean('completed')->default(false);
        //     $table->timestamps();
        // });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecture_student');
        Schema::dropIfExists('students');
    }
};
