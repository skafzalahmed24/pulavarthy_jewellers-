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
            'scheme_id' => 'required|exists:investment_plans,id',
            'monthly_amount' => 'required|numeric|min:5000',
            'nominee_name' => 'required|string|max:255',
            'nominee_relationship' => 'required|string|max:255',
            'nominee_contact' => 'required|string|max:20',
        ]);

        $user->update($request->only([
            'address', 'city', 'pincode', 'state', 'identity_proof',
            'nominee_name', 'nominee_relationship', 'nominee_contact'
        ]));

        UserScheme::create([
            'user_id' => $user->id,
            'scheme_id' => $request->scheme_id,
            'monthly_amount' => $request->monthly_amount,
            'scheme_number' => null, // Generated after first payment
        ]);

        return redirect()->route('purchase-plan')->with('success', 'Your plan has been activated! You can now proceed with your first payment.');
    }
}
