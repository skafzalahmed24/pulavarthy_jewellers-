<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = User::where('is_admin', false)->latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display a listing of pending customers for approval.
     */
    public function approvals()
    {
        $customers = User::where('is_admin', false)->where('status', 'pending')->latest()->paginate(10);
        return view('admin.customers.approvals', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = \App\Models\InvestmentPlan::all();
        return view('admin.customers.create', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $schemeNumber = null;
        $plan = \App\Models\InvestmentPlan::where('name', $request->plan_category)->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'state' => $request->state,
            'identity_proof' => $request->identity_proof,
            'nominee_name' => $request->nominee_name,
            'nominee_relationship' => $request->nominee_relationship,
            'nominee_contact' => $request->nominee_contact,
            'status' => 'approved', // Admin manual additions are auto-approved
            'is_admin' => false,
        ]);

        if ($plan) {
            \App\Models\UserScheme::create([
                'user_id' => $user->id,
                'scheme_id' => $plan->id,
                'scheme_number' => $schemeNumber,
            ]);
        }

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = User::findOrFail($id);
        $plans = \App\Models\InvestmentPlan::all();
        return view('admin.customers.edit', compact('customer', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
            'mobile' => 'required|string|unique:users,mobile,' . $customer->id,
            'status' => 'required|in:pending,approved,rejected',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:20',
            'state' => 'nullable|string|max:255',
            'identity_proof' => 'nullable|string|max:255',
            'plan_category' => 'nullable|string|max:255',
            'nominee_name' => 'nullable|string|max:255',
            'nominee_relationship' => 'nullable|string|max:255',
            'nominee_contact' => 'nullable|string|max:20',
        ]);

        $data = $request->only([
            'name', 'email', 'mobile', 'status', 'address', 'city',
            'pincode', 'state', 'identity_proof', 
            'nominee_name', 'nominee_relationship',
            'nominee_contact'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $customer->update($data);

        // Update plan category if provided
        if ($request->filled('plan_category')) {
            $plan = \App\Models\InvestmentPlan::where('name', $request->plan_category)->first();
            if ($plan) {
                $userScheme = $customer->userSchemes()->first();
                if ($userScheme) {
                    $userScheme->update(['scheme_id' => $plan->id]);
                } else {
                    $customer->userSchemes()->create(['scheme_id' => $plan->id, 'scheme_number' => null]);
                }
            }
        }

        // Scheme number generation logic is now handled after the first payment.

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = User::findOrFail($id);
        $customer->update(['status' => 'rejected']);

        return redirect()->route('admin.customers.index')->with('success', 'Customer has been marked as rejected.');
    }

    /**
     * View Payment Terms for a Customer
     */
    public function paymentTerms($id)
    {
        $customer = User::findOrFail($id);
        
        // Ensure user has a scheme before trying to get payments
        $scheme = $customer->userSchemes()->first();
        $payments = $scheme ? $scheme->payments()->orderBy('due_date', 'asc')->get() : collect([]);

        return view('admin.customers.payments', compact('customer', 'payments'));
    }

    /**
     * Update a specific payment
     */
    public function updatePayment(Request $request, $paymentId)
    {
        $request->validate([
            'payment_status' => 'required|string',
            'due_date' => 'required|date',
            'payable_amount' => 'required|numeric'
        ]);

        $payment = \App\Models\Payment::findOrFail($paymentId);
        $payment->update([
            'payment_status' => $request->payment_status,
            'due_date' => $request->due_date,
            'payable_amount' => $request->payable_amount
        ]);

        return back()->with('success', 'Payment details updated successfully.');
    }
}