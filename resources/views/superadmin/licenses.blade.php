@extends('layouts.app')
@section('title', 'লাইসেন্স ম্যানেজার')
@section('page-title', 'সফটওয়্যার লাইসেন্স ম্যানেজার (License Key Manager)')
@section('breadcrumb', 'Super Admin / Licenses')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
    <div>
        <h3 style="font-size:18px; font-weight:700; margin-bottom:2px;">লাইসেন্স কী জেনারেটর ও অ্যাক্টিভেশন তালিকা</h3>
        <p style="font-size:13px; color:var(--text-muted); margin:0;">গ্রাহকদের জন্য লিমিটেড ট্রায়াল (৩০ মিনিট, ১ ঘণ্টা, কাস্টম) অথবা লাইফটাইম কোড তৈরি করুন। প্রতিটি কোড মাত্র একবার ব্যবহারযোগ্য।</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('generateModal').classList.add('open')">
        <i class="bi bi-plus-lg"></i> নতুন লাইসেন্স তৈরি করুন
    </button>
</div>

{{-- Filter Tabs --}}
<div class="tabs">
    <a href="{{ route('super-admin.licenses') }}" class="tab-btn {{ !$status ? 'active' : '' }}">সব লাইসেন্স (All)</a>
    <a href="{{ route('super-admin.licenses', ['status' => 'unused']) }}" class="tab-btn {{ $status === 'unused' ? 'active' : '' }}">অব্যবহৃত কোড (Unused)</a>
    <a href="{{ route('super-admin.licenses', ['status' => 'used']) }}" class="tab-btn {{ $status === 'used' ? 'active' : '' }}">ব্যবহৃত কোড (Used)</a>
</div>

{{-- Licenses Table --}}
<div class="card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>লাইসেন্স কোড (Code)</th>
                    <th>ধরন (Type)</th>
                    <th>ক্লায়েন্ট / নোট</th>
                    <th>অবস্থা (Status)</th>
                    <th>ব্যবহারকারী</th>
                    <th>সক্রিয়তার তারিখ</th>
                    <th>মেয়াদোত্তীর্ণ</th>
                    <th style="text-align:right;">পদক্ষেপ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($licenses as $lic)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="font-family:monospace; font-weight:700; font-size:13.5px; color:var(--primary); background:#eef2ff; padding:3px 8px; border-radius:6px; border:1px solid #c7d2fe;">
                                {{ $lic->code }}
                            </span>
                            <button type="button" class="btn btn-outline btn-sm" style="padding:2px 7px;" onclick="copyCode('{{ $lic->code }}')" title="Copy Code">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <strong>{{ $lic->type_label }}</strong>
                    </td>
                    <td>{{ $lic->client_name ?: '—' }}</td>
                    <td>
                        @if($lic->status === 'unused')
                            <span class="badge badge-green"><i class="bi bi-check-circle"></i> অব্যবহৃত (Ready)</span>
                        @elseif($lic->isExpired())
                            <span class="badge badge-red"><i class="bi bi-x-circle"></i> মেয়াদ শেষ (Expired)</span>
                        @else
                            <span class="badge badge-blue"><i class="bi bi-shield-check"></i> ব্যবহৃত (Active)</span>
                        @endif
                    </td>
                    <td style="font-family:var(--font-en); font-size:12px;">{{ $lic->used_by_email ?: '—' }}</td>
                    <td style="font-size:12px;">{{ $lic->used_at ? $lic->used_at->format('d/m/Y h:i A') : '—' }}</td>
                    <td style="font-size:12px;">
                        @if($lic->type === 'lifetime')
                            <span class="badge badge-purple">আজীবন</span>
                        @else
                            {{ $lic->expires_at ? $lic->expires_at->format('d/m/Y h:i A') : '—' }}
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <form method="POST" action="{{ route('super-admin.licenses.delete', $lic) }}" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই লাইসেন্সটি মুছে ফেলতে চান?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:36px; color:var(--text-muted);">
                        কোনো লাইসেন্স কোড পাওয়া যায়নি।
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($licenses->hasPages())
    <div class="pagination-wrap">
        {{ $licenses->links() }}
    </div>
    @endif
</div>

{{-- Generate License Modal --}}
<div class="modal-overlay" id="generateModal">
    <div class="modal-box">
        <div class="modal-header">
            <h4 style="font-size:16px; font-weight:700;"><i class="bi bi-key-fill text-primary"></i> নতুন লাইসেন্স কোড তৈরি করুন</h4>
            <button type="button" class="btn-close" onclick="document.getElementById('generateModal').classList.remove('open')" style="background:none; border:none; font-size:18px; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('super-admin.licenses.generate') }}">
            @csrf
            <div class="modal-body">
                <div class="form-grid" style="gap:14px;">
                    <div>
                        <label class="form-label">ক্লায়েন্ট / স্কুল বা প্রতিষ্ঠানের নাম</label>
                        <input type="text" name="client_name" class="form-control" placeholder="যেমন: সেন্ট্রাল হাই স্কুল ডেমো" required>
                    </div>
                    <div>
                        <label class="form-label">লাইসেন্সের ধরন (Duration)</label>
                        <select name="type" class="form-select" onchange="modalToggleCustom(this.value)">
                            <option value="trial_30m">⏱️ ৩০ মিনিট ট্রায়াল (30 Minutes Trial)</option>
                            <option value="trial_1h">⏱️ ১ ঘণ্টা ট্রায়াল (1 Hour Trial)</option>
                            <option value="custom_minutes">⏱️ কাস্টম মিনিট (Custom Minutes)</option>
                            <option value="custom_days">📅 কাস্টম দিন (Custom Days)</option>
                            <option value="lifetime">♾️ লাইফটাইম অ্যাক্সেস (Lifetime)</option>
                        </select>
                    </div>
                    <div id="modalCustomDurationBox" style="display:none;">
                        <label class="form-label" id="modalCustomLabel">সংখ্যা লিখুন</label>
                        <input type="number" name="custom_duration" class="form-control" placeholder="যেমন: 45 বা 30" min="1">
                    </div>
                    <div style="font-size:12px; color:var(--text-muted); background:var(--bg); padding:10px; border-radius:8px;">
                        <i class="bi bi-info-circle-fill" style="color:var(--primary);"></i>
                        একটি লাইসেন্স কোড ক্লায়েন্ট প্রবেশ করানোর সাথে সাথে <strong>ব্যবহৃত (Used)</strong> হিসেবে চিহ্নিত হবে এবং সেটি আর দ্বিতীয়বার ব্যবহার করা যাবে না।
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('generateModal').classList.remove('open')">বাতিল</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-magic"></i> জেনারেট করুন</button>
            </div>
        </form>
    </div>
</div>

<script>
function modalToggleCustom(val) {
    const box = document.getElementById('modalCustomDurationBox');
    const label = document.getElementById('modalCustomLabel');
    if (val === 'custom_minutes') {
        box.style.display = 'block';
        label.innerText = 'মিনিটের সংখ্যা লিখুন (যেমন: 45)';
    } else if (val === 'custom_days') {
        box.style.display = 'block';
        label.innerText = 'দিনের সংখ্যা লিখুন (যেমন: 30)';
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
