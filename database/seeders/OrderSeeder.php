<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Outlet;
use App\Models\Table;
use App\Models\Promotion;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data
        $users = User::all();
        $outlets = Outlet::all();
        $tables = Table::all();
        $promotions = Promotion::all();

        if ($outlets->isEmpty()) {
            $this->command->info('No outlets found. Creating sample outlet...');
            $outlet = Outlet::factory()->create();
            $outlets = collect([$outlet]);
        }

        if ($tables->isEmpty()) {
            $this->command->info('No tables found. Creating sample tables...');
            foreach ($outlets as $outlet) {
                for ($i = 1; $i <= 5; $i++) {
                    Table::create([
                        'outlet_id' => $outlet->id,
                        'table_number' => $i,
                        'capacity' => rand(2, 8),
                        'status' => 'available'
                    ]);
                }
            }
            $tables = Table::all();
        }

        if ($users->isEmpty()) {
            $this->command->info('No users found. Creating sample users...');
            User::factory(5)->create();
            $users = User::all();
        }

        if ($promotions->isEmpty()) {
            $this->command->info('No promotions found. Creating sample promotions...');
            Promotion::factory(3)->create();
            $promotions = Promotion::all();
        }

        // Create sample orders with realistic status distribution
        $this->command->info('Creating sample orders...');

        // Create orders for the last 30 days
        for ($i = 0; $i < 50; $i++) {
            $orderDate = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $user = $users->random();
            $outlet = $outlets->random();
            $table = $tables->where('outlet_id', $outlet->id)->random();
            $promotion = $promotions->random();

            // Determine realistic status based on order date
            $status = $this->getRealisticStatus($orderDate);

            // Determine payment status
            $paymentStatus = $status === Order::STATUS_PENDING ? 'pending' : 'paid';

            // Calculate completion time based on status
            $completedAt = null;
            if (in_array($status, [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED])) {
                $completedAt = $orderDate->copy()->addMinutes(rand(15, 120));
            }

            $order = Order::create([
                'user_id' => $user->id,
                'outlet_id' => $outlet->id,
                'table_id' => $table->id,
                'promotion_id' => rand(0, 1) ? $promotion->id : null, // 50% chance of having promotion
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'guest_info' => null,
                'total_amount' => rand(25000, 150000),
                'subtotal' => rand(20000, 120000),
                'additional_fee' => rand(1000, 5000),
                'other_fee' => rand(1000, 3000),
                'discount_amount' => rand(0, 10000),
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => rand(0, 1) ? 'QRIS' : 'cash',
                'note' => rand(0, 1) ? 'Sample order note' : null,
                'ordered_at' => $orderDate,
                'completed_at' => $completedAt,
            ]);

            // Update table status based on order status
            if (in_array($status, [Order::STATUS_PREPARING, Order::STATUS_READY, Order::STATUS_SERVED])) {
                $table->update(['status' => 'occupied']);
            }
        }

        $this->command->info('Sample orders created successfully!');
    }

    /**
     * Get realistic status based on order date
     */
    private function getRealisticStatus($orderDate)
    {
        $now = Carbon::now();
        $hoursDiff = $now->diffInHours($orderDate);

        // Orders from today
        if ($hoursDiff <= 24) {
            $rand = rand(1, 100);
            if ($rand <= 20) return Order::STATUS_PENDING;
            if ($rand <= 40) return Order::STATUS_PREPARING;
            if ($rand <= 60) return Order::STATUS_READY;
            if ($rand <= 80) return Order::STATUS_SERVED;
            return Order::STATUS_COMPLETED;
        }

        // Orders from yesterday
        if ($hoursDiff <= 48) {
            $rand = rand(1, 100);
            if ($rand <= 10) return Order::STATUS_PENDING;
            if ($rand <= 20) return Order::STATUS_PREPARING;
            if ($rand <= 30) return Order::STATUS_READY;
            if ($rand <= 40) return Order::STATUS_SERVED;
            return Order::STATUS_COMPLETED;
        }

        // Older orders - mostly completed
        $rand = rand(1, 100);
        if ($rand <= 5) return Order::STATUS_CANCELLED;
        return Order::STATUS_COMPLETED;
    }
}
