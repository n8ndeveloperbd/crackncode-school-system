@extends('layouts.app')
@section('title', 'সুপার অ্যাডমিন ড্যাশবোর্ড')
@section('page-title', 'সুপার অ্যাডমিন পোর্টাল (Super Admin)')
@section('breadcrumb', 'CrackNCode / Super Admin')

@section('content')
<div style="background:linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); border-radius:var(--radius); padding:24px 28px; color:#fff; margin-bottom:24px; box-shadow:0 10px 30px rgba(49,46,129,0.3); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
    <div>
        <div style="display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,0.15); padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; margin-bottom:8px; font-family:var(--font-en);">
            <i class="bi bi-shield-lock-fill" style="color:#a5b4fc;"></i> Master Control Panel
        </div>
        <h2 style="font-size:22px; font-weight:800; margin-bottom:4px;">সুপার অ্যাডমিন ম্যানেজমেন্ট কনসোল</h2>
        <p style="font-size:13.5px; opacity:0.85; margin:0;">লাইসেন্স কী জেনারেট করুন, অধ্যক্ষ ও হিসাব রক্ষক অ্যাকাউন্ট নিয়ন্ত্রণ করুন এবং স্কুল ব্রান্ডিং পরিচালনা করুন।</p>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('super-admin.licenses') }}" class="btn" style="background:#fff; color:#312e81; font-weight:700;">
            <i class="bi bi-key-fill"></i> লাইসেন্স ম্যানেজার
        </a>
        <a href="{{ route('super-admin.users') }}" class="btn" style="background:rgba(255,255,255,0.2); color:#fff; font-weight:700;">
            <i class="bi bi-person-plus-fill"></i> স্টাফ অ্যাকাউন্ট
        </a>
    </div>
</div>

{{-- Metric Counters --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px;" class="stats-grid">
    <div class="stat-card" style="background:linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
        <div class="icon"><i class="bi bi-key-fill"></i></div>
        <div class="value">{{ number_format($unusedLicenses) }}</div>
        <div class="label">অব্যবহৃত লাইসেন্স কোড (Unused)</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #059669 0%, #10b981 100%);">
        <div class="icon"><i class="bi bi-check-circle-fill"></i></div>
        <div class="value">{{ number_format($usedLicenses) }}</div>
        <div class="label">সক্রিয় / ব্যবহৃত লাইসেন্স (Used)</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);">
        <div class="icon"><i class="bi bi-person-badge-fill"></i></div>
        <div class="value">{{ number_format($principalsCount) }}</div>
        <div class="label">প্রধান শিক্ষক অ্যাকাউন্ট (Principal)</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #d97706 0%, #f59e0b 100%);">
        <div class="icon"><i class="bi bi-cash-stack"></i></div>
        <div class="value">{{ number_format($cashiersCount) }}</div>
        <div class="label">হিসাব রক্ষক অ্যাকাউন্ট (Cashier)</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1.2fr; gap:20px; margin-bottom:24px;" class="responsive-grid">
    {{-- Quick Generate License Form --}}
    <div class="card">
        <div class="card-header" style="border-bottom:1px solid var(--border); padding-bottom:14px;">
            <div class="card-title"><i class="bi bi-lightning-charge-fill"></i> দ্রুত লাইসেন্স কোড তৈরি করুন</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('super-admin.licenses.generate') }}">
                @csrf
                <div class="form-grid" style="gap:14px;">
                    <div>
                        <label class="form-label">গ্রাহক / প্রতিষ্ঠানের নাম বা রেফারেন্স</label>
                        <input type="text" name="client_name" class="form-control" placeholder="যেমন: আইডিয়াল স্কুল ডেমো" required>
                    </div>
                    <div>
                        <label class="form-label">মেয়াদ / অ্যাক্সেসের ধরন</label>
                        <select name="type" class="form-select" id="licenseTypeSelect" onchange="toggleCustomInput(this.value)">
                            <option value="trial_30m">⏱️ ৩০ মিনিট ট্রায়াল (30 Min Trial)</option>
                            <option value="trial_1h">⏱️ ১ ঘণ্টা ট্রায়াল (1 Hour Trial)</option>
                            <option value="custom_minutes">⏱️ কাস্টম মিনিট (Custom Minutes)</option>
                            <option value="custom_days">📅 কাস্টম দিন (Custom Days)</option>
                            <option value="lifetime">♾️ লাইফটাইম অ্যাক্সেস (Lifetime)</option>
                        </select>
                    </div>
                    <div id="customDurationBox" style="display:none;">
                        <label class="form-label" id="customDurationLabel">পরিমাণ লিখুন</label>
                        <input type="number" name="custom_duration" class="form-control" placeholder="সংখ্যা লিখুন (যেমন: 15 বা 7)" min="1">
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top:4px;">
                        <i class="bi bi-plus-circle-fill"></i> লাইসেন্স কোড তৈরি করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- System Status & Active License Info --}}
    <div class="card">
        <div class="card-header" style="border-bottom:1px solid var(--border); padding-bottom:14px;">
            <div class="card-title"><i class="bi bi-shield-check"></i> সিস্টেম লাইসেন্স বর্তমান স্ট্যাটাস</div>
            <a href="{{ route('super-admin.licenses') }}" class="btn btn-outline btn-sm">সব কোড দেখুন</a>
        </div>
        <div class="card-body">
            <div style="background:var(--bg); border-radius:12px; padding:16px; margin-bottom:16px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13.5px;">
                    <span style="color:var(--text-muted);">সক্রিয়তার অবস্থা:</span>
                    <span class="badge badge-green">সক্রিয় (Active)</span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13.5px;">
                    <span style="color:var(--text-muted);">বর্তমান কোড:</span>
                    <span style="font-family:var(--font-en); font-weight:700; color:var(--primary);">{{ $licenseInfo['code'] ?: '—' }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13.5px;">
                    <span style="color:var(--text-muted);">অবশিষ্ট সময়সীমা:</span>
                    <span style="font-weight:700; color:var(--text);">{{ $licenseInfo['remaining_human'] }}</span>
                </div>
            </div>

            <div style="font-size:13px; font-weight:700; margin-bottom:10px;">সাম্প্রতিক লাইসেন্স কোডসমূহ:</div>
            <div style="display:flex; flex-direction:column; gap:8px;">
                @foreach($recentLicenses as $lic)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 12px; border:1px solid var(--border); border-radius:8px; font-size:12.5px;">
                    <div>
                        <strong style="font-family:monospace; color:var(--primary);">{{ $lic->code }}</strong>
                        <span style="color:var(--text-muted); margin-left:6px;">({{ $lic->type_label }})</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span class="badge {{ $lic->status === 'unused' ? 'badge-green' : 'badge-gray' }}">
                            {{ $lic->status === 'unused' ? 'অব্যবহৃত' : 'ব্যবহৃত' }}
                        </span>
                        <button class="btn btn-outline btn-sm" onclick="copyCode('{{ $lic->code }}')" title="Copy Code">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Staff Users Quick Table --}}
<div class="card">
    <div class="card-header" style="border-bottom:1px solid var(--border); padding-bottom:14px;">
        <div class="card-title"><i class="bi bi-people-fill"></i> অনুমোদিত স্টাফ অ্যাকাউন্টসমূহ (Principal & Cashier)</div>
        <a href="{{ route('super-admin.users') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus-fill"></i> নতুন অ্যাকাউন্ট যোগ করুন
        </a>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>নাম</th>
                    <th>ইমেইল ঠিকানা</th>
                    <th>ফোন</th>
                    <th>ভূমিকা (Role)</th>
                    <th>অবস্থা</th>
                    <th style="text-align:right;">পদক্ষেপ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffUsers as $usr)
                <tr>
                    <td>
                        <strong>{{ $usr->name }}</strong>
                    </td>
                    <td style="font-family:var(--font-en);">{{ $usr->email }}</td>
                    <td style="font-family:var(--font-en);">{{ $usr->phone ?: '—' }}</td>
                    <td>
                        @if($usr->isPrincipal())
                        <span class="badge badge-purple"><i class="bi bi-mortarboard-fill"></i> Principal</span>
                        @else
                        <span class="badge badge-blue"><i class="bi bi-wallet2"></i> Cashier</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $usr->status === 'active' ? 'badge-green' : 'badge-red' }}">
                            {{ $usr->status === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <a href="{{ route('super-admin.users') }}" class="btn btn-outline btn-sm">ব্যবস্থাপনা করুন</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleCustomInput(val) {
    const box = document.getElementById('customDurationBox');
    const label = document.getElementById('customDurationLabel');
    if (val === 'custom_minutes') {
        box.style.display = 'block';
        label.innerText = 'মিনিটের সংখ্যা লিখুন (যেমন: 45)';
    } else if (val === 'custom_days') {
        box.style.display = 'block';
        label.innerText = 'দিনের সংখ্যা লিখুন (যেমন: 15)';
    } else {
        box.style.display = 'none';
    }
}

function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        alert('📋 লাইসেন্স কোড কপি হয়েছে: ' + code);
    });
}
</script>
@endsection
