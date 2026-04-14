@extends('layouts.luxury')

@section('title', 'Secure Your Golden Future')

@section('content')
@section('styles')
<style>
    /* Custom Split Hero Styles */
    .split-hero {
        display: flex;
        min-height: 90vh;
        background: var(--white);
        margin-top: var(--header-height);
        overflow: hidden;
    }
    .split-hero-content {
        flex: 1;
        padding: 6rem 8%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: var(--bg-secondary);
        position: relative;
    }
    .split-hero-image {
        flex: 1.2;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--white);
        padding: 2rem;
    }
    .image-frame {
        width: 100%;
        height: 85vh;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.08); 
        position: relative;
    }
    .image-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover; 
        display: block;
    }
    
    @media (max-width: 991px) {
        .split-hero { flex-direction: column; }
        .split-hero-content { padding: 4rem 5%; text-align: center; align-items: center; }
        .split-hero-image { padding: 1rem; }
        .image-frame { height: auto; max-height: 60vh; aspect-ratio: 4/3; }
    }

    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(3rem, 5vw, 4.5rem);
        color: var(--heading-color);
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.15rem;
        color: var(--text-secondary);
        margin-bottom: 3rem;
        max-width: 500px;
        line-height: 1.8;
    }

    .stats-row {
        display: flex;
        gap: 3rem;
        margin-top: 3rem;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding-top: 2rem;
    }
    .stat-item h4 {
        font-size: 1.8rem;
        color: var(--heading-color);
        margin-bottom: 0.2rem;
    }
    .stat-item p {
        font-size: 0.85rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    @media (max-width: 768px) {
        .stats-row { justify-content: center; flex-wrap: wrap; gap: 2rem; }
    }
</style>
@endsection

@section('content')
<!-- Split Layout Hero Section -->
<section class="split-hero">
    <!-- Left Text Content -->
    <div class="split-hero-content">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at top right, rgba(159,31,99,0.05) 0%, transparent 50%); pointer-events: none;"></div>
        
        <div style="position: relative; z-index: 2;">
            <p style="color: var(--accent-color); font-weight: 700; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 1.5rem; font-size: 0.9rem;"><i class="fas fa-crown" style="margin-right: 8px;"></i> Pure Elegance</p>
            <h1 class="hero-title">Timeless Beauty,<br><span style="color: var(--accent-color);">Smart</span> Investment</h1>
            <p class="hero-subtitle">Make your luxury dreams a reality. Join our secure, systematic gold purchase plans with exclusive bonuses and absolute trust.</p>
            
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                <a href="{{ url('/purchase-plan') }}" class="btn-premium" style="padding: 1rem 2.5rem; border-radius: 12px; font-size: 1rem;">Explore Plans <i class="fas fa-arrow-right" style="margin-left: 8px;"></i></a>
            </div>

            <div class="stats-row">
                <div class="stat-item">
                    <h4>100%</h4>
                    <p>BIS Hallmark</p>
                </div>
                <div class="stat-item">
                    <h4>0%</h4>
                    <p>Making Charges</p>
                </div>
                <div class="stat-item">
                    <h4>24/7</h4>
                    <p>Secure Portal</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Image Content -->
    <div class="split-hero-image">
        <div style="position: absolute; right: 10%; bottom: 5%; width: 200px; height: 200px; background: var(--accent-light); border-radius: 50%; opacity: 0.6; filter: blur(30px); z-index: 0;"></div>
        
        <!-- The premium uncropped image frame -->
        <div class="image-frame" style="z-index: 1;">
            <img src="{{ asset('img/image (1).jpg') }}" alt="Premium Jewellery Collection" onerror="this.src='https://images.unsplash.com/photo-1611591437281-460bfbe1220a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'">
        </div>
        
        <!-- Trust Badge Overlay -->
        <div style="position: absolute; top: 15%; left: 0; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); padding: 1rem 1.5rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 15px; z-index: 2; border: 1px solid rgba(255,255,255,1);">
            <div style="width: 40px; height: 40px; background: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #2e7d32;">
                <i class="fas fa-shield-alt" style="font-size: 1.2rem;"></i>
            </div>
            <div>
                <p style="font-weight: 700; color: var(--heading-color); margin: 0; font-size: 0.95rem;">Verified Platform</p>
                <p style="color: var(--text-secondary); margin: 0; font-size: 0.75rem;">100% Secure Payments</p>
            </div>
        </div>
    </div>
</section>

<!-- Live Market Rates Section (Upgraded) -->
<section id="rates" class="section-standard" style="position: relative; overflow: hidden; background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%); padding-top: 6rem; padding-bottom: 6rem;">
    <!-- Decorative abstract spheres -->
    <div style="position: absolute; top: -5%; left: -5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(212,175,55,0.15) 0%, transparent 70%); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -5%; right: -5%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(192,192,192,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
    
    <div class="container-standard" style="position: relative; z-index: 2;">
        <div style="text-align: center; margin-bottom: 4rem;">
            <p style="color: var(--accent-color); font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 0.5rem;"><i class="fas fa-chart-line" style="margin-right: 8px;"></i> Real-Time Data</p>
            <h2 class="section-title" style="font-size: 2.8rem; margin-bottom: 1.5rem;">Live Market Trends</h2>
            <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto; font-size: 1.1rem; line-height: 1.6;">Stay updated with live gold and silver prices to make informed and secure investment decisions.</p>
        </div>

        <div class="rates-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3rem; max-width: 900px; margin: 0 auto;">
            <!-- Gold Card Premium -->
            <div style="background: rgba(255,255,255,0.9); backdrop-filter: blur(20px); padding: 3rem 2.5rem; border-radius: 24px; border: 1px solid rgba(212,175,55,0.3); box-shadow: 0 20px 50px rgba(212,175,55,0.08); text-align: center; position: relative; overflow: hidden; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 6px; background: linear-gradient(90deg, #BF953F, #FCF6BA, #B38728, #FBF5B7, #AA771C);"></div>
                <h4 style="text-transform: uppercase; color: #AA771C; letter-spacing: 3px; margin-bottom: 0.5rem; font-size: 0.9rem; font-weight: 700;">22K Gold Price</h4>
                <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem;">Per Gram Today</p>
                
                <div style="font-size: 3.2rem; font-weight: 900; color: var(--heading-color); font-family: 'Playfair Display', serif; line-height: 1;">
                    <span style="font-size: 2rem; vertical-align: super; color: #AA771C;">₹</span>{{ number_format($prices['Gold']->today_price ?? 0, 2) }}
                </div>
                
                <div style="margin-top: 1rem;">
                    @if(isset($prices['Gold']))
                        @if($prices['Gold']->today_price > $prices['Gold']->yesterday_price)
                            <span style="display: inline-flex; align-items: center; background: #e8f5e9; color: #2e7d32; padding: 0.4rem 1rem; border-radius: 20px; font-weight: 700; font-size: 0.9rem;"><i class="fas fa-caret-up" style="margin-right: 5px;"></i> Market Up</span>
                        @elseif($prices['Gold']->today_price < $prices['Gold']->yesterday_price)
                            <span style="display: inline-flex; align-items: center; background: #ffebee; color: #c62828; padding: 0.4rem 1rem; border-radius: 20px; font-weight: 700; font-size: 0.9rem;"><i class="fas fa-caret-down" style="margin-right: 5px;"></i> Market Down</span>
                        @endif
                    @endif
                </div>

                <div style="margin-top: 2.5rem; font-size: 0.8rem; color: #aaa; border-top: 1px solid #eee; padding-top: 1rem; text-transform: uppercase; letter-spacing: 1px;">
                    Updated on {{ isset($prices['Gold']) ? $prices['Gold']->updated_at->format('d M Y - h:i A') : 'N/A' }}
                </div>
            </div>

            <!-- Silver Card Premium -->
            <div style="background: rgba(255,255,255,0.9); backdrop-filter: blur(20px); padding: 3rem 2.5rem; border-radius: 24px; border: 1px solid rgba(192,192,192,0.5); box-shadow: 0 20px 50px rgba(0,0,0,0.04); text-align: center; position: relative; overflow: hidden; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 6px; background: linear-gradient(90deg, #b8c6db, #f5f7fa);"></div>
                <h4 style="text-transform: uppercase; color: #7f8c8d; letter-spacing: 3px; margin-bottom: 0.5rem; font-size: 0.9rem; font-weight: 700;">Silver Price</h4>
                <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem;">Per Gram Today</p>
                
                <div style="font-size: 3.2rem; font-weight: 900; color: var(--heading-color); font-family: 'Playfair Display', serif; line-height: 1;">
                    <span style="font-size: 2rem; vertical-align: super; color: #7f8c8d;">₹</span>{{ number_format($prices['Silver']->today_price ?? 0, 2) }}
                </div>
                
                <div style="margin-top: 1rem;">
                    @if(isset($prices['Silver']))
                        @if($prices['Silver']->today_price > $prices['Silver']->yesterday_price)
                            <span style="display: inline-flex; align-items: center; background: #e8f5e9; color: #2e7d32; padding: 0.4rem 1rem; border-radius: 20px; font-weight: 700; font-size: 0.9rem;"><i class="fas fa-caret-up" style="margin-right: 5px;"></i> Market Up</span>
                        @elseif($prices['Silver']->today_price < $prices['Silver']->yesterday_price)
                            <span style="display: inline-flex; align-items: center; background: #ffebee; color: #c62828; padding: 0.4rem 1rem; border-radius: 20px; font-weight: 700; font-size: 0.9rem;"><i class="fas fa-caret-down" style="margin-right: 5px;"></i> Market Down</span>
                        @endif
                    @endif
                </div>

                <div style="margin-top: 2.5rem; font-size: 0.8rem; color: #aaa; border-top: 1px solid #eee; padding-top: 1rem; text-transform: uppercase; letter-spacing: 1px;">
                    Updated on {{ isset($prices['Silver']) ? $prices['Silver']->updated_at->format('d M Y - h:i A') : 'N/A' }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Highlights Section (Premium) -->
<section class="section-standard" style="padding-top: 7rem; padding-bottom: 7rem; background: var(--white);">
    <div class="container-standard">
        <div style="text-align: center; margin-bottom: 5rem;">
            <p style="color: var(--accent-color); font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 0.5rem;">Smart Investments</p>
            <h2 class="section-title" style="font-size: 2.8rem; margin-bottom: 1.5rem;">Jewellery Savings Made Simple</h2>
            <p style="color: var(--text-secondary); max-width: 700px; margin: 0 auto; font-size: 1.1rem; line-height: 1.7;">Our flexible purchase plans are designed to help you acquire your favorite premium jewellery without financial strain.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2.5rem; margin-bottom: 5rem;">
            <!-- Feature 1 -->
            <div style="background: var(--bg-secondary); padding: 3rem 2.5rem; border-radius: 20px; transition: all 0.4s ease; border: 1px solid rgba(0,0,0,0.03);" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <div style="width: 75px; height: 75px; background: white; border-radius: 18px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); transform: rotate(-5deg);">
                    <i class="fas fa-shield-alt" style="font-size: 2rem; color: var(--accent-color); transform: rotate(5deg);"></i>
                </div>
                <h4 style="font-family: 'Playfair Display', serif; font-size: 1.6rem; margin-bottom: 1rem;">Secure Investment</h4>
                <p style="color: var(--text-secondary); line-height: 1.7; font-size: 1.05rem;">Your gold is backed by our decades of trust and highly transparent, market-linked valuation.</p>
            </div>
            
            <!-- Feature 2 -->
            <div style="background: var(--bg-secondary); padding: 3rem 2.5rem; border-radius: 20px; transition: all 0.4s ease; border: 1px solid rgba(0,0,0,0.03);" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <div style="width: 75px; height: 75px; background: white; border-radius: 18px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); transform: rotate(5deg);">
                    <i class="fas fa-calendar-alt" style="font-size: 2rem; color: var(--accent-color); transform: rotate(-5deg);"></i>
                </div>
                <h4 style="font-family: 'Playfair Display', serif; font-size: 1.6rem; margin-bottom: 1rem;">Flexible Payments</h4>
                <p style="color: var(--text-secondary); line-height: 1.7; font-size: 1.05rem;">Choose an installment amount that suits your budget and pay with ease directly through our portal.</p>
            </div>
            
            <!-- Feature 3 -->
            <div style="background: var(--bg-secondary); padding: 3rem 2.5rem; border-radius: 20px; transition: all 0.4s ease; border: 1px solid rgba(0,0,0,0.03);" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <div style="width: 75px; height: 75px; background: white; border-radius: 18px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); transform: rotate(-5deg);">
                    <i class="fas fa-gift" style="font-size: 2rem; color: var(--accent-color); transform: rotate(5deg);"></i>
                </div>
                <h4 style="font-family: 'Playfair Display', serif; font-size: 1.6rem; margin-bottom: 1rem;">Bonus Benefits</h4>
                <p style="color: var(--text-secondary); line-height: 1.7; font-size: 1.05rem;">Enjoy special maturity bonuses and exclusive discounts on making charges upon plan completion.</p>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/purchase-plan') }}" class="btn-premium" style="padding: 1.2rem 3.5rem; font-size: 1.05rem; border-radius: 50px; text-transform: uppercase; letter-spacing: 1px;">Explore Purchase Plans <i class="fas fa-arrow-right" style="margin-left: 8px;"></i></a>
        </div>
    </div>
</section>

<!-- Terms & Conditions Section -->
<section class="section-standard" style="background: #fffcfb; border-top: 1px solid #f9e6f0;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div
            style="background: white; border: 2px solid var(--accent-color); padding: 3rem; border-radius: 20px; box-shadow: var(--shadow-soft);">
            <h3 style="text-align: center; margin-bottom: 2rem; color: var(--accent-color);">Terms & Conditions</h3>
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