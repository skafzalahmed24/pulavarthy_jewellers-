@extends('layouts.luxury')

@section('title', 'Welcome to Pulavarthy Jewellers')

@section('content')
<!-- Hero Section -->
<div class="hero-swiper swiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide list-style-none">
            <div class="slide-item" style="background-image: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.1)), url('{{ asset('img/hero.png') }}'); padding-right: 5%; justify-content: flex-start; padding-left: 10%;">
                <div class="hero-content-glass" style="text-align: left; max-width: 600px;">
                    <h1>Secure Your Golden Future</h1>
                    <p>Discover the smartest way to invest in premium gold and diamond jewellery. Automated installments, exclusive bonuses, and absolute trust.</p>
                    <div style="display: flex; gap: 1rem;">
                        <a href="{{ url('/purchase-plan') }}" class="btn-premium" style="background: var(--accent-gradient);">Explore Plans</a>
                        <a href="#features" class="btn-premium" style="background: transparent; border: 2px solid white; color: white; box-shadow: none;">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-slide list-style-none">
            <div class="slide-item" style="background-image: linear-gradient(to left, rgba(0,0,0,0.8), rgba(0,0,0,0.1)), url('{{ asset('img/hero.png') }}'); background-position: right center;">
                <div class="hero-content-glass">
                    <h1>Exclusive Member Benefits</h1>
                    <p>Join our premium purchase plans to get zero making charges, guaranteed purity, and exclusive previews to our newest collections.</p>
                    <div class="btn-group" style="display: flex; gap: 1rem;">
                        <a href="{{ url('/purchase-plan') }}" class="btn-premium">Join Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
    <!-- Add Navigation -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<!-- Features Section -->
<section id="features" class="section-standard" style="background: var(--bg-primary);">
    <div class="container-standard">
        <div style="text-align: center; margin-bottom: 4rem;">
            <p style="color: var(--accent-color); font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1rem;">Why Choose Us</p>
            <h2 class="section-title">The Pulavarthy Promise</h2>
        </div>

        <div class="grid-container grid-3">
            <div class="luxury-card" style="text-align: center; padding: 3rem 2rem;">
                <div style="width: 80px; height: 80px; background: var(--accent-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
                    <i class="fas fa-gem" style="font-size: 2.5rem; color: var(--accent-color);"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">100% BIS Hallmarked</h3>
                <p style="color: var(--text-secondary); line-height: 1.8;">Every single piece of jewellery meets the highest standard of purity and quality.</p>
            </div>
            <div class="luxury-card" style="text-align: center; padding: 3rem 2rem;">
                <div style="width: 80px; height: 80px; background: var(--accent-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
                    <i class="fas fa-shield-alt" style="font-size: 2.5rem; color: var(--accent-color);"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">Secure Investments</h3>
                <p style="color: var(--text-secondary); line-height: 1.8;">Your installments are securely tracked, holding the value of gold perfectly.</p>
            </div>
            <div class="luxury-card" style="text-align: center; padding: 3rem 2rem;">
                <div style="width: 80px; height: 80px; background: var(--accent-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
                    <i class="fas fa-gift" style="font-size: 2.5rem; color: var(--accent-color);"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">Zero Making Charges</h3>
                <p style="color: var(--text-secondary); line-height: 1.8;">Redeem your completed plan with zero making charges on selected gold items.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section style="background: var(--accent-gradient); padding: 5rem 2rem; color: white; text-align: center; position: relative; overflow: hidden;">
    <!-- Abstract Background Pattern -->
    <div style="position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; blur: 20px;"></div>
    <div style="position: absolute; bottom: -100px; right: 10%; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div class="container-standard" style="position: relative; z-index: 2;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; font-family: 'Playfair Display', serif; color: white;">Ready to start your journey?</h2>
        <p style="font-size: 1.1rem; max-width: 600px; margin: 0 auto 3rem; opacity: 0.9;">Join thousands of smart buyers who are systematically building their gold portfolio.</p>
        <a href="{{ url('/purchase-plan') }}" class="btn-premium" style="background: white; color: var(--accent-color); font-size: 1.1rem; padding: 1.2rem 3rem;">View Investment Plans</a>
    </div>
</section>
@endsection
