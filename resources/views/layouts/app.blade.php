<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ setting('school_name', 'CrackNCode') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    /* বাংলা সংখ্যার জন্য Noto Sans Bengali */
    @font-face {
        font-family: "BengaliMixed";
        src: local("Noto Sans Bengali");
        unicode-range: U+09E6-09EF; /* বাংলা সংখ্যা ০-৯ */
    }
    @font-face {
        font-family: "BengaliMixed";
        src: local("Hind Siliguri");
        unicode-range: U+0000-09E5, U+09F0-FFFF; /* বাকি সব */
    }

    :root {
        --primary: #4f46e5;
        --primary-light: #eef2ff;
        --primary-mid: #6366f1;
        --primary-dark: #3730a3;
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
        --accent: #06b6d4;

        /* Compatibility aliases */
        --green: #4f46e5;
        --green-light: #eef2ff;
        --green-mid: #6366f1;

        --bg: #f8fafc;
        --card: #ffffff;
        --sidebar-bg: #090d16;
        --sidebar-border: rgba(255, 255, 255, 0.08);
        --sidebar-active: linear-gradient(90deg, rgba(79, 70, 229, 0.28) 0%, rgba(99, 102, 241, 0.08) 100%);
        --sidebar-text: #94a3b8;
        --sidebar-text-hover: #ffffff;

        --text: #0f172a;
        --text-muted: #64748b;
        --border: #e2e8f0;
        --border-focus: #818cf8;

        --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.05);
        --shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
        --shadow-hover: 0 10px 25px -3px rgba(15, 23, 42, 0.1);
        --radius: 14px;
        --radius-sm: 8px;

        --font-bn: 'BengaliMixed', 'Hind Siliguri', 'Noto Sans Bengali', sans-serif;
        --font-en: 'Plus Jakarta Sans', sans-serif;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: var(--font-bn);
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
        display: flex;
        -webkit-font-smoothing: antialiased;
    }

    /* Sidebar */
    .sidebar {
        width: 260px;
        background: var(--sidebar-bg);
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        z-index: 100;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-right: 1px solid var(--sidebar-border);
    }
    .sidebar-brand {
        padding: 20px;
        border-bottom: 1px solid var(--sidebar-border);
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.02);
    }
    .brand-icon {
        width: 42px;
        height: 42px;
        background: var(--primary-gradient);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        color: #ffffff;
    }
    .brand-text .name {
        color: #ffffff;
        font-weight: 700;
        font-size: 15px;
        line-height: 1.2;
        letter-spacing: -0.3px;
        font-family: var(--font-en);
    }
    .brand-text .tagline {
        color: #94a3b8;
        font-size: 11px;
        font-family: var(--font-en);
        letter-spacing: 0.3px;
    }
    .sidebar-nav {
        flex: 1;
        padding: 14px 10px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .nav-section-label {
        font-size: 10px;
        font-family: var(--font-en);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #64748b;
        padding: 14px 12px 6px;
        font-weight: 700;
    }
    .nav-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 9.5px 14px;
        border-radius: var(--radius-sm);
        color: var(--sidebar-text);
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 3px;
        transition: all 0.18s ease;
        position: relative;
    }
    .nav-item i {
        font-size: 16px;
        width: 20px;
        text-align: center;
        color: #94a3b8;
        transition: color 0.18s;
    }
    .nav-item:hover {
        background: rgba(255, 255, 255, 0.06);
        color: #ffffff;
        transform: translateX(3px);
    }
    .nav-item:hover i {
        color: #818cf8;
    }
    .nav-item.active {
        background: var(--sidebar-active);
        color: #ffffff;
        font-weight: 600;
        border-left: 3px solid #818cf8;
    }
    .nav-item.active i {
        color: #818cf8;
    }

    .sidebar-footer {
        padding: 14px 12px;
        border-top: 1px solid var(--sidebar-border);
        background: rgba(0, 0, 0, 0.2);
    }
    .session-box {
        padding: 9px 12px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .session-box .session-label {
        font-size: 10px;
        color: #94a3b8;
        font-family: var(--font-en);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .session-box .session-val {
        font-size: 13px;
        color: #ffffff;
        font-weight: 600;
        margin-top: 2px;
        font-family: var(--font-en);
    }

    /* Sidebar Developer Widget */
    .sidebar-dev-box {
        padding: 11px 12px;
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.12) 0%, rgba(6, 182, 212, 0.06) 100%);
        border: 1px solid rgba(99, 102, 241, 0.25);
        border-radius: 9px;
        font-size: 11px;
    }
    .sidebar-dev-box .dev-header {
        color: #e2e8f0;
        font-weight: 600;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .sidebar-dev-box .dev-header strong {
        color: #a5b4fc;
        font-family: var(--font-en);
    }
    .sidebar-dev-box .dev-links {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 6px;
    }
    .sidebar-dev-box .dev-links a {
        color: #94a3b8;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        font-family: var(--font-en);
        font-size: 11.5px;
        transition: color 0.15s;
    }
    .sidebar-dev-box .dev-links a:hover {
        color: #818cf8;
        text-decoration: underline;
    }
    .sidebar-dev-box .dev-copy {
        color: #64748b;
        font-size: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        padding-top: 5px;
        font-family: var(--font-en);
    }

    /* Main Content Wrapper */
    .main-wrapper {
        margin-left: 260px;
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* Topbar */
    .topbar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 0 28px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 0;
        z-index: 50;
    }
    .page-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--text);
        letter-spacing: -0.2px;
    }
    .breadcrumb-trail {
        font-size: 12px;
        color: var(--text-muted);
        font-family: var(--font-en);
    }
    .topbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .topbar-btn {
        width: 38px;
        height: 38px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-muted);
        font-size: 16px;
        transition: all 0.18s;
        text-decoration: none;
    }
    .topbar-btn:hover {
        background: var(--primary-light);
        color: var(--primary);
        border-color: #c7d2fe;
    }
    .user-chip {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 5px 14px 5px 6px;
        border: 1px solid var(--border);
        border-radius: 40px;
        cursor: pointer;
        text-decoration: none;
        color: var(--text);
        background: #ffffff;
        transition: all 0.18s;
        position: relative;
    }
    .user-chip:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.1);
    }
    .user-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        font-family: var(--font-en);
    }
    .user-name {
        font-size: 13px;
        font-weight: 600;
    }
    .user-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        min-width: 185px;
        box-shadow: var(--shadow-hover);
        display: none;
        z-index: 200;
        overflow: hidden;
    }
    .user-chip:hover .user-dropdown { display: block; }
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 10px 16px;
        font-size: 13px;
        color: var(--text);
        text-decoration: none;
        transition: background 0.15s;
    }
    .dropdown-item:hover { background: var(--bg); color: var(--primary); }
    .dropdown-item.danger { color: #e11d48; }
    .dropdown-item.danger:hover { background: #fff1f2; color: #be123c; }

    /* Content */
    .page-content {
        padding: 26px 28px;
        flex: 1;
    }

    /* Cards */
    .card {
        background: var(--card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        transition: box-shadow 0.2s ease;
    }
    .card-header {
        padding: 16px 20px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-title {
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text);
    }
    .card-title i { color: var(--primary); }
    .card-body { padding: 18px 20px 22px; }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }
    .stat-card {
        padding: 20px;
        border-radius: var(--radius);
        position: relative;
        overflow: hidden;
        color: #ffffff;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .stat-card .icon {
        font-size: 26px;
        margin-bottom: 12px;
    }
    .stat-card .value {
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 6px;
        letter-spacing: -0.5px;
    }
    .stat-card .label {
        font-size: 13px;
        opacity: 0.88;
        font-weight: 500;
    }
    .stat-green  { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); box-shadow: 0 8px 20px rgba(79, 70, 229, 0.22); }
    .stat-blue   { background: linear-gradient(135deg, #059669 0%, #047857 100%); box-shadow: 0 8px 20px rgba(5, 150, 105, 0.22); }
    .stat-orange { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); box-shadow: 0 8px 20px rgba(217, 119, 6, 0.22); }
    .stat-rose   { background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); box-shadow: 0 8px 20px rgba(225, 29, 72, 0.22); }

    /* Tables */
    .table-wrap { overflow-x: auto; }
    table.data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .data-table thead th {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        padding: 11px 16px;
        text-align: left;
        border-bottom: 2px solid var(--border);
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-family: var(--font-en);
        white-space: nowrap;
    }
    .data-table tbody td {
        padding: 11px 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        color: var(--text);
    }
    .data-table tbody tr {
        transition: background-color 0.15s;
    }
    .data-table tbody tr:hover { background: #f8fafc; }
    .data-table tbody tr:last-child td { border-bottom: none; }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 600;
        font-family: var(--font-en);
    }
    .badge-green  { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .badge-blue   { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .badge-orange { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge-red    { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .badge-gray   { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .badge-purple { background: #faf5ff; color: #7c3aed; border: 1px solid #e9d5ff; }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8.5px 18px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.18s ease;
        text-decoration: none;
        font-family: var(--font-bn);
    }
    .btn-primary {
        background: var(--primary-gradient);
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.38);
        color: #ffffff !important;
        filter: brightness(1.05);
    }
    .btn-outline {
        background: transparent;
        color: var(--primary);
        border: 1.5px solid var(--primary);
    }
    .btn-outline:hover {
        background: var(--primary-light);
        color: var(--primary-dark);
        border-color: var(--primary-dark);
    }
    .btn-danger {
        background: #fee2e2;
        color: #dc2626;
    }
    .btn-danger:hover {
        background: #fca5a5;
    }
    .btn-sm {
        padding: 5px 12px;
        font-size: 12px;
    }
    .btn-icon {
        width: 34px;
        height: 34px;
        padding: 0;
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    /* Forms */
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 5px;
    }
    .form-control, .form-select {
        width: 100%;
        padding: 9px 13px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 13.5px;
        font-family: var(--font-bn);
        color: var(--text);
        background: #ffffff;
        transition: all 0.18s;
        outline: none;
        -webkit-appearance: none;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }
    .form-grid { display: grid; gap: 16px; }
    .form-grid-2 { grid-template-columns: 1fr 1fr; }
    .form-grid-3 { grid-template-columns: 1fr 1fr 1fr; }

    /* Alerts */
    .alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        border-radius: var(--radius-sm);
        font-size: 13.5px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
    }
    .alert-success { background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981; }
    .alert-error   { background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444; }
    .alert-warning { background: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b; }

    /* Misc Elements */
    .filter-bar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .filter-bar .form-control, .filter-bar .form-select { width: auto; min-width: 140px; }
    .avatar-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--primary-light);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        font-family: var(--font-en);
    }
    .student-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--border);
    }
    .progress-bar-wrap { height: 7px; background: #e2e8f0; border-radius: 20px; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 20px; background: var(--primary-gradient); }
    .empty-state { text-align: center; padding: 50px 20px; color: var(--text-muted); }
    .empty-state i { font-size: 44px; color: #cbd5e1; display: block; margin-bottom: 12px; }
    .pagination-wrap { padding: 14px 20px; border-top: 1px solid var(--border); }
    .tabs { display: flex; gap: 4px; border-bottom: 2px solid var(--border); margin-bottom: 20px; }
    .tab-btn {
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        font-family: var(--font-bn);
        color: var(--text-muted);
        background: none;
        border: none;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.18s;
    }
    .tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
    .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 1000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
    .modal-overlay.open { display: flex; }
    .modal-box { background: var(--card); border-radius: var(--radius); max-width: 580px; width: 95%; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 60px rgba(0,0,0,0.25); border: 1px solid var(--border); }
    .modal-header { padding: 18px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .modal-body { padding: 20px 22px; }
    .modal-footer { padding: 14px 22px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 10px; }

    /* Global Page Footer with Developer Info */
    .app-footer {
        background: #ffffff;
        border-top: 1px solid var(--border);
        padding: 16px 28px;
        margin-top: auto;
    }
    .footer-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        font-size: 13px;
        color: var(--text-muted);
    }
    .footer-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .footer-left strong {
        color: var(--primary);
        font-family: var(--font-en);
    }
    .footer-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .dev-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary-light);
        color: var(--primary);
        padding: 4px 11px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        border: 1px solid #c7d2fe;
    }
    .dev-badge strong {
        font-family: var(--font-en);
    }
    .footer-sep {
        color: #cbd5e1;
    }
    .footer-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #334155;
        text-decoration: none;
        font-weight: 600;
        font-family: var(--font-en);
        transition: color 0.15s;
    }
    .footer-link:hover {
        color: var(--primary);
        text-decoration: underline;
    }

    /* Mobile Responsive */
    @media (max-width: 1024px) {
        #sidebarToggle { display: flex !important; }
        .sidebar { transform: translateX(-260px) !important; }
        .sidebar.open { transform: translateX(0) !important; }
        .main-wrapper { margin-left: 0 !important; }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; }
        .responsive-grid { grid-template-columns: 1fr !important; }
        .form-grid-2, .form-grid-3 { grid-template-columns: 1fr !important; }
        .page-content { padding: 14px !important; }
        .topbar { padding: 0 16px !important; }
        .breadcrumb-trail { display: none; }
        .footer-inner { flex-direction: column; align-items: flex-start; gap: 8px; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr !important; }
    }

    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99; }
    #sidebarToggle { display: none; }
    @media print { .sidebar, .topbar, .app-footer, .no-print { display: none !important; } .main-wrapper { margin: 0; } }
    </style>
    @stack('styles')
</head>
<body>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        @if(setting('school_logo'))
            <img src="{{ asset('storage/' . setting('school_logo')) }}" alt="{{ setting('school_name', 'School') }}" style="width:40px; height:40px; border-radius:10px; object-fit:contain; background:#ffffff; padding:2px; box-shadow:0 4px 12px rgba(0,0,0,0.25); flex-shrink:0;">
        @else
            <div class="brand-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
        @endif
        <div class="brand-text" style="overflow:hidden;">
            <div class="name" style="white-space:nowrap; text-overflow:ellipsis; overflow:hidden;" title="{{ setting('school_name', 'CrackNCode School') }}">
                {{ setting('school_name', 'CrackNCode School') }}
            </div>
            <div class="tagline">Smart School IMS</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        @if(auth()->check() && auth()->user()->isSuperAdmin())
        <div class="nav-section-label" style="color:#a5b4fc; display:flex; align-items:center; gap:6px;">
            <i class="bi bi-shield-lock-fill"></i> Master Control
        </div>
        <a href="{{ route('super-admin.index') }}" class="nav-item {{ request()->routeIs('super-admin.index') ? 'active' : '' }}" style="background:rgba(99,102,241,0.12); border-left:3px solid #818cf8;">
            <i class="bi bi-speedometer2" style="color:#a5b4fc;"></i> সুপার অ্যাডমিন ড্যাশবোর্ড
        </a>
        <a href="{{ route('super-admin.licenses') }}" class="nav-item {{ request()->routeIs('super-admin.licenses*') ? 'active' : '' }}">
            <i class="bi bi-key-fill" style="color:#a5b4fc;"></i> লাইসেন্স ম্যানেজার
        </a>
        <a href="{{ route('super-admin.users') }}" class="nav-item {{ request()->routeIs('super-admin.users*') ? 'active' : '' }}">
            <i class="bi bi-people-fill" style="color:#a5b4fc;"></i> স্টাফ অ্যাকাউন্ট (Principal/Cashier)
        </a>
        @endif

        <div class="nav-section-label">Overview</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> ড্যাশবোর্ড
        </a>
        <div class="nav-section-label">Academic</div>
        <a href="{{ route('students.index') }}" class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> ছাত্র-ছাত্রী
        </a>
        <a href="{{ route('teachers.index') }}" class="nav-item {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill"></i> শিক্ষক ও কর্মচারী
        </a>
        <a href="{{ route('designations.index') }}" class="nav-item {{ request()->routeIs('designations.*') ? 'active' : '' }}">
            <i class="bi bi-award"></i> পদবি যোগ করুন
        </a>
        <a href="{{ route('classes.index') }}" class="nav-item {{ request()->routeIs('classes.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> শ্রেণী ও বিভাগ
        </a>
        <a href="{{ route('subjects.index') }}" class="nav-item {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark-fill"></i> বিষয়সমূহ
        </a>
        <div class="nav-section-label">Daily</div>
        <a href="{{ route('attendance.index') }}" class="nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i> উপস্থিতি
        </a>
        <a href="{{ route('notices.index') }}" class="nav-item {{ request()->routeIs('notices.*') ? 'active' : '' }}">
            <i class="bi bi-megaphone-fill"></i> নোটিশ বোর্ড
        </a>
        <div class="nav-section-label">Examination</div>
        <a href="{{ route('exams.index') }}" class="nav-item {{ request()->routeIs('exams.*') ? 'active' : '' }}">
            <i class="bi bi-pencil-square"></i> পরীক্ষাসমূহ
        </a>
        <a href="{{ route('results.index') }}" class="nav-item {{ request()->routeIs('results.*') ? 'active' : '' }}">
            <i class="bi bi-award-fill"></i> ফলাফল
        </a>
        <div class="nav-section-label">Special</div>
        <a href="{{ route('meals.index') }}" class="nav-item {{ request()->routeIs('meals.*') ? 'active' : '' }}">
            <i class="bi bi-cup-hot-fill"></i> খোরাকি
        </a>
        <a href="{{ route('welfare.index') }}" class="nav-item {{ request()->routeIs('welfare.*') ? 'active' : '' }}">
            <i class="bi bi-heart-fill"></i> গোরাবা ফান্ড
        </a>
        <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> রিপোর্ট
        </a>
        <div class="nav-section-label">Finance</div>
        <a href="{{ route('fees.index') }}" class="nav-item {{ request()->routeIs('fees.*') ? 'active' : '' }}">
            <i class="bi bi-cash-coin"></i> বেতন ও ফি
        </a>
        <a href="{{ route('fee-categories.index') }}" class="nav-item {{ request()->routeIs('fee-categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i> ফি ক্যাটাগরি
        </a>
        <a href="{{ route('salaries.index') }}" class="nav-item {{ request()->routeIs('salaries.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> বেতন প্রদান
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}" style="margin-bottom:8px;">
            <i class="bi bi-gear-fill"></i> সেটিংস
        </a>
        <div class="session-box">
            <div class="session-label"><i class="bi bi-calendar2-check" style="color:#818cf8;"></i> Session</div>
            <div class="session-val">{{ $currentSession->name ?? '2024-2025' }}</div>
        </div>
        <div class="sidebar-dev-box">
            <div class="dev-header">
                <i class="bi bi-code-slash" style="color:#818cf8;"></i> Developed by <strong>CrackNCode</strong>
            </div>
            <div class="dev-links">
                <a href="https://crackncode.com" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-globe2" style="color:#818cf8;"></i> crackncode.com
                </a>
                <a href="tel:01788155379">
                    <i class="bi bi-telephone-fill" style="color:#818cf8;"></i> 01788155379
                </a>
            </div>
            <div class="dev-copy">© {{ date('Y') }} All rights reserved.</div>
        </div>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-wrapper">
    <header class="topbar">
        <div style="display:flex; align-items:center; gap:12px;">
            <button class="topbar-btn" id="sidebarToggle" style="border:none; cursor:pointer;">
                <i class="bi bi-list" style="font-size:20px;"></i>
            </button>
            <div>
                <div class="page-title">@yield('page-title', 'ড্যাশবোর্ড')</div>
                <div class="breadcrumb-trail">@yield('breadcrumb', 'Home')</div>
            </div>
        </div>
        <div class="topbar-right" style="display:flex; align-items:center; gap:12px;">
            {{-- License Status Indicator --}}
            @php $currentLicense = \App\Services\LicenseService::check(); @endphp
            @if(!empty($currentLicense['is_lifetime']))
                <span style="display:inline-flex; align-items:center; gap:5px; background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; padding:4px 10px; border-radius:20px; font-size:11.5px; font-weight:700;">
                    <i class="bi bi-patch-check-fill" style="color:#10b981;"></i> আজীবন লাইসেন্স
                </span>
            @elseif(!empty($currentLicense['is_valid']))
                <span style="display:inline-flex; align-items:center; gap:5px; background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; padding:4px 10px; border-radius:20px; font-size:11.5px; font-weight:700;" title="মেয়াদ শেষ: {{ !empty($currentLicense['expires_at']) ? $currentLicense['expires_at']->format('d/m/Y h:i A') : '' }}">
                    <i class="bi bi-clock-history" style="color:#3b82f6;"></i> মেয়াদ: {{ $currentLicense['remaining_human'] ?? '' }}
                </span>
            @else
                <a href="{{ route('license.activate') }}" style="display:inline-flex; align-items:center; gap:5px; background:#fef2f2; color:#991b1b; border:1px solid #fecaca; padding:4px 10px; border-radius:20px; font-size:11.5px; font-weight:700; text-decoration:none;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#ef4444;"></i> মেয়াদ শেষ! কোড দিন
                </a>
            @endif

            <a href="{{ route('attendance.index') }}" class="topbar-btn" title="উপস্থিতি">
                <i class="bi bi-calendar-check"></i>
            </a>
            <a href="{{ route('students.create') }}" class="topbar-btn" title="নতুন ভর্তি">
                <i class="bi bi-person-plus"></i>
            </a>
            <div class="user-chip">
                <div class="user-avatar">{{ mb_substr(auth()->user()->name ?? 'C', 0, 1) }}</div>
                <div>
                    <span class="user-name">{{ auth()->user()->name ?? 'User' }}</span>
                </div>
                <i class="bi bi-chevron-down" style="font-size:10px; color:var(--text-muted);"></i>
                <div class="user-dropdown">
                    <div style="padding:10px 14px; border-bottom:1px solid var(--border); background:var(--bg);">
                        <div style="font-weight:700; font-size:13px; color:var(--text-dark);">{{ auth()->user()->name }}</div>
                        <div style="font-size:11px; color:var(--primary); font-weight:600;">{{ auth()->user()->role_label }}</div>
                    </div>
                    @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('super-admin.index') }}" class="dropdown-item" style="color:#4f46e5; font-weight:700;">
                        <i class="bi bi-shield-lock-fill"></i> সুপার অ্যাডমিন প্যানেল
                    </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="bi bi-person"></i> প্রোফাইল
                    </a>
                    <a href="{{ route('settings.index') }}" class="dropdown-item">
                        <i class="bi bi-gear"></i> সেটিংস
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item danger" style="width:100%; background:none; border:none; cursor:pointer; text-align:left;">
                            <i class="bi bi-box-arrow-right"></i> লগআউট
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-error">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
        </div>
        @endif
        @if(isset($errors) && $errors->any())
        <div class="alert alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
        </div>
        @endif
        @yield('content')
    </main>

    <footer class="app-footer no-print">
        <div class="footer-inner">
            <div class="footer-left">
                <span>© {{ date('Y') }} <strong>CrackNCode</strong> — All rights reserved.</span>
            </div>
            <div class="footer-right">
                <span class="dev-badge"><i class="bi bi-code-slash"></i> Developer: <strong>CrackNCode</strong></span>
                <span class="footer-sep">•</span>
                <a href="https://crackncode.com" target="_blank" rel="noopener noreferrer" class="footer-link">
                    <i class="bi bi-globe2" style="color:var(--primary);"></i> crackncode.com
                </a>
                <span class="footer-sep">•</span>
                <a href="tel:01788155379" class="footer-link">
                    <i class="bi bi-telephone-fill" style="color:var(--primary);"></i> 01788155379
                </a>
            </div>
        </div>
    </footer>
</div>

<script>
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.add('open');
        if (overlay) overlay.style.display = 'block';
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        if (overlay) overlay.style.display = 'none';
    }

    if (toggle) {
        toggle.addEventListener('click', () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
    }
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) closeSidebar();
    });
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>
@stack('scripts')
</body>
</html>
