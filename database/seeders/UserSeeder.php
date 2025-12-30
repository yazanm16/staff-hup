<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
        ['email' => 'admin@gmail.com'],
        [
            'name' => 'admin',
            'password' => bcrypt('123456'),
        ]
    );

    if (! $admin->hasRole('admin')) {
        $admin->assignRole('admin');
    }
    }
}
