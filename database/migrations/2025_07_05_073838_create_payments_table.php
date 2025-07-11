<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_gateway_ref')->nullable(); // Reference from payment gateway
            $table->decimal('amount', 10, 2);
            $table->string('method'); // e.g., QRIS, cash
            $table->string('status')->default('pending'); // e.g., pending, paid, failed, refunded
            $table->text('qr_code_data')->nullable(); // For QRIS payments
            $table->string('external_id')->nullable(); // Xendit external_id
            $table->text('qr_string')->nullable(); // Xendit qr_string
            $table->string('image_url')->nullable(); // Xendit qr_url
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
