<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\puser;
use App\Models\roles;
use App\Models\customers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = customers::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => Hash::make(uniqid())
                ]
            );
            Auth::login($user, true);
            return redirect()->intended('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login');
        }
    }

    public function register(Request $request)
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

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
        ]);
    }

    public function login(Request $request)
    {
        // ตรวจสอบข้อมูล input ให้รองรับ username หรือ phone_number และ password
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // ตรวจสอบว่าข้อมูลที่ส่งมาคือ username หรือ phone_number โดยใช้ regular expression
        $credentials = [];
        if (preg_match('/^[0-9]+$/', $request->username)) {
            // ถ้าเป็นเบอร์โทร (ประกอบด้วยเลขอย่างเดียว) ให้ใช้ phone_number
            $credentials['phone_number'] = $request->username;
        } else {
            // ถ้าเป็น username
            $credentials['username'] = $request->username;
        }

        $credentials['password'] = $request->password;

        // พยายามล็อกอิน
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $role = roles::where('Role_ID', $user->Role_ID)->first();
            // Store additional data in session if needed
            session(['user_role' => $user->Role_ID]);

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'role_name' => $role ? $role->Role_name : 'Unknown',
            ]);
        }

        // บันทึกข้อมูลการล็อกอินที่ไม่สำเร็จ
        Log::warning('Login attempt failed for username/phone_number: ' . $request->username);

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        session()->flush(); // Clears all session data

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function resetPassword(Request $request)
    {
        // ตรวจสอบข้อมูลที่ส่งมา
        $request->validate([
            'username' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
            'new_password_confirmation' => 'required|string|min:6',
        ]);

        // กำหนดตัวแปร $credentials สำหรับค้นหาผู้ใช้
        $credentials = [];
        if (preg_match('/^[0-9]+$/', $request->username)) {
            // ถ้าเป็นเบอร์โทร (ประกอบด้วยเลขอย่างเดียว) ให้ใช้ phone_number
            $credentials['phone_number'] = $request->username;
        } else {
            // ถ้าเป็น username
            $credentials['username'] = $request->username;
        }

        // ตรวจสอบการยืนยันรหัสผ่านใหม่
        if ($request->new_password !== $request->new_password_confirmation) {
            return response()->json(['message' => 'New passwords do not match'], 400);
        }

        // ค้นหาผู้ใช้
        $user = puser::where($credentials)->first();

        // ตรวจสอบว่าพบผู้ใช้หรือไม่
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // ใช้ User_ID เพื่ออัปเดตรหัสผ่าน
        $userId = $user->User_ID; // สมมุติว่า `id` เป็นคอลัมน์ที่เก็บ User_ID

        // อัปเดตรหัสผ่าน
        $userToUpdate = puser::find($userId); // ค้นหาผู้ใช้โดย User_ID
        if ($userToUpdate) {
            $userToUpdate->password = Hash::make($request->new_password);
            $userToUpdate->save();

            return response()->json(['message' => 'Password reset successful.'], 200);
        } else {
            return response()->json(['message' => 'User not found.'], 404);
        }
    }


    public function checkGoogleUser(Request $request)
    {
        $googleId = $request->input('google_id');
        $email = $request->input('email');
        $username = $request->input('username');
        $profileImage = $request->input('profile_image');

        // ค้นหาผู้ใช้จาก google_id, email, username, และ profile_image
        $user = customers::where(function ($query) use ($googleId, $email, $username, $profileImage) {
            $query->where('google_id', $googleId)
                ->orWhere('email', $email)
                ->orWhere('username', $username)
                ->orWhere('profile_image', $profileImage);
        })->first();

        if ($user) {
            // ถ้ามีผู้ใช้อยู่แล้ว
            return response()->json(['status' => 'existing_user' , 'user' => $user]);
        } else {
            // ถ้าไม่พบผู้ใช้ หรือทุกค่าที่ค้นหามีค่าเป็น NULL
            return response()->json(['status' => 'new_user']);
        }
    }


    public function updateUserInfo(Request $request)
    {
        $googleId = $request->input('google_id');
        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $phoneNumber = $request->input('phone_number');
        $email = $request->input('email');
        $username = $request->input('username');
        $profileImage = $request->input('profile_image');

        // ค้นหาผู้ใช้ตาม first_name, last_name, และ phone_number
        $user = customers::where('FirstName', $firstName)
            ->where('Lastname', $lastName)
            ->where('PhoneNumber', $phoneNumber)
            ->first();

        if ($user) {
            // อัพเดทข้อมูลผู้ใช้
            $user->update([
                'google_id' => $googleId,
                'email' => $email,
                'username' => $username,
                'profile_image' => $profileImage,
            ]);

            return response()->json(['status' => 'success', 'message' => 'User updated']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
    }

    public function SignUp(Request $request)
    {
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $phoneNumber = $request->input('phone_number');
        $email = $request->input('email');
        $username = $request->input('username');
        $password = $request->input('password');

        $customer = customers::where('FirstName', $firstName)
            ->where('Lastname', $lastName)
            ->where('PhoneNumber', $phoneNumber)
            ->first();

        if ($customer) {
            $customer->update([
                'username' => $username,
                'password' => Hash::make($password),
                'email' => $email,
            ]);
            return response()->json(['status' => 'success', 'message' => 'User updated']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
    }

    public function logincustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
        }

        $user = customers::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }

        // ส่งข้อมูลผู้ใช้กลับไป
        return response()->json(['status' => 'success', 'user' => $user]);
    }

    public function Weblogin(Request $request)
    {
        // Validate input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check if input is phone number or username
        $credentials = [];
        if (preg_match('/^[0-9]+$/', $request->username)) {
            $credentials['phone_number'] = $request->username;
        } else {
            $credentials['username'] = $request->username;
        }

        $credentials['password'] = $request->password;

        // Attempt to login
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $role = roles::where('Role_ID', $user->Role_ID)->first();

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'role_name' => $role ? $role->Role_name : 'Unknown',
            ]);
        }

        Log::warning('Login attempt failed for username/phone_number: ' . $request->username);

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function Weblogout(Request $request)
    {
        Auth::logout();
        session()->flush(); // Clears all session data

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
