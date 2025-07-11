<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Outlet;
use App\Models\Table;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->boolean(70) ? User::factory() : null, // 70% chance to have a user
            'outlet_id' => Outlet::factory(),
            'table_id' => $this->faker->boolean(50) ? Table::factory() : null, // 50% chance to have a table
            'promotion_id' => $this->faker->boolean(30) ? Promotion::factory() : null, // 30% chance to have a promotion
            'guest_info' => $this->faker->boolean(30) ? json_encode([
                'name' => $this->faker->name,
                'phone' => $this->faker->phoneNumber,
                'email' => $this->faker->unique()->safeEmail,
            ]) : null,
            'total_amount' => $this->faker->randomFloat(2, 50000, 500000),
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'failed']),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
        ];
    }
}
