<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->info('No products found. Creating sample products...');
            $categories = Category::all();
            if ($categories->isEmpty()) {
                $this->command->info('No categories found. Creating sample categories...');
                Category::factory(5)->create();
                $categories = Category::all();
            }

            foreach ($categories as $category) {
                Product::factory(3)->create([
                    'category_id' => $category->id,
                    'outlet_id' => $category->outlet_id
                ]);
            }
            $products = Product::all();
        }

        if ($orders->isEmpty()) {
            $this->command->info('No orders found. Please run OrderSeeder first.');
            return;
        }

        // Create order items for each order
        foreach ($orders as $order) {
            // Each order will have 1-4 items
            $itemCount = rand(1, 4);
            $orderProducts = $products->where('outlet_id', $order->outlet_id)->random($itemCount);

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $priceAtOrder = $product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price_at_order' => $priceAtOrder,
                    'note' => rand(0, 1) ? 'Sample order item note' : null,
                ]);
            }
        }

        $this->command->info('Created order items for all orders.');
    }
}
