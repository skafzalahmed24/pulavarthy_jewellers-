@extends('layouts.admin')

@section('title', 'Update Market Prices')

@section('content')
<div class="dashboard-header" style="margin-bottom: 3rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Update Market Prices</h1>
    <p style="color: var(--text-secondary);">Manage your store's live market prices for Gold and Silver.</p>
</div>

@if(session('success'))
<div class="luxury-card"
    style="background: #e8f5e9; color: #2e7d32; padding: 1rem; margin-bottom: 2rem; border-radius: 12px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

<div class="luxury-card">
    <h3
        style="margin-bottom: 2rem; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem; display: inline-block;">
        Current Market Rates</h3>

    <form action="{{ route('admin.prices.update') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">
            <!-- Gold Section -->
            <div style="padding: 2rem; border: 1px solid #eee; border-radius: 20px;">
                <h4 style="margin-bottom: 1.5rem; color: var(--accent-color);"><i class="fas fa-coins"></i> 22K Gold
                    Price</h4>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Today's Price (Per
                        Gram)</label>
                    <input type="number" step="0.01" name="gold_today" class="form-control"
                        value="{{ $prices['Gold']->today_price ?? '' }}" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Yesterday's Price (Per
                        Gram)</label>
                    <input type="number" step="0.01" name="gold_yesterday" class="form-control"
                        value="{{ $prices['Gold']->yesterday_price ?? '' }}" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
            </div>

            <!-- Silver Section -->
            <div style="padding: 2rem; border: 1px solid #eee; border-radius: 20px;">
                <h4 style="margin-bottom: 1.5rem; color: #666;"><i class="fas fa-layer-group"></i> Silver Price</h4>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Today's Price (Per
                        Gram)</label>
                    <input type="number" step="0.01" name="silver_today" class="form-control"
                        value="{{ $prices['Silver']->today_price ?? '' }}" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Yesterday's Price (Per
                        Gram)</label>
                    <input type="number" step="0.01" name="silver_yesterday" class="form-control"
                        value="{{ $prices['Silver']->yesterday_price ?? '' }}" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 3rem;">
            <button type="submit" class="btn-premium" style="min-width: 250px; padding: 1.2rem; cursor: pointer;">Update
                Prices Now</button>
        </div>
    </form>
</div>
@endsection