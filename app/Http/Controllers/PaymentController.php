<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        $user = auth()->user();
        $userScheme = $user->userSchemes()->first();
        if (!$userScheme) {
            return response()->json(['error' => 'No active scheme found.'], 400);
        }

        $baseDepositRaw = $userScheme->investmentPlan->base_deposit;
        $payableAmount = (float) preg_replace('/[^0-9.]/', '', $baseDepositRaw);

        // Fetch Razorpay credentials from env
        $keyId = env('RAZORPAY_KEY', 'rzp_test_placeholder');
        $keySecret = env('RAZORPAY_SECRET', 'secret_placeholder');

        try {
            if ($keyId === 'rzp_test_placeholder' || empty($keyId)) {
                return response()->json(['error' => 'Razorpay keys not configured. Please add them to your .env file.'], 400);
            }

            $api = new \Razorpay\Api\Api($keyId, $keySecret);
            $order = $api->order->create([
                'receipt' => 'order_rcptid_' . uniqid(),
                'amount' => (int) ($payableAmount * 100), // Amount in paise must be integer
                'currency' => 'INR',
            ]);

            return response()->json([
                'order_id' => $order['id'],
                'amount' => $payableAmount,
                'key' => $keyId,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $keyId = env('RAZORPAY_KEY', 'rzp_test_placeholder');
        $keySecret = env('RAZORPAY_SECRET', 'secret_placeholder');
        
        try {
            $api = new \Razorpay\Api\Api($keyId, $keySecret);
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];
            $api->utility->verifyPaymentSignature($attributes);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Payment verification failed: ' . $e->getMessage()], 400);
        }

        $user = auth()->user();
        $userScheme = $user->userSchemes()->first();
        $baseDepositRaw = $userScheme->investmentPlan->base_deposit;
        $payableAmount = (float) preg_replace('/[^0-9.]/', '', $baseDepositRaw);
        
        $goldPriceObj = \App\Models\MetalPrice::where('metal_name', 'Gold')->first();
        $todaysRate = $goldPriceObj ? (float) preg_replace('/[^0-9.]/', '', $goldPriceObj->today_price) : 0;

        $existingPaymentsCount = $userScheme->payments()->count();

        if ($existingPaymentsCount === 0) {
            // Create the first paid payment
            \App\Models\Payment::create([
                'user_scheme_id' => $userScheme->id,
                'payment_id' => $request->razorpay_payment_id,
                'current_gold_rate' => $todaysRate,
                'payable_amount' => $payableAmount,
                'due_date' => now(), // Base date
                'next_due_date' => now()->addDays(30),
                'grace_start_date' => now()->addDays(1),
                'grace_end_date' => now()->addDays(7),
                'payment_status' => 'paid',
            ]);

            // Generate 11 more pending months
            $baseDueDate = now();
            for ($i = 1; $i <= 11; $i++) {
                $dueDate = $baseDueDate->copy()->addDays(30 * $i);
                \App\Models\Payment::create([
                    'user_scheme_id' => $userScheme->id,
                    'payment_id' => null,
                    'current_gold_rate' => null,
                    'payable_amount' => $payableAmount,
                    'due_date' => $dueDate,
                    'next_due_date' => $i == 11 ? null : $dueDate->copy()->addDays(30),
                    'grace_start_date' => $dueDate->copy()->addDays(1),
                    'grace_end_date' => $dueDate->copy()->addDays(7),
                    'payment_status' => 'pending',
                ]);
            }
        } else {
            // Find the pending payment for this month and mark it paid!
            $pendingPayment = $userScheme->payments()
                ->where('payment_status', 'pending')
                ->where('id', $request->payment_record_id)
                ->first();
                
            if (!$pendingPayment) {
                 $pendingPayment = $userScheme->payments()
                    ->where('payment_status', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first();
            }

            if ($pendingPayment) {
                $pendingPayment->update([
                    'payment_id' => $request->razorpay_payment_id,
                    'current_gold_rate' => $todaysRate,
                    'payment_status' => 'paid',
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Payment processed successfully.']);
    }

    public function requestGrace(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'reason' => 'required|string|max:500'
        ]);

        $payment = \App\Models\Payment::findOrFail($request->payment_id);
        
        if ($payment->userScheme->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized access.');
        }

        $payment->grace_extension_status = 'pending';
        $payment->grace_extension_reason = $request->reason;
        $payment->save();

        return back()->with('success', 'Grace period extension requested successfully. Admin will review your request.');
    }
}
