@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')
<div class="dashboard-header" style="margin-bottom: 3rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Edit Customer</h1>
    <p style="color: var(--text-secondary);">Update information for member: <strong>{{ $customer->name }}</strong></p>
</div>

<div class="luxury-card">
    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">
            <!-- Personal Info -->
            <div style="padding: 2rem; border: 1px solid #eee; border-radius: 20px;">
                <h4 style="margin-bottom: 2rem; color: var(--accent-color);"><i class="fas fa-user"></i> Personal
                    Information</h4>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #888;">Scheme Number
                        (Generated on Approval)</label>
                    <input type="text" class="form-control" value="{{ $customer->userSchemes->first()?->scheme_number ?? 'Pending Approval' }}"
                        readonly
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd; background-color: #f9f9f9; cursor: not-allowed; font-weight: 700; color: var(--accent-color);">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ $customer->email }}" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Mobile Number</label>
                    <input type="tel" name="mobile" class="form-control" value="{{ $customer->mobile }}" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Account Status</label>
                    <select name="status" class="form-control"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                        <option value="pending" {{ $customer->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $customer->status == 'approved' ? 'selected' : '' }}>Approved
                        </option>
                        <option value="rejected" {{ $customer->status == 'rejected' ? 'selected' : '' }}>Rejected
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Account Password (Leave
                        blank to keep current)</label>
                    <input type="password" name="password" class="form-control"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
            </div>

            <!-- Address & ID -->
            <div style="padding: 2rem; border: 1px solid #eee; border-radius: 20px;">
                <h4 style="margin-bottom: 2rem; color: var(--accent-color);"><i class="fas fa-map-marker-alt"></i>
                    Address & Identity</h4>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Street Address</label>
                    <input type="text" name="address" class="form-control" value="{{ $customer->address }}"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">City</label>
                        <input type="text" name="city" class="form-control" value="{{ $customer->city }}"
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Pincode</label>
                        <input type="text" name="pincode" class="form-control" value="{{ $customer->pincode }}"
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">State</label>
                    <input type="text" name="state" class="form-control" value="{{ $customer->state }}"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Identity Proof
                        (Aadhar/PAN)</label>
                    <input type="text" name="identity_proof" class="form-control"
                        value="{{ $customer->identity_proof }}"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
            </div>

            <!-- Plan & Nominee -->
            <div style="padding: 2rem; border: 1px solid #eee; border-radius: 20px;">
                <h4 style="margin-bottom: 2rem; color: var(--accent-color);"><i class="fas fa-gem"></i> Plan & Nominee
                </h4>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Plan Category</label>
                    <select name="plan_category" class="form-control"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                        <option value="" {{ !($customer->userSchemes->first()) ? 'selected' : '' }}>-- Not Selected (User picks after approval) --</option>
                        @foreach($plans as $plan)
                        <option value="{{ $plan->name }}" {{ ($customer->userSchemes->first()?->investmentPlan->name ?? '') == $plan->name ? 'selected' : ''
                            }}>
                            {{ $plan->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nominee Name</label>
                    <input type="text" name="nominee_name" class="form-control" value="{{ $customer->nominee_name }}"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Relationship</label>
                        <input type="text" name="nominee_relationship" class="form-control"
                            value="{{ $customer->nominee_relationship }}"
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Contact</label>
                        <input type="tel" name="nominee_contact" class="form-control"
                            value="{{ $customer->nominee_contact }}"
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 4rem; border-top: 1px solid #eee; padding-top: 3rem;">
            <a href="{{ route('admin.customers.index') }}"
                style="margin-right: 20px; color: #888; text-decoration: none; font-weight: 600;">Cancel</a>
            <button type="submit" class="btn-premium" style="min-width: 250px; padding: 1.2rem; cursor: pointer;">Update
                Customer Details</button>
        </div>
    </form>

    @php
        $graceRequests = $customer->userSchemes->first()?->payments()->where('grace_extension_status', 'pending')->get() ?? collect();
    @endphp
    
    @if($graceRequests->count() > 0)
    <div style="margin-top: 3rem; padding: 2rem; border: 1px solid #f9a825; border-radius: 20px; background: #fff8e1;">
        <h4 style="margin-bottom: 2rem; color: #f9a825;"><i class="fas fa-clock"></i> Pending Grace Extension Requests</h4>
        @foreach($graceRequests as $req)
        <div style="background: white; padding: 1.5rem; border-radius: 12px; margin-bottom: 1rem;">
            <p><strong>Due Date:</strong> {{ $req->due_date->format('d M Y') }}</p>
            <p><strong>Current Grace End:</strong> {{ $req->grace_end_date->format('d M Y') }}</p>
            <p><strong>Reason:</strong> {{ $req->grace_extension_reason }}</p>
            
            <div style="margin-top: 1rem; display: flex; gap: 1rem;">
                <form action="{{ route('admin.payments.approve_grace', $req->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-premium" style="background: #2e7d32; padding: 0.8rem 1.5rem; cursor: pointer;">Approve & Extend 15 Days</button>
                </form>
                <form action="{{ route('admin.payments.reject_grace', $req->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-premium" style="background: #c62828; padding: 0.8rem 1.5rem; cursor: pointer;">Reject</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection