<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user || $user->is_admin) {
            return back()->withErrors(['mobile' => 'Invalid mobile number or password.']);
        }

        if ($user->status === 'pending') {
            return back()->withErrors(['mobile' => 'Your account is pending admin approval. You can login once it\'s approved.']);
        }

        if ($user->status === 'rejected') {
            return back()->withErrors(['mobile' => 'Your account is rejected by the owner. Kindly reach out to <a href="tel:9154175992" style="color: #c53030; text-decoration: underline; font-weight: 700;">9154175992</a>']);
        }

        if (Auth::attempt(['mobile' => $request->mobile, 'password' => $request->password])) {
            return redirect()->route('purchase-plan')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['mobile' => 'Invalid mobile number or password.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('purchase-plan')->with('success', 'Logged out successfully!');
    }
}