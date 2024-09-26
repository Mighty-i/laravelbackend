<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\quotations;
use App\Models\repair_steps;
use App\Models\repair_process;
use App\Models\vehicles;

class quotationscontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ShowAlldata()
    {
        $quotation = quotations::all();
        return response()->json([
            'status' => true,
            'data' => $quotation,
        ]);
    }

    public function LatestQuotations()
    {
        // ดึงข้อมูล quotations ที่ Status เป็น Pending พร้อมกับดึงข้อมูลจาก vehicles และ car_brands
        $quotations = quotations::where('Status', 'Pending')
            ->with(['vehicles.car_brands', 'customers']) // ดึงข้อมูล vehicle และ car brand ที่สัมพันธ์กัน
            ->get()
            ->map(function ($quotation) {
                return [
                    'Quotation_ID' => $quotation->Quotation_ID,
                    'Customer_ID' => $quotation->Customer_ID,
                    'Vehicle_ID' => $quotation->Vehicle_ID,
                    'Brand' => $quotation->vehicles->car_brands->Brand ?? null, // แสดงข้อมูล Brand
                    'Model' => $quotation->vehicles->Model ?? null, // แสดงข้อมูล Model
                    'Year' => $quotation->vehicles->Year ?? null, // แสดงข้อมูล Year
                    'QuotationDate' => $quotation->QuotationDate,
                    'TotalAmount' => $quotation->TotalAmount,
                    'licenseplate' => $quotation->licenseplate,
                    'damageassessment' => $quotation->damageassessment,
                    'problemdetails' => $quotation->problemdetails,
                    'minRepairCost' => $quotation->minRepairCost,
                    'maxRepairCost' => $quotation->maxRepairCost,
                    'completionDate' => $quotation->completionDate,
                    'customer' => [
                        'FullName' => $quotation->customers->FirstName . ' ' . $quotation->customers->Lastname ?? null, // ชื่อเต็มของลูกค้า
                        'CustomerPhone' => $quotation->customers->PhoneNumber ?? null,    // เบอร์โทรของลูกค้า
                    ],

                ];
            });

        return response()->json([
            'status' => true,
            'data' => $quotations,
        ]);
    }


    public function index()
    {
        // ดึงข้อมูล quotations ที่ Status เป็น Pending พร้อมกับดึงข้อมูลจาก vehicles และ car_brands
        $quotations = quotations::where('Status', 'Pending')
            ->with(['vehicles.car_brands']) // ดึงข้อมูล vehicle และ car brand ที่สัมพันธ์กัน
            ->get()
            ->map(function ($quotation) {
                return [
                    'Quotation_ID' => $quotation->Quotation_ID,
                    'Customer_ID' => $quotation->Customer_ID,
                    'User_ID' => $quotation->User_ID,
                    'Vehicle_ID' => $quotation->Vehicle_ID,
                    'Brand' => $quotation->vehicles->car_brands->Brand ?? null, // แสดงข้อมูล Brand
                    'Model' => $quotation->vehicles->Model ?? null, // แสดงข้อมูล Model
                    'Year' => $quotation->vehicles->Year ?? null, // แสดงข้อมูล Year
                    'QuotationDate' => $quotation->QuotationDate,
                    'TotalAmount' => $quotation->TotalAmount,
                    'Status' => $quotation->Status,
                    'color' => $quotation->color,
                    'licenseplate' => $quotation->licenseplate,
                    'damageassessment' => $quotation->damageassessment,
                    'problemdetails' => $quotation->problemdetails,
                    'completionDate' => $quotation->completionDate,
                    'PaymentMethod' => $quotation->PaymentMethod,
                    'PaymentDate' => $quotation->PaymentDate,
                    'ImageSlie' => $quotation->ImageSlie,
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $quotations,
        ]);
    }

    public function index2()
    {
        $quotations = quotations::where('Status', 'In Progress')
            ->with(['vehicles.car_brands'])
            ->get();

        $data = $quotations->map(function ($quotation) {
            $repairProcesses = repair_process::where('Quotation_ID', $quotation->Quotation_ID)
                ->join('repair_steps', 'repair_processes.Step_ID', '=', 'repair_steps.Step_ID')
                ->select('repair_processes.*', 'repair_steps.StepName')
                ->get();


            $quotation['repair_processes'] = $repairProcesses;

            // return $quotation;
            return [
                'Quotation_ID' => $quotation->Quotation_ID,
                'Customer_ID' => $quotation->Customer_ID,
                'User_ID' => $quotation->User_ID,
                'Vehicle_ID' => $quotation->Vehicle_ID,
                'Brand' => $quotation->vehicles->car_brands->Brand ?? null, // Vehicle Brand
                'Model' => $quotation->vehicles->Model ?? null,            // Vehicle Model
                'Year' => $quotation->vehicles->Year ?? null,              // Vehicle Year
                'QuotationDate' => $quotation->QuotationDate,
                'Status' => $quotation->Status,
                'color' => $quotation->color,
                'licenseplate' => $quotation->licenseplate,
                'damageassessment' => $quotation->damageassessment,
                'problemdetails' => $quotation->problemdetails,
                'repair_processes' => $repairProcesses->map(function ($process) {
                    return [
                        'Process_ID' => $process->Process_ID,
                        'Quotation_ID' => $process->Quotation_ID,
                        'Step_ID' => $process->Step_ID,
                        'licenseplate' => $process->licenseplate,
                        'Description' => $process->Description,
                        'Status' => $process->Status,
                        'created_at' => $process->created_at,
                        'updated_at' => $process->updated_at,
                        'StepName' => $process->StepName,
                    ];
                }),
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function INProcessonweb()
    {
        $quotations = quotations::where('Status', 'In Progress')
            ->with(['vehicles.car_brands', 'customers', 'insurance_company']) // เพิ่ม customers
            ->get();

        $data = $quotations->map(function ($quotation) {
            $repairProcesses = repair_process::where('Quotation_ID', $quotation->Quotation_ID)
                ->join('repair_steps', 'repair_processes.Step_ID', '=', 'repair_steps.Step_ID')
                ->select('repair_processes.*', 'repair_steps.StepName')
                ->get();

            $quotation['repair_processes'] = $repairProcesses;

            return [
                'Quotation_ID' => $quotation->Quotation_ID,
                'Customer_ID' => $quotation->Customer_ID,
                'User_ID' => $quotation->User_ID,
                'Vehicle_ID' => $quotation->Vehicle_ID,
                'Brand' => $quotation->vehicles->car_brands->Brand ?? null, // Vehicle Brand
                'Model' => $quotation->vehicles->Model ?? null,             // Vehicle Model
                'Year' => $quotation->vehicles->Year ?? null,               // Vehicle Year
                'QuotationDate' => $quotation->QuotationDate,
                'Status' => $quotation->Status,
                'color' => $quotation->color,
                'licenseplate' => $quotation->licenseplate,
                'damageassessment' => $quotation->damageassessment,
                'problemdetails' => $quotation->problemdetails,

                'company' => [
                    'Name' => $quotation->insurance_company->Name,
                ],

                // ข้อมูลลูกค้า
                'customer' => [
                    'FullName' => $quotation->customers->FirstName . ' ' . $quotation->customers->Lastname ?? null, // ชื่อเต็มของลูกค้า
                    'CustomerPhone' => $quotation->customers->PhoneNumber ?? null,    // เบอร์โทรของลูกค้า
                ],

                'repair_processes' => $repairProcesses->map(function ($process) {
                    return [
                        'Process_ID' => $process->Process_ID,
                        'Quotation_ID' => $process->Quotation_ID,
                        'Step_ID' => $process->Step_ID,
                        'licenseplate' => $process->licenseplate,
                        'Description' => $process->Description,
                        'Status' => $process->Status,
                        'created_at' => $process->created_at,
                        'updated_at' => $process->updated_at,
                        'StepName' => $process->StepName,
                    ];
                }),
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Customer_ID' => 'required|integer',
            'User_ID' => 'required|integer',
            'Company_ID' => 'required|integer',
            'Vehicle_ID' => 'required|integer',
            'completionDate' => 'required|date',
            // 'TotalAmount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'color' => 'required|string|max:255',
            'licenseplate' => 'required|string|max:255',
            'damageassessment' => 'required|string|max:255',
            'problemdetails' => 'required|string|max:255',
            'minRepairCost' => 'required|string|max:255',
            'maxRepairCost' => 'required|string|max:255',
        ]);

        $quotation = quotations::create([
            'Customer_ID' => $request->input('Customer_ID'),
            'User_ID' => $request->input('User_ID'),
            'Company_ID' => $request->input('Company_ID'),
            'Vehicle_ID' => $request->input('Vehicle_ID'),
            'QuotationDate' => now(),
            // 'TotalAmount' => $request->input('TotalAmount'),
            'TotalAmount' => $request->input('TotalAmount', 0.00),
            'color' => $request->input('color'),
            // 'color' => null,
            'licenseplate' => $request->input('licenseplate'),
            'damageassessment' => $request->input('damageassessment'),
            // 'damageassessment' => null,
            'problemdetails' => $request->input('problemdetails'),
            'Status' => 'Pending',
            'minRepairCost' => $request->input('minRepairCost'),
            'maxRepairCost' => $request->input('maxRepairCost'),
            'completionDate' => $request->input('completionDate'),
            'PaymentMethod' => null,
            'PaymentDate' => null,
            'ImageSlie' => null
        ]);
        // dd($request->all());

        if ($quotation) {
            return response()->json([
                'message' => 'Quotation created successfully',
                'data' => $quotation,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Failed to create quotation'
            ], 500);
        }
    }

    public function updateQWeb(Request $request, $id)
    {
        $request->validate([
            'Customer_IDS' => 'required|integer',
            'insuranceCompanyIdS' => 'required|integer',
            'vehicleIdS' => 'required|integer',
            'completionDate' => 'required|date',
            // 'TotalAmount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'color' => 'required|string|max:255',
            'licensePlate' => 'required|string|max:255',
            'damageAssessment' => 'required|string|max:255',
            'problemDetails' => 'required|string|max:255',
            'minRepairCost' => 'required|string|max:255',
            'maxRepairCost' => 'required|string|max:255',
        ]);

        // Find the quotation by Quotation_ID
        $quotation = quotations::where('Quotation_ID', $id)->first();

        if ($quotation) {
            // Update the quotation with the new data
            $quotation->Customer_ID = $request->input('Customer_IDS');
            $quotation->Company_ID = $request->input('insuranceCompanyIdS');
            $quotation->Vehicle_ID = $request->input('vehicleIdS');
            $quotation->completionDate = $request->input('completionDate');
            $quotation->color = $request->input('color');
            $quotation->licenseplate = $request->input('licensePlate');
            $quotation->damageassessment = $request->input('damageAssessment');
            $quotation->problemdetails = $request->input('problemDetails');
            $quotation->minRepairCost = $request->input('minRepairCost');
            $quotation->maxRepairCost = $request->input('maxRepairCost');

            $quotation->save();

            return response()->json([
                'message' => 'Quotation updated successfully.',
                'data' => $quotation,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Quotation not found.',
            ], 404);
        }
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
        // Validate the incoming request data
        $validatedData = $request->validate([
            'Quotation_ID' => 'required|integer',
        ]);

        $quotationId = $request->input('Quotation_ID');
        // Find the repair process by Process_ID
        $quotations = quotations::where('Quotation_ID', $quotationId)->first();

        if ($quotations) {
            // Update the Description and Status
            $quotations->Status = 'In Progress';

            // Save the changes
            $quotations->save();

            return response()->json([
                'message' => 'quotations updated successfully.',
                'data' => $quotations,
            ], 200);
        } else {
            return response()->json([
                'message' => 'quotations not found.',
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

    public function checkAndUpdateQuotationStatus(Request $request)
    {
        $request->validate([
            'Quotation_ID' => 'required|integer',
        ]);
        $quotationId = $request->input('Quotation_ID');
        // Step 1: Fetch all processes for the given Quotation_ID
        $repairProcesses = repair_process::where('Quotation_ID', $quotationId)->get();

        // Step 2: Check if all processes have status = 'Completed'
        $incompleteProcesses = $repairProcesses->filter(function ($process) {
            return $process->Status != 'Completed';
        });

        if ($incompleteProcesses->isEmpty()) {
            // Step 3: If all are completed, update the quotation status
            $quotation = quotations::where('Quotation_ID', $quotationId)->first();
            if ($quotation) {
                $quotation->Status = 'Completed';
                $quotation->save();
                return response()->json([
                    'message' => 'Quotation status updated to Completed'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Quotation not found'
                ], 404);
            }
        } else {
            // If some processes are incomplete, return which ones are not completed
            return response()->json([
                'message' => 'Not all processes are completed',
                'incomplete_processes' => $incompleteProcesses->pluck('Process_ID')
            ], 400);
        }
    }

    public function searchByLicensePlate(Request $request)
    {

        $request->validate([
            'LicensePlate' => 'required|string|max:255',
        ]);
        $licenseplate = $request->input('LicensePlate');
        // ค้นหา quotations ตาม licenseplate พร้อมดึงข้อมูลที่เกี่ยวข้อง
        $quotations = quotations::with(['customers', 'insurance_company', 'vehicles.car_brands'])
            ->where('licenseplate', $licenseplate)
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ต้องการ
        $response = $quotations->map(function ($quotation) {
            return [
                'Quotation_ID' => $quotation->Quotation_ID,
                'QuotationDate' => $quotation->QuotationDate,
                // 'TotalAmount' => $quotation->TotalAmount,
                'Status' => $quotation->Status,
                'completionDate' => $quotation->completionDate,
                'LicensePlate' => $quotation->licenseplate,
                'DamageAssessment' => $quotation->damageassessment,
                'ProblemDetails' => $quotation->problemdetails,
                'color' => $quotation->color,
                'minRepairCost' => $quotation->minRepairCost,
                'maxRepairCost' => $quotation->maxRepairCost,
                'Customer' => $quotation->customers ? [
                    'Customer_ID' => $quotation->customers->Customer_ID,
                    'FirstName' => $quotation->customers->FirstName,
                    'LastName' => $quotation->customers->Lastname,
                    'PhoneNumber' => $quotation->customers->PhoneNumber
                ] : null,
                'InsuranceCompany' => $quotation->insurance_company ? [
                    'InsuranceCompany' => $quotation->insurance_company->Name,
                    'Company_ID' => $quotation->insurance_company->Company_ID,
                ] : null,
                'Vehicle' => $quotation->vehicles ? [
                    'Vehicle_ID' => $quotation->vehicles->Vehicle_ID, // เพิ่ม Vehicle_ID
                    'Model' => $quotation->vehicles->Model,
                    'Year' => $quotation->vehicles->Year,
                    'Brand' => $quotation->vehicles->car_brands ? [
                        'Brand' => $quotation->vehicles->car_brands->Brand,
                        'Brand_ID' => $quotation->vehicles->car_brands->Brand_ID // เพิ่ม Brand_ID
                    ] : null
                ] : null,
                'Color' => $quotation->color,
            ];
        });

        return response()->json($response);
    }

    public function showdata()
    {
        // ค้นหา quotations ตาม licenseplate พร้อมดึงข้อมูลที่เกี่ยวข้อง
        $quotations = quotations::with(['customers', 'insurance_company', 'vehicles.car_brands'])
            ->where('Status', 'Pending')
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ต้องการ
        $response = $quotations->map(function ($quotation) {
            return [
                'Quotation_ID' => $quotation->Quotation_ID,
                'QuotationDate' => $quotation->QuotationDate,
                // 'TotalAmount' => $quotation->TotalAmount,
                // 'Status' => $quotation->Status,

                'LicensePlate' => $quotation->licenseplate,
                'DamageAssessment' => $quotation->damageassessment,
                'ProblemDetails' => $quotation->problemdetails,
                'Customer' => $quotation->customers ? [
                    'FirstName' => $quotation->customers->FirstName,
                    'LastName' => $quotation->customers->Lastname,
                    'PhoneNumber' => $quotation->customers->PhoneNumber
                ] : null,
                'InsuranceCompany' => $quotation->insurance_company ? $quotation->insurance_company->Name : null,
                'Vehicle' => $quotation->vehicles ? [
                    'Model' => $quotation->vehicles->Model,
                    'Year' => $quotation->vehicles->Year,
                    'Brand' => $quotation->vehicles->car_brands ? $quotation->vehicles->car_brands->Brand : null
                ] : null,
                'Color' => $quotation->color,
            ];
        });

        return response()->json($response);
    }

    public function Completed()
    {
        // ค้นหา quotations ตาม licenseplate พร้อมดึงข้อมูลที่เกี่ยวข้อง
        $quotations = quotations::with(['customers', 'vehicles.car_brands'])
            ->where('Status', 'Completed')
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ต้องการ
        $response = $quotations->map(function ($quotation) {
            return [
                'Quotation_ID' => $quotation->Quotation_ID,
                'QuotationDate' => $quotation->QuotationDate,
                // 'TotalAmount' => $quotation->TotalAmount,
                // 'Status' => $quotation->Status,
                'LicensePlate' => $quotation->licenseplate,
                'customer' => [
                    'FullName' => $quotation->customers->FirstName . ' ' . $quotation->customers->Lastname ?? null, // ชื่อเต็มของลูกค้า
                    'CustomerPhone' => $quotation->customers->PhoneNumber ?? null,    // เบอร์โทรของลูกค้า
                ],
                'Vehicle' => $quotation->vehicles ? [
                    'Model' => $quotation->vehicles->Model,
                    'Year' => $quotation->vehicles->Year,
                    'Brand' => $quotation->vehicles->car_brands ? $quotation->vehicles->car_brands->Brand : null
                ] : null,
                'Color' => $quotation->color,
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $response,
        ]);
    }

    public function Quotationinvoice()
    {
        // ค้นหา quotations ตาม licenseplate พร้อมดึงข้อมูลที่เกี่ยวข้อง
        $quotations = quotations::where('Status', 'Completed')
            ->with(['customers', 'vehicles.car_brands', 'repairProcesses.part_usage.part'])
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ต้องการ
        $response = $quotations->map(function ($quotation) {
            return [
                'Quotation_ID' => $quotation->Quotation_ID,
                'QuotationDate' => $quotation->QuotationDate,
                'TotalAmount' => $quotation->TotalAmount,
                // 'Status' => $quotation->Status,
                'LicensePlate' => $quotation->licenseplate,
                'ProblemDetails' => $quotation->problemdetails,
                'customer' => [
                    'FullName' => $quotation->customers->FirstName . ' ' . $quotation->customers->Lastname ?? null, // ชื่อเต็มของลูกค้า
                    'CustomerPhone' => $quotation->customers->PhoneNumber ?? null,    // เบอร์โทรของลูกค้า
                ],
                'Vehicle' => $quotation->vehicles ? [
                    'Model' => $quotation->vehicles->Model,
                    'Year' => $quotation->vehicles->Year,
                    'Brand' => $quotation->vehicles->car_brands ? $quotation->vehicles->car_brands->Brand : null
                ] : null,
                'Color' => $quotation->color,
                'repairProcesses' => $quotation->repairProcesses->map(function ($repairProcess) {
                    return [
                        'Process_ID' => $repairProcess->Process_ID, // แสดง Process_ID
                        'partUsages' => $repairProcess->part_usage->map(function ($partUsage) {
                            return [
                                'Quantity' => $partUsage->Quantity,
                                'PartName' => $partUsage->part->Name ?? null, // ตัวอย่างการดึงข้อมูล part ที่เกี่ยวข้อง
                                'PricePerUnit' => $partUsage->part->PricePerUnit,
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $response,
        ]);
    }

    public function customersearchByLicensePlate(Request $request)
    {

        $request->validate([
            'LicensePlate' => 'required|string|max:255',
        ]);
        $licenseplate = $request->input('LicensePlate');
        // ค้นหา quotations ตาม licenseplate พร้อมดึงข้อมูลที่เกี่ยวข้อง
        $quotations = quotations::with(['customers', 'insurance_company', 'vehicles.car_brands', 'repairProcesses.repair_status', 'repairProcesses.repair_steps'])
            ->where('licenseplate', $licenseplate)
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ต้องการ
        $response = $quotations->map(function ($quotation) {
            return [
                'Quotation_ID' => $quotation->Quotation_ID,
                'QuotationDate' => $quotation->QuotationDate,
                // 'TotalAmount' => $quotation->TotalAmount,
                'Status' => $quotation->Status,
                'LicensePlate' => $quotation->licenseplate,
                'DamageAssessment' => $quotation->damageassessment,
                'ProblemDetails' => $quotation->problemdetails,
                'color' => $quotation->color,
                // 'customer' => [
                'FullName' => $quotation->customers->FirstName . ' ' . $quotation->customers->Lastname ?? null, // ชื่อเต็มของลูกค้า
                'CustomerPhone' => $quotation->customers->PhoneNumber ?? null,    // เบอร์โทรของลูกค้า
                // ],
                'InsuranceCompany' => $quotation->insurance_company->Name,
                'Brand' => $quotation->vehicles->car_brands->Brand ?? null, // Vehicle Brand
                'Model' => $quotation->vehicles->Model ?? null,             // Vehicle Model
                'Year' => $quotation->vehicles->Year ?? null,               // Vehicle Year
                'repair_processes' => $quotation->repairProcesses->map(function ($process) {
                    return [
                        'StepName' => $process->repair_steps->StepName,
                        'Status' => $process->Status,
                        'created_at' => $process->created_at,
                        'updated_at' => $process->updated_at,
                        'repair_status' => $process->repair_status->map(function ($status) {
                            return [
                                'StatusType' => $status->StatusType,
                                'Image1' => $status->Image1,
                                'Image2' => $status->Image2,
                                'Image3' => $status->Image3,
                                'Status' => $status->Status,

                            ];
                        }),
                    ];
                }),
            ];
        });

        // return response()->json($response);
        return response()->json([
            'status' => true,
            'data' => $response,
        ]);
    }
}
