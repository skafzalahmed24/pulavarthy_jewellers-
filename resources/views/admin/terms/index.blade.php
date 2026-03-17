@extends('layouts.admin')

@section('title', 'Manage Terms and Conditions')

@section('content')
<div class="dashboard-header" style="margin-bottom: 3rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Terms and Conditions</h1>
    <p style="color: var(--text-secondary);">Manage the terms and conditions displayed on your homepage.</p>
</div>

@if(session('success'))
<div class="luxury-card alert-auto-dismiss"
    style="background: #e8f5e9; color: #2e7d32; padding: 1rem; margin-bottom: 2rem; border-radius: 12px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

<div class="luxury-card">
    <h3
        style="margin-bottom: 2rem; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem; display: inline-block;">
        Update Homepage Terms</h3>

    <form action="{{ route('admin.terms.update') }}" method="POST">
        @csrf
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            @foreach($terms as $index => $term)
            <div style=1.5rem 2rem" background: #fffcfb;">
                <input type="hidden" name="terms[{{ $index }}][id]" value="{{ $term->id }}">
                <div style="display: flex; align-items: flex-start; gap: 1.5rem;">
                    <div style="font-size: 2rem; color: var(--accent-color); padding-top: 5px;">
                        <i class="{{ $term->icon }}"></i>
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 0.8rem; font-weight: 700; color: #333;">Term
                            Content #{{ $index + 1 }}</label>
                        <textarea name="terms[{{ $index }}][content]" class="form-control" rows="3" required
                            style="width: 100%; padding: 1rem; border-radius: 12px; border: 1px solid #ddd; font-family: inherit; font-size: 1rem; resize: vertical;">{{ $term->content }}</textarea>
                        <span style="font-size: 0.8rem; color: #888; margin-top: 0.5rem; display: block;">Icon:
                            <code>{{ $term->icon }}</code></span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 3rem;">
            <button type="submit" class="btn-premium" style="min-width: 250px; padding: 1.2rem; cursor: pointer;">Update
                Terms Now</button>
        </div>
    </form>
</div>
@endsection