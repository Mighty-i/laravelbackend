<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\insurance_company;

class insurance_companycontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = insurance_company::all();

        $response = $companies->map(function ($companie) {
            return [
                'Company_ID' => $companie->Company_ID,
                'Name' => $companie->Name,
                'Address' => $companie->Address,
                'PhoneNumber' => $companie->PhoneNumber,
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $response,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
            'Address' => 'required|string|max:255',
            'PhoneNumber' => 'required|string|max:15',
        ]);

        $company = insurance_company::create($request->all());

        return response()->json([
            'message' => 'Insurance company created successfully',
            'data' => $company
        ], 201);
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
        $company = insurance_company::findOrFail($id);
        $request->validate([
            'Name' => 'string|max:255',
            'Address' => 'string|max:255',
            'PhoneNumber' => 'string|max:15',
        ]);

        $company->update($request->all());

        return response()->json([
            'message' => 'Insurance company updated successfully',
            'data' => $company
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        insurance_company::destroy($id);

        return response()->json([
            'message' => 'Insurance company deleted successfully'
        ]);
    }
}
