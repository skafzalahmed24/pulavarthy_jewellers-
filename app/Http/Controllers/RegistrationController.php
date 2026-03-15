<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
        ]);

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
            'status' => 'pending',
            'is_admin' => false,
        ]);

        if ($request->filled('plan_category')) {
            $plan = \App\Models\InvestmentPlan::where('name', $request->plan_category)->first();
            if ($plan) {
                \App\Models\UserScheme::create([
                    'user_id' => $user->id,
                    'scheme_id' => $plan->id,
                    'scheme_number' => null,
                ]);
            }
        }

        return redirect()->route('purchase-plan')->with('success', 'Thanks for signing in! Your account is pending admin approval. You can login once it\'s approved.');
    }
}