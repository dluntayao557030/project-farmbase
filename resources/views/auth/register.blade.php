<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmbase – Create Your Barn</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --green-dark:   #2d5a1b;
            --green-mid:    #3d7a25;
            --green-main:   #4e9a30;
            --green-light:  #6bbf42;
            --green-pale:   #e8f5e0;
            --green-border: #b8dda0;
            --cream:        #f7f9f3;
            --text-dark:    #1a2e10;
            --text-mid:     #3a5228;
            --font:         'Inconsolata', monospace;
        }

        * { box-sizing: border-box; }

        html, body {
            min-height: 100%;
            margin: 0;
            font-family: var(--font);
            background: url('/images/backgrounds/FarmbaseBackground.png') center/cover fixed no-repeat;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(60, 120, 30, 0.25);
            pointer-events: none;
        }

        .page-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .setup-card {
            width: 100%;
            max-width: 480px;
            background: #f7f9f3;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 16px 50px rgba(20, 60, 5, 0.3);
            border: 1px solid var(--green-border);
        }

        .card-hero {
            position: relative;
            height: 130px;
            background: url('/images/backgrounds/BarnImage1.jpg') center 40%/cover no-repeat;
        }

        .card-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(20,50,8,0.3), rgba(20,50,8,0.65));
        }

        .hero-logo {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -60%);
            z-index: 2;
        }

        .hero-logo img {
            height: 42px;
            mix-blend-mode: screen;
            filter: brightness(1.0);
        }

        .stepper {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 2;
            display: flex;
            justify-content: center;
            padding: 0 1.5rem 0.9rem;
            gap: 0;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            flex: 1;
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 10px;
            left: calc(50% + 12px);
            right: calc(-50% + 12px);
            height: 2px;
            background: rgba(255,255,255,0.4);
        }

        .step-item.done:not(:last-child)::after,
        .step-item.active:not(:last-child)::after {
            background: rgba(255,255,255,0.85);
        }

        .step-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(255,255,255,0.35);
            border: 2px solid rgba(255,255,255,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            color: #fff;
            font-weight: 700;
        }

        .step-item.active .step-dot {
            background: #fff;
            border-color: #fff;
            color: var(--green-dark);
        }

        .step-item.done .step-dot {
            background: var(--green-light);
            border-color: var(--green-light);
        }

        .step-label {
            font-size: 0.6rem;
            font-weight: 600;
            color: rgba(255,255,255,0.75);
        }

        .step-item.active .step-label,
        .step-item.done .step-label { color: #fff; }

        .card-body-inner {
            padding: 2rem 2.2rem;
        }

        .card-body-inner h2 {
            font-size: 1.42rem;
            font-weight: 800;
            color: var(--text-dark);
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 0.83rem;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 0.35rem;
        }

        .form-control, .form-select {
            font-family: var(--font);
            font-size: 0.91rem;
            background: #fff;
            border: 1.5px solid var(--green-border);
            border-radius: 8px;
            padding: 0.6rem 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(78, 154, 48, 0.18);
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #c0392b;
        }

        .password-wrapper { position: relative; }
        .password-wrapper .form-control { padding-right: 2.8rem; }

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-mid);
            font-size: 1rem;
        }

        .upload-zone {
            border: 2px dashed var(--green-border);
            border-radius: 10px;
            background: #fff;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            cursor: pointer;
            transition: all 0.2s;
            padding: 1.2rem;
            position: relative;
        }

        .upload-zone:hover {
            border-color: var(--green-main);
            background: var(--green-pale);
        }

        .upload-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .btn-row {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-back, .btn-next {
            flex: 1;
            padding: 0.65rem;
            font-weight: 700;
            border-radius: 8px;
            font-family: var(--font);
        }

        .btn-back {
            background: transparent;
            color: var(--green-dark);
            border: 1.5px solid var(--green-border);
        }

        .btn-next {
            background: var(--green-main);
            color: white;
            border: none;
        }

        .btn-next:hover { background: var(--green-dark); }

        .alert-farmbase {
            background: #fdecea;
            border: 1px solid #e57373;
            border-radius: 8px;
            padding: 0.8rem 1rem;
            font-size: 0.85rem;
            color: #b71c1c;
        }

        .step-panel { display: none; }
        .step-panel.active { display: block; }

        .modal-backdrop-custom {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(20, 50, 8, 0.6);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-backdrop-custom.show {
            display: flex;
        }

        .success-modal {
            background: var(--cream);
            border-radius: 18px;
            padding: 2.5rem 2rem;
            max-width: 380px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(20,50,8,0.35);
            border: 1px solid var(--green-border);
            animation: popIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes popIn {
            from { transform: scale(0.7); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }

        .success-icon {
            font-size: 3.5rem;
            margin-bottom: 0.8rem;
        }

        .btn-success-ok {
            background: var(--green-main);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.65rem 2.5rem;
            font-weight: 700;
            font-family: var(--font);
        }

        .btn-success-ok:hover { background: var(--green-dark); }
    </style>
</head>
<body>

<div class="page-wrap">
    <div class="setup-card">

        <div class="card-hero">
            <div class="hero-logo">
                <img src="/images/logos/FarmbaseLogoOnly.png" alt="Farmbase">
            </div>

            <div class="stepper" id="stepper">
                <div class="step-item active" data-step="1">
                    <div class="step-dot">1</div>
                    <div class="step-label">Personal Details</div>
                </div>
                <div class="step-item" data-step="2">
                    <div class="step-dot">2</div>
                    <div class="step-label">Farm and Barn Details</div>
                </div>
                <div class="step-item" data-step="3">
                    <div class="step-dot">3</div>
                    <div class="step-label">Verification</div>
                </div>
            </div>
        </div>

        <div class="card-body-inner">

            @if ($errors->any())
                <div class="alert-farmbase">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" id="setupForm">
                @csrf

                @include('auth.partials.register-barn-step-1')
                @include('auth.partials.register-barn-step-2')
                @include('auth.partials.register-barn-step-3')
            </form>
        </div>
    </div>
</div>

{{-- Success Modal --}}
<div class="modal-backdrop-custom @if(session('success')) show @endif" id="successModal">
    <div class="success-modal">
        <span class="success-icon">🌾</span>
        <h3>Barn Created!</h3>
        <p>Your digital barn has been set up successfully.<br>You can now log in and start managing your farm inventory.</p>
        <button class="btn-success-ok" onclick="goToLogin()">Go to Login</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let currentStep = 1;

    // Determine starting step based on validation errors
    @if ($errors->any())
        @if ($errors->hasAny(['first_name','last_name','email','username','password','password_confirmation']))
            currentStep = 1;
        @elseif ($errors->hasAny(['barn_name','country','region','city','farm_type']))
            currentStep = 2;
        @elseif ($errors->has('permit_number'))
            currentStep = 3;
        @endif
    @endif

    document.addEventListener('DOMContentLoaded', () => {
        showStep(currentStep);
    });

    function showStep(step) {
        document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('panel-' + step).classList.add('active');

        document.querySelectorAll('.step-item').forEach(item => {
            const s = parseInt(item.dataset.step);
            item.classList.remove('active', 'done');
            if (s < step) item.classList.add('done');
            if (s === step) item.classList.add('active');
        });

        currentStep = step;
    }

    function goToStep(step) {
        if (step > currentStep) {
            if (currentStep === 1 && !validateStep1()) return;
            if (currentStep === 2 && !validateStep2()) return;
        }
        showStep(step);
    }

    function validateStep1() {
        let valid = true;
        const fields = ['first_name','last_name','email','username','password','password_confirmation'];

        fields.forEach(f => {
            const el = document.querySelector(`[name="${f}"]`);
            if (el) el.classList.remove('is-invalid');
        });

        if (!document.querySelector('[name="first_name"]').value.trim()) valid = false;
        if (!document.querySelector('[name="last_name"]').value.trim()) valid = false;
        if (!document.querySelector('[name="email"]').value.trim()) valid = false;
        if (!document.querySelector('[name="username"]').value.trim()) valid = false;
        if (document.querySelector('[name="password"]').value.length < 8) valid = false;
        if (document.querySelector('[name="password"]').value !== document.querySelector('[name="password_confirmation"]').value) valid = false;

        if (!valid) {
            // Re-apply invalid classes for visual feedback
            if (!document.querySelector('[name="first_name"]').value.trim()) document.querySelector('[name="first_name"]').classList.add('is-invalid');
            if (!document.querySelector('[name="last_name"]').value.trim()) document.querySelector('[name="last_name"]').classList.add('is-invalid');
            if (!document.querySelector('[name="email"]').value.trim()) document.querySelector('[name="email"]').classList.add('is-invalid');
            if (!document.querySelector('[name="username"]').value.trim()) document.querySelector('[name="username"]').classList.add('is-invalid');
            if (document.querySelector('[name="password"]').value.length < 8) document.querySelector('[name="password"]').classList.add('is-invalid');
            if (document.querySelector('[name="password"]').value !== document.querySelector('[name="password_confirmation"]').value) document.querySelector('[name="password_confirmation"]').classList.add('is-invalid');
        }

        return valid;
    }

    function validateStep2() {
        let valid = true;
        const fields = ['barn_name','country','region','city','farm_type'];

        fields.forEach(f => {
            const el = document.querySelector(`[name="${f}"]`);
            if (el) el.classList.remove('is-invalid');
        });

        if (!document.querySelector('[name="barn_name"]').value.trim()) valid = false;
        if (!document.querySelector('[name="country"]').value.trim()) valid = false;
        if (!document.querySelector('[name="region"]').value.trim()) valid = false;
        if (!document.querySelector('[name="city"]').value.trim()) valid = false;
        if (!document.querySelector('[name="farm_type"]').value) valid = false;

        if (!valid) {
            if (!document.querySelector('[name="barn_name"]').value.trim()) document.querySelector('[name="barn_name"]').classList.add('is-invalid');
            if (!document.querySelector('[name="country"]').value.trim()) document.querySelector('[name="country"]').classList.add('is-invalid');
            if (!document.querySelector('[name="region"]').value.trim()) document.querySelector('[name="region"]').classList.add('is-invalid');
            if (!document.querySelector('[name="city"]').value.trim()) document.querySelector('[name="city"]').classList.add('is-invalid');
            if (!document.querySelector('[name="farm_type"]').value) document.querySelector('[name="farm_type"]').classList.add('is-invalid');
        }

        return valid;
    }

    function handleFileSelect(input) {
        const preview = document.getElementById('uploadPreview');
        if (input.files && input.files[0]) {
            preview.style.display = 'block';
            preview.textContent = '✅ ' + input.files[0].name;
            document.querySelector('.upload-label').textContent = 'File selected';
        }
    }

    function togglePw(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (field.type === 'password') {
            field.type = 'text';
            icon.textContent = '⌣';
        } else {
            field.type = 'password';
            icon.textContent = '👁';
        }
    }

    function goToLogin() {
        window.location.href = "{{ route('login') }}";
    }
</script>

</body>
</html>