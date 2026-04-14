@extends('layouts.luxury')

@section('title', 'Purchase Plans')

@section('content')
<div class="tabs-container section-standard">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 class="section-title">Choose Your Sparkle</h1>
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
        @guest
        <button class="tab-btn" data-tab="explore-plan">EXPLORE PLANS</button>
        <button class="tab-btn" data-tab="join-new">JOIN NEW PLAN</button>
        @endguest
        @auth
        @if(!auth()->user()->is_admin)
        <button class="tab-btn" data-tab="my-plan">MY ACCOUNT</button>
        <a href="{{ route('customer.dashboard') }}" class="tab-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">MY DASHBOARD</a>
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
                $userScheme = auth()->user()->userSchemes->first();
                $userPlan = $userScheme?->investmentPlan;
                $rawBaseDeposit = $userPlan ? $userPlan->base_deposit : 0;
                $baseDeposit = (float) preg_replace('/[^0-9.]/', '', $rawBaseDeposit);

                $paymentsCount = $userScheme ? $userScheme->payments()->count() : 0;
                $pendingPayment = $userScheme ? $userScheme->payments()->where('payment_status', 'pending')->orderBy('due_date', 'asc')->first() : null;
                
                $isCompleted = ($paymentsCount > 0 && !$pendingPayment);
                $isFirstPayment = ($paymentsCount == 0);
                $isGraceOver = $pendingPayment && $pendingPayment->grace_end_date && now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($pendingPayment->grace_end_date)->startOfDay());
                $dueDate = $pendingPayment ? $pendingPayment->due_date->format('d-M-Y') : date('d-M-Y');
                $isFuturePayment = $pendingPayment && now()->format('Y-m') < $pendingPayment->due_date->format('Y-m');
                
                $goldPrice = isset($prices) ? $prices->get('Gold') : null;
                $todaysGoldRate = $goldPrice ? (float) preg_replace('/[^0-9.]/', '', $goldPrice->today_price) : 0;
                @endphp
                
                @if(session('success'))
                <div style="background: #e8f5e9; color: #2e7d32; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; justify-content: center; font-weight: 600;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div style="background: #ffebee; color: #c62828; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; justify-content: center; font-weight: 600;">
                    <i class="fas fa-times-circle"></i> {{ session('error') }}
                </div>
                @endif

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
                                    auth()->user()->userSchemes->first()?->scheme_number ?? 'Pending Approval' }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="opacity: 0.7;">Active Plan</span>
                                <span style="font-weight: 700; color: var(--heading-color);">{{
                                    auth()->user()->userSchemes->first()?->investmentPlan->name ?? 'None' }}</span>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; text-align: left;">
                            <div style="background: white; padding: 1rem; border-radius: 12px;">
                                <label style="display: block; font-size: 0.75rem; opacity: 0.6; margin-bottom: 0.3rem;">Due Date</label>
                                <span style="font-weight: 700; color: #c0392b;">{{ $dueDate }}</span>
                            </div>
                            <div style="background: white; padding: 1rem; border-radius: 12px;">
                                <label style="display: block; font-size: 0.75rem; opacity: 0.6; margin-bottom: 0.3rem;">Payable Amount</label>
                                <span style="font-weight: 700; color: var(--heading-color);">₹ {{ number_format($baseDeposit) }}</span>
                            </div>
                            <div style="background: white; padding: 1rem; border-radius: 12px; grid-column: span 2;">
                                <label style="display: block; font-size: 0.75rem; opacity: 0.6; margin-bottom: 0.3rem;">Approx. Gold Weight (Today's 24K Rate)</label>
                                <span style="font-weight: 700; color: var(--accent-color);">
                                    {{ $todaysGoldRate > 0 ? number_format($baseDeposit / $todaysGoldRate, 3) : 0 }} g <small style="color: #888;">(@ ₹{{ number_format($todaysGoldRate) }}/g)</small>
                                </span>
                            </div>
                        </div>

                        @if($isCompleted)
                            <div style="background: #e8f5e9; color: #2e7d32; padding: 1.2rem; border-radius: 12px; text-align: center; font-weight: 700;">
                                Scheme Completed! <i class="fas fa-check-circle"></i>
                            </div>
                        @elseif(isset($isFuturePayment) && $isFuturePayment)
                            <div style="background: #e3f2fd; color: #1976d2; padding: 1.2rem; border-radius: 12px; text-align: center; font-weight: 700;">
                                Upcoming payment is due on {{ $dueDate }}. <i class="fas fa-calendar-check"></i>
                            </div>
                        @elseif($isGraceOver)
                            @if($pendingPayment->grace_extension_status == 'pending')
                                <div style="background: #fff8e1; color: #f9a825; padding: 1.2rem; border-radius: 12px; text-align: center; font-weight: 700;">
                                    <i class="fas fa-clock"></i> Grace Extension Requested
                                </div>
                            @elseif($pendingPayment->grace_extension_status == 'rejected')
                                <div style="background: #ffebee; color: #c62828; padding: 1.2rem; border-radius: 12px; text-align: center; font-weight: 700; margin-bottom: 1rem;">
                                    <i class="fas fa-times-circle"></i> Extension Rejected. Please visit branch.
                                </div>
                            @else
                                <button class="btn-premium" style="width: 100%; padding: 1.2rem; border-radius: 12px; font-weight: 700; background: #c0392b; cursor: pointer;" onclick="document.getElementById('graceModal').style.display='flex'">
                                    <i class="fas fa-exclamation-triangle"></i> Grace Period Over - Request Extension
                                </button>
                            @endif
                        @else
                            <button class="btn-premium" onclick="showCheckout()"
                                style="width: 100%; padding: 1.2rem; border-radius: 12px; font-weight: 700; cursor: pointer;">Proceed
                                with Payment
                            </button>
                        @endif
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
                                <input type="number" id="payable-amount" value="{{ $baseDeposit }}" class="form-control"
                                    placeholder="Enter amount" required readonly
                                    style="background: white; border: 1px solid #ddd; padding: 1rem; font-weight: 700; color: var(--heading-color);">
                            </div>
                            <div class="form-group" style="margin: 0;">
                                <label
                                    style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; opacity: 0.7;">Scheme
                                    Number</label>
                                <div
                                    style="background: #f0f0f0; border: 1px solid #ddd; padding: 1rem; border-radius: 10px; font-weight: 700; color: var(--accent-color);">
                                    {{ auth()->user()->userSchemes->first()?->scheme_number ?? 'Pending Approval' }}
                                </div>
                            </div>
                            <!-- Row 2 -->
                            <div class="form-group" style="margin: 0;">
                                <label
                                    style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; opacity: 0.7;">Weight
                                    (Approx. Gold)</label>
                                <input type="text" id="approx-weight" placeholder="e.g. 1.5g" class="form-control" readonly
                                    style="background: #f9f9f9; border: 1px solid #ddd; padding: 1rem; font-weight: 700; color: var(--accent-color);">
                            </div>
                            <div class="form-group" style="margin: 0;">
                                <label
                                    style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; opacity: 0.7;">Current
                                    Gold Rate (24K)</label>
                                <div
                                    style="background: #f0f0f0; border: 1px solid #ddd; padding: 1rem; border-radius: 10px; font-weight: 700; color: #27ae60;">
                                    ₹ <span id="current-gold-rate">{{ $todaysGoldRate > 0 ? number_format($todaysGoldRate) : '--' }}</span> / g
                                </div>
                                <div style="font-size: 0.75rem; color: #888; margin-top: 0.5rem; text-align: right;">
                                    Updated on: {{ $goldPrice ? $goldPrice->updated_at->format('d M Y - h:i A') : '--' }}
                                </div>
                            </div>
                        </div>

                        <button id="rzp-button1" class="btn-premium"
                            style="width: 100%; padding: 1.2rem; border-radius: 12px; font-weight: 700;">Proceed to
                            Pay</button>
                    </div>
                </div>

                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                <script>
                    document.getElementById('rzp-button1').onclick = function(e){
                        e.preventDefault();
                        const btn = this;
                        btn.disabled = true;
                        btn.innerHTML = 'Processing...';

                        fetch('{{ route("payment.create_order") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.error) {
                                alert(data.error);
                                btn.disabled = false;
                                btn.innerHTML = 'Proceed to Pay';
                                return;
                            }
                            var options = {
                                "key": data.key, 
                                "amount": data.amount * 100,
                                "currency": "INR",
                                "name": "PJ Chits",
                                "description": "Investment Plan Payment",
                                "order_id": data.order_id,
                                "handler": function (response){
                                    fetch('{{ route("payment.verify") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            razorpay_payment_id: response.razorpay_payment_id,
                                            razorpay_order_id: response.razorpay_order_id,
                                            razorpay_signature: response.razorpay_signature
                                        })
                                    })
                                    .then(res => res.json())
                                    .then(verifyData => {
                                        if(verifyData.success) {
                                            alert('Payment successful!');
                                            location.reload();
                                        } else {
                                            alert(verifyData.message);
                                        }
                                    });
                                },
                                "prefill": {
                                    "name": data.user.name,
                                    "email": data.user.email,
                                    "contact": data.user.mobile
                                },
                                "theme": {
                                    "color": "#262261"
                                }
                            };
                            var rzp1 = new Razorpay(options);
                            rzp1.on('payment.failed', function (response){
                                alert(response.error.description);
                                btn.disabled = false;
                                btn.innerHTML = 'Proceed to Pay';
                            });
                            rzp1.open();
                            
                            // fallback just in case they close it
                            setTimeout(() => {
                                btn.disabled = false;
                                btn.innerHTML = 'Proceed to Pay';
                            }, 5000);
                        })
                        .catch(err => {
                            console.error(err);
                            btn.disabled = false;
                            btn.innerHTML = 'Proceed to Pay';
                            alert('Something went wrong!');
                        });
                    }
                    
                    function showCheckout() {
                        document.getElementById('payment-summary').style.display = 'none';
                        document.getElementById('payment-checkout').style.display = 'block';
                    }
                    function hideCheckout() {
                        document.getElementById('payment-summary').style.display = 'block';
                        document.getElementById('payment-checkout').style.display = 'none';
                    }

                    // Auto-calculate logic
                    document.addEventListener('DOMContentLoaded', function() {
                        const amountInput = document.getElementById('payable-amount');
                        const weightInput = document.getElementById('approx-weight');
                        const rateStr = '{{ $todaysGoldRate }}'; // Numeric value injected
                        const rate = parseFloat(rateStr);

                        function recalculate() {
                            const amt = parseFloat(amountInput.value) || 0;
                            if (amt > 0 && rate > 0) {
                                weightInput.value = (amt / rate).toFixed(3) + ' g';
                            } else {
                                weightInput.value = '-- g';
                            }
                        }

                        recalculate();
                        amountInput.addEventListener('input', recalculate);
                    });
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
                        <input type="tel" name="mobile" class="form-control" placeholder="Enter your mobile number" pattern="[0-9]{10}" title="Please enter a valid 10-digit mobile number" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock" style="margin-right: 8px;"></i> Password</label>
                        <div style="position: relative;">
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            <i class="fas fa-eye" onclick="const input = this.parentElement.querySelector('input'); if(input.type === 'password') { input.type = 'text'; this.classList.remove('fa-eye'); this.classList.add('fa-eye-slash'); } else { input.type = 'password'; this.classList.remove('fa-eye-slash'); this.classList.add('fa-eye'); }" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888;"></i>
                        </div>
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
                            <div style="position: relative;">
                                <input type="password" name="password" class="form-control"
                                    placeholder="Create a strong password" required>
                                <i class="fas fa-eye" onclick="const input = this.parentElement.querySelector('input'); if(input.type === 'password') { input.type = 'text'; this.classList.remove('fa-eye'); this.classList.add('fa-eye-slash'); } else { input.type = 'password'; this.classList.remove('fa-eye-slash'); this.classList.add('fa-eye'); }" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888;"></i>
                            </div>
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
                            <input type="text" name="address" class="form-control" placeholder="e.g. 12-34/A, Golden Street" required>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" placeholder="e.g. Vijayawada" required>
                            </div>
                            <div class="form-group">
                                <label>Pincode</label>
                                <input type="text" name="pincode" class="form-control" placeholder="6 digits" pattern="[0-9]{6}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" class="form-control" placeholder="e.g. Andhra Pradesh" required>
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div>
                        <h4
                            style="margin-bottom: 2rem; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem; display: inline-block;">
                            Plan Selection</h4>
                        <div class="form-group">
                            <label>Identity Proof (Aadhaar/PAN)</label>
                            <input type="text" name="identity_proof" class="form-control" placeholder="Enter Aadhaar or PAN number" required>
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
                            <input type="text" name="nominee_name" class="form-control" placeholder="Enter nominee's full name" required>
                        </div>
                        <div class="form-group">
                            <label>Relationship</label>
                            <input type="text" name="nominee_relationship" class="form-control" placeholder="e.g. Spouse, Parent" required>
                        </div>
                        <div class="form-group">
                            <label>Nominee Contact</label>
                            <input type="tel" name="nominee_contact" class="form-control" placeholder="10-digit number" pattern="[0-9]{10}" required>
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
                            <span style="font-weight: 700; color: var(--accent-color);">{{ auth()->user()->userSchemes->first()?->scheme_number
                                ?? 'Pending Approval' }}</span>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; opacity: 0.6;">Active Plan</label>
                            <span style="font-weight: 600; color: var(--accent-color);">{{ auth()->user()->userSchemes->first()?->investmentPlan->name ?? 'None'
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
                        <div style="position: relative;">
                            <input type="password" name="password" class="form-control" placeholder="Account Password"
                                required>
                            <i class="fas fa-eye" onclick="const input = this.parentElement.querySelector('input'); if(input.type === 'password') { input.type = 'text'; this.classList.remove('fa-eye'); this.classList.add('fa-eye-slash'); } else { input.type = 'password'; this.classList.remove('fa-eye-slash'); this.classList.add('fa-eye'); }" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888;"></i>
                        </div>
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

<!-- Grace Period Modal -->
@if(isset($isGraceOver) && $isGraceOver && $pendingPayment)
<div id="graceModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 20px; padding: 2.5rem; width: 90%; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; font-weight: 700; color: var(--heading-color);">Request Grace Extension</h3>
            <button onclick="document.getElementById('graceModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #888;">&times;</button>
        </div>
        <form action="{{ route('customer.request_grace') }}" method="POST">
            @csrf
            <input type="hidden" name="payment_id" value="{{ $pendingPayment->id }}">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Reason for Delay</label>
                <textarea name="reason" rows="4" style="width: 100%; padding: 1rem; border-radius: 12px; border: 1px solid #ddd; resize: vertical; font-family: inherit;" required placeholder="Please explain why you need an extension..."></textarea>
            </div>
            <button type="submit" class="btn-premium" style="width: 100%; padding: 1.2rem; border-radius: 12px; font-weight: 700;">Submit Request</button>
        </form>
    </div>
</div>
@endif

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
        const select = document.getElementById('plan_category_select');
        if (select) {
            select.value = planName;
        }
    }
    
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const tabs = document.querySelectorAll('.tab-content');
            const targetId = btn.dataset.tab;
            const targetTab = document.getElementById(targetId);

            if (!targetTab) return;

            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            tabs.forEach(c => c.classList.remove('active'));

            btn.classList.add('active');
            targetTab.classList.add('active');
        });
    });

    function handleHashChange() {
        if(window.location.hash) {
            const hashTarget = window.location.hash.substring(1);
            const hashBtn = document.querySelector('.tab-btn[data-tab="' + hashTarget + '"]');
            if(hashBtn) {
                hashBtn.click();
            }
        }
    }

    // Handle initial load
    setTimeout(handleHashChange, 100);
    
    // Handle subsequent hash changes (e.g., clicking dropdown links on the same page)
    window.addEventListener('hashchange', handleHashChange);
</script>
@endsection