<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use OmiseCharge;
use OmiseSource;


class PaymentController extends Controller
{
    public function createPromptPay(Request $request)
    {
        // ตั้งค่า Omise ด้วย secret key
        // define('OMISE_API_VERSION', '2019-05-29');
        // define('OMISE_PUBLIC_KEY', env('OMISE_PUBLIC_KEY'));
        // define('OMISE_SECRET_KEY', env('OMISE_SECRET_KEY'));
        $request->validate([
            'amount' => 'required|integer|min:1',
        ]);
        try {
            // \Omise\Omise::setApiKey(env('OMISE_SECRET_KEY'));
            // รับข้อมูลจาก request
            // $amount = $request->input('amount');

            define('OMISE_API_VERSION', '2019-05-29');
            define('OMISE_PUBLIC_KEY', env('OMISE_PUBLIC_KEY'));
            define('OMISE_SECRET_KEY', env('OMISE_SECRET_KEY'));

            // เรียก Omise API เพื่อสร้าง PromptPay QR Code
            $source = OmiseSource::create([
                'type' => 'promptpay',
                'amount' => $request->input('amount'), // หน่วยเป็นสตางค์
                'currency' => 'thb',
            ]);

            return response()->json([
                'success' => true,
                'source' => $source,
                'qrCodeUrl' => $source['scannable_code']['image']['download_uri'], // URL สำหรับ QR Code
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
