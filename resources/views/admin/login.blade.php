<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | XYZ JEWELLERS</title>
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
        body {
            background-color: var(--bg-secondary);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 600px;
            padding: 2rem;
        }

        .login-card {
            background: white;
            border-radius: 25px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .logo-box {
            margin-bottom: 2.5rem;
        }

        .logo-box img {
            height: 60px;
        }

        .form-title {
            margin-bottom: 2rem;
        }

        .form-title h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .alert {
            background: #fff0f6;
            color: var(--accent-color);
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-box">
                <img src="{{ asset('img/logo.webp') }}" alt="XYZ JEWELLERS">
            </div>
            <div class="form-title">
                <h2>Admin Panel</h2>
                <p style="color: var(--text-secondary);">Welcome back, please login</p>
            </div>

            @if($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div class="form-group" style="text-align: left;">
                    <label><i class="fas fa-envelope" style="margin-right: 8px;"></i> Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@gmail.com"
                        value="{{ old('email') }}" required>
                </div>
                <div class="form-group" style="text-align: left;">
                    <label><i class="fas fa-lock" style="margin-right: 8px;"></i> Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-premium"
                    style="width: 100%; margin-top: 1rem; border-radius: 15px;">Login to Dashboard</button>
            </form>
        </div>
    </div>
</body>

</html>