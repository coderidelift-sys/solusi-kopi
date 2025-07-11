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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Nullable for guest checkout
            $table->foreignId('outlet_id')->constrained()->onDelete('cascade');
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->string('order_type')->default('dine-in'); // e.g., dine-in, takeaway, delivery
            $table->string('status')->default('pending'); // e.g., pending, preparing, completed, cancelled
            $table->decimal('total_amount', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('additional_fee', 10, 2)->default(0); // e.g., service fee
            $table->decimal('other_fee', 10, 2)->default(0); // e.g., tax
            $table->string('payment_method')->nullable(); // e.g., QRIS, cash
            $table->string('payment_status')->default('pending'); // e.g., pending, paid, failed, refunded
            $table->text('note')->nullable();
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('promotion_id')->nullable()->constrained()->onDelete('set null');
            $table->json('guest_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['promotion_id']);
            $table->dropColumn('promotion_id');
            $table->dropColumn('guest_info');
        });
    }
};
