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
}
