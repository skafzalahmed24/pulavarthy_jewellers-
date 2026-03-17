@extends('layouts.admin')

@section('title', 'Pending Approvals')

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
                    <th style="padding: 1.5rem 2rem;">Name</th>
                    <th style="padding: 1.5rem 2rem;">Contact</th>
                    <th style="padding: 1.5rem 2rem;">Scheme No.</th>
                    <th style="padding: 1.5rem 2rem;">Status</th>
                    <th style="padding: 1.5rem 2rem;">Joined Date</th>
                    <th style="padding: 1.5rem 2rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr style="border-bottom: 1px solid #eee; transition: background 0.3s ease;">
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 700; color: var(--heading-color);">{{ $customer->name }}</div>
                        <div style="font-size: 0.85rem; color: #888;">{{ $customer->email }}</div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 600;">{{ $customer->mobile }}</div>
                        <div style="font-size: 0.85rem; color: #888;">{{ $customer->city }}, {{ $customer->state }}
                        </div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 700; color: var(--accent-color);">{{ $customer->userSchemes->first()?->scheme_number ??
                            'Pending Approval' }}
                        </div>
                        <div style="font-size: 0.85rem; color: #888;">{{ $customer->userSchemes->first()?->investmentPlan->name ?? 'N/A' }}</div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <span
                            style="background: #fff8e1; color: #f9a825; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700;">Pending</span>
                    </td>
                    <td style="padding: 1.5rem 2rem; color: var(--text-secondary);">
                        {{ $customer->created_at->format('d M, Y') }}
                    </td>
                    <td style="padding: 1.5rem 2rem; text-align: right;">
                        <div style="display: flex; gap: 10px; justify-content: flex-end;">
                            <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                class="btn-action btn-edit"
                                title="Edit & Approve">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this pending approval?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="btn-action btn-delete"
                                    title="Reject & Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 4rem; text-align: center; color: #888;">
                        <i class="fas fa-user-check"
                            style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                        No pending approvals found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
    <div class="pagination-container">
        <div class="pagination-info">
            Displaying {{ $customers->firstItem() }}-{{ $customers->lastItem() }} of {{ $customers->total() }} Pending
            Requests
        </div>
        {{ $customers->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection