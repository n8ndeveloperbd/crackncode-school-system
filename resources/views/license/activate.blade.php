<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সফটওয়্যার লাইসেন্স অ্যাক্টিভেশন — CrackNCode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
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
        .license-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 42px 36px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        .key-badge {
            width: 72px;
            height: 72px;
            background: var(--primary-gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #ffffff;
            margin: 0 auto 18px;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
        }
        .title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }
        .subtitle {
            font-size: 13.5px;
            color: #64748b;
            margin-bottom: 24px;
            line-height: 1.5;
        }
        .status-box {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            text-align: left;
            font-size: 13px;
        }
        .code-input {
            border: 2px solid #c7d2fe;
            border-radius: 12px;
            padding: 13px 16px;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            font-family: 'Plus Jakarta Sans', monospace;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #1e1b4b;
            transition: all 0.2s;
        }
        .code-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
        }
        .btn-activate {
            background: var(--primary-gradient);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-size: 15px;
            font-weight: 700;
            width: 100%;
            margin-top: 14px;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35);
            transition: all 0.2s;
        }
        .btn-activate:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(79, 70, 229, 0.45);
            color: #ffffff;
        }
        .contact-box {
            margin-top: 26px;
            padding: 14px;
            background: rgba(79, 70, 229, 0.05);
            border: 1px solid rgba(79, 70, 229, 0.15);
            border-radius: 12px;
            font-size: 12.5px;
            color: #475569;
        }
        .contact-box a {
            color: #4f46e5;
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="license-card">
    <div class="key-badge">
        <i class="bi bi-key-fill"></i>
    </div>
    <div class="title">সফটওয়্যার লাইসেন্স সক্রিয়করণ</div>
    <div class="subtitle">
        {{ setting('school_name', 'CrackNCode School Management') }}-এ স্বাগতম। সফটওয়্যার ব্যবহারের জন্য আপনার লাইসেন্স বা ট্রায়াল কোড প্রবেশ করান।
    </div>

    @if(session('error'))
    <div class="alert alert-danger mb-3 text-start" style="font-size:13px; border-radius:10px;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
    </div>
    @endif

    @if(auth()->check())
    <div style="background:#f8fafc; border-radius:12px; padding:10px 14px; margin-bottom:18px; font-size:12.5px; text-align:left; border:1.5px solid #e2e8f0; display:flex; align-items:center; gap:10px;">
        <div style="width:36px; height:36px; border-radius:50%; background:#eef2ff; color:#4f46e5; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0;">
            <i class="bi bi-person-badge-fill"></i>
        </div>
        <div>
            <div style="font-weight:700; color:#1e293b;">{{ auth()->user()->name }} <span class="badge badge-purple" style="font-size:10px; margin-left:4px;">{{ auth()->user()->role_label }}</span></div>
            <div style="color:#64748b; font-size:11.5px;">{{ auth()->user()->email }}</div>
        </div>
    </div>
    @endif

    <div class="status-box">
        <div class="d-flex justify-content-between mb-1">
            <span class="text-muted">বর্তমান অবস্থা:</span>
            <span class="fw-bold {{ $licenseInfo['active'] ? 'text-success' : 'text-danger' }}">
                {{ $licenseInfo['active'] ? 'সক্রিয় (Active)' : 'মেয়াদোত্তীর্ণ / অননুমোদিত (Expired)' }}
            </span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="text-muted">অবশিষ্ট সময়সীমা:</span>
            <span class="fw-bold text-dark">{{ $licenseInfo['remaining_human'] }}</span>
        </div>
    </div>

    <form method="POST" action="{{ route('license.submit') }}">
        @csrf
        <div class="mb-3 text-start">
            <label class="form-label fw-bold" style="font-size:13px;">লাইসেন্স কোড (License Key)</label>
            <input type="text" name="code" class="form-control code-input" placeholder="CNC-XXXX-XXXX-XXXX" required autofocus value="{{ old('code') }}">
            <small class="text-muted d-block mt-1" style="font-size:11.5px;">* একটি লাইসেন্স কোড কেবলমাত্র একবারই ব্যবহারযোগ্য।</small>
        </div>

        <button type="submit" class="btn-activate">
            <i class="bi bi-shield-check me-2"></i> লাইসেন্স সক্রিয় করুন
        </button>
    </form>

    <div class="contact-box">
        <div class="fw-bold mb-1"><i class="bi bi-headset me-1"></i> লাইসেন্স কি প্রয়োজন?</div>
        <div>লাইসেন্স কোড সংগ্রহ বা ট্রায়াল সময় বৃদ্ধির জন্য যোগাযোগ করুন:</div>
        <div class="mt-1">
            <strong>CrackNCode</strong> &bull;
            <a href="https://crackncode.com" target="_blank">crackncode.com</a> &bull;
            <a href="tel:01788155379">01788155379</a>
        </div>
    </div>

    @if(auth()->check())
    <div class="mt-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-muted btn-sm" style="font-size:12px; text-decoration:none;">
                <i class="bi bi-box-arrow-right"></i> লগআউট করুন
            </button>
        </form>
    </div>
    @endif
</div>
</body>
</html>
