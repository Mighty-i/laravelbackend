<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PusersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('pusers')->truncate();
        DB::table('pusers')->insert([
            [
                'User_ID' => 1,
                'username' => 'user1',
                'password' => Hash::make('1234'),
                'email' => 'user1@example.com',
                'remember_token' => Str::random(10),
                'name' => 'John Doe',
                'Role_ID' => 1
            ],
            [
                'User_ID' => 2,
                'username' => 'user2',
                'password' => Hash::make('1234'),
                'email' => 'user2@example.com',
                'remember_token' => Str::random(10),
                'name' => 'Jane Smith',
                'Role_ID' => 2
            ],
            [
                'User_ID' => 3,
                'username' => 'user3',
                'password' => Hash::make('1234'),
                'email' => 'user3@example.com',
                'remember_token' => Str::random(10),
                'name' => 'Jim Beam',
                'Role_ID' => 3
            ],
        ]);
    }
}
