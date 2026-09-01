<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'melysaputrifebriani.82@gmail.com'],
            [
                'name' => 'Administrator School Marketplace',
                'password' => Hash::make('Admin12345!'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
