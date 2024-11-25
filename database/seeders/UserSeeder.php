<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'ADMIN',
            'username' => 'admin',
            'email' => 'pilkada@bonebolangokab.go.id',
            'password' => Hash::make('123456'),
            'phone_number' => 321321313,
        ]);
    }
}
