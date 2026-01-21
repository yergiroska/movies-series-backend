<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Yergiroska',
            'email' => 'yergiroska66@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
