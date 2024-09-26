<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category_part')->insert([
            ['Category_ID' => 1, 'CategoryName' => 'ส่วนประกอบเครื่องยนต์'],
            ['Category_ID' => 2, 'CategoryName' => 'ระบบไฟฟ้า'],
            ['Category_ID' => 3, 'CategoryName' => 'ชิ้นส่วนตัวถัง'],
            ['Category_ID' => 4, 'CategoryName' => 'ระบบกันสะเทือน'],
            ['Category_ID' => 5, 'CategoryName' => 'ระบบท่อไอเสีย'],
        ]);
    }
}
