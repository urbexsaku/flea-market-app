<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => '鈴木一郎',
            'email' => 'ichiro@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '山田太郎',
            'email' => 'taro@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '佐藤花子',
            'email' => 'hanako@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
