@extends('layouts.admin')

@section('title', 'Price History')

@section('content')
<div class="dashboard-header">
    <div>
        <h1>Price History</h1>
        <p style="color: var(--text-secondary);">View the history of Gold and Silver price updates.</p>
    </div>
    <a href="{{ route('admin.prices.index') }}" class="btn-premium" style="text-decoration: none; background: #333;">
        <i class="fas fa-arrow-left"></i> Back to Update Price
    </a>
</div>

<div class="luxury-card" style="margin-bottom: 2rem;">
    <form action="{{ route('admin.prices.history') }}" method="GET">
        <label style="display: block; font-weight: 600; margin-bottom: 0.8rem; color: var(--text-secondary);">Filter by Type</label>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <select name="type" class="form-control" onchange="this.form.submit()" style="margin: 0; min-width: 200px; max-width: 250px; height: 45px; padding-top: 0; padding-bottom: 0;">
                <option value="">All Types</option>
                <option value="Gold" {{ request('type') == 'Gold' ? 'selected' : '' }}>Gold</option>
                <option value="Silver" {{ request('type') == 'Silver' ? 'selected' : '' }}>Silver</option>
            </select>
            <a href="{{ route('admin.prices.history') }}" class="btn-premium btn-secondary" style="text-decoration: none; margin: 0; height: 45px; display: inline-flex; align-items: center; justify-content: center; padding: 0 2rem;">Clear Filter</a>
        </div>
    </form>
</div>

<div class="luxury-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive" style="padding: 1rem;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--bg-secondary); border-bottom: 1px solid #eee;">
                    <th style="padding: 1.5rem 2rem;">Metal Type</th>
                    <th style="padding: 1.5rem 2rem;">Price Updated</th>
                    <th style="padding: 1.5rem 2rem;">Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $history)
                <tr style="border-bottom: 1px solid #eee; transition: background 0.3s ease;">
                    <td style="padding: 1.5rem 2rem;">
                        <span style="font-weight: 700; color: {{ $history->metal_name == 'Gold' ? '#f7d08a' : '#c0c0c0' }}; font-size: 1.1rem; text-shadow: 0px 0px 1px rgba(0,0,0,0.1);">
                            {{ $history->metal_name }}
                        </span>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <span style="font-weight: 700;">₹{{ number_format($history->price, 2) }}</span> / g
                    </td>
                    <td style="padding: 1.5rem 2rem; color: var(--text-secondary);">
                        {{ $history->created_at->timezone('Asia/Kolkata')->format('d M, Y - h:i A') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding: 4rem; text-align: center; color: #888;">
                        <i class="fas fa-history" style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                        No price history found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($histories->hasPages())
    <div class="pagination-container">
        <div class="pagination-info">
            Displaying {{ $histories->firstItem() }}-{{ $histories->lastItem() }} of {{ $histories->total() }} Records
        </div>
        {{ $histories->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection
