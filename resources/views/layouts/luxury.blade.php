<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Modern Jewellery') | Premium Purchase Plan</title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/luxury-style.css') }}">
    <style>
        .dropdown { position: relative; }
        .dropdown-menu { 
            position: absolute; 
            top: 100%; 
            right: 0; 
            background: var(--white); 
            min-width: 220px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            border-radius: 12px;
            padding: 0.5rem 0;
            display: none;
            flex-direction: column;
            gap: 0;
            z-index: 1000;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            list-style: none !important;
            margin: 0;
        }
        .dropdown:hover .dropdown-menu {
            display: flex;
            opacity: 1;
            transform: translateY(0);
        }
        .dropdown-menu li { width: 100%; }
        .dropdown-menu li a {
            padding: 0.8rem 1.5rem !important;
            display: flex !important;
            align-items: center;
            font-size: 0.9rem !important;
            color: var(--text-primary) !important;
        }
        .dropdown-menu li a:after { display: none !important; }
        .dropdown-menu li a:hover {
            background: rgba(159, 31, 99, 0.05);
            color: var(--accent-color) !important;
        }
        
        @media (max-width: 768px) {
            .dropdown-menu { position: static; box-shadow: none; display: none; opacity: 1; transform: none; background: transparent; padding-left: 2rem; padding-top: 0; padding-bottom: 0; }
            .dropdown:hover .dropdown-menu, .dropdown.active .dropdown-menu { display: flex; }
        }
    </style>
    @yield('styles')
</head>

<body>
    <header id="main-header">
        <div class="nav-container">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.webp') }}" alt="Pulavarthy Jewellers" class="logo-img">
            </a>
            <div class="nav-right">
                <nav>
                    <ul>
                        <li><a href="{{ url('/') }}"><i class="fas fa-home" style="margin-right: 8px;"></i>Home</a></li>
                        <li><a href="{{ url('/purchase-plan') }}"><i class="fas fa-gem" style="margin-right: 8px;"></i>Jewellery Purchase Plan</a></li>
                        @auth
                            @if(Auth::user()->is_admin)
                                <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line" style="margin-right: 8px;"></i>Admin Dashboard</a></li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('nav-admin-logout-form').submit();">
                                        <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i>Logout
                                    </a>
                                    <form id="nav-admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            @else
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" style="display: flex; align-items: center;">
                                        <i class="fas fa-user-circle" style="margin-right: 8px;"></i>{{ Auth::user()->name }} <i class="fas fa-caret-down" style="margin-left: 5px;"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ url('/customer/dashboard') }}"><i class="fas fa-tachometer-alt" style="margin-right: 8px;"></i>My Dashboard</a></li>
                                        <li><a href="{{ url('/purchase-plan#pay-now') }}"><i class="fas fa-credit-card" style="margin-right: 8px;"></i>Pay Now</a></li>
                                        <li><a href="{{ url('/purchase-plan#my-plan') }}"><i class="fas fa-user-cog" style="margin-right: 8px;"></i>My Account</a></li>
                                        <li>
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('nav-logout-form').submit();">
                                                <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i>Logout
                                            </a>
                                            <form id="nav-logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @else
                            <li><a href="{{ url('/purchase-plan#my-plan') }}"><i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>Login</a></li>
                        @endauth
                        <li><a href="#footer"><i class="fas fa-phone-alt" style="margin-right: 8px;"></i>Contact</a></li>
                    </ul>
                </nav>
                <div class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>

    <main style="margin-top: var(--header-height);">
        @yield('content')
    </main>

    <footer id="footer">
        <div class="footer-content">
            <div class="footer-section">
                <img src="{{ asset('img/logo.webp') }}" alt="Pulavarthy Jewellers" class="logo-img"
                    style="filter: brightness(0) invert(1); margin-bottom: 1.5rem;">
                <p>Timeless elegance meets modern investment. Pulavarthy Jewellers offers the most trusted jewellery purchase
                    plans for a secure future.</p>
            </div>
            <div class="footer-section">
                <h4>Store Location</h4>
                <p><i class="fas fa-map-marker-alt" style="color: var(--accent-color); margin-right: 10px;"></i> Pulavarthy
                    Jewellers, Temple St, Kakinada, Andhra Pradesh 533001</p>
                <p><i class="fas fa-phone" style="color: var(--accent-color); margin-right: 10px;"></i> 089776 91008
                </p>
                <p><i class="fas fa-envelope" style="color: var(--accent-color); margin-right: 10px;"></i>
                    contact@pulavarthyjewellers.com</p>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <div
            style="margin-top: 4rem; opacity: 0.6; font-size: 0.85rem; text-align: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 2rem; letter-spacing: 1px;">
            &copy; 2026 Pulavarthy Jewellers. All Rights Reserved.
        </div>
    </footer>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/luxury-script.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                const alerts = document.querySelectorAll('.alert-auto-dismiss');
                alerts.forEach(function (alert) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function () {
                        alert.style.display = 'none';
                    }, 500);
                });
            }, 5000);
        });
    </script>
    @yield('scripts')
</body>

</html>