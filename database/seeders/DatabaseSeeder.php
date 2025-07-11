<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder peran dan user
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            OutletSeeder::class,
            CategorySeeder::class,
            TableSeeder::class,
            PromotionSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
        ]);

        session()->flush();

        // \App\Models\User::factory(10)->create(); // Ini bisa dihapus jika UserSeeder sudah membuat user

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
