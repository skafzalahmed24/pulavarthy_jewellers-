@extends('layouts.admin')

@section('title', 'Pending Payments Report')

@section('content')
<div class="dashboard-header">
    <div>
        <h1>Pending Payments Report</h1>
        <p style="color: var(--text-secondary);">View and filter customers with overdue pending payments.</p>
    </div>
</div>

<div class="luxury-card" style="margin-bottom: 2rem;">
    <form action="{{ route('admin.reports.pending_payments') }}" method="GET">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-secondary);">Scheme ID</label>
                <input type="text" name="scheme_number" value="{{ request('scheme_number') }}" class="form-control" placeholder="Search Scheme ID..." style="height: 45px;">
            </div>
            <div class="form-group" style="margin: 0;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-secondary);">Customer Name</label>
                <input type="text" name="customer_name" value="{{ request('customer_name') }}" class="form-control" placeholder="Search Customer..." style="height: 45px;">
            </div>
            <div class="form-group" style="margin: 0;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-secondary);">Phone Number</label>
                <input type="text" name="phone_number" value="{{ request('phone_number') }}" class="form-control" placeholder="Search Phone..." style="height: 45px;">
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn-premium" style="height: 45px; margin: 0; flex: 1; display: inline-flex; justify-content: center; align-items: center;"><i class="fas fa-search" style="margin-right: 5px;"></i> Search</button>
                <a href="{{ route('admin.reports.pending_payments') }}" class="btn-premium btn-secondary" style="text-decoration: none; height: 45px; margin: 0; display: inline-flex; justify-content: center; align-items: center; padding: 0 1rem;"><i class="fas fa-times"></i></a>
            </div>
        </div>
    </form>
</div>

<div class="luxury-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive" style="padding: 1rem;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--bg-secondary); border-bottom: 1px solid #eee;">
                    <th style="padding: 1.5rem 1rem;">Scheme ID</th>
                    <th style="padding: 1.5rem 1rem;">Customer</th>
                    <th style="padding: 1.5rem 1rem;">Phone</th>
                    <th style="padding: 1.5rem 1rem;">Pending Months</th>
                    <th style="padding: 1.5rem 1rem;">Pending Amount</th>
                    <th style="padding: 1.5rem 1rem;">Total Due</th>
                    <th style="padding: 1.5rem 1rem;">Installments</th>
                    <th style="padding: 1.5rem 1rem; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schemes as $row)
                <tr style="border-bottom: 1px solid #eee; transition: background 0.3s ease;">
                    <td style="padding: 1.5rem 1rem; font-weight: 700; color: var(--accent-color);">
                        {{ $row->scheme_id }}
                    </td>
                    <td style="padding: 1.5rem 1rem; font-weight: 600;">
                        {{ $row->customer_name }}
                    </td>
                    <td style="padding: 1.5rem 1rem; color: var(--text-secondary);">
                        {{ $row->phone_number }}
                    </td>
                    <td style="padding: 1.5rem 1rem; line-height: 1.6;">
                        <span style="background: #fff3e0; color: #e65100; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.85rem; font-weight: bold; display: inline-block;">
                            {{ $row->pending_months }}
                        </span>
                    </td>
                    <td style="padding: 1.5rem 1rem; color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6;">
                        {{ $row->pending_amounts }}
                    </td>
                    <td style="padding: 1.5rem 1rem; font-weight: 800; color: #c62828;">
                        ₹{{ number_format($row->total_pending, 2) }}
                    </td>
                    <td style="padding: 1.5rem 1rem;">
                        <span style="background: #e8f5e9; color: #2e7d32; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700;">
                            Paid: {{ $row->paid_count }} / {{ $row->total_months }}
                        </span>
                    </td>
                    <td style="padding: 1.5rem 1rem; text-align: center;">
                        <a href="{{ route('admin.customers.payment_terms', $row->user_id) }}" class="btn-action btn-view" title="In-detail View" style="padding: 0.5rem 1rem; background: var(--bg-secondary); border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-decoration: none; color: var(--heading-color); display: inline-block;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 4rem; text-align: center; color: #888;">
                        <i class="fas fa-check-circle" style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.3; color: #4caf50;"></i>
                        No pending payments found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($schemes->hasPages())
    <div class="pagination-container">
        <div class="pagination-info">
            Displaying {{ $schemes->firstItem() }}-{{ $schemes->lastItem() }} of {{ $schemes->total() }} Records
        </div>
        {{ $schemes->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection
