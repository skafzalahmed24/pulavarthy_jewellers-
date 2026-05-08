@extends('layouts.admin')

@section('title', 'Customer Payments')

@section('content')
<div class="dashboard-header">
    <div>
        <h1>Payments for {{ $customer->name }}</h1>
        <p style="color: var(--text-secondary);">Manage payment terms and statuses for this customer.</p>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="btn-premium" style="text-decoration: none; background: #333;">
        <i class="fas fa-arrow-left"></i> Back to Customers
    </a>
</div>

<!-- Rates Quick View -->
<div class="grid-container grid-2" style="margin-bottom: 2rem;">
    <div class="luxury-card" style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="font-size: 3rem; color: #f7d08a;"><i class="fas fa-coins"></i></div>
        <div>
            <h4 style="margin: 0; font-size: 1rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Current Gold Rate</h4>
            <div style="font-size: 1.8rem; font-weight: 700; color: var(--heading-color); margin-top: 0.3rem;">₹{{ isset($prices) && $prices->has('Gold') ? $prices['Gold']->today_price : '--' }} <small style="font-size: 1rem; font-weight: normal; color: var(--text-secondary);">/ g</small></div>
        </div>
    </div>
    <div class="luxury-card" style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="font-size: 3rem; color: #c0c0c0;"><i class="fas fa-coins"></i></div>
        <div>
            <h4 style="margin: 0; font-size: 1rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Current Silver Rate</h4>
            <div style="font-size: 1.8rem; font-weight: 700; color: var(--heading-color); margin-top: 0.3rem;">₹{{ isset($prices) && $prices->has('Silver') ? $prices['Silver']->today_price : '--' }} <small style="font-size: 1rem; font-weight: normal; color: var(--text-secondary);">/ g</small></div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="luxury-card alert-auto-dismiss"
    style="background: #e8f5e9; color: #2e7d32; padding: 1rem; margin-bottom: 2rem; border-radius: 12px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

<div class="luxury-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive" style="padding: 1rem;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--bg-secondary); border-bottom: 1px solid #eee;">
                    <th style="padding: 1.5rem 2rem;">Due Date</th>
                    <th style="padding: 1.5rem 2rem;">Amount</th>
                    <th style="padding: 1.5rem 2rem;">Gold Rate</th>
                    <th style="padding: 1.5rem 2rem;">Weight (g)</th>
                    <th style="padding: 1.5rem 2rem;">Status</th>
                    <th style="padding: 1.5rem 2rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                @php
                    $isOverdue = $payment->payment_status !== 'paid' && now()->gt($payment->grace_end_date ?? $payment->due_date);
                @endphp
                <tr class="{{ $isOverdue ? 'status-outdated' : '' }}" style="border-bottom: 1px solid #eee; transition: background 0.3s ease;">
                    <td style="padding: 1.5rem 2rem; font-weight: 600;">
                        {{ \Carbon\Carbon::parse($payment->due_date)->format('d M, Y') }}
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <span style="font-weight: 700;">₹{{ number_format($payment->payable_amount, 2) }}</span>
                    </td>
                    <td style="padding: 1.5rem 2rem; color: var(--text-secondary);">
                        {{ $payment->current_gold_rate ? '₹' . number_format($payment->current_gold_rate, 2) : 'N/A' }}
                    </td>
                    <td style="padding: 1.5rem 2rem; font-weight: 700; color: var(--accent-color);">
                        {{ $payment->current_gold_rate && $payment->payment_status == 'paid' ? number_format($payment->payable_amount / $payment->current_gold_rate, 3) . ' g' : 'N/A' }}
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        @php
                            $isFuturePayment = now()->format('Y-m') < \Carbon\Carbon::parse($payment->due_date)->format('Y-m');
                        @endphp
                        @if($payment->payment_status == 'paid')
                            <span style="background: #e8f5e9; color: #2e7d32; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700;">Paid</span>
                        @elseif($payment->payment_status == 'pending')
                            @if($isOverdue)
                                <span style="background: #ffebee; color: #c62828; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700;">Overdue</span>
                            @elseif($isFuturePayment)
                                <span style="background: #e3f2fd; color: #1565c0; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700;">Not Yet Due</span>
                            @else
                                <span style="background: #fff8e1; color: #f9a825; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700;">Due</span>
                            @endif
                        @else
                            <span style="background: #f1f3f5; color: #495057; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700;">{{ ucfirst($payment->payment_status) }}</span>
                        @endif
                        
                        @if($payment->grace_extension_status == 'approved')
                            <div style="font-size: 0.75rem; color: #e67e22; margin-top: 5px; font-weight: bold;">
                                <i class="fas fa-clock"></i> Grace: {{ \Carbon\Carbon::parse($payment->grace_end_date)->format('d M') }}
                            </div>
                        @elseif($payment->grace_extension_status == 'pending')
                            <div style="font-size: 0.75rem; color: #f9a825; margin-top: 5px; font-weight: bold;">
                                <i class="fas fa-clock"></i> Grace Extension Requested
                            </div>
                        @elseif($payment->grace_extension_status == 'rejected')
                            <div style="font-size: 0.75rem; color: #c62828; margin-top: 5px; font-weight: bold;">
                                <i class="fas fa-times-circle"></i> Extension Rejected
                            </div>
                        @endif
                    </td>
                    <td style="padding: 1.5rem 2rem; text-align: right;">
                        <button type="button" 
                            onclick="openEditModal({{ $payment->id }}, '{{ $payment->payment_status }}', '{{ $payment->due_date ? \Carbon\Carbon::parse($payment->due_date)->format('Y-m-d') : '' }}', '{{ $payment->payable_amount }}')"
                            class="btn-action btn-edit" title="Edit Payment">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center; color: #888;">
                        <i class="fas fa-file-invoice"
                            style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                        No payments found for this customer.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Payment Modal -->
<div id="editPaymentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="luxury-card" style="width: 100%; max-width: 500px; padding: 2.5rem; position: relative;">
        <button onclick="closeEditModal()" style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #888;">&times;</button>
        <h3 style="margin-bottom: 1.5rem;">Edit Payment</h3>
        <form id="editPaymentForm" method="POST" action="">
            @csrf
            <div class="form-group">
                <label>Status</label>
                <select name="payment_status" id="modal_payment_status" class="form-control" required>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="due_date" id="modal_due_date" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Payable Amount (₹)</label>
                <input type="number" step="0.01" name="payable_amount" id="modal_payable_amount" class="form-control" required>
            </div>

            <button type="submit" class="btn-premium" style="width: 100%; margin-top: 1rem;">Update Payment</button>
        </form>
    </div>
</div>

<script>
    function openEditModal(paymentId, status, dueDate, amount) {
        document.getElementById('editPaymentForm').action = '/admin/payments/' + paymentId + '/update';
        document.getElementById('modal_payment_status').value = status;
        document.getElementById('modal_due_date').value = dueDate;
        document.getElementById('modal_payable_amount').value = amount;
        
        document.getElementById('editPaymentModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editPaymentModal').style.display = 'none';
    }

    // Close on out-click
    window.onclick = function(event) {
        if (event.target == document.getElementById('editPaymentModal')) {
            closeEditModal();
        }
    }
</script>
@endsection
