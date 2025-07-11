<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Outlet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'outlet_id' => 1,
            'name' => $this->faker->unique()->word() . ' Category',
            'description' => $this->faker->sentence,
            'image' => null, // Will handle image later if needed or keep as null for dummy
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
