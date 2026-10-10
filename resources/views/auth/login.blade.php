<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>লগইন — CrackNCode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-mid: #6366f1;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
        }
        * { font-family: 'Hind Siliguri', 'Plus Jakarta Sans', sans-serif; }
        body {
            background: radial-gradient(ellipse at top, #1e1b4b 0%, #090d16 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: #0f172a;
        }
        .login-wrap {
            width: 100%;
            max-width: 440px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }
        .brand-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-gradient);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #ffffff;
            margin: 0 auto 16px;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.35);
        }
        .brand-title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            text-align: center;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .brand-sub {
            font-size: 13px;
            color: #64748b;
            text-align: center;
            margin-bottom: 28px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }
        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 11px 15px;
            font-size: 14px;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }
        .btn-login {
            background: var(--primary-gradient);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
            color: #ffffff;
            filter: brightness(1.06);
        }
        .alert-danger {
            background: #fef2f2;
            border: none;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            border-radius: 8px;
            font-size: 13.5px;
        }

        /* Developer Footer Info */
        .login-footer {
            margin-top: 24px;
            text-align: center;
            color: #94a3b8;
            font-size: 12.5px;
        }
        .dev-badge-login {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            padding: 7px 16px;
            border-radius: 30px;
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.12);
            margin-bottom: 8px;
        }
        .dev-badge-login a {
            color: #818cf8;
            text-decoration: none;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: color 0.15s;
        }
        .dev-badge-login a:hover {
            color: #c7d2fe;
            text-decoration: underline;
        }
        .login-footer-copy {
            color: #64748b;
            font-size: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="brand-icon">
            <i class="bi bi-code-slash"></i>
        </div>
        <div class="brand-title">CrackNCode</div>
        <div class="brand-sub">Smart Institutional Management System</div>

        @if($errors->any())
        <div class="alert alert-danger mb-3">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            ইমেইল বা পাসওয়ার্ড সঠিক নয়!
        </div>
        @endif

        @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">ইমেইল ঠিকানা</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', 'admin@school.com') }}" required autofocus placeholder="admin@school.com">
            </div>
            <div class="mb-4">
                <label class="form-label">পাসওয়ার্ড</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <div class="mb-4 form-check d-flex justify-content-between align-items-center">
                <div>
                    <input type="checkbox" name="remember" class="form-check-input" id="remember" checked>
                    <label class="form-check-label" for="remember" style="font-size:13px; color:#475569;">মনে রাখুন</label>
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i> লগইন করুন
            </button>
        </form>
    </div>

    <div class="login-footer">
        <div class="dev-badge-login">
            <i class="bi bi-code-slash" style="color:#818cf8;"></i>
            Developer: <strong>CrackNCode</strong> &bull;
            <a href="https://crackncode.com" target="_blank" rel="noopener noreferrer">crackncode.com</a> &bull;
            <a href="tel:01788155379">01788155379</a>
        </div>
        <div class="login-footer-copy">
            &copy; {{ date('Y') }} CrackNCode — All rights reserved.
        </div>
    </div>
</div>
</body>
</html>
