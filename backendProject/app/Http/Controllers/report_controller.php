<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\report_mechanic_times;

class report_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repairStatuses = report_mechanic_times::join('pusers', 'view_report_mechanic_times.User_ID', '=', 'pusers.User_ID')
            ->select('view_report_mechanic_times.*', 'pusers.email', 'pusers.image')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $repairStatuses,
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
