<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('parts')->insert([
            ['Part_ID' => 1, 'Category_ID' => 1, 'Name' => 'หัวเทียน', 'Description' => 'หัวเทียนประสิทธิภาพสูง', 'Quantity' => 50, 'PricePerUnit' => 150],
            ['Part_ID' => 2, 'Category_ID' => 2, 'Name' => 'แบตเตอรี่', 'Description' => 'แบตเตอรี่รถยนต์ 12V', 'Quantity' => 20, 'PricePerUnit' => 3000],
            ['Part_ID' => 3, 'Category_ID' => 3, 'Name' => 'กันชน', 'Description' => 'กันชนหน้า', 'Quantity' => 15, 'PricePerUnit' => 5000],
            ['Part_ID' => 4, 'Category_ID' => 4, 'Name' => 'โช้คอัพ', 'Description' => 'โช้คอัพระบบไฮดรอลิก', 'Quantity' => 25, 'PricePerUnit' => 1200],
            ['Part_ID' => 5, 'Category_ID' => 5, 'Name' => 'หม้อพักไอเสีย', 'Description' => 'หม้อพักไอเสียสแตนเลส', 'Quantity' => 30, 'PricePerUnit' => 2500],
            ['Part_ID' => 6, 'Category_ID' => 1, 'Name' => 'สายพานไทม์มิ่ง', 'Description' => 'สายพานไทม์มิ่งทนทานสำหรับเครื่องยนต์', 'Quantity' => 40, 'PricePerUnit' => 800],
            ['Part_ID' => 7, 'Category_ID' => 2, 'Name' => 'อัลเตอร์เนเตอร์', 'Description' => 'อัลเตอร์เนเตอร์ประสิทธิภาพสูงสำหรับรถยนต์', 'Quantity' => 10, 'PricePerUnit' => 4500],
            ['Part_ID' => 8, 'Category_ID' => 3, 'Name' => 'บังโคลน', 'Description' => 'บังโคลนหลัง', 'Quantity' => 20, 'PricePerUnit' => 4000],
            ['Part_ID' => 9, 'Category_ID' => 4, 'Name' => 'คอยล์สปริง', 'Description' => 'คอยล์สปริงรับน้ำหนักสูง', 'Quantity' => 35, 'PricePerUnit' => 700],
            ['Part_ID' => 10, 'Category_ID' => 5, 'Name' => 'แคตาไลติกคอนเวอร์เตอร์', 'Description' => 'แคตาไลติกคอนเวอร์เตอร์สำหรับควบคุมการปล่อยไอเสีย', 'Quantity' => 15, 'PricePerUnit' => 6000],
        ]);
    }
}
