<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car_brands;

class car_brandscontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ดึงข้อมูล car_brands พร้อม vehicles ที่ Brand_ID ตรงกัน
        $carBrands = car_brands::with('vehicles')->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ต้องการ
        $response = $carBrands->map(function ($carBrand) {
            // Group vehicles by Model
            $models = $carBrand->vehicles->groupBy('Model')->map(function ($vehicles, $model) {
                return [
                    'Model' => $model,
                    'Vehicles' => $vehicles->map(function ($vehicle) {
                        return [
                            'Vehicle_ID' => $vehicle->Vehicle_ID,
                            'Year' => $vehicle->Year,
                        ];
                    })->all(), // แสดงข้อมูล Vehicle_ID และ Year
                ];
            });

            return [
                'Brand_ID' => $carBrand->Brand_ID,
                'Brand' => $carBrand->Brand,
                'Models' => $models->values()->all() // ส่งค่าเป็น array
            ];
        });

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
