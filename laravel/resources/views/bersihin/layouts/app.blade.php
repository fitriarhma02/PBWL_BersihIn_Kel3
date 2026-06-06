<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BersihIn - @yield('page-title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bersihin.css') }}">
</head>
<body class="bg-gray-50">
<div class="flex min-h-screen">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <h1>BersihIn</h1>
            @if(auth()->user()->hasRole('admin'))
                <span>Admin Panel</span>
            @elseif(auth()->user()->hasRole('petugas'))
                <span>Petugas</span>
            @else
                <span>Pengguna</span>
            @endif
        </div>

        {{-- Profile Singkat (hanya petugas) --}}
        @if(auth()->user()->hasRole('petugas'))
        <div class="sidebar-profile">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <p class="sidebar-name">{{ auth()->user()->name }}</p>
                <p class="sidebar-role-label">Petugas Lapangan</p>
            </div>
        </div>
        @endif

        {{-- Nav Menu --}}
        <nav class="sidebar-nav">

            {{-- ===== MENU ADMIN ===== --}}
            @if(auth()->user()->hasRole('admin'))
                <a href="/bersihin/admin" class="nav-item {{ request()->is('bersihin/admin') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard Admin
                </a>
                <a href="/bersihin/admin/verifikasi" class="nav-item {{ request()->is('bersihin/admin/verifikasi') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Verifikasi Pembayaran
                </a>
                <a href="/bersihin/admin/petugas" class="nav-item {{ request()->is('bersihin/admin/petugas') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Petugas & Penjadwalan
                </a>
                <a href="/bersihin/admin/layanan" class="nav-item {{ request()->is('bersihin/admin/layanan') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Layanan & Promo
                </a>
                <a href="/bersihin/admin/laporan" class="nav-item {{ request()->is('bersihin/admin/laporan') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Laporan Transaksi
                </a>

            {{-- ===== MENU PETUGAS ===== --}}
            @elseif(auth()->user()->hasRole('petugas'))
                <a href="/bersihin/petugas/dashboard" class="nav-item {{ request()->is('bersihin/petugas/dashboard') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                <a href="/bersihin/petugas/jadwal" class="nav-item {{ request()->is('bersihin/petugas/jadwal') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Jadwal Petugas
                </a>
                <a href="/bersihin/petugas/performance" class="nav-item {{ request()->is('bersihin/petugas/performance') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Performance
                </a>

            {{-- ===== MENU CUSTOMER ===== --}}
            @else
                <a href="/bersihin/customer/dashboard" class="nav-item {{ request()->is('bersihin/customer/dashboard') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                <a href="/bersihin/customer/pesanan" class="nav-item {{ request()->is('bersihin/customer/pesanan') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Pesanan Saya
                </a>
                <a href="/bersihin/customer/promo" class="nav-item {{ request()->is('bersihin/customer/promo') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Promo
                </a>
                <a href="/bersihin/customer/riwayat-pesanan" class="nav-item {{ request()->is('bersihin/customer/riwayat-pesanan') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Riwayat Pesanan
                </a>
            @endif

        </nav>

        {{-- Bottom Menu --}}
        <div class="sidebar-bottom">

            {{-- Pengaturan --}}
            @if(auth()->user()->hasRole('admin'))
                <a href="/bersihin/admin/pengaturan" class="nav-item {{ request()->is('bersihin/admin/pengaturan') ? 'active' : '' }}">
            @elseif(auth()->user()->hasRole('petugas'))
                <a href="/bersihin/petugas/pengaturan" class="nav-item {{ request()->is('bersihin/petugas/pengaturan') ? 'active' : '' }}">
            @else
                <a href="/bersihin/customer/pengaturan" class="nav-item {{ request()->is('bersihin/customer/pengaturan') ? 'active' : '' }}">
            @endif
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Pengaturan
            </a>

            {{-- Logout --}}
            <button onclick="document.getElementById('modalLogout').classList.remove('hidden')" class="nav-item nav-logout w-full text-left">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </div>

    </aside>

    {{-- ===== MAIN AREA ===== --}}
    <div class="main-wrapper">

        {{-- Topbar --}}
        <header class="topbar">

            {{-- Kiri: Judul Halaman --}}
            <div>
                <h2 class="topbar-title">@yield('page-title', 'Dashboard')</h2>
                <p class="topbar-subtitle">@yield('page-subtitle', 'Selamat datang kembali!')</p>
            </div>

            {{-- Kanan: Toggle (petugas) + Notif + User --}}
            <div class="topbar-right">

               {{-- Toggle Status Kerja (hanya dashboard petugas) --}}
            @if(
                auth()->user()->hasRole('petugas') &&
                request()->is('bersihin/petugas/dashboard')
            )
            <div class="status-toggle">
                <span class="status-label-text">Status Kerja</span>
                <button onclick="toggleStatus(this)" class="toggle-btn active-toggle">
                    <span class="toggle-dot translate-x-5"></span>
                </button>
                <span id="statusLabel" class="status-text">Aktif</span>
            </div>
            @endif

                {{-- Notifikasi --}}
                @if(
                    auth()->user()->hasRole('petugas') &&
                    request()->is('bersihin/petugas/dashboard')
                )
                <div class="notif-wrap" onclick="toggleNotif()">
                    <button class="icon-btn">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="notif-badge">3</span>
                    </button>

                    <div class="notif-dropdown" id="notifDropdown">
                        <div class="notif-header">
                            <span>Notifikasi</span>
                            <button onclick="markAllRead()" class="notif-read-btn">Tandai dibaca</button>
                        </div>
                        <div class="notif-item unread">
                            <div class="notif-icon" style="background:#dcfce7">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="notif-title">Pesanan dikonfirmasi</p>
                                <p class="notif-desc">Pesanan #BSH-99281 telah dikonfirmasi</p>
                                <p class="notif-time">2 menit lalu</p>
                            </div>
                        </div>
                        <div class="notif-item unread">
                            <div class="notif-icon" style="background:#fef9c3">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </div>
                            <div>
                                <p class="notif-title">Promo baru tersedia!</p>
                                <p class="notif-desc">Diskon 30% untuk Deep Clean</p>
                                <p class="notif-time">1 jam lalu</p>
                            </div>
                        </div>
                        <div class="notif-item">
                            <div class="notif-icon" style="background:#f3f4f6">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#6b7280" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="notif-title">Tugas selesai dikonfirmasi</p>
                                <p class="notif-desc">General Cleaning telah diverifikasi</p>
                                <p class="notif-time" style="color:#9ca3af">Kemarin</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- User Info --}}
                <div class="user-info">
                    <div class="user-text">
                        <p class="user-name">{{ auth()->user()->name }}</p>
                        <p class="user-role">
                            @if(auth()->user()->hasRole('admin')) Super Admin
                            @elseif(auth()->user()->hasRole('petugas')) Petugas Lapangan
                            @else Member
                            @endif
                        </p>
                    </div>
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                </div>

            </div>
        </header>

        {{-- Konten Halaman --}}
        <main class="main-content">
            @yield('content')
        </main>

        <p class="footer-text">© 2024 BersihIn Professional Cleaning Services</p>

    </div>
</div>

{{-- ===== MODAL LOGOUT ===== --}}
<div id="modalLogout"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
    onclick="if(event.target===this) this.classList.add('hidden')">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    <div class="modal-box">
        <div class="modal-icon">
            <svg width="28" height="28" fill="none" stroke="#ef4444" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
        </div>
        <h2 class="modal-title">Konfirmasi Keluar</h2>
        <p class="modal-desc">Apakah kamu yakin ingin keluar dari sistem <strong>BersihIn</strong>?</p>
        <div class="modal-actions">
            <button onclick="document.getElementById('modalLogout').classList.add('hidden')" class="btn-cancel">Batal</button>
            <form method="POST" action="{{ route('logout') }}" style="flex:1">
                @csrf
                <button type="submit" class="btn-logout">Ya, Keluar</button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleNotif() {
    document.getElementById('notifDropdown').classList.toggle('show');
}
function markAllRead() {
    document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
    document.querySelector('.notif-badge').style.display = 'none';
}
document.addEventListener('click', function(e) {
    const wrap = document.querySelector('.notif-wrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('notifDropdown').classList.remove('show');
    }
});
function toggleStatus(btn) {
    const dot = btn.querySelector('span');
    const label = document.getElementById('statusLabel');
    const isActive = dot.classList.contains('translate-x-5');
    if (isActive) {
        dot.classList.replace('translate-x-5', 'translate-x-0');
        btn.classList.remove('active-toggle');
        btn.classList.add('inactive-toggle');
        label.textContent = 'Tidak Aktif';
        label.style.color = '#9ca3af';
    } else {
        dot.classList.replace('translate-x-0', 'translate-x-5');
        btn.classList.add('active-toggle');
        btn.classList.remove('inactive-toggle');
        label.textContent = 'Aktif';
        label.style.color = '#064E3B';
    }
}
</script>
</body>
</html>