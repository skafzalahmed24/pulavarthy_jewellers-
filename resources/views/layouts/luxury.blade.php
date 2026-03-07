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
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/luxury-style.css') }}">
    @yield('styles')
</head>

<body>
    <header id="main-header">
        <div class="nav-container">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.webp') }}" alt="XYZ JEWELLERS" class="logo-img">
            </a>
            <nav>
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/purchase-plan') }}">Jewellery Purchase Plan</a></li>
                    <!-- <li><a href="{{ url('/purchase-plan#pay-now') }}">My Plan Login</a></li> -->
                    <li><a href="#footer">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer id="footer">
        <div class="footer-content">
            <div class="footer-section">
                <img src="{{ asset('img/logo.webp') }}" alt="XYZ JEWELLERS" class="logo-img"
                    style="filter: brightness(0) invert(1); margin-bottom: 1.5rem;">
                <p>Timeless elegance meets modern investment. XYZ Jewellers offers the most trusted jewellery purchase
                    plans for a secure future.</p>
            </div>
            <div class="footer-section">
                <h4>Store Location</h4>
                <p><i class="fas fa-map-marker-alt" style="color: var(--accent-color); margin-right: 10px;"></i> XYZ
                    Jewellers, MG Road, Vijayawada, AP</p>
                <p><i class="fas fa-phone" style="color: var(--accent-color); margin-right: 10px;"></i> +91 91234 56789
                </p>
                <p><i class="fas fa-envelope" style="color: var(--accent-color); margin-right: 10px;"></i>
                    contact@xyzjewellers.com</p>
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
            &copy; 2026 XYZ Jewellers. All Rights Reserved.
        </div>
    </footer>

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