<?php

namespace App\Http\Controllers;

use App\Models\puser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class pusercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $puser = puser::all();
        return response()->json($puser);
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
    public function update(Request $request)
    {
        $request->validate([
            'User_ID' => 'required|integer',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $user = puser::find($request->User_ID);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // if ($user->image && \Storage::exists('public/images/' . $user->image)) {
        //     \Storage::delete('public/' . $user->image);
        // }

        if ($request->hasFile('profile_picture')) {
            // อัปโหลดไฟล์ใหม่
            $imagePath = $request->file('profile_picture')->store('public/images');
            $user->Image = str_replace('public/', '', $imagePath);
    
            // อัปเดตข้อมูลในฐานข้อมูล
            // $user->profile_picture = $imageName;
            $user->save();
        }
        return response()->json([
            'message' => 'Profile picture updated successfully',
            'data' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updatePassword(Request $request)
    {
        // ตรวจสอบว่า User_ID ถูกต้องหรือไม่
        $user = puser::find($request->User_ID);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // ตรวจสอบรหัสผ่านเดิม
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        // ตรวจสอบการยืนยันรหัสผ่านใหม่
        if ($request->new_password !== $request->confirm_password) {
            return response()->json(['message' => 'New passwords do not match'], 400);
        }

        // อัพเดตรหัสผ่านใหม่
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }

    public function updateusername(Request $request)
    {
        // ตรวจสอบว่า User_ID ถูกต้องหรือไม่
        $user = puser::find($request->User_ID);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        $user->username = $request->new_username;
        $user->save();

        return response()->json(['message' => 'username updated successfully'], 200);
    }
}
