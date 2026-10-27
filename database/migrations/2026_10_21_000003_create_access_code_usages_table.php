<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('access_code_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('access_code_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['access_code_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_code_usages');
    }
};
