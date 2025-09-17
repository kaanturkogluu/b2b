<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@motojetservis.com',
            'password' => Hash::make('admin123'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Create Staff User
        User::create([
            'name' => 'Personel User',
            'username' => 'personel',
            'email' => 'personel@motojetservis.com',
            'password' => Hash::make('personel123'),
            'role' => User::ROLE_STAFF,
        ]);

        // Create Additional Staff User
        User::create([
            'name' => 'Mehmet Yılmaz',
            'username' => 'mehmet',
            'email' => 'mehmet@motojetservis.com',
            'password' => Hash::make('mehmet123'),
            'role' => User::ROLE_STAFF,
        ]);

        // Create Additional Staff User
        User::create([
            'name' => 'Ayşe Demir',
            'username' => 'ayse',
            'email' => 'ayse@motojetservis.com',
            'password' => Hash::make('ayse123'),
            'role' => User::ROLE_STAFF,
        ]);
    }
}
