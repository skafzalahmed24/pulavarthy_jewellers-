<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials)) {
            if (auth()->user()->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }
            auth()->logout();
            return back()->withErrors(['email' => 'Unauthorized access.']);
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Prices Management
    public function pricesIndex()
    {
        $prices = \App\Models\MetalPrice::all()->keyBy('metal_name');
        return view('admin.prices.index', compact('prices'));
    }

    public function pricesUpdate(Request $request)
    {
        $request->validate([
            'gold_today' => 'required|numeric',
            'gold_yesterday' => 'required|numeric',
            'silver_today' => 'required|numeric',
            'silver_yesterday' => 'required|numeric',
        ]);

        \App\Models\MetalPrice::updateOrCreate(
        ['metal_name' => 'Gold'],
        ['today_price' => $request->gold_today, 'yesterday_price' => $request->gold_yesterday]
        );

        \App\Models\MetalPrice::updateOrCreate(
        ['metal_name' => 'Silver'],
        ['today_price' => $request->silver_today, 'yesterday_price' => $request->silver_yesterday]
        );

        return back()->with('success', 'Market prices updated successfully.');
    }

    // Terms Management
    public function termsIndex()
    {
        $terms = \App\Models\Term::all();
        return view('admin.terms.index', compact('terms'));
    }

    public function termsUpdate(Request $request)
    {
        $request->validate([
            'terms' => 'required|array',
            'terms.*.id' => 'required|exists:terms,id',
            'terms.*.content' => 'required|string',
        ]);

        foreach ($request->terms as $termData) {
            \App\Models\Term::where('id', $termData['id'])->update([
                'content' => $termData['content']
            ]);
        }

        return back()->with('success', 'Terms and conditions updated successfully.');
    }

    public function approveGrace($id)
    {
        $payment = \App\Models\Payment::findOrFail($id);
        $payment->grace_extension_status = 'approved';
        $payment->grace_end_date = \Carbon\Carbon::parse($payment->grace_end_date)->addDays(15);
        $payment->save();

        return back()->with('success', 'Grace period extended by 15 days.');
    }

    public function rejectGrace($id)
    {
        $payment = \App\Models\Payment::findOrFail($id);
        $payment->grace_extension_status = 'rejected';
        $payment->save();

        return back()->with('success', 'Grace period extension rejected.');
    }

    public function graceRequests()
    {
        $requests = \App\Models\Payment::with('userScheme.user')
                                        ->where('grace_extension_status', 'pending')
                                        ->orderBy('created_at', 'asc')
                                        ->get();

        return view('admin.grace_requests', compact('requests'));
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('admin.login');
    }
}