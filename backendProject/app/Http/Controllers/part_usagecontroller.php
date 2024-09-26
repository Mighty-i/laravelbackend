<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\part_usage;
use App\Models\part;

class part_usagecontroller extends Controller
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
        $validatedData = $request->validate([
            'Process_ID' => 'required|exists:repair_processes,Process_ID',
            'Part_ID' => 'required|exists:parts,Part_ID',
            'Quantity' => 'required',
        ]);

        $partUsage = part_usage::create($validatedData);

        $part = part::find($validatedData['Part_ID']);

        if ($part) {
            // Update the Quantity in the parts table by subtracting the used quantity
            $part->Quantity -= $validatedData['Quantity'];

            // Save the updated part quantity
            $part->save();
        }

        return response()->json([
            'message' => 'Part usage created successfully',
            'data' => $partUsage
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function getPartsByProcessId(Request $request)
    {
        $request->validate([
            'Process_ID' => 'required|integer',
        ]);

        $processId = $request->input('Process_ID');

        $parts = part_usage::where('Process_ID', $processId)
            ->join('parts', 'part_usages.Part_ID', '=', 'parts.Part_ID')
            ->select('part_usages.*', 'parts.Name')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $parts,
        ]);
    }
}
