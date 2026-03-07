<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | XYZ JEWELLERS</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/luxury-style.css') }}">
    <style>
        :root {
            --sidebar-width: 280px;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: #0a0a1a;
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 100;
            padding: 2rem 0;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 0 2rem 3rem;
            text-align: center;
        }

        .sidebar-header img {
            width: 150px;
            filter: brightness(0) invert(1);
        }

        .nav-links {
            list-style: none;
            padding: 0;
        }

        .nav-item {
            padding: 0.5rem 2rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--accent-gradient);
            color: white;
            box-shadow: var(--shadow-accent);
        }

        .nav-link i {
            font-size: 1.2rem;
        }

        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
        }

        .top-header {
            background: white;
            height: 80px;
            padding: 0 3rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            box-shadow: var(--shadow-soft);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 73px;
            cursor: pointer;
        }

        .content-area {
            padding: 3rem;
            flex: 1;
        }

        .logout-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s ease;
        }

        .logout-btn:hover {
            color: var(--accent-color);
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
                transform: translateX(0);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                padding: 1rem 0;
            }

            .sidebar-header {
                padding: 0 0.5rem 1rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .sidebar-header .sidebar-logo-desktop {
                display: none;
            }

            .mobile-toggle {
                display: flex !important;
                align-items: center;
                justify-content: center;
                width: 45px;
                height: 45px;
                background: var(--accent-gradient);
                border-radius: 12px;
                color: white;
                cursor: pointer;
                box-shadow: var(--shadow-accent);
                transition: transform 0.3s ease;
            }

            .mobile-toggle:active {
                transform: scale(0.9);
            }

            .nav-item {
                padding: 0.5rem 0.75rem;
            }

            .nav-link {
                padding: 0.85rem;
                justify-content: center;
                gap: 0;
            }

            .nav-link span,
            .nav-link .badge,
            .nav-item span[style*="text-transform: uppercase"] {
                display: none;
            }

            .nav-link i {
                font-size: 1.4rem;
                margin: 0;
            }

            .main-wrapper {
                margin-left: 80px;
            }

            /* Expanded State for Mobile */
            body.sidebar-expanded .sidebar {
                width: var(--sidebar-width);
                z-index: 1000;
            }

            body.sidebar-expanded .sidebar-header {
                padding: 0 2rem 3rem;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            body.sidebar-expanded .sidebar-header .sidebar-logo-desktop {
                display: block;
                width: 120px;
            }

            body.sidebar-expanded .nav-item {
                padding: 0.5rem 2rem;
            }

            body.sidebar-expanded .nav-link {
                justify-content: flex-start;
                gap: 15px;
                padding: 1rem 1.5rem;
            }

            body.sidebar-expanded .nav-link span,
            body.sidebar-expanded .nav-link .badge,
            body.sidebar-expanded .nav-item span[style*="text-transform: uppercase"] {
                display: inline-block;
            }

            .top-header {
                padding: 0 1.5rem;
                justify-content: space-between;
                left: 80px;
                width: calc(100% - 80px);
            }

            .mobile-nav-logo {
                display: block !important;
                height: 40px;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(5px);
                z-index: 950;
            }

            body.sidebar-expanded .sidebar-overlay {
                display: block;
            }

            .content-area {
                padding: 1.5rem;
            }
        }

        .mobile-nav-logo {
            display: none;
        }

        .mobile-toggle {
            display: none;
        }

        .luxury-card {
            overflow: hidden;
            max-width: 100%;
        }

        /* Premium Pagination Styles */
        .pagination-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
            border-top: 1px solid #f0f0f0;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            margin-top: 0;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 1rem 0 0;
            gap: 10px;
            align-items: center;
        }

        .page-item .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
            height: 45px;
            padding: 0 15px;
            border-radius: 12px;
            background: #f8f9fa;
            color: #262261;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            border: 1px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .page-item.active .page-link {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 8px 20px rgba(100, 255, 218, 0.3);
            border: none;
        }

        .page-item .page-link:hover:not(.active) {
            background: #fff;
            border-color: var(--accent-color);
            color: var(--accent-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .page-item.disabled .page-link {
            color: #cbd5e0;
            background: #fdfdfd;
            cursor: not-allowed;
            opacity: 0.6;
            transform: none !important;
            box-shadow: none !important;
        }

        .pagination-info {
            color: #888;
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
    </style>
    @yield('styles')
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="mobile-toggle" id="mobileToggle">
                <i class="fas fa-bars"></i>
            </div>
            <a href="{{ url('/') }}" class="sidebar-logo-desktop">
                <img src="{{ asset('img/logo.webp') }}" alt="XYZ JEWELLERS"
                    style="width: 150px; filter: brightness(0) invert(1);">
            </a>
        </div>
        <ul class="nav-links">

            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.customers.index') }}"
                    class="nav-link {{ request()->routeIs('admin.customers.*') && !request()->routeIs('admin.approvals') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.approvals') }}"
                    class="nav-link {{ request()->routeIs('admin.approvals') ? 'active' : '' }}"
                    style="position: relative;">
                    <i class="fas fa-user-check"></i>
                    <span>Approvals</span>
                    @if(isset($pending_approvals_count) && $pending_approvals_count > 0)
                    <span class="badge"
                        style="position: absolute; right: 1.5rem; background: #ff4d4d; color: white; padding: 0.1rem 0.5rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; box-shadow: 0 2px 10px rgba(255, 77, 77, 0.4);">
                        {{ $pending_approvals_count }}
                    </span>
                    @endif
                </a>
            </li>


            <li class="nav-item" style="margin-top: 1.5rem;">
                <span
                    style="color: rgba(255,255,255,0.4); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; padding-left: 1.5rem; font-weight: 700;">Content
                    Management</span>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.prices.index') }}"
                    class="nav-link {{ request()->routeIs('admin.prices.*') ? 'active' : '' }}">
                    <i class="fas fa-coins"></i>
                    <span>Update Price</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.terms.index') }}"
                    class="nav-link {{ request()->routeIs('admin.terms.*') ? 'active' : '' }}">
                    <i class="fas fa-file-contract"></i>
                    <span>Terms and Conditions</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.plans.index') }}"
                    class="nav-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                    <i class="fas fa-gem"></i>
                    <span>Investment Plans</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-wrapper">
        <header class="top-header">
            <a href="{{ url('/') }}" class="mobile-nav-logo">
                <img src="{{ asset('img/logo.webp') }}" alt="XYZ JEWELLERS" style="height: 100%;">
            </a>
            <div class="user-profile">
                <span style="font-weight: 600;">{{ auth()->user()->name }}</span>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;
            const overlay = document.getElementById('sidebarOverlay');
            const mobileToggle = document.getElementById('mobileToggle');

            if (mobileToggle) {
                mobileToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    body.classList.toggle('sidebar-expanded');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', () => {
                    body.classList.remove('sidebar-expanded');
                });
            }

            // Close sidebar expansion on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    body.classList.remove('sidebar-expanded');
                }
            });
        });
    </script>
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