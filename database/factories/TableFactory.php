<?php

namespace Database\Factories;

use App\Models\Table;
use App\Models\Outlet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Table::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'outlet_id' => 1,
            'table_number' => $this->faker->unique()->numberBetween(1, 100),
            'table_code' => $this->faker->unique()->numberBetween(1, 999),
            'capacity' => $this->faker->numberBetween(2, 10),
            'qr_code_url' => null, // Will be generated later by controller or seeder if needed
            'status' => $this->faker->randomElement(['available', 'occupied', 'unavailable']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Table $table) {
            // Ensure unique table_number per outlet_id by re-generating if needed
            // This might not be strictly necessary if database unique constraint handles it,
            // but good for ensuring factory generates valid data.
            $uniqueTableNumber = false;
            while (!$uniqueTableNumber) {
                $existingTable = Table::where('outlet_id', $table->outlet_id)
                                    ->where('table_number', $table->table_number)
                                    ->where('id', '!=', $table->id)
                                    ->first();
                if ($existingTable) {
                    $table->table_number = $this->faker->unique()->numberBetween(1, 100);
                    $table->save();
                } else {
                    $uniqueTableNumber = true;
                }
            }
        });
    }
}
