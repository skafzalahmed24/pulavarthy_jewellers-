@extends('layouts.admin')

@section('title', 'Pending Grace Requests')

@section('content')
<div class="dashboard-header">
    <div>
        <h1>Grace Period Requests</h1>
        <p style="color: var(--text-secondary);">Review and approve payment deadline extensions requested by customers.</p>
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
                    <th style="padding: 1.5rem 2rem;">Customer</th>
                    <th style="padding: 1.5rem 2rem;">Payment Due Date</th>
                    <th style="padding: 1.5rem 2rem;">Amount</th>
                    <th style="padding: 1.5rem 2rem;">Reason</th>
                    <th style="padding: 1.5rem 2rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr style="border-bottom: 1px solid #eee; transition: background 0.3s ease;">
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 700; color: var(--heading-color);">{{ $req->userScheme->user->name }}</div>
                        <div style="font-size: 0.85rem; color: #888;">{{ $req->userScheme->user->mobile }}</div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <span style="font-weight: 600; color: #d32f2f;">{{ \Carbon\Carbon::parse($req->due_date)->format('d M, Y') }}</span>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <span style="font-weight: 700;">₹{{ number_format($req->payable_amount, 2) }}</span>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <p style="font-size: 0.9rem; color: var(--text-secondary); margin: 0; max-width: 250px;">
                            {{ $req->grace_extension_reason ?? 'No reason provided' }}
                        </p>
                    </td>
                    <td style="padding: 1.5rem 2rem; text-align: right;">
                        <div style="display: flex; gap: 10px; justify-content: flex-end;">
                            <form action="{{ route('admin.payments.approve_grace', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-action btn-view" title="Approve" style="width: auto; padding: 0 15px; height: 38px; font-weight: 600;">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.payments.reject_grace', $req->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to reject this request?');">
                                @csrf
                                <button type="submit" class="btn-action btn-delete" title="Reject" style="width: auto; padding: 0 15px; height: 38px; font-weight: 600;">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center; color: #888;">
                        <i class="fas fa-clock"
                            style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                        No pending grace requests found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
