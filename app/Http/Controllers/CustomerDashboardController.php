<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserScheme;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        $userSchemes = $user->userSchemes()->with('investmentPlan')->get();
        return view('customer.dashboard', compact('userSchemes'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $userScheme = UserScheme::with(['investmentPlan', 'payments'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('customer.scheme-details', compact('userScheme'));
    }

    public function completeApplication(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|string|max:20',
            'state' => 'required|string|max:255',
            'identity_proof' => 'required|string|max:255',
            'identity_proof_type' => 'required|string|max:255',
            'pan_number' => 'nullable|string|max:10',
            'scheme_id' => 'required|exists:investment_plans,id',
            'monthly_amount' => 'required|numeric|min:5000',
            'nominee_name' => 'required|string|max:255',
            'nominee_relationship' => 'required|string|max:255',
            'nominee_contact' => 'required|string|max:20',
            'dob' => 'required|date',
            'wedding_anniversary' => 'nullable|date',
            'bank_acc_no' => 'nullable|string|max:50',
            'bank_branch' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:20',
        ]);

        $user->update($request->only([
            'address', 'city', 'pincode', 'state', 'identity_proof', 'identity_proof_type',
            'pan_number', 'nominee_name', 'nominee_relationship', 'nominee_contact',
            'dob', 'wedding_anniversary', 'bank_acc_no', 'bank_branch', 'ifsc_code'
        ]));

        UserScheme::create([
            'user_id' => $user->id,
            'scheme_id' => $request->scheme_id,
            'monthly_amount' => $request->monthly_amount,
            'scheme_number' => null,
        ]);

        return redirect()->route('purchase-plan')->with('success', 'Your plan has been activated! You can now proceed with your first payment.');
    }

    public function saveEnrollmentDataAjax(Request $request)
    {
        $user = Auth::user();
        try {
            $request->validate([
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'pincode' => 'required|string|max:20',
                'state' => 'required|string|max:255',
                'identity_proof' => 'required|string|max:255',
                'identity_proof_type' => 'required|string|max:255',
                'pan_number' => 'nullable|string|max:10',
                'scheme_id' => 'required|exists:investment_plans,id',
                'monthly_amount' => 'required|numeric|min:5000',
                'nominee_name' => 'required|string|max:255',
                'nominee_relationship' => 'required|string|max:255',
                'nominee_contact' => 'required|string|max:20',
                'dob' => 'required|date',
                'wedding_anniversary' => 'nullable|date',
                'bank_acc_no' => 'nullable|string|max:50',
                'bank_branch' => 'nullable|string|max:255',
                'ifsc_code' => 'nullable|string|max:20',
            ]);

            $user->update($request->only([
                'address', 'city', 'pincode', 'state', 'identity_proof', 'identity_proof_type',
                'pan_number', 'nominee_name', 'nominee_relationship', 'nominee_contact',
                'dob', 'wedding_anniversary', 'bank_acc_no', 'bank_branch', 'ifsc_code'
            ]));

            $scheme = UserScheme::create([
                'user_id' => $user->id,
                'scheme_id' => $request->scheme_id,
                'monthly_amount' => $request->monthly_amount,
                'status' => 'pending_payment'
            ]);

            // Now create Razorpay Order
            $keyId = env('RAZORPAY_KEY', 'rzp_test_placeholder');
            $keySecret = env('RAZORPAY_SECRET', 'secret_placeholder');
            
            $api = new \Razorpay\Api\Api($keyId, $keySecret);
            $order = $api->order->create([
                'receipt' => 'enroll_rcpt_' . $scheme->id,
                'amount' => (int) ($request->monthly_amount * 100),
                'currency' => 'INR',
            ]);

            return response()->json([
                'success' => true,
                'key' => $keyId,
                'order_id' => $order['id'],
                'amount' => $request->monthly_amount,
                'enrollment_id' => $scheme->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
