<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuotationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('quotations')->insert([
            ['Quotation_ID' => 1, 'User_ID' => 1, 'QuotationDate' => now(), 'TotalAmount' => 1000.00, 'Status' => 'Pending', 'color' => 'Red', 'licenseplate' => 'ABC123', 'damageassessment' => 'Minor', 'problemdetails' => 'Scratch on door', 'admissionDate' => now(), 'PaymentMethod' => 'Credit Card', 'PaymentDate' => now(), 'ImageSlie' => 'image.jpg'],
            ['Quotation_ID' => 2, 'User_ID' => 2, 'QuotationDate' => now(), 'TotalAmount' => 1500.00, 'Status' => 'Completed', 'color' => 'Blue', 'licenseplate' => 'XYZ456', 'damageassessment' => 'Major', 'problemdetails' => 'Broken windshield', 'admissionDate' => now(), 'PaymentMethod' => 'Cash', 'PaymentDate' => now(), 'ImageSlie' => 'image.jpg'],
            ['Quotation_ID' => 3, 'User_ID' => 3, 'QuotationDate' => now(), 'TotalAmount' => 1000.00, 'Status' => 'Pending', 'color' => 'Red', 'licenseplate' => 'ABC123', 'damageassessment' => 'Minor', 'problemdetails' => 'Scratch on door', 'admissionDate' => now(), 'PaymentMethod' => 'Credit Card', 'PaymentDate' => now(), 'ImageSlie' => 'image.jpg'],
            
        ]);
    }
}
