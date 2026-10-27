<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lecture_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('file_path');
            $table->string('file_type'); // pdf, pptx
            $table->unsignedBigInteger('file_size')->nullable(); // bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecture_materials');
    }
};