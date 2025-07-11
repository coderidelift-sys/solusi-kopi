<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'amount' => $this->faker->randomFloat(2, 10000, 500000),
            'payment_method' => $this->faker->randomElement(['cash', 'qris', 'bank_transfer']),
            'transaction_id' => $this->faker->uuid,
            'status' => $this->faker->randomElement(['completed', 'pending', 'failed']),
        ];
    }
}
