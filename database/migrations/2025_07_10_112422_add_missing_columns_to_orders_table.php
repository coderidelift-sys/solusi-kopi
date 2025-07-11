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
        Schema::table('orders', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('orders', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }

            if (!Schema::hasColumn('orders', 'note')) {
                $table->text('note')->nullable();
            }

            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('orders', 'other_fee')) {
                $table->decimal('other_fee', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('orders', 'additional_fee')) {
                $table->decimal('additional_fee', 10, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'completed_at',
                'note',
                'discount_amount',
                'subtotal',
                'other_fee',
                'additional_fee'
            ]);
        });
    }
};
