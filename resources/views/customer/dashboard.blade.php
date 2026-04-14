@extends('layouts.luxury')

@section('title', 'My Dashboard')

@section('content')
<section class="section-standard" style="background: var(--bg-secondary); min-height: 80vh;">
    <div class="container-standard">
        <div style="text-align: center; margin-bottom: 3rem; padding-top: 2rem;">
            <h2 class="section-title">My Subscribed Plans</h2>
            <p style="color: var(--text-secondary); max-width: 600px; margin: 1.5rem auto;">Manage your purchase plans, track payments, and secure your golden future.</p>
        </div>

        <div class="tabs-nav" style="text-align: center; margin-bottom: 3rem;">
            <a href="{{ url('/purchase-plan#pay-now') }}" class="tab-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">PAY NOW</a>
            <a href="{{ url('/purchase-plan#my-plan') }}" class="tab-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">MY ACCOUNT</a>
            <button class="tab-btn active" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; cursor: default;">MY DASHBOARD</button>
        </div>

        @if(session('success'))
            <div style="background: #e8f5e9; color: #2e7d32; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif

        @if($userSchemes->isEmpty())
            <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 15px; box-shadow: var(--shadow-soft);">
                <i class="fas fa-box-open" style="font-size: 4rem; color: #ccc; margin-bottom: 1.5rem;"></i>
                <h3 style="color: var(--heading-color); margin-bottom: 1rem;">No Active Plans</h3>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">You haven't subscribed to any plans yet.</p>
                <a href="{{ url('/purchase-plan') }}" class="btn-premium">Explore Plans</a>
            </div>
        @else
            <div class="grid-container grid-3">
                @foreach($userSchemes as $scheme)
                    <div class="luxury-card" style="border: 1px solid rgba(0,0,0,0.05); padding: 2rem; position: relative; overflow: hidden; background: white;">
                        @if($scheme->investmentPlan)
                            <div style="position: absolute; top: 0; right: 0; background: var(--accent-color); color: white; padding: 0.5rem 1.5rem; font-size: 0.8rem; font-weight: bold; border-bottom-left-radius: 15px;">
                                Active
                            </div>
                            <h3 style="color: var(--heading-color); margin-bottom: 0.5rem; margin-top: 1rem;">
                                {{ $scheme->investmentPlan->name }}
                            </h3>
                            <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 0.9rem;">
                                Scheme No: <strong>{{ $scheme->scheme_number ?? 'Pending Approval' }}</strong>
                            </p>
                            
                            <hr style="border: none; border-top: 1px solid #eee; margin-bottom: 1.5rem;">
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 2rem; font-size: 0.95rem;">
                                <span style="color: var(--text-secondary);">Monthly Amt</span>
                                <strong style="color: var(--heading-color);">₹ {{ number_format($scheme->investmentPlan->installment_amount, 2) }}</strong>
                            </div>

                            <a href="{{ route('customer.scheme_details', $scheme->id) }}" class="btn-premium" style="display: block; text-align: center; padding: 1rem; width: 100%;">
                                View Details & Payments
                            </a>
                        @else
                            <div style="text-align: center; color: var(--text-secondary);">
                                Plan information unavailable.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
