@extends('layouts.luxury')

@section('title', 'Purchase Plans')

@section('content')
<div class="tabs-container">
    <div style="text-align: center; margin-bottom: 5rem;">
        <h1 style="font-size: 3.5rem; margin-bottom: 1rem;">Choose Your Sparkle</h1>
        <p style="color: var(--text-secondary); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Smart investment
            plans designed to make luxury accessible for everyone. Secure your gold today.</p>
    </div>

    @if(session('success'))
    <div class="luxury-card alert-auto-dismiss"
        style="background: #e8f5e9; color: #2e7d32; padding: 1.5rem; margin-bottom: 3rem; border-radius: 12px; font-weight: 600; text-align: center; max-width: 800px; margin-left: auto; margin-right: auto;">
        <i class="fas fa-check-circle" style="margin-right: 10px;"></i> {{ session('success') }}
    </div>
    @endif

    <div class="tabs-nav">
        <button class="tab-btn active" data-tab="pay-now">PAY NOW</button>
        <button class="tab-btn" data-tab="explore-plan">EXPLORE PLANS</button>
        <button class="tab-btn" data-tab="join-new">JOIN NEW PLAN</button>
        @auth
        @if(!auth()->user()->is_admin)
        <button class="tab-btn" data-tab="my-plan">MY ACCOUNT</button>
        @endif
        @endauth
    </div>

    <!-- PAY NOW TAB -->
    <div id="pay-now" class="tab-content active">
        <div style="max-width: 500px; margin: 0 auto;">
            <div class="luxury-card">
                @auth
                @if(auth()->user()->status == 'approved')
                @php
                $userPlan = $plans->where('name', auth()->user()->plan_category)->first();
                $rawBaseDeposit = $userPlan ? $userPlan->base_deposit : 0;
                // Remove currenty symbols, commas etc to get pure numeric value
                $baseDeposit = (float) preg_replace('/[^0-9.]/', '', $rawBaseDeposit);
                @endphp
                <div id="payment-summary">
                    <div style="text-align: center; margin-bottom: 2.5rem;">
                        <h2 style="margin-bottom: 0.5rem;">Hi {{ explode(' ', auth()->user()->name)[0] }},</h2>
                        <p style="color: var(--text-secondary);">Hope you are having a wonderful day!</p>
                    </div>
                    <div style="background: var(--bg-secondary); padding: 2.5rem; border-radius: 20px;">
                        <div style="margin-bottom: 1.5rem; border-bottom: 1px dashed #ddd; padding-bottom: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                                <span style="opacity: 0.7;">Scheme Number</span>
                                <span style="font-weight: 700; color: var(--accent-color);">{{
                                    auth()->user()->scheme_number }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="opacity: 0.7;">Active Plan</span>
                                <span style="font-weight: 700; color: var(--heading-color);">{{
                                    auth()->user()->plan_category }}</span>
                            </div>
                        </div>

                        <div
                            style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; text-align: left;">
                            <div style="background: white; padding: 1rem; border-radius: 12px;">
                                <label
                                    style="display: block; font-size: 0.75rem; opacity: 0.6; margin-bottom: 0.3rem;">Due
                                    Date</label>
                                <span style="font-weight: 700; color: #c0392b;">{{ date('10-M-Y') }}</span>
                            </div>
                            <div style="background: white; padding: 1rem; border-radius: 12px;">
                                <label
                                    style="display: block; font-size: 0.75rem; opacity: 0.6; margin-bottom: 0.3rem;">Payable
                                    Amount</label>
                                <span style="font-weight: 700; color: var(--heading-color);">₹ {{
                                    number_format($baseDeposit) }}</span>
                            </div>
                        </div>

                        <button class="btn-premium" onclick="showCheckout()"
                            style="width: 100%; padding: 1.2rem; border-radius: 12px; font-weight: 700; cursor: pointer;">Proceed
                            with Payment
                        </button>
                    </div>
                </div>

                <!-- CHECKOUT SECTION -->
                <div id="payment-checkout" style="display: none;">
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <button onclick="hideCheckout()"
                            style="background: none; border: none; color: var(--accent-color); cursor: pointer; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 5px; margin: 0 auto 1.5rem;">
                            <i class="fas fa-arrow-left"></i> Back to Summary
                        </button>
                        <h2 style="margin-bottom: 0.5rem;">Secure Checkout</h2>
                        <p style="color: var(--text-secondary);">Complete your monthly investment</p>
                    </div>

                    <div
                        style="background: var(--bg-secondary); padding: 2.5rem; border-radius: 24px; box-shadow: var(--shadow-sm);">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
                            <!-- Row 1 -->
                            <div class="form-group" style="margin: 0;">
                                <label
                                    style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; opacity: 0.7;">Payable
                                    Amount (₹)</label>
                                <input type="number" value="{{ $baseDeposit }}" class="form-control"
                                    style="background: white; border: 1px solid #ddd; padding: 1rem; font-weight: 700; color: var(--heading-color);">
                            </div>
                            <div class="form-group" style="margin: 0;">
                                <label
                                    style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; opacity: 0.7;">Scheme
                                    Number</label>
                                <div
                                    style="background: #f0f0f0; border: 1px solid #ddd; padding: 1rem; border-radius: 10px; font-weight: 700; color: var(--accent-color);">
                                    {{ auth()->user()->scheme_number }}
                                </div>
                            </div>
                            <!-- Row 2 -->
                            <div class="form-group" style="margin: 0;">
                                <label
                                    style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; opacity: 0.7;">Weight
                                    (Approx. Gold)</label>
                                <input type="text" placeholder="--" class="form-control"
                                    style="background: white; border: 1px solid #ddd; padding: 1rem;">
                            </div>
                            <div class="form-group" style="margin: 0;">
                                <label
                                    style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; opacity: 0.7;">Current
                                    Gold Rate (24K)</label>
                                <div
                                    style="background: #f0f0f0; border: 1px solid #ddd; padding: 1rem; border-radius: 10px; font-weight: 700; color: #27ae60;">
                                    ₹ {{ isset($prices['Gold (24K)']) ? number_format($prices['Gold
                                    (24K)']->rate_per_gram) : '--' }} / g
                                </div>
                            </div>
                        </div>

                        <button class="btn-premium"
                            style="width: 100%; padding: 1.2rem; border-radius: 12px; font-weight: 700;">Proceed to
                            Pay</button>
                    </div>
                </div>

                <script>
                    function showCheckout() {
                        document.getElementById('payment-summary').style.display = 'none';
                        document.getElementById('payment-checkout').style.display = 'block';
                    }
                    function hideCheckout() {
                        document.getElementById('payment-summary').style.display = 'block';
                        document.getElementById('payment-checkout').style.display = 'none';
                    }
                </script>
                @elseif(auth()->user()->status == 'pending')
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <h2 style="margin-bottom: 0.5rem;">Ready to Invest, {{ explode(' ', auth()->user()->name)[0] }}?
                    </h2>
                    <p style="color: var(--text-secondary);">Your application is currently <strong
                            style="color: #f9a825;">Pending Approval</strong></p>
                </div>
                <div style="background: var(--bg-secondary); padding: 2.5rem; border-radius: 20px; text-align: center;">
                    <i class="fas fa-user-clock"
                        style="font-size: 3.5rem; color: var(--accent-color); margin-bottom: 2rem; display: block;"></i>
                    <p
                        style="margin-bottom: 2.5rem; font-size: 1.1rem; line-height: 1.6; color: var(--text-secondary);">
                        Please allow 24-48 hours for our team to verify your details. You will be able to make payments
                        once approved.
                    </p>

                    <form action="{{ route('customer.logout') }}" method="POST" style="width: 100%;">
                        @csrf
                        <button type="submit"
                            style="background: transparent; border: 1px solid var(--accent-color); color: var(--accent-color); padding: 0.8rem 2rem; border-radius: 12px; width: 100%; font-weight: 700;">Logout</button>
                    </form>
                </div>
                @else
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <h2 style="margin-bottom: 0.5rem;">Account Status</h2>
                </div>
                <div style="background: var(--bg-secondary); padding: 2.5rem; border-radius: 20px; text-align: center;">
                    <p style="margin-bottom: 2rem; color: #c0392b; font-weight: 600;">Your account has been rejected.
                        Please contact support.</p>
                    <form action="{{ route('customer.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-premium" style="width: 100%;">Return to Login</button>
                    </form>
                </div>
                @endif
                @else
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <h2 style="margin-bottom: 0.5rem;">Welcome Back</h2>
                    <p style="color: var(--text-secondary);">Enter your credentials to manage payments</p>
                </div>
                @if($errors->has('mobile'))
                <div class="luxury-card"
                    style="background: #fdf2f2; border: 1px solid #f8b4b4; color: #9b1c1c; margin-bottom: 2rem; padding: 1rem;">
                    {!! $errors->first('mobile') !!}
                </div>
                @endif
                <form action="{{ route('customer.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label><i class="fas fa-mobile-alt" style="margin-right: 8px;"></i> Registered Mobile</label>
                        <input type="tel" name="mobile" class="form-control" placeholder="98765 43210" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock" style="margin-right: 8px;"></i> Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn-premium"
                        style="width: 100%; margin-top: 1rem; border-radius: 15px;">Login to Portal</button>
                    <div
                        style="text-align: center; margin-top: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                        <a href="#"
                            style="color: var(--accent-color); text-decoration: none; font-weight: 600; font-size: 0.9rem;">Reset
                            Password?</a>
                        <span style="font-size: 0.85rem; color: #888;">No account? <a href="javascript:void(0)"
                                onclick="document.querySelector('[data-tab=\'join-new\']').click()"
                                style="color: var(--accent-color); text-decoration: none; font-weight: 700;">
                                Sign up
                            </a></span>
                    </div>
                </form>
                @endauth
            </div>
        </div>
    </div>

    <!-- EXPLORE PLANS TAB -->
    <div id="explore-plan" class="tab-content">
        <div class="rates-grid">
            @forelse($plans as $plan)
            <div class="luxury-card" style="position: relative; overflow: hidden;">
                @if($plan->is_popular)
                <div
                    style="position: absolute; top: 20px; right: -30px; background: var(--accent-color); color: white; padding: 5px 40px; transform: rotate(45deg); font-size: 0.8rem; font-weight: 700;">
                    POPULAR</div>
                @endif
                <div
                    style="margin-bottom: 2rem; width: 60px; height: 60px; background: var(--accent-light); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                    <i class="{{ $plan->icon }}" style="color: var(--accent-color); font-size: 1.8rem;"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">{{ $plan->name }}</h3>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">{{ $plan->description }}</p>

                <div
                    style="background: var(--bg-secondary); padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                        <span style="opacity: 0.7;">Term</span>
                        <span style="font-weight: 700; color: var(--heading-color);">{{ $plan->term }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                        <span style="opacity: 0.7;">Base Deposit</span>
                        <span style="font-weight: 700; color: var(--heading-color);">{{ $plan->base_deposit }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="opacity: 0.7;">Bonus Benefit</span>
                        <span style="font-weight: 700; color: var(--accent-color);">{{ $plan->bonus_benefit }}</span>
                    </div>
                </div>

                @if($plan->features)
                <ul style="list-style: none; margin-bottom: 2.5rem;">
                    @foreach($plan->features as $feature)
                    <li style="margin-bottom: 0.8rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: #27ae60;"></i> <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
                @endif
                <button class="btn-premium" style="width: 100%;" onclick="openJoinWithPlan('{{ $plan->name }}')">{{
                    $plan->button_text }}</button>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
                <p>No investment plans available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- JOIN NEW PLAN TAB -->
    <div id="join-new" class="tab-content">
        <div class="luxury-card">
            <h2 style="margin-bottom: 3rem; text-align: center;">New Membership Application</h2>

            @if($errors->any() && !$errors->has('mobile'))
            <div class="luxury-card"
                style="background: #fdf2f2; border: 1px solid #f8b4b4; color: #9b1c1c; margin-bottom: 2rem;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">
                    <!-- Section 1 -->
                    <div>
                        <h4
                            style="margin-bottom: 2rem; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem; display: inline-block;">
                            Personal Information</h4>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter first and last name"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input type="tel" name="mobile" class="form-control" placeholder="+91 00000 00000" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@email.com" required>
                        </div>
                        <div class="form-group">
                            <label>Account Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Create a strong password" required>
                            <small style="font-size: 0.75rem; color: #888;">Minimum 8 characters</small>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div>
                        <h4
                            style="margin-bottom: 2rem; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem; display: inline-block;">
                            Address Details</h4>
                        <div class="form-group">
                            <label>Street & Door No.</label>
                            <input type="text" name="address" class="form-control" placeholder="Street layout, number">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Pincode</label>
                                <input type="text" name="pincode" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" class="form-control">
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div>
                        <h4
                            style="margin-bottom: 2rem; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem; display: inline-block;">
                            Plan Selection</h4>
                        <div class="form-group">
                            <label>Identity Proof (Aadhaar/PAN)</label>
                            <input type="text" name="identity_proof" class="form-control" placeholder="Enter ID number">
                        </div>
                        <div class="form-group">
                            <label>Select Plan Category</label>
                            <select name="plan_category" id="plan_category_select" class="form-control">
                                @foreach($plans as $plan)
                                <option value="{{ $plan->name }}">{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Section 4 (Full Width) -->
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px dashed #eee;">
                    <h4 style="margin-bottom: 2rem; color: var(--heading-color);">Nominee Information</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                        <div class="form-group">
                            <label>Nominee Name</label>
                            <input type="text" name="nominee_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Relationship</label>
                            <input type="text" name="nominee_relationship" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Nominee Contact</label>
                            <input type="tel" name="nominee_contact" class="form-control">
                        </div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 4rem;">
                    <button type="submit" class="btn-premium"
                        style="min-width: 320px; font-size: 1.1rem; border-radius: 20px;">Submit My Application</button>
                    <p style="margin-top: 1.5rem; font-size: 0.85rem; opacity: 0.6;">By submitting, you agree to our
                        terms and conditions.</p>
                </div>
            </form>
        </div>
    </div>

    <!-- MY ACCOUNT TAB -->
    @auth
    @if(!auth()->user()->is_admin)
    <div id="my-plan" class="tab-content">
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="luxury-card">
                <div
                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; border-bottom: 1px solid #eee; padding-bottom: 1.5rem;">
                    <div>
                        <h2 style="margin-bottom: 0.5rem;">My Account Portfolio</h2>
                        <p style="color: var(--text-secondary);">Welcome back, {{ auth()->user()->name }}</p>
                    </div>
                    <form action="{{ route('customer.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-premium"
                            style="background: transparent; border: 1px solid var(--accent-color); color: var(--accent-color); padding: 0.5rem 1.5rem;">Logout</button>
                    </form>
                </div>


                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
                    <div>
                        <h4 style="margin-bottom: 1.5rem; color: var(--heading-color);">Personal Details</h4>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; opacity: 0.6;">Registered Mobile</label>
                            <span style="font-weight: 600;">{{ auth()->user()->mobile }}</span>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; opacity: 0.6;">Email Address</label>
                            <span style="font-weight: 600;">{{ auth()->user()->email }}</span>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; opacity: 0.6;">Address</label>
                            <span style="font-weight: 600;">{{ auth()->user()->address }}, {{ auth()->user()->city }},
                                {{ auth()->user()->pincode }}</span>
                        </div>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 1.5rem; color: var(--heading-color);">Plan Information</h4>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; opacity: 0.6;">Scheme Number</label>
                            <span style="font-weight: 700; color: var(--accent-color);">{{ auth()->user()->scheme_number
                                ?? 'Pending Approval' }}</span>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; opacity: 0.6;">Active Plan</label>
                            <span style="font-weight: 600; color: var(--accent-color);">{{ auth()->user()->plan_category
                                }}</span>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; opacity: 0.6;">Account Status</label>
                            <span
                                style="background: {{ auth()->user()->status == 'approved' ? '#e8f5e9' : '#fff8e1' }}; color: {{ auth()->user()->status == 'approved' ? '#2e7d32' : '#f9a825' }}; padding: 0.2rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">
                                {{ ucfirst(auth()->user()->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endauth

    @guest
    <div id="my-plan" class="tab-content">
        <div style="max-width: 500px; margin: 0 auto;">
            <div class="luxury-card" style="text-align: center;">
                <div
                    style="width: 80px; height: 80px; background: var(--bg-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
                    <i class="fas fa-user-circle" style="font-size: 3rem; color: var(--heading-color);"></i>
                </div>
                <h2 style="margin-bottom: 2rem;">Login to Dashboard</h2>
                @if($errors->has('mobile'))
                <div class="luxury-card"
                    style="background: #fdf2f2; border: 1px solid #f8b4b4; color: #9b1c1c; margin-bottom: 2rem; padding: 1rem;">
                    {!! $errors->first('mobile') !!}
                </div>
                @endif
                <form action="{{ route('customer.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="tel" name="mobile" class="form-control" placeholder="Mobile Number" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Account Password"
                            required>
                    </div>
                    <button type="submit" class="btn-premium" style="width: 100%;">Access Account</button>
                    <div style="text-align: center; margin-top: 1.5rem;">
                        <span style="font-size: 0.85rem; color: #888;">No account? <a href="javascript:void(0)"
                                onclick="openJoinTab()"
                                style="color: var(--accent-color); text-decoration: none; font-weight: 700;">Sign
                                up</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endguest
</div>

<script>
    function openJoinTab() {
        const joinTabBtn = document.querySelector('[data-tab="join-new"]');
        if (joinTabBtn) {
            joinTabBtn.click();
            // Scroll to form
            document.getElementById('join-new').scrollIntoView({ behavior: 'smooth' });
        }
    }

    function openJoinWithPlan(planName) {
        openJoinTab();
        const selent.getElementById('plan_category_select');
        if (select) {
            se = planNam  document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tabs = document.querySelectorAll('.tab-content');
                    const targetId = btn.dataset.tab;
                    const targetTab = document.getElementById(targetId);

                    if (!targetTab) return;

                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    tabs.forEach(c => c.classList.remove('active'));

                    btn.classList.add('active');
                    targetTab.classList.add('active');
      );
</script>
@endsection