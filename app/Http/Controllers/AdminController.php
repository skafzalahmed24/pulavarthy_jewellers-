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
        $prices = \App\Models\MetalPrice::all()->keyBy('metal_name');
        return view('admin.dashboard', compact('prices'));
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

        $currentGold = \App\Models\MetalPrice::where('metal_name', 'Gold')->first();
        if (!$currentGold || (float)$currentGold->today_price != (float)$request->gold_today || (float)$currentGold->yesterday_price != (float)$request->gold_yesterday) {
            \App\Models\MetalPrice::updateOrCreate(
                ['metal_name' => 'Gold'],
                ['today_price' => $request->gold_today, 'yesterday_price' => $request->gold_yesterday]
            );
            if (!$currentGold || (float)$currentGold->today_price != (float)$request->gold_today) {
                \App\Models\PriceHistory::create([
                    'metal_name' => 'Gold',
                    'price' => $request->gold_today
                ]);
            }
        }

        $currentSilver = \App\Models\MetalPrice::where('metal_name', 'Silver')->first();
        if (!$currentSilver || (float)$currentSilver->today_price != (float)$request->silver_today || (float)$currentSilver->yesterday_price != (float)$request->silver_yesterday) {
            \App\Models\MetalPrice::updateOrCreate(
                ['metal_name' => 'Silver'],
                ['today_price' => $request->silver_today, 'yesterday_price' => $request->silver_yesterday]
            );
            if (!$currentSilver || (float)$currentSilver->today_price != (float)$request->silver_today) {
                \App\Models\PriceHistory::create([
                    'metal_name' => 'Silver',
                    'price' => $request->silver_today
                ]);
            }
        }

        return back()->with('success', 'Market prices updated successfully.');
    }

    public function priceHistoryIndex(Request $request)
    {
        $query = \App\Models\PriceHistory::query();

        if ($request->filled('type')) {
            $query->where('metal_name', $request->type);
        }

        $histories = $query->latest()->paginate(10);

        return view('admin.prices.history', compact('histories'));
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