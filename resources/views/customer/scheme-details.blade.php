@extends('layouts.luxury')

@section('title', 'Plan Details')

@section('content')
<section class="section-standard" style="background: var(--bg-secondary); min-height: 80vh;">
    <div class="container-standard">
        <div style="margin-bottom: 2rem; padding-top: 2rem;">
            <a href="{{ route('customer.dashboard') }}" style="color: var(--accent-color); font-weight: 600; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div style="background: white; border-radius: 15px; box-shadow: var(--shadow-soft); padding: 3rem; margin-bottom: 3rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid #eee; padding-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h2 style="color: var(--heading-color); margin-bottom: 0.5rem; font-size: 2rem;">
                        {{ $userScheme->investmentPlan->name ?? 'Unknown Plan' }}
                    </h2>
                    <p style="color: var(--text-secondary); font-size: 1.1rem;">
                        Scheme Number: <strong>{{ $userScheme->scheme_number ?? 'Pending' }}</strong>
                    </p>
                </div>
                <div>
                    <span style="display: inline-block; background: #e8f5e9; color: #2e7d32; padding: 0.5rem 1.5rem; border-radius: 30px; font-weight: bold; border: 1px solid #c8e6c9;">
                        Active Status
                    </span>
                </div>
            </div>

            <div class="grid-container grid-3" style="margin-bottom: 3rem;">
                <div style="background: #fcfcfc; padding: 1.5rem; border-radius: 10px; border: 1px solid #eee;">
                    <p style="color: var(--text-secondary); font-size: 0.95rem; margin-bottom: 0.5rem; font-weight: 600;">Monthly Installment</p>
                    <h4 style="color: var(--heading-color); font-size: 1.5rem;">₹ {{ number_format($userScheme->monthly_amount ?? ($userScheme->investmentPlan->installment_amount ?? 0), 2) }}</h4>
                </div>
                <div style="background: #fcfcfc; padding: 1.5rem; border-radius: 10px; border: 1px solid #eee;">
                    <p style="color: var(--text-secondary); font-size: 0.95rem; margin-bottom: 0.5rem; font-weight: 600;">Duration (Months)</p>
                    <h4 style="color: var(--heading-color); font-size: 1.5rem;">{{ $userScheme->investmentPlan->duration_months ?? 'N/A' }}</h4>
                </div>
                <div style="background: #fcfcfc; padding: 1.5rem; border-radius: 10px; border: 1px solid #eee;">
                    <p style="color: var(--text-secondary); font-size: 0.95rem; margin-bottom: 0.5rem; font-weight: 600;">Total Paid</p>
                    <h4 style="color: var(--heading-color); font-size: 1.5rem;">
                        @php
                            $totalPaid = $userScheme->payments ? $userScheme->payments->where('payment_status', 'paid')->sum('payable_amount') : 0;
                        @endphp
                        ₹ {{ number_format($totalPaid, 2) }}
                    </h4>
                </div>
            </div>

            <h3 style="color: var(--heading-color); margin-bottom: 1.5rem; font-size: 1.5rem; border-left: 4px solid var(--accent-color); padding-left: 1rem;">Payment History</h3>
            
            @if($userScheme->payments && $userScheme->payments->count() > 0)
                <div style="border: none;">
                    <table class="table-mobile-cards" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background: #f9f9f9; color: var(--heading-color);">
                                <th style="padding: 1.2rem 1rem; border-bottom: 1px solid #eee; font-weight: 700;">S.No</th>
                                <th style="padding: 1.2rem 1rem; border-bottom: 1px solid #eee; font-weight: 700;"># ID</th>
                                <th style="padding: 1.2rem 1rem; border-bottom: 1px solid #eee; font-weight: 700;">Due Date</th>
                                <th style="padding: 1.2rem 1rem; border-bottom: 1px solid #eee; font-weight: 700;">Amount</th>
                                <th style="padding: 1.2rem 1rem; border-bottom: 1px solid #eee; font-weight: 700;">Gold Rate</th>
                                <th style="padding: 1.2rem 1rem; border-bottom: 1px solid #eee; font-weight: 700;">Weight (g)</th>
                                <th style="padding: 1.2rem 1rem; border-bottom: 1px solid #eee; font-weight: 700;">Status</th>
                                <th style="padding: 1.2rem 1rem; border-bottom: 1px solid #eee; font-weight: 700;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userScheme->payments as $payment)
                                <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;">
                                    <td data-label="S.No" style="padding: 1.2rem 1rem; font-weight: bold; color: var(--heading-color);">{{ $loop->iteration }}</td>
                                    <td data-label="Transaction ID" style="padding: 1.2rem 1rem; color: var(--text-secondary); font-size: 0.85rem;">{{ $payment->payment_id ?? 'N/A' }}</td>
                                    <td data-label="Due Date" style="padding: 1.2rem 1rem; font-weight: 500;">
                                        {{ $payment->due_date ? $payment->due_date->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td data-label="Amount" style="padding: 1.2rem 1rem; font-weight: bold; color: var(--heading-color);">₹ {{ number_format($payment->payable_amount, 2) }}</td>
                                    <td data-label="Gold Rate" style="padding: 1.2rem 1rem; color: #7f8c8d;">
                                        {{ $payment->current_gold_rate ? '₹ '.number_format($payment->current_gold_rate, 2) : '---' }}
                                    </td>
                                    <td data-label="Weight" style="padding: 1.2rem 1rem; font-weight: bold; color: var(--accent-color);">
                                        {{ $payment->current_gold_rate && $payment->payment_status == 'paid' ? number_format($payment->payable_amount / $payment->current_gold_rate, 3) . ' g' : '---' }}
                                    </td>
                                    @php
                                        $isPaid = $payment->payment_status === 'paid';
                                        $isPending = $payment->payment_status === 'pending';
                                        $today = now()->startOfDay();
                                        $dueDateObj = $payment->due_date ? \Carbon\Carbon::parse($payment->due_date)->startOfDay() : clone $today;
                                        $graceEndObj = $payment->grace_end_date ? \Carbon\Carbon::parse($payment->grace_end_date)->startOfDay() : null;
                                        
                                        $isTodayOrPast = $today->greaterThanOrEqualTo($dueDateObj);
                                        $dueDatePast = $today->greaterThan($dueDateObj);
                                        $graceOver = $graceEndObj && $today->greaterThan($graceEndObj);
                                        
                                        $showOverdue = $isPending && $dueDatePast;
                                        $showNotDueDate = $isPending && !$isTodayOrPast;
                                        $showPayNow = $isPending && $isTodayOrPast && !$graceOver;
                                    @endphp
                                    <td data-label="Status" style="padding: 1.2rem 1rem;">
                                        @if($isPaid)
                                            <span style="background: #e8f5e9; color: #2e7d32; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold; display: inline-block;">Paid</span>
                                        @elseif($showOverdue)
                                            <span style="background: #ffebee; color: #c62828; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold; display: inline-block;">Overdue</span>
                                        @elseif($showNotDueDate)
                                            <span style="background: #fff3e0; color: #ef6c00; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold; display: inline-block;">Not Yet Date</span>
                                        @else
                                            <span style="background: #fff3e0; color: #ef6c00; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold; display: inline-block;">{{ ucfirst($payment->payment_status) }}</span>
                                        @endif
                                    </td>
                                    <td data-label="Action" style="padding: 1.2rem 1rem;">
                                        @if($isPaid)
                                            <span style="color: #4caf50; font-size: 0.95rem; font-weight: 600;"><i class="fas fa-check-circle"></i> Completed</span>
                                        @elseif($isPending && $graceOver)
                                            <button class="btn-premium" style="padding: 0.5rem 1.2rem; font-size: 0.85rem; border-radius: 5px; background: #c0392b;" onclick="window.location.href='{{ url('/purchase-plan#pay-now') }}'">Admin Request Raise It</button>
                                        @elseif($showPayNow)
                                            <button class="btn-premium" style="padding: 0.5rem 1.2rem; font-size: 0.85rem; border-radius: 5px;" onclick="window.location.href='{{ url('/purchase-plan#pay-now') }}'">Pay Now</button>
                                        @else
                                            <span style="color: #888; font-size: 0.85rem; font-style: italic;">Upcoming</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 4rem 2rem; background: #fafafa; border-radius: 10px; border: 1px dashed #ccc;">
                    <i class="fas fa-receipt" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <p style="color: var(--text-secondary); margin: 0; font-size: 1.1rem;">No payments found for this scheme.</p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
