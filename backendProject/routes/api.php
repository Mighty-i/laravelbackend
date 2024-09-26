<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\rolescontroller;
use App\Http\Controllers\pusercontroller;
use App\Http\Controllers\repair_processcontroller;
use App\Http\Controllers\quotationscontroller;
use App\Http\Controllers\repair_stepscontroller;
use App\Http\Controllers\partcontroller;
use App\Http\Controllers\part_usagecontroller;
use App\Http\Controllers\repair_statuscontroller;
use App\Http\Controllers\customerscontroller;
use App\Http\Controllers\report_controller;
use App\Http\Controllers\insurance_companycontroller;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\car_brandscontroller;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::get('auth/google', [App\Http\Controllers\AuthController::class, 'redirectToGoogle']);
Route::get('auth/callback', [App\Http\Controllers\AuthController::class, 'handleGoogleCallback']);

Route::post('/check-google-user', [AuthController::class, 'checkGoogleUser']);
Route::post('/update-user-info', [AuthController::class, 'updateUserInfo']);
Route::post('/sign-Up', [AuthController::class, 'SignUp']);
Route::post('/logincustomer', [AuthController::class, 'logincustomer']);

Route::post('/weblogin', [AuthController::class, 'Weblogin']);
Route::post('/weblogout', [AuthController::class, 'Weblogout']);

Route::get('/roles', [rolescontroller::class, 'index']);
Route::get('/puser', [pusercontroller::class, 'index']);

Route::post('/customersstore', [customerscontroller::class, 'store']);//web



Route::get('/datacar', [car_brandscontroller::class, 'index']);//ข้อมูลรถ
Route::get('/quotationsS', [quotationscontroller::class, 'searchByLicensePlate']);//เรียกดูข้อมูลเก่า

Route::get('/repair-process', [repair_processcontroller::class, 'index']);
// Route::get('/repair-process/step/{roleId}', [repair_processcontroller::class, 'showByStep']);
Route::get('/repair-processid/{id}', [repair_processcontroller::class, 'show']);
Route::get('/repair-processidCOM/{id}', [repair_processcontroller::class, 'showCom']);
Route::post('/repair_processes', [repair_processcontroller::class, 'store']);
Route::get('/repair-processid', [repair_processcontroller::class, 'getProcessId']);
Route::get('/repair-processQID', [repair_processcontroller::class, 'getRepairProcessesByQuotationId']);
Route::put('/repair-processUpdate',[repair_processcontroller::class, 'update']);

Route::put('/repair-update_Completed',[repair_processcontroller::class, 'update_Completed']);

Route::post('/startupload-images', [repair_statuscontroller::class, 'startuploadImages']);
Route::post('/endupload-images', [repair_statuscontroller::class, 'enduploadImages']);
Route::post('/repair_status', [repair_statuscontroller::class, 'store']);
Route::get('/repair_statusimages', [repair_statuscontroller::class, 'getAllStatuses']);
Route::get('/repair_statusPID', [repair_statuscontroller::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::post('/update-passwordMobile', [pusercontroller::class, 'updatePassword']);
Route::post('/update-usernameMobile', [pusercontroller::class, 'updateusername']);
Route::post('/update-profile', [pusercontroller::class, 'update']);

Route::get('/quotations', [quotationscontroller::class, 'index']);
Route::get('/quotationsInProgress', [quotationscontroller::class, 'index2']);
Route::put('/quotationsupdateStatus', [quotationscontroller::class, 'update']);
Route::post('/quotations-insert', [quotationscontroller::class, 'store']);

Route::get('/quotationsshowdata', [quotationscontroller::class, 'showdata']);

Route::get('/quotationsshowAlldata', [quotationscontroller::class, 'ShowAlldata']);

Route::get('/check-quotation-status', [quotationscontroller::class, 'checkAndUpdateQuotationStatus']);

Route::get('/quotationsInProgressonweb', [quotationscontroller::class, 'INProcessonweb']);

Route::get('/quotationsCompleted', [quotationscontroller::class, 'Completed']);

Route::get('/Quotationinvoice', [quotationscontroller::class, 'Quotationinvoice']);

Route::get('/LatestQuotations', [quotationscontroller::class, 'LatestQuotations']);

Route::get('/customersearchByLicensePlate', [quotationscontroller::class, 'customersearchByLicensePlate']);

Route::put('/quotations/{id}', [quotationscontroller::class, 'updateQWeb']);

Route::get('/repair_steps', [repair_stepscontroller::class, 'index']);

Route::get('/parts', [partcontroller::class, 'index']);

Route::post('/part_usage', [part_usagecontroller::class, 'store']);
Route::get('/part_usage', [part_usagecontroller::class, 'getPartsByProcessId']);


//admin
Route::get('/repair-status-times', [report_controller::class, 'index']);//report_mechanic_times

Route::get('/insurance-companies', [insurance_companycontroller::class, 'index']);
Route::post('/insurance-companies', [insurance_companycontroller::class, 'store']);
Route::put('/insurance-companies/{id}', [insurance_companycontroller::class, 'update']);
Route::delete('/insurance-companies/{id}', [insurance_companycontroller::class, 'destroy']);

Route::get('/partsAdmin', [partcontroller::class, 'index2']);
Route::post('/parts-store', [partcontroller::class, 'store']);     // เพิ่มข้อมูลใหม่
Route::put('/parts/{id}', [partcontroller::class, 'update']); // อัปเดตข้อมูล
Route::delete('/parts/{id}', [partcontroller::class, 'destroy']); // ลบข้อมูล