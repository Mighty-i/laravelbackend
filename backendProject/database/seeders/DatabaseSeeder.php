<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // DB::table('pusers')->truncate();
        $this->call([
            // RolesTableSeeder::class,
            // RepairStepsTableSeeder::class,
            // PusersTableSeeder::class,
            // QuotationsTableSeeder::class,
            RepairProcessTableSeeder::class,
            // CategorySeeder::class,
            // PartSeeder::class,
        ]);
    }
}
