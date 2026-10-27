<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // e.g. "اشتراك شهري"
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);          // e.g. 150.00
            $table->enum('duration', ['month', 'term', 'year'])->default('month');
            $table->integer('duration_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 8, 2);
            $table->enum('method', ['fawry', 'vodafone_cash'])->default('fawry');
            $table->string('reference_number')->nullable();   // Fawry ref / Vodafone transaction id
            $table->string('phone_number')->nullable();       // number used to pay (Vodafone Cash)
            $table->enum('status', ['pending', 'paid', 'failed', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();           // admin can add note when approving/rejecting
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};