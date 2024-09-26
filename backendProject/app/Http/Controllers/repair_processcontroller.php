<?php

namespace App\Http\Controllers;

use App\Models\repair_process;
use App\Models\roles;
use App\Models\puser;
use App\Models\repair_steps;
use App\Models\quotations;
use Illuminate\Http\Request;

class repair_processcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repairProcess = repair_process::with(['quotations.puser.roles', 'repair_steps'])->get();
        return response()->json($repairProcess);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่ได้รับ
        $request->validate([
            'Quotation_ID' => 'required|integer',
            'licenseplate' => 'required',
            'Step_ID' => 'required|integer'
            
        ]);

        try {
            // สร้าง Repair Process ใหม่
            $repairProcess = repair_process::create([
                'Quotation_ID' => $request->input('Quotation_ID'),
                'licenseplate' => $request->input('licenseplate'),
                'Step_ID' => $request->input('Step_ID')
            ]);
    
            // ดึงข้อมูล Process_ID จาก repair_process ที่เพิ่งสร้าง
            $process = repair_process::where('Quotation_ID', $request->input('Quotation_ID'))
                ->where('Step_ID', $request->input('Step_ID'))
                ->where('licenseplate', $request->input('licenseplate'))
                ->first();
    
            if ($process) {
                return response()->json([
                    'message' => 'Successfully submitted repair process',
                    'Process_ID' => $process->Process_ID,
                    'Step_ID' => $process->Step_ID,
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Failed to retrieve Process_ID'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create repair process'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       // หา Role ที่มี Role_ID ตรงกับ $id ที่ระบุ
       $role = roles::where('Role_ID', $id)->first();

       if (!$role) {
           return response()->json(['message' => 'Role not found'], 404);
       }

       // หา RepairStep ที่มี Step_ID ตรงกับ Role_ID ที่ระบุ
       $repairStep = repair_steps::where('Step_ID', $role->Role_ID)->first();

       if (!$repairStep) {
           return response()->json(['message' => 'RepairStep not found'], 404);
       }

        // ดึงข้อมูล puser ที่มี Role_ID ตรงกับ Role_ID ที่ระบุ
        $pusers = Puser::where('Role_ID', $role->Role_ID)->get();

        if ($pusers->isEmpty()) {
            return response()->json(['message' => 'No users found for the specified role'], 404);
        }

        // รวบรวม Process_ID ของ RepairProcess ที่เกี่ยวข้องกับ Step_ID
        $processIds = repair_process::where('Step_ID', $repairStep->Step_ID)->pluck('Process_ID');

        // ดึงข้อมูล RepairProcesses ตาม Process_ID ที่รวบรวมได้
        $repairProcesses = repair_process::whereIn('Process_ID', $processIds)
                            ->whereIn('Status',['In Progress'])
                            ->get();

        // return response()->json($repairProcesses);
        return response()->json([
            'status' => true,
            'data' => $repairProcesses,
        ]);
    }

    public function showCom(string $id)
    {
       // หา Role ที่มี Role_ID ตรงกับ $id ที่ระบุ
       $role = roles::where('Role_ID', $id)->first();

       if (!$role) {
           return response()->json(['message' => 'Role not found'], 404);
       }

       // หา RepairStep ที่มี Step_ID ตรงกับ Role_ID ที่ระบุ
       $repairStep = repair_steps::where('Step_ID', $role->Role_ID)->first();

       if (!$repairStep) {
           return response()->json(['message' => 'RepairStep not found'], 404);
       }

        // ดึงข้อมูล puser ที่มี Role_ID ตรงกับ Role_ID ที่ระบุ
        $pusers = Puser::where('Role_ID', $role->Role_ID)->get();

        if ($pusers->isEmpty()) {
            return response()->json(['message' => 'No users found for the specified role'], 404);
        }

        // รวบรวม Process_ID ของ RepairProcess ที่เกี่ยวข้องกับ Step_ID
        $processIds = repair_process::where('Step_ID', $repairStep->Step_ID)->pluck('Process_ID');

        // ดึงข้อมูล RepairProcesses ตาม Process_ID ที่รวบรวมได้
        $repairProcesses = repair_process::whereIn('Process_ID', $processIds)
                            ->whereIn('Status',['verification','Completed'])
                            ->get();

        // return response()->json($repairProcesses);
        return response()->json([
            'status' => true,
            'data' => $repairProcesses,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'Process_ID' => 'required|integer',
            'Description' => 'required|string|max:255',
        ]);

        $processId = $request->input('Process_ID');
        // Find the repair process by Process_ID
        $repairProcess = repair_process::where('Process_ID', $processId)->first();

        if ($repairProcess) {
            // Update the Description and Status
            $repairProcess->Description = $validatedData['Description'];
            $repairProcess->Status = 'In Progress';
        
            // Save the changes
            $repairProcess->save();

            return response()->json([
                'message' => 'Repair process updated successfully.',
                'data' => $repairProcess,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Repair process not found.',
            ], 404);
        }
    }

    public function update_Completed(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'Process_ID' => 'required|integer',
        ]);

        $processId = $request->input('Process_ID');
        // Find the repair process by Process_ID
        $repairProcess = repair_process::where('Process_ID', $processId)->first();

        if ($repairProcess) {
            // Update the Description and Status
            $repairProcess->Status = 'Completed';
        
            // Save the changes
            $repairProcess->save();

            return response()->json([
                'message' => 'Repair process updated successfully.',
                'data' => $repairProcess,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Repair process not found.',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getProcessId(Request $request)
    {
        $request->validate([
            'Quotation_ID' => 'required|integer',
            'licenseplate' => 'required',
            'Step_ID' => 'required|integer'
        ]);

        $processId = repair_process::where([
            ['Quotation_ID', '=', $request->input('Quotation_ID')],
            ['licenseplate', '=', $request->input('licenseplate')],
            ['Step_ID', '=', $request->input('Step_ID')]
        ])->value('Process_ID');

        return response()->json(['Process_ID' => $processId]);
    }

    public function getRepairProcessesByQuotationId(Request $request)
    {
        $request->validate([
            'Quotation_ID' => 'required|integer',
        ]);
        
        $repairProcesses = repair_process::where('Quotation_ID', $request->input('Quotation_ID'))
        ->join('repair_steps', 'repair_processes.Step_ID', '=', 'repair_steps.Step_ID')
        ->select('repair_processes.*', 'repair_steps.StepName')
        ->get();


        return response()->json([
            'status' => true,
            'data' => $repairProcesses,
        ]);
    }

    
}
// // รวบรวม Process_ID ของ RepairProcess ที่เกี่ยวข้องกับ pusers
    // $processIds = collect();

    // foreach ($pusers as $puser) {
    //     $quotations = $puser->quotations;

    //     foreach ($quotations as $quotation) {
    //         $repairProcesses = $quotation->repairProcesses;

    //         foreach ($repairProcesses as $repairProcess) {
    //             if ($repairProcess->Step_ID == $repairStep->Step_ID) {
    //                 $processIds->push($repairProcess->Process_ID);
    //             }
    //         }
    //     }
    // }

    // $processIds = $processIds->unique();

    //    // ดึงข้อมูล RepairProcesses ตาม Process_ID ที่รวบรวมได้
    //    $repairProcesses = repair_process::whereIn('Process_ID', $processIds)->get();