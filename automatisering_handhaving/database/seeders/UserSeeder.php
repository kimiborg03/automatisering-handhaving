<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Jan Jansen',
            'username' => 'jjansen',
            'email' => 'jan@example.com',
            'password' => Hash::make('wachtwoord123'),
            'group_id' => 1,
            'role' => 'admin',
            'access' => true,
        ]);
    }
}
