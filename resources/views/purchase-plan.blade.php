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
        @php
            $showOnboardingFlow = auth()->check()
                && !auth()->user()->is_admin
                && auth()->user()->status == 'approved'
                && !auth()->user()->userSchemes->first();
        @endphp
        <div class="pay-now-shell {{ $showOnboardingFlow ? 'pay-now-shell-wide' : 'pay-now-shell-compact' }}">
            <div class="luxury-card pay-now-card {{ $showOnboardingFlow ? 'pay-now-card--onboarding' : '' }}">
                @auth
                @if(auth()->user()->status == 'approved')
                @php
                $userScheme = auth()->user()->userSchemes->first();
                
                if (!$userScheme) {
                    $plans = \App\Models\InvestmentPlan::all();
                }
                
                $userPlan = $userScheme?->investmentPlan;
                $rawBaseDeposit = $userPlan ? $userPlan->base_deposit : 0;
                $baseDeposit = $userScheme?->monthly_amount ?? (float) preg_replace('/[^0-9.]/', '', $rawBaseDeposit);

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

                @if($userScheme)
                <div id="payment-summary">
                    <div style="text-align: center; margin-bottom: 2.5rem;">
                        <h2 style="margin-bottom: 0.5rem;">Hi {{ explode(' ', auth()->user()->name)[0] }},</h2>
                        <p style="color: var(--text-secondary);">Hope you are having a wonderful day!</p>
                    </div>
                    <div class="dashboard-card luxury-card">
                        <div class="dashboard-header">
                            <div class="header-row">
                                <span class="label">Scheme Number</span>
                                <span class="value accent">{{ $userScheme->scheme_number ?? 'Pending Approval' }}</span>
                            </div>
                            <div class="header-row">
                                <span class="label">Active Plan</span>
                                <span class="value heading">{{ $userPlan->name ?? 'None' }}</span>
                            </div>
                        </div>

                        <div class="dashboard-grid">
                            <div class="grid-item">
                                <label>Due Date</label>
                                <span class="due-date">{{ $dueDate }}</span>
                            </div>
                            <div class="grid-item">
                                <label>Payable Amount</label>
                                <span class="amount">₹ {{ number_format($baseDeposit) }}</span>
                            </div>
                            <div class="grid-item full-width">
                                <label>Approx. Gold Weight (Today's 24K Rate)</label>
                                <span class="weight">
                                    {{ $todaysGoldRate > 0 ? number_format($baseDeposit / $todaysGoldRate, 3) : 0 }} g <small>(@ ₹{{ number_format($todaysGoldRate) }}/g)</small>
                                </span>
                            </div>
                        </div>

                        @if($isCompleted)
                            <div class="status-badge success">
                                Scheme Completed! <i class="fas fa-check-circle"></i>
                            </div>
                        @elseif(isset($isFuturePayment) && $isFuturePayment)
                            <div class="status-badge info">
                                Upcoming payment is due on {{ $dueDate }}. <i class="fas fa-calendar-check"></i>
                            </div>
                        @elseif($isGraceOver)
                            @if($pendingPayment->grace_extension_status == 'pending')
                                <div class="status-badge warning">
                                    <i class="fas fa-clock"></i> Grace Extension Requested
                                </div>
                            @elseif($pendingPayment->grace_extension_status == 'rejected')
                                <div class="status-badge danger">
                                    <i class="fas fa-times-circle"></i> Extension Rejected. Please visit branch.
                                </div>
                            @else
                                <button class="btn-premium danger" onclick="document.getElementById('graceModal').style.display='flex'">
                                    <i class="fas fa-exclamation-triangle"></i> Grace Period Over - Request Extension
                                </button>
                            @endif
                        @else
                            <button class="btn-premium" onclick="showCheckout()">Proceed with Payment</button>
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
                                    {{ $userScheme->scheme_number ?? 'Pending Approval' }}
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

                <script>
                    // Lazy-loads Razorpay SDK only when needed (prevents background chunk polling)
                    function loadRazorpay(callback) {
                        if (window.Razorpay) { callback(); return; }
                        const script = document.createElement('script');
                        script.src = 'https://checkout.razorpay.com/v1/checkout.js';
                        script.onload = callback;
                        script.onerror = function() { alert('Failed to load payment gateway. Please check your internet connection.'); };
                        document.head.appendChild(script);
                    }

                    document.getElementById('rzp-button1').onclick = function(e){
                        e.preventDefault();
                        const btn = this;
                        btn.disabled = true;
                        btn.innerHTML = 'Processing...';

                        loadRazorpay(function() {
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
                                    "theme": { "color": "#c5a059" },
                                    "modal": {
                                        "ondismiss": function() {
                                            btn.disabled = false;
                                            btn.innerHTML = 'Proceed to Pay';
                                        }
                                    }
                                };
                                var rzp1 = new Razorpay(options);
                                rzp1.on('payment.failed', function (response){
                                    alert(response.error.description);
                                    btn.disabled = false;
                                    btn.innerHTML = 'Proceed to Pay';
                                });
                                rzp1.open();
                            })
                            .catch(err => {
                                console.error(err);
                                btn.disabled = false;
                                btn.innerHTML = 'Proceed to Pay';
                                alert('Something went wrong!');
                            });
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
                @else
                <script>
                    // Shared lazy-loader — available to all enrollment payment calls
                    function loadRazorpay(callback) {
                        if (window.Razorpay) { callback(); return; }
                        var script = document.createElement('script');
                        script.src = 'https://checkout.razorpay.com/v1/checkout.js';
                        script.onload = callback;
                        script.onerror = function() { alert('Failed to load payment gateway. Please check your internet connection.'); };
                        document.head.appendChild(script);
                    }
                </script>
                <div id="selection-form" class="selection-form enrollment-wide">
                    <div class="selection-intro">
                        <h1 class="section-title">Welcome to the Jewellery Purchase Plan</h1>
                        <p class="mt-4">Follow the steps to complete your registration and start your investment.</p>
                    </div>

                    <div class="stepper-wrapper ">
                        <div class="stepper-nav" aria-label="Registration steps">
                            <div class="step-item active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-label">User Details</div>
                            </div>
                            <div class="step-item" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-label">Scheme Details</div>
                            </div>
                            <div class="step-item" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-label">Additional Information</div>
                            </div>
                            <div class="step-item" data-step="4">
                                <div class="step-number">4</div>
                                <div class="step-label">Detail Summary</div>
                            </div>
                            <div class="step-item" data-step="5">
                                <div class="step-number">5</div>
                                <div class="step-label">Payment Method</div>
                            </div>
                        </div>

                        <div class="stepper-content">
                            <form id="multi-step-form" action="{{ route('customer.complete_application') }}" method="POST" novalidate>
                                @csrf

                                <div class="step-content active" id="step-1">
                                    <h3 class="step-heading">Verify your details</h3>

                                    <div class="form-grid form-grid-2 form-grid-readonly">
                                        <div class="form-group form-group-tight" style="grid-column: span 2;">
                                            <label>Full Name</label>
                                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                        </div>
                                        <div class="form-group form-group-tight">
                                            <label>Mobile</label>
                                            <input type="text" class="form-control" value="{{ auth()->user()->mobile }}" readonly>
                                        </div>
                                        <div class="form-group form-group-tight">
                                            <label>Email</label>
                                            <input type="text" class="form-control" value="{{ auth()->user()->email }}" readonly>
                                        </div>
                                    </div>

                                    <div class="luxury-card step-surface">
                                        <h5 class="step-section-title">Delivery details</h5>

                                        <div id="address-list">
                                            @php
                                                $hasAddress = auth()->user()->address && auth()->user()->city && auth()->user()->pincode;
                                            @endphp
                                            <div id="no-address" class="empty-address-state" style="{{ $hasAddress ? 'display: none;' : '' }}">
                                                <button type="button" class="add-address-btn" onclick="toggleAddressDrawer(true)" style="width: 100%; border-style: solid; border-width: 2px;">
                                                    <i class="fas fa-plus-circle" style="margin-right: 8px;"></i> Add Delivery Address
                                                </button>
                                            </div>

                                            <div id="selected-address-preview" style="{{ $hasAddress ? '' : 'display: none;' }} position: relative;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                                    <h5 class="step-section-title" style="margin: 0;">Selected Address</h5>
                                                    <button type="button" onclick="toggleAddressDrawer(true)" style="background: var(--accent-light); color: var(--accent-color); border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;" title="Add Multiple / Change">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <div class="address-card selected">
                                                    <div class="address-icon"><i class="fas fa-map-marker-alt"></i></div>
                                                    <div class="address-details">
                                                        <h5 id="prev-name">{{ auth()->user()->name }}</h5>
                                                        <p id="prev-addr">{{ auth()->user()->address ?? '--' }}</p>
                                                        <p id="prev-city">{{ auth()->user()->city ?? '--' }}{{ auth()->user()->state ? ', '.auth()->user()->state : '' }}{{ auth()->user()->pincode ? ' - '.auth()->user()->pincode : '' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="address" id="final-address" value="{{ auth()->user()->address }}">
                                        <input type="hidden" name="city" id="final-city" value="{{ auth()->user()->city }}">
                                        <input type="hidden" name="pincode" id="final-pincode" value="{{ auth()->user()->pincode }}">
                                        <input type="hidden" name="state" id="final-state" value="{{ auth()->user()->state ?? 'Andhra Pradesh' }}">
                                    </div>

                                    <div class="action-grid action-grid-single">
                                        <button type="button" class="btn-premium" onclick="nextStep(2)">Proceed to next step</button>
                                    </div>
                                </div>

                                <!-- STEP 2: Scheme Details -->
                                <div class="step-content" id="step-2" style="display: none;">
                                    <h3 class="step-heading">Enter details regarding the scheme</h3>
                                    
                                    <div class="luxury-card step-surface">
                                        <div class="form-group">
                                            <label>Select the Scheme Name*</label>
                                            <select name="scheme_id" id="scheme_select_field" class="form-control" required onchange="updatePlanDetails(this)">
                                                <option value="" disabled selected>-- Choose a Plan --</option>
                                                @foreach($plans as $plan)
                                                    <option value="{{ $plan->id }}" data-name="{{ $plan->name }}" data-term="{{ $plan->term }}" data-base="{{ (float) preg_replace('/[^0-9.]/', '', $plan->base_deposit) }}">{{ $plan->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group form-group-tight">
                                            <label>Select monthly amount*</label>
                                            <input type="number" name="monthly_amount" id="monthly_amount_input" class="form-control" placeholder="Enter amount (Min: 5000)" min="5000" value="5000" required>
                                            <small class="field-note">Minimum entry amount ₹5,000 required.</small>

                                            <div style="margin-top: 1.5rem;">
                                                <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.8rem;">Customer usually prefer</p>
                                                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                                    <div class="amount-option" onclick="setQuickAmount(5000)">₹5000</div>
                                                    <div class="amount-option" onclick="setQuickAmount(10000)">₹10000</div>
                                                    <div class="amount-option" onclick="setQuickAmount(15000)">₹15000</div>
                                                    <div class="amount-option" onclick="setQuickAmount(20000)">₹20000</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="plan-preview-box" class="plan-preview" style="display: none;">
                                            <p style="margin: 0; font-size: 0.95rem; color: var(--text-secondary); display: flex; justify-content: space-between;">Term: <strong id="preview-term-txt" style="color: var(--heading-color);">--</strong></p>
                                            <p style="margin: 0.5rem 0 0; font-size: 0.95rem; color: var(--text-secondary); display: flex; justify-content: space-between;">Total Investment: <strong id="preview-total-txt" style="color: var(--accent-color);">--</strong></p>
                                        </div>
                                    </div>

                                    <div class="action-grid">
                                        <button type="button" class="btn-premium btn-secondary" onclick="prevStep(1)">Back</button>
                                        <button type="button" class="btn-premium" onclick="nextStep(3)">Proceed to next step</button>
                                    </div>
                                </div>

                                <div class="step-content" id="step-3" style="display: none;">
                                    <h3 class="step-heading">Additional Information</h3>

                                    <div class="luxury-card step-surface">
                                        <div class="form-grid form-grid-2">
                                            <div class="form-group" id="pan-group" style="display: none;">
                                                <label>PAN Number*</label>
                                                <input type="text" name="pan_number" id="pan_number_val" class="form-control" placeholder="ABCDE1234F" maxlength="10">
                                            </div>
                                            <div class="form-group">
                                                <label>Identity Proof Type*</label>
                                                <select name="identity_proof_type" id="id_type_val" class="form-control" required>
                                                    <option value="Aadhaar">Aadhaar</option>
                                                    <option value="Voter ID">Voter ID</option>
                                                    <option value="Driving License">Driving License</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Identity Proof Number*</label>
                                                <input type="text" name="identity_proof" id="identity_proof_input" class="form-control" placeholder="Enter ID number" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Name of Nominee*</label>
                                                <input type="text" name="nominee_name" class="form-control" placeholder="Full name" required id="nominee_name_val">
                                            </div>
                                            <div class="form-group">
                                                <label>Nominee Relationship*</label>
                                                <select name="nominee_relationship" class="form-control" required id="nominee_rel_val">
                                                    <option value="" disabled selected>-- Select Relationship --</option>
                                                    <option value="Father">Father</option>
                                                    <option value="Mother">Mother</option>
                                                    <option value="Husband">Husband</option>
                                                    <option value="Wife">Wife</option>
                                                    <option value="Son">Son</option>
                                                    <option value="Daughter">Daughter</option>
                                                    <option value="Brother">Brother</option>
                                                    <option value="Sister">Sister</option>
                                                    <option value="Grandfather">Grandfather</option>
                                                    <option value="Grandmother">Grandmother</option>
                                                    <option value="Uncle">Uncle</option>
                                                    <option value="Aunt">Aunt</option>
                                                    <option value="Cousin">Cousin</option>
                                                    <option value="Father-in-law">Father-in-law</option>
                                                    <option value="Mother-in-law">Mother-in-law</option>
                                                    <option value="Son-in-law">Son-in-law</option>
                                                    <option value="Daughter-in-law">Daughter-in-law</option>
                                                    <option value="Legal Guardian">Legal Guardian</option>
                                                    <option value="Friend">Friend</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Nominee Contact Number*</label>
                                                <input type="tel" name="nominee_contact" class="form-control" placeholder="10 digits" pattern="[0-9]{10}" required id="nominee_tel_val">
                                            </div>
                                            <div class="form-group">
                                                <label>Choose date of birth*</label>
                                                <input type="date" name="dob" class="form-control" required id="dob_val">
                                            </div>
                                            <div class="form-group">
                                                <label>Wedding Anniversary</label>
                                                <input type="date" name="wedding_anniversary" class="form-control" id="anniversary_val">
                                            </div>
                                            <div class="form-group">
                                                <label>Bank account number</label>
                                                <input type="text" name="bank_acc_no" class="form-control" placeholder="Enter Account number" id="bank_acc_val">
                                            </div>
                                            <div class="form-group">
                                                <label>Re-enter bank account number</label>
                                                <input type="text" class="form-control" placeholder="Re-enter Account number" id="bank_acc_confirm_val">
                                            </div>
                                            <div class="form-group">
                                                <label>Bank branch</label>
                                                <input type="text" name="bank_branch" class="form-control" placeholder="Enter Branch name" id="bank_branch_val">
                                            </div>
                                            <div class="form-group">
                                                <label>IFSC Code</label>
                                                <input type="text" name="ifsc_code" class="form-control" placeholder="e.g. SBIN0012345" id="ifsc_val">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="action-grid">
                                        <button type="button" class="btn-premium btn-secondary" onclick="prevStep(2)">Back</button>
                                        <button type="button" class="btn-premium" onclick="generateSummary()">Proceed to next step</button>
                                    </div>
                                </div>

                                <div class="step-content" id="step-4" style="display: none;">
                                    <h3 class="step-heading">Detail Summary</h3>
                                    
                                    <div class="summary-block">
                                        <div class="summary-grid">
                                            <div>
                                                <h5 class="summary-section-title">Scheme Details</h5>
                                                <div class="summary-item">
                                                    <label>Selected Plan</label>
                                                    <span id="sum-plan">--</span>
                                                </div>
                                                <div class="summary-item">
                                                    <label>Monthly Amount</label>
                                                    <span id="sum-amount">--</span>
                                                </div>
                                            </div>
                                            <div>
                                                <h5 class="summary-section-title">Personal & Nominee</h5>
                                                <div class="summary-item">
                                                    <label>Nominee</label>
                                                    <span id="sum-nominee">--</span>
                                                </div>
                                                <div class="summary-item">
                                                    <label>ID Proof</label>
                                                    <span id="sum-id">--</span>
                                                </div>
                                                <div class="summary-item" id="sum-pan-item" style="display: none;">
                                                    <label>PAN Number</label>
                                                    <span id="sum-pan-no">--</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="summary-bank-block">
                                            <h5 class="summary-section-title">Bank Details</h5>
                                            <div class="summary-bank-grid">
                                                <div class="summary-item">
                                                    <label>Account No</label>
                                                    <span id="sum-bank-acc">--</span>
                                                </div>
                                                <div class="summary-item">
                                                    <label>IFSC</label>
                                                    <span id="sum-ifsc">--</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="action-grid">
                                        <button type="button" class="btn-premium btn-secondary" onclick="prevStep(3)">Back</button>
                                        <button type="button" class="btn-premium" onclick="nextStep(5)">Proceed to next step</button>
                                    </div>
                                </div>

                                <div class="step-content" id="step-5" style="display: none;">
                                    <h3 class="step-heading">Choose Payment Method</h3>
                                    
                                    <div class="payment-method-card selected">
                                        <div style="width: 40px; height: 40px; background: var(--accent-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--accent-color);">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <h5 style="margin: 0; font-family: 'Poppins', sans-serif;">Pay with Razorpay</h5>
                                            <p style="margin: 0; font-size: 0.85rem; color: var(--text-secondary);">Secure online payment via credit card, UPI, or Net Banking.</p>
                                        </div>
                                        <div class="selection-check active"></div>
                                    </div>

                                    <div style="background: #fff8e1; border-radius: 12px; padding: 1.5rem; border-left: 4px solid #f9a825; margin-bottom: 2rem;">
                                        <p style="margin: 0; font-size: 0.9rem; color: #856404;">
                                            <i class="fas fa-info-circle"></i> You will be redirected to the secure payment gateway to complete your first instalment.
                                        </p>
                                    </div>

                                    <div class="action-grid">
                                        <button type="button" class="btn-premium btn-secondary" onclick="prevStep(4)">Back</button>
                                        <button type="button" id="enroll-pay-btn" class="btn-premium" onclick="startEnrollmentPayment()">Activate & Pay Now</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="drawer-overlay" id="drawer-overlay" onclick="toggleAddressDrawer(false)"></div>
                <div class="address-drawer" id="address-drawer" aria-hidden="true">
                    <div class="drawer-header">
                        <h4 style="margin: 0; font-family: 'Poppins', sans-serif; font-weight: 600;">Delivery Address</h4>
                        <i class="fas fa-times" style="cursor: pointer; font-size: 1.2rem; color: var(--text-secondary);" onclick="toggleAddressDrawer(false)"></i>
                    </div>
                    <div class="drawer-body">
                        <div class="form-group">
                            <label>Street & Door No.</label>
                            <input type="text" id="draw-addr" class="form-control" placeholder="e.g. 12-34/A, Golden Street">
                        </div>
                        <div class="drawer-grid">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" id="draw-city" class="form-control" placeholder="e.g. Kakinada">
                            </div>
                            <div class="form-group">
                                <label>Pincode</label>
                                <input type="text" id="draw-pin" class="form-control" placeholder="6 digits" maxlength="6">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <select id="draw-state" class="form-control">
                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                <option value="Telangana">Telangana</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Karnataka">Karnataka</option>
                                <option value="Kerala">Kerala</option>
                            </select>
                        </div>
                        <button type="button" class="btn-premium" style="width: 100%; margin-top: 1rem;" onclick="saveDrawerAddress()">Save Address Details</button>
                    </div>
                </div>

                <script>
                    let currentStep = 1;

                    function setQuickAmount(amount) {
                        document.getElementById('monthly_amount_input').value = amount;
                        
                        // Update active state
                        document.querySelectorAll('.amount-option').forEach(opt => {
                            opt.classList.remove('active');
                            if(parseInt(opt.innerText.replace('₹', '')) === amount) {
                                opt.classList.add('active');
                            }
                        });

                        // Trigger term calculation if plan selected
                        const select = document.getElementById('scheme_select_field');
                        if(select.value) updatePlanDetails(select);

                        // Toggle PAN requirement logic
                        togglePanRequirement(amount);
                    }

                    function togglePanRequirement(amount) {
                        const panGroup = document.getElementById('pan-group');
                        const panInput = document.getElementById('pan_number_val');
                        if (amount >= 18000) {
                            panGroup.style.display = 'block';
                            panInput.required = true;
                        } else {
                            panGroup.style.display = 'none';
                            panInput.required = false;
                        }
                    }

                    // Also bind to manual input
                    document.getElementById('monthly_amount_input').onchange = function() {
                        togglePanRequirement(parseFloat(this.value));
                    };
                    document.getElementById('monthly_amount_input').oninput = function() {
                        togglePanRequirement(parseFloat(this.value));
                    };

                    function toggleAddressDrawer(show) {
                        const drawer = document.getElementById('address-drawer');
                        const overlay = document.getElementById('drawer-overlay');
                        
                        if (show) {
                            drawer.classList.add('active');
                            overlay.classList.add('active');
                            overlay.style.display = 'block';
                        } else {
                            drawer.classList.remove('active');
                            overlay.classList.remove('active');
                            setTimeout(() => {
                                if(!overlay.classList.contains('active')) overlay.style.display = 'none';
                            }, 300);
                        }
                    }

                    function saveDrawerAddress() {
                        const addr = document.getElementById('draw-addr').value;
                        const city = document.getElementById('draw-city').value;
                        const pin = document.getElementById('draw-pin').value;
                        const state = document.getElementById('draw-state').value;

                        if(!addr || !city || !pin) {
                            alert('Please fill all address fields');
                            return;
                        }

                        // Update hidden fields
                        document.getElementById('final-address').value = addr;
                        document.getElementById('final-city').value = city;
                        document.getElementById('final-pincode').value = pin;
                        document.getElementById('final-state').value = state;

                        // Update preview
                        document.getElementById('prev-addr').innerText = addr;
                        document.getElementById('prev-city').innerText = city + ', ' + state + ' - ' + pin;
                        
                        document.getElementById('no-address').style.display = 'none';
                        document.getElementById('selected-address-preview').style.display = 'block';

                        toggleAddressDrawer(false);
                    }

                    function nextStep(step) {
                        goToStep(step);
                    }

                    function prevStep(step) {
                        goToStep(step);
                    }

                    function goToStep(step) {
                        const getVal = (id) => {
                            const el = document.getElementById(id);
                            return el ? el.value : '';
                        };

                        const getEl = (id) => document.getElementById(id);

                        // Validate current step before moving forward
                        if (step > currentStep) {
                            if (currentStep === 1) {
                                if(!getVal('final-address')) {
                                    alert('Please add a delivery address.');
                                    return;
                                }
                            }
                            if (currentStep === 2) {
                                const schemeSelect = getEl('scheme_select_field');
                                if(!schemeSelect || !schemeSelect.value) {
                                    alert('Please select a plan.');
                                    return;
                                }
                                const amountInput = getEl('monthly_amount_input');
                                const amount = amountInput ? parseFloat(amountInput.value) : 0;
                                if(amount < 3000) {
                                    alert('Minimum investment amount is ₹3,000.');
                                    return;
                                }
                            }
                            if (currentStep === 3) {
                                const required = [
                                    'identity_proof_input', 'nominee_name_val', 'nominee_rel_val', 
                                    'nominee_tel_val', 'dob_val'
                                ];
                                for(let id of required) {
                                    const el = getEl(id);
                                    if(el && !el.value) {
                                        alert('Please fill all required fields marked with *');
                                        return;
                                    }
                                }
                                const bankAcc = getVal('bank_acc_val');
                                const bankAccConfirm = getVal('bank_acc_confirm_val');
                                if(bankAcc && bankAcc !== bankAccConfirm) {
                                    alert('Bank account numbers do not match.');
                                    return;
                                }
                            }
                        }

                        // Hide all steps
                        document.querySelectorAll('.step-content').forEach(s => s.style.display = 'none');
                        
                        // Show new
                        currentStep = step;
                        const targetStep = document.getElementById('step-' + currentStep);
                        if (targetStep) {
                            targetStep.style.display = 'block';
                            targetStep.style.animation = 'fadeInUp 0.5s ease forwards';
                        }
                        
                        // Update nav
                        document.querySelectorAll('.step-item').forEach(i => {
                            const s = parseInt(i.getAttribute('data-step'));
                            i.classList.remove('active');
                            if (s === currentStep) {
                                i.classList.add('active');
                            }
                            if (s < currentStep) {
                                i.classList.add('completed');
                            } else {
                                i.classList.remove('completed');
                            }
                        });
                        
                        document.getElementById('selection-form').scrollIntoView({ behavior: 'smooth' });
                    }

                    // Disable direct clicks on future steps
                    document.querySelectorAll('.step-item').forEach(item => {
                        item.onclick = function() {
                            const target = parseInt(this.getAttribute('data-step'));
                            if (target < currentStep || (target === currentStep + 1 && canGoToNext())) {
                                goToStep(target);
                            }
                        };
                    });

                    function canGoToNext() {
                        // Add validation logic here if needed for clicking next step nav
                        return true; 
                    }

                    function generateSummary() {
                        const getVal = (id) => {
                            const el = document.getElementById(id);
                            return el ? el.value : '';
                        };
                        const setTxt = (id, txt) => {
                            const el = document.getElementById(id);
                            if(el) el.innerText = txt;
                        };

                        // Populate summary fields
                        const schemeSelect = document.getElementById('scheme_select_field');
                        const selectedOption = schemeSelect ? schemeSelect.options[schemeSelect.selectedIndex] : null;
                        const amount = parseFloat(getVal('monthly_amount_input') || '0');
                        
                        setTxt('sum-plan', selectedOption ? selectedOption.getAttribute('data-name') : '--');
                        setTxt('sum-amount', '₹ ' + amount.toLocaleString());
                        
                        setTxt('sum-nominee', getVal('nominee_name_val') + ' (' + getVal('nominee_rel_val') + ')');
                        setTxt('sum-id', getVal('id_type_val') + ': ' + getVal('identity_proof_input'));
                        
                        const panItem = document.getElementById('sum-pan-item');
                        if(amount >= 18000) {
                            if(panItem) panItem.style.display = 'block';
                            setTxt('sum-pan-no', getVal('pan_number_val'));
                        } else {
                            if(panItem) panItem.style.display = 'none';
                        }

                        setTxt('sum-bank-acc', getVal('bank_acc_val'));
                        setTxt('sum-ifsc', getVal('ifsc_val'));

                        goToStep(4);
                    }

                    function startEnrollmentPayment() {
                        const btn = document.getElementById('enroll-pay-btn');
                        const amount = parseFloat(document.getElementById('monthly_amount_input').value);
                        
                        if(!amount || amount < 5000) {
                            alert('Invalid amount selected.');
                            return;
                        }

                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Initializing...';

                        const formData = new FormData(document.getElementById('multi-step-form'));
                        
                        fetch('{{ route("customer.enroll_scheme_ajax") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.error) {
                                alert(data.error);
                                btn.disabled = false;
                                btn.innerHTML = 'Activate & Pay Now';
                                return;
                            }

                            // Load Razorpay only now — after server confirmed the order
                            loadRazorpay(function() {
                                var options = {
                                    "key": data.key,
                                    "amount": data.amount * 100,
                                    "currency": "INR",
                                    "name": "PJ Chits Enrollment",
                                    "description": "Scheme Enrollment Fee",
                                    "order_id": data.order_id,
                                    "handler": function (response){
                                        fetch('{{ route("customer.enroll_verify_payment") }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                razorpay_payment_id: response.razorpay_payment_id,
                                                razorpay_order_id: response.razorpay_order_id,
                                                razorpay_signature: response.razorpay_signature,
                                                enrollment_id: data.enrollment_id
                                            })
                                        })
                                        .then(res => res.json())
                                        .then(verifyData => {
                                            if(verifyData.success) {
                                                alert('Enrollment successful! Your account is now active.');
                                                window.location.href = '{{ route("customer.dashboard") }}';
                                            } else {
                                                alert(verifyData.message || 'Payment verification failed.');
                                                btn.disabled = false;
                                                btn.innerHTML = 'Activate & Pay Now';
                                            }
                                        });
                                    },
                                    "prefill": {
                                        "name": "{{ auth()->user()->name }}",
                                        "email": "{{ auth()->user()->email }}",
                                        "contact": "{{ auth()->user()->mobile }}"
                                    },
                                    "theme": { "color": "#c5a059" },
                                    "modal": {
                                        "ondismiss": function() {
                                            btn.disabled = false;
                                            btn.innerHTML = 'Activate & Pay Now';
                                        }
                                    }
                                };
                                var rzp1 = new Razorpay(options);
                                rzp1.open();
                            });
                        })
                        .catch(err => {
                            console.error(err);
                            alert('An error occurred. Please try again.');
                            btn.disabled = false;
                            btn.innerHTML = 'Activate & Pay Now';
                        });
                    }

                    function updatePlanDetails(select) {
                        const option = select.options[select.selectedIndex];
                        const termTxt = option.getAttribute('data-term');
                        const amountInput = document.getElementById('monthly_amount_input');
                        const previewBox = document.getElementById('plan-preview-box');
                        const previewTerm = document.getElementById('preview-term-txt');
                        const previewTotal = document.getElementById('preview-total-txt');

                        if (termTxt) {
                            previewBox.style.display = 'block';
                            previewTerm.innerText = termTxt;
                            
                            const termNum = parseInt(termTxt.replace(/[^0-9]/g, '')) || 11;
                            const currentAmount = parseFloat(amountInput.value) || 0;
                            const total = termNum * currentAmount;
                            previewTotal.innerText = '₹ ' + total.toLocaleString();

                            amountInput.oninput = function() {
                                const newAmount = parseFloat(this.value) || 0;
                                const newTotal = termNum * newAmount;
                                previewTotal.innerText = '₹ ' + newTotal.toLocaleString();
                                togglePanRequirement(newAmount);
                            };
                        } else {
                            previewBox.style.display = 'none';
                        }
                    }
                </script>

                @endif

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
                        <!-- <a href="#"
                            style="color: var(--accent-color); text-decoration: none; font-weight: 600; font-size: 0.9rem;">Reset
                            Password?</a> -->
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
        <div style="max-width: 600px; margin: 0 auto;">
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
                        <!-- Only Personal Information Section -->
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
                                <input type="tel" name="mobile" class="form-control" placeholder="10-digit mobile number" pattern="[0-9]{10}" required>
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




                    <div style="text-align: center; margin-top: 4rem;">
                        <button type="submit" class="btn-premium"
                            style="width: 100%; font-size: 1.1rem; border-radius: 20px;">Submit My Application</button>
                        <p style="margin-top: 1.5rem; font-size: 0.85rem; opacity: 0.6;">By submitting, you agree to our
                            terms and conditions.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MY ACCOUNT TAB -->
    @auth
    @if(!auth()->user()->is_admin)
    <div id="my-plan" class="tab-content">
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="luxury-card">
                <div class="account-header">
                    <div>
                        <h2 style="margin-bottom: 0.5rem;">My Account Portfolio</h2>
                        <p style="color: var(--text-secondary);">Welcome back, {{ auth()->user()->name }}</p>
                    </div>
                    <form action="{{ route('customer.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-premium btn-logout-pill">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>


                <div class="account-details-grid">
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

                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #eee;">
                    <h4 style="margin-bottom: 1.5rem; color: var(--heading-color);">Bank & Nominee Details</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
                        <div>
                            <label style="display: block; font-size: 0.8rem; opacity: 0.6;">Nominee Name</label>
                            <span style="font-weight: 600;">{{ auth()->user()->nominee_name ?? '--' }}</span>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; opacity: 0.6;">Relationship</label>
                            <span style="font-weight: 600;">{{ auth()->user()->nominee_relationship ?? '--' }}</span>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; opacity: 0.6;">Bank Account</label>
                            <span style="font-weight: 600;">{{ auth()->user()->bank_acc_no ?? '--' }}</span>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; opacity: 0.6;">IFSC Code</label>
                            <span style="font-weight: 600;">{{ auth()->user()->ifsc_code ?? '--' }}</span>
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
