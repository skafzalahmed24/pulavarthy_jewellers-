@extends('layouts.admin')

@section('title', 'Add New Customer')

@section('content')
<div class="dashboard-header" style="margin-bottom: 3rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Add New Customer</h1>
    <p style="color: var(--text-secondary);">Manually register a new member to the jewellery purchase plan.</p>
</div>

<div class="luxury-card">
    <form action="{{ route('admin.customers.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">
            <!-- Personal Info -->
            <div style="padding: 1.5rem 2rem; border: 1px solid #eee; border-radius: 20px;">
                <h4 style="margin-bottom: 2rem; color: var(--accent-color);"><i class="fas fa-user"></i> Personal
                    Information</h4>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Full Name</label>
                    <input type="text" name="name" class="form-control" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email Address</label>
                    <input type="email" name="email" class="form-control" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Mobile Number</label>
                    <input type="tel" name="mobile" class="form-control" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Account Password</label>
                    <input type="password" name="password" class="form-control" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
            </div>

            <!-- Address & ID -->
            <div style="padding: 1.5rem 2rem; border: 1px solid #eee; border-radius: 20px;">
                <h4 style="margin-bottom: 2rem; color: var(--accent-color);"><i class="fas fa-map-marker-alt"></i>
                    Address & Identity</h4>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Street Address</label>
                    <input type="text" name="address" class="form-control"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">City</label>
                        <input type="text" name="city" class="form-control"
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Pincode</label>
                        <input type="text" name="pincode" class="form-control"
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">State</label>
                    <input type="text" name="state" class="form-control"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Identity Proof
                        (Aadhar/PAN)</label>
                    <input type="text" name="identity_proof" class="form-control"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
            </div>

            <!-- Plan & Nominee -->
            <div style="padding: 1.5rem 2rem; border: 1px solid #eee; border-radius: 20px;">
                <h4 style="margin-bottom: 2rem; color: var(--accent-color);"><i class="fas fa-gem"></i> Plan & Nominee
                </h4>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Plan Category</label>
                    <select name="plan_category" class="form-control"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                        <option value="Gold Saver (11 Months)">Gold Saver (11 Months)</option>
                        <option value="Elite Diamond (12 Months)">Elite Diamond (12 Months)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nominee Name</label>
                    <input type="text" name="nominee_name" class="form-control"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Relationship</label>
                        <input type="text" name="nominee_relationship" class="form-control"
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Contact</label>
                        <input type="tel" name="nominee_contact" class="form-control"
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 4rem; border-top: 1px solid #eee; padding-top: 3rem;">
            <a href="{{ route('admin.customers.index') }}"
                style="margin-right: 20px; color: #888; text-decoration: none; font-weight: 600;">Cancel</a>
            <button type="submit" class="btn-premium"
                style="min-width: 250px; padding: 1.2rem; cursor: pointer;">Register Customer</button>
        </div>
    </form>
</div>
@endsection