<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Payment;
use Midtrans\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request; 

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false; 
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }

    //versi 2 method store ramsey order id (uuid)
    public function store(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required|integer',
            'gross_amount' => 'required|string',
        ]);
    
        $uniqueOrderId = $request->input('order_id') . '-' . Str::uuid();
    
        $transaction_details = [
            'order_id' => $uniqueOrderId,
            'gross_amount' => $request->input('gross_amount'),
        ];
    
        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
            ],
        ];
    
        try {
            $snapToken = Snap::getSnapToken($params);
            $snapUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken; 
            if (Config::$isProduction) {
                $snapUrl = "https://app.midtrans.com/snap/v2/vtweb/" . $snapToken; 
            }
    
            $payment = Payment::create([
                'order_id' => $uniqueOrderId,
                'transaction_id' => $snapToken,
                'payment_type' => 'online',
                'gross_amount' => $request->input('gross_amount'),
                'transaction_time' => Carbon::now(),
                'transaction_status' => 'pending',
            ]);
    
            return response()->json([
                'status' => 'success',
                'data' => $payment,
                'snap_token' => $snapToken,
                'snap_url' => $snapUrl 
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
  //versi 1 
    // public function store(Request $request)
    // {
    //     $this->validate($request, [
    //         'order_id' => 'required|integer',
    //         'gross_amount' => 'required|string',
    //     ]);

    //     $transaction_details = [
    //         'order_id' => $request->input('order_id'),
    //         'gross_amount' => $request->input('gross_amount'),
    //     ];

    //     $params = [
    //         'transaction_details' => $transaction_details,
    //         'customer_details' => [
    //             'first_name' => $request->input('first_name'),
    //             'last_name' => $request->input('last_name'),
    //             'email' => $request->input('email'),
    //             'phone' => $request->input('phone'),
    //         ],
    //     ];

    //     try {
    //         $snapToken = Snap::getSnapToken($params);
    //         $snapUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken; 
    //         if (Config::$isProduction) {
    //             $snapUrl = "https://app.midtrans.com/snap/v2/vtweb/" . $snapToken; 
    //         }
            
    //         $payment = Payment::create([
    //             'order_id' => $request->input('order_id'),
    //             'transaction_id' => $snapToken,
    //             'payment_type' => 'online',
    //             'gross_amount' => $request->input('gross_amount'),
    //             'transaction_time' => Carbon::now(),
    //             'transaction_status' => 'pending',
    //         ]);

    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $payment,
    //             'snap_token' => $snapToken,
    //             'snap_url' => $snapUrl 
    //         ], 201);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    //cek payment status method
    // public function updatePaymentStatus(Request $request)
    // {
    //     $this->validate($request, [
    //         'order_id' => 'required|string',
    //     ]);
    
    //     $orderId = $request->input('order_id');
    
    //     try {
    //         Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    //         Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    
    //         $status = Transaction::status($orderId);
    //         $transactionStatus = $status->transaction_status;
    
    //         $payment = Payment::where('order_id', $orderId)->first();
    
    //         if (!$payment) {
    //             return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
    //         }
    
    //         if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
    //             $payment->transaction_status = 'paid';
    //         } else {
    //             $payment->transaction_status = 'unpaid';
    //         }
    
    //         $payment->save();
    
    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $payment,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
  

    public function show($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        return response()->json($payment);
    }

    public function edit(Request $request, $id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $this->validate($request, [
            'order_id' => 'required|integer',
            'gross_amount' => 'required|string',
            'transaction_status' => 'required|string',
        ]);

        $payment->order_id = $request->input('order_id');
        $payment->gross_amount = $request->input('gross_amount');
        $payment->transaction_status = $request->input('transaction_status');
        $payment->save();

        return response()->json($payment);
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}