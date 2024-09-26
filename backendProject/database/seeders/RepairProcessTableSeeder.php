<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepairProcessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('repair_processes')->insert([
            ['Process_ID' => 1, 'Quotation_ID' => 1, 'Step_ID' => 1, 'licenseplate' => 'ABC123', 'Description' => 'Initial inspection', 'Status' => 'In Progress'],
            ['Process_ID' => 2, 'Quotation_ID' => 1, 'Step_ID' => 2, 'licenseplate' => 'XYZ456', 'Description' => 'Repairing windshield', 'Status' => 'Completed'],
            ['Process_ID' => 3, 'Quotation_ID' => 1, 'Step_ID' => 3, 'licenseplate' => 'ABC123', 'Description' => 'Initial inspection', 'Status' => 'In Progress'],
            ['Process_ID' => 4, 'Quotation_ID' => 2, 'Step_ID' => 1, 'licenseplate' => 'XYZ456', 'Description' => 'Repairing windshield', 'Status' => 'Completed'],
            ['Process_ID' => 5, 'Quotation_ID' => 2, 'Step_ID' => 2, 'licenseplate' => 'ABC123', 'Description' => 'Initial inspection', 'Status' => 'In Progress'],
            
        ]);
    }
}
