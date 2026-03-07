@extends('layouts.admin')

@section('title', 'Investment Plans')

@section('content')
<div class="dashboard-header"
    style="margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Investment Plans</h1>
        <p style="color: var(--text-secondary);">Manage the plans displayed in the "EXPLORE PLANS" section.</p>
    </div>
    <a href="{{ route('admin.plans.create') }}" class="btn-premium" style="text-decoration: none;">
        <i class="fas fa-plus"></i> Create New Plan
    </a>
</div>

@if(session('success'))
<div class="luxury-card alert-auto-dismiss"
    style="background: #e8f5e9; color: #2e7d32; padding: 1rem; margin-bottom: 2rem; border-radius: 12px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

<div class="luxury-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--bg-secondary); border-bottom: 1px solid #eee;">
                    <th style="padding: 1.5rem 2rem;">Plan Details</th>
                    <th style="padding: 1.5rem 2rem;">Term</th>
                    <th style="padding: 1.5rem 2rem;">Monthly</th>
                    <th style="padding: 1.5rem 2rem;">Bonus</th>
                    <th style="padding: 1.5rem 2rem;">Status</th>
                    <th style="padding: 1.5rem 2rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 1.5rem 2rem;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div
                                style="width: 40px; height: 40px; background: var(--accent-light); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--accent-color);">
                                <i class="{{ $plan->icon }}"></i>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: var(--heading-color);">{{ $plan->name }}</div>
                                <div style="font-size: 0.85rem; color: #888;">{{ Str::limit($plan->description, 50) }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.5rem 2rem; font-weight: 600;">{{ $plan->term }}</td>
                    <td style="padding: 1.5rem 2rem; font-weight: 600;">{{ $plan->base_deposit }}</td>
                    <td style="padding: 1.5rem 2rem;">
                        <span style="color: var(--accent-color); font-weight: 700;">{{ $plan->bonus_benefit }}</span>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        @if($plan->is_popular)
                        <span
                            style="background: var(--accent-color); color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">POPULAR</span>
                        @else
                        <span style="color: #888; font-size: 0.75rem;">Standard</span>
                        @endif
                    </td>
                    <td style="padding: 1.5rem 2rem; text-align: right;">
                        <div style="display: flex; gap: 10px; justify-content: flex-end;">
                            <a href="{{ route('admin.plans.edit', $plan->id) }}"
                                style="color: #262261; background: #eee; padding: 10px; border-radius: 8px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST"
                                onsubmit="return confirm('Delete this plan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="color: #c0392b; background: #fee; border: none; padding: 10px; border-radius: 8px; cursor: pointer;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 4rem; text-align: center; color: #888;">No plans found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection