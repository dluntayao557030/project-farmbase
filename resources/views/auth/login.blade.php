<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmbase – Login</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --green-dark:   #2d5a1b;
            --green-mid:    #3d7a25;
            --green-main:   #4e9a30;
            --green-border: #b8dda0;
            --text-dark:    #1a2e10;
            --font:         'Inconsolata', monospace;
        }

        * { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            font-family: var(--font);
            background: var(--green-main);
        }

        .auth-wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* LEFT PANEL - Static Background */
        .auth-left {
            flex: 0 0 60%;
            position: relative;
            overflow: hidden;
        }

        .auth-left .bg-photo {
            position: absolute;
            inset: 0;
            background: url('/images/backgrounds/BarnImage1.jpg') center/cover no-repeat;
            filter: brightness(0.75);
        }

        .auth-left .overlay-grad {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(20, 50, 8, 0.3) 0%, rgba(20, 50, 8, 0.68) 100%);
        }

        .auth-left .left-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem 3.2rem;
        }

        /* Logo + Title - Side by Side */
        .left-brand {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        .left-brand .logo-icon {
            height: 64px;
            width: auto;
            filter: brightness(0.8);
            mix-blend-mode: screen;
            flex-shrink: 0;
        }

        .left-brand .logo-title {
            height: 150px;
            width: auto;
            filter: brightness(2.0);
            flex-shrink: 0;
        }

        /* Hero Text */
        .hero-copy {
            padding-bottom: 2.5rem;
        }

        .hero-copy h1 {
            font-size: clamp(1.9rem, 3.8vw, 2.9rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 1rem;
            text-shadow: 0 3px 12px rgba(0,0,0,0.5);
        }

        .hero-copy p {
            font-size: 0.97rem;
            color: rgba(255,255,255,0.9);
            max-width: 400px;
            line-height: 1.7;
        }

        /* RIGHT PANEL */
        .auth-right {
            flex: 0 0 40%;
            background: url('/images/backgrounds/FarmbaseBackground.png') right/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        .auth-right::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(78, 154, 48, 0.37);
        }

        .login-card {
            position: relative;
            z-index: 1;
            background: #f7f9f3;
            border-radius: 16px;
            padding: 2.8rem 2.5rem;
            width: 100%;
            max-width: 370px;
            box-shadow: 0 14px 45px rgba(20, 60, 5, 0.28);
            border: 1px solid var(--green-border);
        }

        .card-logo {
            text-align: center;
            margin-bottom: 1.2rem;
        }

        .card-logo img {
            height: 62px;
            mix-blend-mode: multiply;
        }

        .login-card h2 {
            font-size: 1.68rem;
            font-weight: 800;
            color: var(--text-dark);
            text-align: center;
            margin-bottom: 1.8rem;
        }

        /* Form Styles */
        .form-label {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 0.4rem;
        }

        .form-control {
            font-family: var(--font);
            font-size: 0.93rem;
            padding: 0.68rem 0.95rem;
            border: 1.6px solid var(--green-border);
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(78, 154, 48, 0.17);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 3.2rem;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #3a5228;
            font-size: 1.15rem;
        }

        .btn-login {
            width: 100%;
            background: var(--green-main);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.78rem;
            font-weight: 700;
            font-size: 1.03rem;
            margin-top: 0.6rem;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: var(--green-dark);
            transform: translateY(-2px);
        }

        .register-row {
            text-align: center;
            margin-top: 1.4rem;
            font-size: 0.86rem;
            color: var(--text-mid);
        }

        .register-row a {
            color: var(--green-dark);
            font-weight: 700;
        }

        .alert-farmbase {
            background: #fdecea;
            border: 1px solid #e57373;
            border-radius: 8px;
            padding: 0.85rem 1.1rem;
            font-size: 0.86rem;
            color: #b71c1c;
            margin-bottom: 1.3rem;
        }

        @media (max-width: 768px) {
            .auth-left { display: none; }
            .auth-right { flex: 1; }
        }
    </style>
</head>
<body>

<div class="auth-wrapper">

    <!-- LEFT: Hero Image Panel (Static Background) -->
    <div class="auth-left">
        <div class="bg-photo"></div>
        <div class="overlay-grad"></div>
        
        <div class="left-content">
            <!-- Logo Icon + Title Side by Side -->
            <div class="left-brand">
                <img src="/images/logos/FarmbaseLogoOnly.png" 
                     alt="Farmbase Icon" 
                     class="logo-icon">

                <img src="/images/logos/FarmbaseTitleOnly.png" 
                     alt="Farmbase Title" 
                     class="logo-title">
            </div>

            <div class="hero-copy">
                <h1>Manage your farm.<br>Create a digital barn.</h1>
                <p>Farmbase helps you streamline inventory management, track stock movements, and stay in control of your farm operations.</p>
            </div>
        </div>
    </div>

    <!-- RIGHT: Login Form -->
    <div class="auth-right">
        <div class="login-card">

            <div class="card-logo">
                <img src="/images/logos/FarmbaseLogoOnly.png" alt="Farmbase Icon">
            </div>

            <h2>Welcome back</h2>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert-farmbase">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('error'))
                <div class="alert-farmbase">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" 
                           name="username" 
                           value="{{ old('username') }}" 
                           class="form-control @error('username') is-invalid @enderror"
                           autocomplete="username" 
                           autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" 
                               name="password" 
                               id="passwordField"
                               class="form-control @error('password') is-invalid @enderror"
                               autocomplete="current-password">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <span id="eyeIcon">👁</span>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn-login">Log in</button>
            </form>

            <div class="register-row">
                New to Farmbase? <a href="{{ route('register') }}">Create your barn.</a>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword() {
        const field = document.getElementById('passwordField');
        const icon  = document.getElementById('eyeIcon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.textContent = '⌣';
        } else {
            field.type = 'password';
            icon.textContent = '👁';
        }
    }
</script>

</body>
</html>