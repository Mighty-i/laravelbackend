<?php

namespace App\Http\Controllers;

use App\Models\vehicles;
use App\Models\car_brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class vehiclescontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    public function updateCar(Request $request)
    {
        // รับข้อมูลจาก request
        $brandId = $request->input('brandIds');
        $vehicleId = $request->input('vehicleIds');
        $newBrand = $request->input('brand');
        $newModel = $request->input('model');
        $newYear = $request->input('year');

        // หา car_brand ด้วย Brand_ID
        $brand = car_brands::find($brandId);
        if (!$brand) {
            return response()->json(['error' => 'Brand not found'], 404);
        }

        // ตรวจสอบว่าชื่อแบรนด์มีการเปลี่ยนแปลงหรือไม่
        if ($brand->Brand !== $newBrand) {
            // อัปเดตชื่อแบรนด์ใหม่
            $brand->Brand = $newBrand;
            $brand->save();
        }

        // หา vehicle ด้วย Vehicle_ID
        $vehicle = vehicles::where('Vehicle_ID', $vehicleId)->first();
        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        // ตรวจสอบว่า Model หรือ Year มีการเปลี่ยนแปลงหรือไม่
        if ($vehicle->Model !== $newModel || $vehicle->Year !== $newYear) {
            // อัปเดต Model หรือ Year ใหม่
            $vehicle->Model = $newModel;
            $vehicle->Year = $newYear;
            $vehicle->save();
        }

        return response()->json(['success' => 'Car details updated successfully']);
    }

    public function addNewCar(Request $request)
    {
        // Create a new brand
        $brand = new car_brands();
        $brand->Brand = $request->input('brand');
        $brand->save();

        // Create a new vehicle with the new brand ID
        $vehicle = new vehicles();
        $vehicle->Brand_ID = $brand->Brand_ID;
        $vehicle->Model = $request->input('model');
        $vehicle->Year = $request->input('year');
        $vehicle->save();

        return response()->json(['success' => 'New car added successfully']);
    }

    public function addNewModel(Request $request)
    {
        // Create a new vehicle
        $vehicle = new vehicles();
        $vehicle->Brand_ID = $request->input('brandId');
        $vehicle->Model = $request->input('model');
        $vehicle->Year = $request->input('year');
        $vehicle->save();

        return response()->json(['success' => 'New model added successfully']);
    }
}
