<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\repair_steps;

class repair_stepscontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repair_steps = repair_steps::all();
        return response()->json([
            'status' => true,
            'data' => $repair_steps,
        ]);
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
