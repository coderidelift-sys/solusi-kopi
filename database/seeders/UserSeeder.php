<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan peran sudah ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $cashierRole = Role::firstOrCreate(['name' => 'kasir']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Buat user admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin Solusi Kopi',
                'password' => Hash::make('password'), // Ganti dengan password yang kuat
                'phone' => '081234567890',
            ]
        );
        $admin->assignRole($adminRole);

        // Buat user kasir
        $cashier = User::firstOrCreate(
            ['email' => 'kasir@mail.com'],
            [
                'name' => 'Kasir Solusi Kopi',
                'password' => Hash::make('password'), // Ganti dengan password yang kuat
                'phone' => '089876543210',
            ]
        );
        $cashier->assignRole($cashierRole);

        // Buat user biasa/pelanggan
        $regularUser = User::firstOrCreate(
            ['email' => 'user@mail.com'],
            [
                'name' => 'Pelanggan Biasa',
                'password' => Hash::make('password'), // Ganti dengan password yang kuat
                'phone' => '085000000000',
            ]
        );
        $regularUser->assignRole($userRole);

        // Tambahkan user lain jika diperlukan
        User::factory(10)->create()->each(function ($user) use ($userRole) {
            $user->assignRole($userRole);
        });
    }
}
