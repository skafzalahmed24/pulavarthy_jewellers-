@extends('layouts.luxury')

@section('title', 'Purchase Plans')

@section('content')
<div class="tabs-container">
    <div style="text-align: center; margin-bottom: 5rem;">
        <h1 style="font-size: 3.5rem; margin-bottom: 1rem;">Choose Your Sparkle</h1>
        <p style="color: var(--text-secondary); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Smart investment plans designed to make luxury accessible for everyone. Secure your gold today.</p>
    </div>

    <div class="tabs-nav">
        <button class="tab-btn active" data-tab="pay-now">PAY NOW</button>
        <button class="tab-btn" data-tab="explore-plan">EXPLORE PLANS</button>
        <button class="tab-btn" data-tab="join-new">JOIN NEW PLAN</button>
        <button class="tab-btn" data-tab="my-plan">MY ACCOUNT</button>
    </div>

    <!-- PAY NOW TAB -->
    <div id="pay-now" class="tab-content active">
        <div style="max-width: 500px; margin: 0 auto;">
            <div class="luxury-card">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <h2 style="margin-bottom: 0.5rem;">Welcome Back</h2>
                    <p style="color: var(--text-secondary);">Enter your credentials to manage payments</p>
                </div>
                <form onsubmit="return false;">
                    <div class="form-group">
                        <label><i class="fas fa-mobile-alt" style="margin-right: 8px;"></i> Registered Mobile</label>
                        <input type="tel" class="form-control" placeholder="98765 43210">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock" style="margin-right: 8px;"></i> Password</label>
                        <input type="password" class="form-control" placeholder="••••••••">
                    </div>
                    <button class="btn-premium" style="width: 100%; margin-top: 1rem; border-radius: 15px;">Login to Portal</button>
                    <div style="text-align: center; margin-top: 1.5rem;">
                        <a href="#" style="color: var(--accent-color); text-decoration: none; font-weight: 600; font-size: 0.9rem;">Reset Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EXPLORE PLANS TAB -->
    <div id="explore-plan" class="tab-content">
        <div class="rates-grid">
            <!-- Plan 1 -->
            <div class="luxury-card" style="position: relative; overflow: hidden;">
                <div style="position: absolute; top: 20px; right: -30px; background: var(--accent-color); color: white; padding: 5px 40px; transform: rotate(45deg); font-size: 0.8rem; font-weight: 700;">POPULAR</div>
                <div style="margin-bottom: 2rem; width: 60px; height: 60px; background: var(--accent-light); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-gem" style="color: var(--accent-color); font-size: 1.8rem;"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">Gold Saver Plan</h3>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">Secure your dream jewellery with small monthly steps. Best for weddings and long-term savings.</p>
                
                <div style="background: var(--bg-secondary); padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                        <span style="opacity: 0.7;">Term</span>
                        <span style="font-weight: 700; color: var(--heading-color);">11 Months</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                        <span style="opacity: 0.7;">Base Deposit</span>
                        <span style="font-weight: 700; color: var(--heading-color);">₹5,000+</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="opacity: 0.7;">Bonus Benefit</span>
                        <span style="font-weight: 700; color: var(--accent-color);">1 Month Free</span>
                    </div>
                </div>

                <ul style="list-style: none; margin-bottom: 2.5rem;">
                    <li style="margin-bottom: 0.8rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: #27ae60;"></i> <span>No making charges on redemption</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: #27ae60;"></i> <span>Locked gold rate guarantee</span>
                    </li>
                </ul>
                <button class="btn-premium" style="width: 100%;">Get Started Now</button>
            </div>

            <!-- Plan 2 -->
            <div class="luxury-card">
                <div style="margin-bottom: 2rem; width: 60px; height: 60px; background: rgba(38, 34, 97, 0.1); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-crown" style="color: var(--heading-color); font-size: 1.8rem;"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">Elite Diamond Plan</h3>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">Exclusive benefit strategy for premium diamond collections. High flexibility and maximum returns.</p>
                
                <div style="background: var(--bg-secondary); padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                        <span style="opacity: 0.7;">Term</span>
                        <span style="font-weight: 700; color: var(--heading-color);">12 Months</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                        <span style="opacity: 0.7;">Base Deposit</span>
                        <span style="font-weight: 700; color: var(--heading-color);">₹10,000+</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="opacity: 0.7;">Bonus Benefit</span>
                        <span style="font-weight: 700; color: var(--accent-color);">1.5 Months Free</span>
                    </div>
                </div>

                <ul style="list-style: none; margin-bottom: 2.5rem;">
                    <li style="margin-bottom: 0.8rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: #27ae60;"></i> <span>Special diamond discounter coupons</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: #27ae60;"></i> <span>Personalised jewellery styling</span>
                    </li>
                </ul>
                <button class="btn-premium" style="width: 100%; background: var(--heading-color);">Explore Elite</button>
            </div>
        </div>
    </div>

    <!-- JOIN NEW PLAN TAB -->
    <div id="join-new" class="tab-content">
        <div class="luxury-card">
            <h2 style="margin-bottom: 3rem; text-align: center;">New Membership Application</h2>
            <form onsubmit="return false;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">
                    <!-- Section 1 -->
                    <div>
                        <h4 style="margin-bottom: 2rem; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem; display: inline-block;">Personal Information</h4>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" placeholder="Enter first and last name">
                        </div>
                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input type="tel" class="form-control" placeholder="+91 00000 00000">
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" class="form-control" placeholder="name@email.com">
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div>
                        <h4 style="margin-bottom: 2rem; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem; display: inline-block;">Address Details</h4>
                        <div class="form-group">
                            <label>Street & Door No.</label>
                            <input type="text" class="form-control" placeholder="Street layout, number">
                        </div>
                         <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Pincode</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <select class="form-control">
                                <option>Andhra Pradesh</option>
                                <option>Telangana</option>
                                <option>Karnataka</option>
                                <option>Tamil Nadu</option>
                            </select>
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div>
                        <h4 style="margin-bottom: 2rem; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem; display: inline-block;">Plan Selection</h4>
                        <div class="form-group">
                            <label>Identity Proof (Aadhaar/PAN)</label>
                            <input type="text" class="form-control" placeholder="Enter ID number">
                        </div>
                        <div class="form-group">
                            <label>Select Plan Category</label>
                            <select class="form-control">
                                <option>Gold Saver (11 Months)</option>
                                <option>Elite Diamond (12 Months)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Monthly Contribution</label>
                            <select class="form-control">
                                <option>₹ 5,000</option>
                                <option>₹ 10,000</option>
                                <option>₹ 25,000</option>
                                <option>Custom Amount</option>
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
                            <input type="text" class="form-control">
                        </div>
                         <div class="form-group">
                            <label>Relationship</label>
                            <input type="text" class="form-control">
                        </div>
                         <div class="form-group">
                            <label>Nominee Contact</label>
                            <input type="tel" class="form-control">
                        </div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 4rem;">
                    <button class="btn-premium" style="min-width: 320px; font-size: 1.1rem; border-radius: 20px;">Submit My Application</button>
                    <p style="margin-top: 1.5rem; font-size: 0.85rem; opacity: 0.6;">By submitting, you agree to our terms and conditions.</p>
                </div>
            </form>
        </div>
    </div>

    <!-- MY ACCOUNT TAB -->
    <div id="my-plan" class="tab-content">
         <div style="max-width: 500px; margin: 0 auto;">
            <div class="luxury-card" style="text-align: center;">
                <div style="width: 80px; height: 80px; background: var(--bg-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
                    <i class="fas fa-user-circle" style="font-size: 3rem; color: var(--heading-color);"></i>
                </div>
                <h2 style="margin-bottom: 2rem;">Login to Dashboard</h2>
                <form onsubmit="return false;">
                    <div class="form-group">
                        <input type="tel" class="form-control" placeholder="Mobile Number">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Account Password">
                    </div>
                    <button class="btn-premium" style="width: 100%;">Access Account</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
