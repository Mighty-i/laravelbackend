<?php

namespace App\Http\Controllers;

use App\Models\puser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function registerweb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:pusers,username',
            'password' => 'required|string|min:6',
            'email' => 'required|string|email|max:255|unique:pusers,email',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:10',
            'Role_ID' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ]);
        }

        $user = Puser::create([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'phone_number' => $request->input('phone_number'),
            'Role_ID' => $request->input('Role_ID'),
        ]);


        if ($user) {
            return response()->json([
                'status' => true,
                'message' => 'Quotation created successfully',
                'data' => $user,
            ], 201);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Failed to create quotation'
            ], 500);
        }
    }

    public function updateUser(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'User_ID' => 'required|integer|exists:pusers,User_ID', // ตรวจสอบ User_ID
            'username' => 'required|string|max:255|unique:pusers,username,' . $request->input('User_ID'),
            'email' => 'required|string|email|max:255|unique:pusers,email,' . $request->input('User_ID'),
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:10',
            'Role_ID' => 'required|integer',
            'password' => 'nullable|string|min:6', // Password is optional in case it's not changed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ]);
        }

        // Find the user by ID
        $user = puser::find($request->input('User_ID')); // ใช้ข้อมูลจาก request
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Update user fields
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->phone_number = $request->input('phone_number');
        $user->Role_ID = $request->input('Role_ID');

        // Update password only if provided and not null
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Save the changes
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
        ]);
    }
}
