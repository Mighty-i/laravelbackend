<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customers;

class customerscontroller extends Controller
{
    // ดึงข้อมูลรถทั้งหมด (GET /api/cars)
    public function index()
    {
        $cars = Car::all(); // ดึงข้อมูลรถทั้งหมดจากฐานข้อมูล
        return response()->json($cars); // ส่งข้อมูลกลับในรูปแบบ JSON
    }

    // ดึงข้อมูลรถตามเลขทะเบียน (GET /api/cars/{registration})
    public function show($registration)
    {
        $car = Car::where('registration', $registration)->firstOrFail(); // ค้นหารถตามเลขทะเบียน
        return response()->json($car); // ส่งข้อมูลรถกลับ
    }

    // เพิ่มข้อมูลรถใหม่ (POST /api/cars)
    public function store(Request $request)
{
    // ตรวจสอบความถูกต้องของข้อมูลสำหรับลูกค้า
    $validatedCustomer = $request->validate([
        'FirstName' => 'required|max:255',
        'Lastname' => 'required|max:255',
        'PhoneNumber' => 'required|max:20',
    ]);
    // เพิ่มข้อมูลลูกค้าใหม่ในฐานข้อมูล
    $customer = customers::create($validatedCustomer);

    // คืนค่า Customer_ID ของลูกค้าที่เพิ่งสร้างใหม่
    return response()->json(['Customer_ID' => $customer->Customer_ID], 201); // ส่ง Customer_ID กลับพร้อมสถานะ 201 (Created)
}

    // อัปเดตข้อมูลรถ (PUT /api/cars/{registration})
    public function update(Request $request, $registration)
    {
        // ค้นหารถตามเลขทะเบียน
        $car = Car::where('registration', $registration)->firstOrFail();

        // ตรวจสอบความถูกต้องของข้อมูล
        $validated = $request->validate([
            'model' => 'required|max:255',
            'customerName' => 'required|max:255',
            'phoneNumber' => 'required',
            'carCode' => 'required|max:255',
            'year' => 'required|integer',
            'insuranceCompany' => 'required|max:255',
            'repairDate' => 'required|date',
            'damageDetails' => 'nullable|string',
        ]);

        // อัปเดตข้อมูลรถ
        $car->update($validated);

        return response()->json($car); // ส่งข้อมูลรถที่ถูกอัปเดตกลับ
    }

    // ดึงประวัติการซ่อมของรถ (GET /api/cars/{registration}/repair-history)
    public function getRepairHistory($registration)
    {
        $car = Car::where('registration', $registration)->firstOrFail(); // ค้นหารถตามเลขทะเบียน
        $repairHistory = $car->repairHistory; // ดึงประวัติการซ่อมของรถ

        return response()->json($repairHistory); // ส่งประวัติการซ่อมกลับ
    }
}