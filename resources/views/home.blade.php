@extends('layouts.luxury')

@section('title', 'Secure Your Golden Future')

@section('content')
<!-- Hero Section -->
<section class="hero-slider"
    style="background: url('{{ asset('img/freepik__gold-jewellery-with-baby-pink__80995.png') }}') no-repeat center center; background-size: cover;">
    <div class="hero-overlay"></div>
    <div class="centered-hero-content">
        <h1 class="fadeInUp">Secure Your Golden Future Today</h1>
        <p class="fadeInUp" style="animation-delay: 0.2s;">Join our trusted Jewellery Purchase Plan and save monthly
            with confidence. Timeless value for your precious milestones.</p>
        <div class="btn-group fadeInUp" style="animation-delay: 0.4s;">
            <a href="{{ url('/purchase-plan') }}" class="btn-premium floating-btn">Explore Plan</a>
            <a href="{{ url('/purchase-plan#join-new') }}" class="btn-outline-gold floating-btn">Join Now</a>
        </div>
    </div>
</section>

<!-- Live Market Rates Section -->
<section id="rates" style="padding: 8rem 2rem; background: var(--bg-secondary);">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 5rem;">
            <h2 style="font-size: 3rem;">Live Market Trends</h2>
            <p style="color: var(--text-secondary); max-width: 600px; margin: 1.5rem auto;">Real-time updates on gold
                and silver prices to help you make informed investment decisions.</p>
        </div>

        <div class="rates-grid"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 4rem;">
            <!-- Gold Card -->
            <div class="luxury-card rate-card-prominent"
                style="border: 2px solid var(--accent-color); background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); transform: translateZ(0); transition: transform 0.4s ease;">
                <h4
                    style="text-transform: uppercase; color: var(--accent-color); letter-spacing: 2px; margin-bottom: 1rem;">
                    22K Gold Price</h4>
                <div class="price" style="font-size: 4rem; font-weight: 900; color: var(--heading-color);">
                    ₹ {{ number_format($prices['Gold']->today_price ?? 0, 2) }}
                    @if(isset($prices['Gold']))
                    @if($prices['Gold']->today_price > $prices['Gold']->yesterday_price)
                    <i class="fas fa-caret-up" style="color: #27ae60; font-size: 2rem; vertical-align: middle;"></i>
                    @elseif($prices['Gold']->today_price < $prices['Gold']->yesterday_price)
                        <i class="fas fa-caret-down"
                            style="color: #c0392b; font-size: 2rem; vertical-align: middle;"></i>
                        @endif
                        @endif
                </div>
                <p style="font-weight: 600; color: var(--text-secondary);">Per Gram Price Today</p>
                <div
                    style="margin-top: 2rem; font-size: 0.85rem; color: #888; border-top: 1px solid #eee; padding-top: 1.5rem;">
                    Updated on {{ isset($prices['Gold']) ? $prices['Gold']->updated_at->format('d/m/Y h:i A') : 'N/A' }}
                </div>
            </div>

            <!-- Silver Card -->
            <div class="luxury-card rate-card-prominent"
                style="border: 2px solid #333; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); transform: translateZ(0); transition: transform 0.4s ease;">
                <h4 style="text-transform: uppercase; color: #666; letter-spacing: 2px; margin-bottom: 1rem;">Silver
                    Price</h4>
                <div class="price" style="font-size: 4rem; font-weight: 900; color: var(--heading-color);">
                    ₹ {{ number_format($prices['Silver']->today_price ?? 0, 2) }}
                    @if(isset($prices['Silver']))
                    @if($prices['Silver']->today_price > $prices['Silver']->yesterday_price)
                    <i class="fas fa-caret-up" style="color: #27ae60; font-size: 2rem; vertical-align: middle;"></i>
                    @elseif($prices['Silver']->today_price < $prices['Silver']->yesterday_price)
                        <i class="fas fa-caret-down"
                            style="color: #c0392b; font-size: 2rem; vertical-align: middle;"></i>
                        @endif
                        @endif
                </div>
                <p style="font-weight: 600; color: var(--text-secondary);">Per Gram Price Today</p>
                <div
                    style="margin-top: 2rem; font-size: 0.85rem; color: #888; border-top: 1px solid #eee; padding-top: 1.5rem;">
                    Updated on {{ isset($prices['Silver']) ? $prices['Silver']->updated_at->format('d/m/Y h:i A') :
                    'N/A' }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Highlights Section -->
<section style="padding: 8rem 2rem;">
    <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
        <h2 style="font-size: 3.5rem; margin-bottom: 1.5rem;">Jewellery Savings Made Simple</h2>
        <p style="color: var(--text-secondary); max-width: 700px; margin: 0 auto 5rem; font-size: 1.2rem;">Our flexible
            purchase plans are designed to help you acquire your favorite jewellery without financial strain.</p>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; margin-bottom: 5rem;">
            <div class="luxury-card" style="padding: 4rem 3rem;">
                <div style="font-size: 3rem; color: var(--accent-color); margin-bottom: 2rem;"><i
                        class="fas fa-shield-alt"></i></div>
                <h4 style="margin-bottom: 1.5rem;">Secure Investment</h4>
                <p style="color: var(--text-secondary);">Your gold is backed by our decades of trust and transparent
                    market-linked valuation.</p>
            </div>
            <div class="luxury-card" style="padding: 4rem 3rem;">
                <div style="font-size: 3rem; color: var(--accent-color); margin-bottom: 2rem;"><i
                        class="fas fa-calendar-alt"></i></div>
                <h4 style="margin-bottom: 1.5rem;">Flexible Monthly Payments</h4>
                <p style="color: var(--text-secondary);">Choose an installment amount that suits your budget and pay
                    with ease through our portal.</p>
            </div>
            <div class="luxury-card" style="padding: 4rem 3rem;">
                <div style="font-size: 3rem; color: var(--accent-color); margin-bottom: 2rem;"><i
                        class="fas fa-gift"></i></div>
                <h4 style="margin-bottom: 1.5rem;">Bonus Benefits</h4>
                <p style="color: var(--text-secondary);">Enjoy special maturity bonuses and exclusive discounts on
                    making charges upon plan completion.</p>
            </div>
        </div>

        <a href="{{ url('/purchase-plan') }}" class="btn-premium"
            style="padding: 1.5rem 4rem; font-size: 1.1rem; box-shadow: 0 15px 40px rgba(159, 31, 99, 0.4);">Explore
            Jewellery Purchase Plan</a>
    </div>
</section>

<!-- Terms & Conditions Section -->
<section style="padding: 6rem 2rem; background: #fffcfb; border-top: 1px solid #f9e6f0;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div
            style="background: white; border: 2px solid var(--accent-color); padding: 4rem; border-radius: 20px; box-shadow: var(--shadow-soft);">
            <h3 style="text-align: center; margin-bottom: 3rem; color: var(--accent-color);">Terms & Conditions</h3>
            <ul style="list-style: none; padding: 0;">
                @foreach($terms as $term)
                <li style="margin-bottom: 2rem; display: flex; gap: 1.5rem;">
                    <i class="{{ $term->icon }}"
                        style="color: var(--accent-color); font-size: 1.5rem; margin-top: 3px;"></i>
                    <p style="font-size: 1.1rem; color: var(--text-primary); line-height: 1.6;">{{ $term->content }}</p>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
@endsection