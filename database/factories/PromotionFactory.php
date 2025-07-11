<?php

namespace Database\Factories;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PromotionFactory extends Factory
{
    /**
     * The name of thefactory's corresponding model.
     *
     * @var string
     */
    protected $model = Promotion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['percentage', 'fixed']);
        $value = ($type === 'percentage') ? $this->faker->numberBetween(5, 50) : $this->faker->numberBetween(1000, 10000);

        return [
            'name' => $this->faker->unique()->sentence(2),
            'code' => Str::upper($this->faker->unique()->word()),
            'description' => $this->faker->sentence(),
            'discount_type' => $type,
            'discount_value' => $value,
            'min_order_amount' => $this->faker->numberBetween(10000, 50000),
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 week'),
            'end_date' => $this->faker->dateTimeBetween('+1 week', '+2 months'),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
