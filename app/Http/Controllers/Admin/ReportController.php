<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserScheme;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function pendingPayments(Request $request)
    {
        // Query UserSchemes that have at least one pending payment due up to today
        $query = UserScheme::with(['user', 'payments', 'investmentPlan'])
            ->whereHas('payments', function ($q) {
                $q->where('payment_status', 'pending')
                  ->where('due_date', '<=', Carbon::now()->endOfDay());
            });

        // Apply Filters
        if ($request->filled('scheme_number')) {
            $query->where('scheme_number', 'like', '%' . $request->scheme_number . '%');
        }

        if ($request->filled('customer_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }

        if ($request->filled('phone_number')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('mobile', 'like', '%' . $request->phone_number . '%');
            });
        }

        $schemes = $query->latest()->paginate(10)->withQueryString();

        // Process data for the view
        $reportData = $schemes->getCollection()->map(function ($scheme) {
            $payments = $scheme->payments;
            
            // Pending payments up to today
            $pendingPayments = $payments->filter(function ($payment) {
                return $payment->payment_status === 'pending' && Carbon::parse($payment->due_date)->startOfDay()->lte(Carbon::now()->endOfDay());
            })->sortBy('due_date');

            $pendingMonthsList = $pendingPayments->map(function ($payment) {
                return Carbon::parse($payment->due_date)->format('M Y');
            })->implode(', ');

            $pendingAmountList = $pendingPayments->map(function ($payment) {
                return '₹' . number_format($payment->payable_amount, 2);
            })->implode(' + ');

            $totalPendingAmount = $pendingPayments->sum('payable_amount');

            $paidCount = $payments->where('payment_status', 'paid')->count();
            $totalCount = $scheme->investmentPlan ? $scheme->investmentPlan->duration_months : $payments->count();

            return (object) [
                'scheme_id' => $scheme->scheme_number ?? 'Pending Approval',
                'customer_name' => $scheme->user->name ?? 'N/A',
                'phone_number' => $scheme->user->mobile ?? 'N/A',
                'user_id' => $scheme->user_id,
                'pending_months' => $pendingMonthsList,
                'pending_amounts' => $pendingAmountList,
                'total_pending' => $totalPendingAmount,
                'paid_count' => $paidCount,
                'total_months' => $totalCount,
            ];
        });

        // Override collection with processed data
        $schemes->setCollection($reportData);

        return view('admin.reports.pending_payments', compact('schemes'));
    }
}
