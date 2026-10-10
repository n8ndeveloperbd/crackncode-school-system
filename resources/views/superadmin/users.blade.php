@extends('layouts.app')
@section('title', 'স্টাফ অ্যাকাউন্ট ম্যানেজার')
@section('page-title', 'স্টাফ অ্যাকাউন্ট পরিচালনা (Staff Management)')
@section('breadcrumb', 'Super Admin / Users')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
    <div>
        <h3 style="font-size:18px; font-weight:700; margin-bottom:2px;">অধ্যক্ষ ও ক্যাশিয়ার অ্যাকাউন্ট পরিচালনা</h3>
        <p style="font-size:13px; color:var(--text-muted); margin:0;">সুপার অ্যাডমিন হিসেবে আপনি সরাসরি প্রধান শিক্ষক (Principal) এবং হিসাব রক্ষক (Cashier) অ্যাকাউন্ট তৈরি ও নিয়ন্ত্রণ করতে পারেন।</p>
    </div>
    <button class="btn btn-primary" onclick="openCreateModal()">
        <i class="bi bi-person-plus-fill"></i> নতুন অ্যাকাউন্ট তৈরি করুন
    </button>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ব্যবহারকারী (User)</th>
                    <th>দায়িত্ব (Role)</th>
                    <th>ইমেইল ঠিকানা</th>
                    <th>ফোন নম্বর</th>
                    <th>অবস্থা (Status)</th>
                    <th>তৈরির তারিখ</th>
                    <th style="text-align:right;">পদক্ষেপ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:36px; height:36px; border-radius:50%; background:{{ $u->role === 'principal' ? '#eef2ff' : '#ecfdf5' }}; color:{{ $u->role === 'principal' ? '#4f46e5' : '#059669' }}; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px;">
                                <i class="bi {{ $u->role === 'principal' ? 'bi-person-badge-fill' : 'bi-cash-coin' }}"></i>
                            </div>
                            <div>
                                <div style="font-weight:700; color:var(--text-dark);">{{ $u->name }}</div>
                                <div style="font-size:11.5px; color:var(--text-muted); font-family:var(--font-en);">ID #{{ $u->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($u->role === 'principal' || $u->role === 'admin')
                            <span class="badge badge-purple" style="font-size:12px;">
                                <i class="bi bi-person-badge-fill"></i> অধ্যক্ষ (Principal)
                            </span>
                        @elseif($u->role === 'cashier' || $u->role === 'accounts')
                            <span class="badge badge-blue" style="font-size:12px;">
                                <i class="bi bi-cash-stack"></i> ক্যাশিয়ার (Cashier)
                            </span>
                        @else
                            <span class="badge badge-gray">{{ ucfirst($u->role) }}</span>
                        @endif
                    </td>
                    <td style="font-family:var(--font-en); font-weight:500;">{{ $u->email }}</td>
                    <td style="font-family:var(--font-en);">{{ $u->phone ?: '—' }}</td>
                    <td>
                        @if($u->status === 'active')
                            <span class="badge badge-green"><i class="bi bi-check-circle"></i> সক্রিয় (Active)</span>
                        @else
                            <span class="badge badge-red"><i class="bi bi-pause-circle"></i> নিষ্ক্রিয় (Inactive)</span>
                        @endif
                    </td>
                    <td style="font-size:12px; font-family:var(--font-en);">{{ $u->created_at ? $u->created_at->format('d/m/Y') : '—' }}</td>
                    <td style="text-align:right;">
                        <div style="display:inline-flex; gap:6px;">
                            {{-- Toggle Status Button --}}
                            <form method="POST" action="{{ route('super-admin.users.toggle-status', $u) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-sm" title="{{ $u->status === 'active' ? 'Disable Account' : 'Activate Account' }}" style="padding:4px 8px;">
                                    <i class="bi {{ $u->status === 'active' ? 'bi-toggle-on text-success' : 'bi-toggle-off text-muted' }}" style="font-size:15px;"></i>
                                </button>
                            </form>

                            {{-- Edit Button --}}
                            <button type="button" class="btn btn-outline btn-sm" onclick="openEditModal({{ json_encode($u) }})" style="padding:4px 8px;" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            {{-- Delete Button --}}
                            <form method="POST" action="{{ route('super-admin.users.delete', $u) }}" onsubmit="return confirm('আপনি কি নিশ্চিত যে {{ $u->name }} এর অ্যাকাউন্ট মুছে ফেলতে চান?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="padding:4px 8px;" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:32px; color:var(--text-muted);">
                        কোনো স্টাফ অ্যাকাউন্ট পাওয়া যায়নি।
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="padding:16px 20px; border-top:1px solid var(--border);">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Modal: Create User --}}
<div class="modal" id="createModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><i class="bi bi-person-plus-fill"></i> নতুন স্টাফ অ্যাকাউন্ট তৈরি করুন</div>
                <button type="button" class="btn-close" onclick="closeCreateModal()"><i class="bi bi-x"></i></button>
            </div>
            <form method="POST" action="{{ route('super-admin.users.create') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-grid" style="gap:14px;">
                        <div>
                            <label class="form-label">সম্পূর্ণ নাম <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="যেমন: মো: রফিকুল ইসলাম" required>
                        </div>
                        <div>
                            <label class="form-label">দায়িত্ব / পদবী (Role) <span class="required">*</span></label>
                            <select name="role" class="form-control" required>
                                <option value="principal">অধ্যক্ষ / প্রধান শিক্ষক (Principal)</option>
                                <option value="cashier">হিসাব রক্ষক / ক্যাশিয়ার (Cashier)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">ইমেইল ঠিকানা (Login Email) <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="যেমন: principal@school.com" required>
                        </div>
                        <div>
                            <label class="form-label">ফোন নম্বর (ঐচ্ছিক)</label>
                            <input type="text" name="phone" class="form-control" placeholder="017XXXXXXXX">
                        </div>
                        <div>
                            <label class="form-label">লগইন পাসওয়ার্ড <span class="required">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="ন্যূনতম ৬ অক্ষর" minlength="6" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeCreateModal()">বাতিল</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> অ্যাকাউন্ট তৈরি করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Edit User --}}
<div class="modal" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><i class="bi bi-pencil-square"></i> অ্যাকাউন্ট তথ্য পরিবর্তন করুন</div>
                <button type="button" class="btn-close" onclick="closeEditModal()"><i class="bi bi-x"></i></button>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-grid" style="gap:14px;">
                        <div>
                            <label class="form-label">সম্পূর্ণ নাম <span class="required">*</span></label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">দায়িত্ব / পদবী (Role) <span class="required">*</span></label>
                            <select id="edit_role" name="role" class="form-control" required>
                                <option value="principal">অধ্যক্ষ / প্রধান শিক্ষক (Principal)</option>
                                <option value="cashier">হিসাব রক্ষক / ক্যাশিয়ার (Cashier)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">ইমেইল ঠিকানা <span class="required">*</span></label>
                            <input type="email" id="edit_email" name="email" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">ফোন নম্বর</label>
                            <input type="text" id="edit_phone" name="phone" class="form-control">
                        </div>
                        <div>
                            <label class="form-label">নতুন পাসওয়ার্ড (পরিবর্তন করতে চাইলে লিখুন)</label>
                            <input type="password" name="password" class="form-control" placeholder="অপরিবর্তিত রাখতে খালি রাখুন" minlength="6">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeEditModal()">বাতিল</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.add('open');
}
function closeCreateModal() {
    document.getElementById('createModal').classList.remove('open');
}

function openEditModal(user) {
    const form = document.getElementById('editForm');
    form.action = `/super-admin/users/${user.id}`;
    document.getElementById('edit_name').value = user.name || '';
    document.getElementById('edit_email').value = user.email || '';
    document.getElementById('edit_phone').value = user.phone || '';
    
    // Normalize role
    let role = user.role;
    if (role === 'admin') role = 'principal';
    if (role === 'accounts') role = 'cashier';
    document.getElementById('edit_role').value = role;

    document.getElementById('editModal').classList.add('open');
}
function closeEditModal() {
    document.getElementById('editModal').classList.remove('open');
}
</script>
@endsection
