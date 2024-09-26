<?php

namespace App\Http\Controllers;

use App\Models\repair_process;
use App\Models\puser;
use App\Models\repair_status;

use Illuminate\Http\Request;

class repair_statuscontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'Process_ID' => 'required|integer',
        ]);
        $processId = $request->input('Process_ID');

        $repair_status = repair_status::where('Process_ID', $processId)
        ->join('pusers', 'repair_statuses.User_ID', '=', 'pusers.User_ID')  // Join with pusers table
        ->select('repair_statuses.*', 'pusers.name')  // Select repair_status fields and pusers.name
        ->get();
        return response()->json([
            'status' => true,
            'data' => $repair_status,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Process_ID' => 'required|integer',
            'User_ID' => 'required|integer',
            'StatusType' => 'required'
        ]);

        $repair_status = repair_status::create($validatedData);

        return response()->json([
            'message' => 'repair_status created successfully',
            'data' => $repair_status
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

    public function startuploadImages(Request $request)
    {
        $request->validate([
            'Process_ID' => 'required|integer',
            'User_ID' => 'required|integer',
            'image1' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'image2' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'image3' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $repairStatus = new repair_status();
        $repairStatus->Process_ID = $request->Process_ID;
        $repairStatus->User_ID = $request->User_ID;
        $repairStatus->StatusType = 'ก่อน';
        $repairStatus->Status = 'Pending';

        // Store images and get their URLs
        if ($request->hasFile('image1')) {
            $image1Path = $request->file('image1')->store('public/images');
            $repairStatus->Image1 = str_replace('public/', '', $image1Path);
        }
    
        if ($request->hasFile('image2')) {
            $image2Path = $request->file('image2')->store('public/images');
            $repairStatus->Image2 = str_replace('public/', '', $image2Path);
        }
    
        if ($request->hasFile('image3')) {
            $image3Path = $request->file('image3')->store('public/images');
            $repairStatus->Image3 = str_replace('public/', '', $image3Path);
        }
    
        $repairStatus->save();

        return response()->json([
            'message' => 'Images uploaded successfully',
            'data' => [
                'Image1' => asset('storage/' . $repairStatus->Image1),
                'Image2' => asset('storage/' . $repairStatus->Image2),
                'Image3' => asset('storage/' . $repairStatus->Image3),
            ]
        ], 200);
    }

    public function enduploadImages(Request $request)
    {
        $request->validate([
            'Process_ID' => 'required|integer',
            'User_ID' => 'required|integer',
            'image1' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'image2' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'image3' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $repairStatus = new repair_status();
        $repairStatus->Process_ID = $request->Process_ID;
        $repairStatus->User_ID = $request->User_ID;
        $repairStatus->StatusType = 'หลัง';
        $repairStatus->Status = 'Complete';

        // Store images and get their URLs
        if ($request->hasFile('image1')) {
            $image1Path = $request->file('image1')->store('public/images');
            $repairStatus->Image1 = str_replace('public/', '', $image1Path);
        }
    
        if ($request->hasFile('image2')) {
            $image2Path = $request->file('image2')->store('public/images');
            $repairStatus->Image2 = str_replace('public/', '', $image2Path);
        }
    
        if ($request->hasFile('image3')) {
            $image3Path = $request->file('image3')->store('public/images');
            $repairStatus->Image3 = str_replace('public/', '', $image3Path);
        }
    
        $repairStatus->save();

        // ตรวจสอบจำนวนช่างที่เพิ่มรูปภาพก่อนทำงาน (Status = Pending)
        $pendingCount = repair_status::where('Process_ID', $request->Process_ID)
                                  ->where('Status', 'Pending')
                                  ->count();

        // ตรวจสอบจำนวนช่างที่เพิ่มรูปภาพหลังทำงาน (Status = Complete)
        $completeCount = repair_status::where('Process_ID', $request->Process_ID)
                                   ->where('Status', 'Complete')
                                   ->count();

        // เช็คว่าจำนวนรูปภาพหลังทำงานตรงกับจำนวนรูปภาพก่อนทำงานหรือไม่
        if ($pendingCount === $completeCount) {
            // อัปเดตสถานะ repair_process เป็น 'verification'
            repair_process::where('Process_ID', $request->Process_ID)
                      ->update(['Status' => 'verification']);
        }
                                
        return response()->json([
            'message' => 'Images uploaded successfully',
            'data' => [
                'Image1' => asset('storage/' . $repairStatus->Image1),
                'Image2' => asset('storage/' . $repairStatus->Image2),
                'Image3' => asset('storage/' . $repairStatus->Image3),
            ]
        ], 200);
    }

    public function getAllStatuses()
    {
        // ดึงข้อมูลทั้งหมดจาก repair_statuses
        $statuses = repair_status::all();

        // เตรียมข้อมูลสำหรับส่งคืนโดยเพิ่ม URL รูปภาพ
        $statuses = $statuses->map(function ($status) {
            return [
                'Status_ID' => $status->Status_ID,
                'User_ID' => $status->User_ID,
                'Process_ID' => $status->Process_ID,
                'StatusType' => $status->StatusType,
                'image1' => $status->Image1 ? asset('storage/' . $status->Image1) : null,
                'image2' => $status->Image2 ? asset('storage/' . $status->Image2) : null,
                'image3' => $status->Image3 ? asset('storage/' . $status->Image3) : null,
                'Status' => $status->Status,
                'created_at' => $status->created_at,
                'updated_at' => $status->updated_at,
            ];
        });

        return response()->json($statuses);
    }

}
// public function startuploadImages(Request $request)
//     {
//         $request->validate([
//             'Process_ID' => 'required|integer',
//             'User_ID' => 'required|integer',
//             'image1' => 'required|image|mimes:jpeg,png,jpg|max:10240',
//             'image2' => 'required|image|mimes:jpeg,png,jpg|max:10240',
//             'image3' => 'required|image|mimes:jpeg,png,jpg|max:10240',
//         ]);

//         $repairStatus = new repair_status();
//         $repairStatus->Process_ID = $request->Process_ID;
//         $repairStatus->User_ID = $request->User_ID;
//         $repairStatus->StatusType = 'ก่อน';
//         $repairStatus->Status = 'Pending';

//         if ($request->hasFile('image1')) {
//             $repairStatus->Image1 = $request->file('image1')->store('images');
//         }

//         if ($request->hasFile('image2')) {
//             $repairStatus->Image2 = $request->file('image2')->store('images');
//         }

//         if ($request->hasFile('image3')) {
//             $repairStatus->Image3 = $request->file('image3')->store('images');
//         }

//         $repairStatus->save();

//         return response()->json(['message' => 'Images uploaded successfully'], 200);
//     }