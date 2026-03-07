@extends('layouts.admin')

@section('title', 'Create Investment Plan')

@section('content')
<div class="dashboard-header" style="margin-bottom: 3rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Create New Plan</h1>
    <p style="color: var(--text-secondary);">Add a new investment strategy to the portal.</p>
</div>

<div class="luxury-card">
    <form action="{{ route('admin.plans.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 3rem;">
            <!-- Basic Details -->
            <div style="padding: 2rem; border: 1px solid #eee; border-radius: 20px;">
                <h4 style="margin-bottom: 2rem; color: var(--accent-color);"><i class="fas fa-info-circle"></i> Basic
                    Details</h4>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Plan Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Gold Saver Plan" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Scheme Number Prefix
                        (Unique)</label>
                    <input type="text" name="scheme_prefix" class="form-control" placeholder="e.g. GS" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Short Description</label>
                    <textarea name="description" class="form-control" rows="3" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;"></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Term</label>
                        <input type="text" name="term" class="form-control" placeholder="e.g. 11 Months" required
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Base Deposit</label>
                        <input type="text" name="base_deposit" class="form-control" placeholder="e.g. ₹5,000+" required
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                </div>
            </div>

            <!-- Features & Styling -->
            <div style="padding: 2rem; border: 1px solid #eee; border-radius: 20px;">
                <h4 style="margin-bottom: 2rem; color: var(--accent-color);"><i class="fas fa-star"></i> Features &
                    Benefits</h4>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Bonus Benefit</label>
                    <input type="text" name="bonus_benefit" class="form-control" placeholder="e.g. 1 Month Free"
                        required style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Plan Features (Add up to
                        4)</label>
                    <input type="text" name="features[]" class="form-control" placeholder="Feature 1"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 0.5rem;">
                    <input type="text" name="features[]" class="form-control" placeholder="Feature 2"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 0.5rem;">
                    <input type="text" name="features[]" class="form-control" placeholder="Feature 3"
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 0.5rem;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Icon Class</label>
                        <input type="text" name="icon" class="form-control" value="fas fa-gem" required
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Button Text</label>
                        <input type="text" name="button_text" class="form-control" value="Get Started Now" required
                            style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_popular" value="1">
                        <span style="font-weight: 600;">Mark as Popular Plan</span>
                    </label>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 4rem; border-top: 1px solid #eee; padding-top: 3rem;">
            <a href="{{ route('admin.plans.index') }}"
                style="margin-right: 20px; color: #888; text-decoration: none; font-weight: 600;">Cancel</a>
            <button type="submit" class="btn-premium" style="min-width: 250px; padding: 1.2rem; cursor: pointer;">Save
                Plan</button>
        </div>
    </form>
</div>
@endsection