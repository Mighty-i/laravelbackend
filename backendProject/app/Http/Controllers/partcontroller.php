<?php

namespace App\Http\Controllers;

use App\Models\part;
use Illuminate\Http\Request;

class partcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $part = part::with(['vehicles.car_brands'])
        ->get()
        ->map(function ($part) {
            return [
                'Part_ID' => $part->Part_ID,
                'Category_ID' => $part->Category_ID,
                'Vehicle_ID' => $part->Vehicle_ID,
                'Brand' => $part->vehicles->car_brands->Brand ?? null, // แสดงข้อมูล Brand
                'Model' => $part->vehicles->Model ?? null, // แสดงข้อมูล Model
                'Year' => $part->vehicles->Year ?? null, // แสดงข้อมูล Year
                'Name' => $part->Name,
                'Description' => $part->Description,
                'Quantity' => $part->Quantity,
                'PricePerUnit' => $part->PricePerUnit,
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $part,
        ]);
    }

    public function index2()
    {
        $part = part::with(['vehicles.car_brands','category_part'])
        ->get()
        ->map(function ($part) {
            return [
                'Part_ID' => $part->Part_ID,
                'Category_ID' => $part->Category_ID,
                'CategoryName' => $part->category_part->CategoryName ?? null,
                'Vehicle_ID' => $part->Vehicle_ID,
                'Brand' => $part->vehicles->car_brands->Brand ?? null, // แสดงข้อมูล Brand
                'Model' => $part->vehicles->Model ?? null, // แสดงข้อมูล Model
                'Year' => $part->vehicles->Year ?? null, // แสดงข้อมูล Year
                'Name' => $part->Name,
                'Description' => $part->Description,
                'Quantity' => $part->Quantity,
                'PricePerUnit' => $part->PricePerUnit,
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $part,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Category_ID' => 'required|integer',
            'Vehicle_ID' => 'required|integer',
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string',
            'Quantity' => 'required|integer',
            'PricePerUnit' => 'required|numeric'
        ]);

        $part = part::create($request->all());

        return response()->json([
            'message' => 'Part created successfully',
            'data' => $part
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $part = part::findOrFail($id);

        $request->validate([
            'Category_ID' => 'integer',
            'Vehicle_ID' => 'integer',
            'Name' => 'string|max:255',
            'Description' => 'nullable|string',
            'Quantity' => 'integer',
            'PricePerUnit' => 'numeric'
        ]);

        $part->update($request->all());

        return response()->json([
            'message' => 'Part updated successfully',
            'data' => $part
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        part::destroy($id);

        return response()->json([
            'message' => 'Part deleted successfully'
        ]);
    }
}
