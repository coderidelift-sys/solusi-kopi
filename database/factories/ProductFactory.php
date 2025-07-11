<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Outlet;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'outlet_id' => 1,
            'category_id' => 1,
            'name' => $this->faker->unique()->word() . ' Product',
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 1000, 2000),
            'image_url' => null, // Will handle image later if needed or keep as null for dummy
            'is_available' => $this->faker->boolean,
        ];
    }
}
