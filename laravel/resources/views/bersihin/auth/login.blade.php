<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - BersihIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Custom Focus Ring */
        .input-focus:focus { 
            outline: none; 
            border-color: #064E3B; 
            box-shadow: 0 0 0 2px rgba(6, 78, 59, 0.1); 
        }
        .left-panel {
            background-image: url('https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=800&q=80');
        }
    </style>
</head>
<body class="min-h-screen flex bg-white">

    <!-- BAGIAN KIRI: VISUAL & STATS -->
    <section class="hidden lg:flex lg:w-1/2 relative left-panel bg-cover bg-center">
        <!-- Overlay Gradien yang lebih smooth -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#064E3B] via-[#064E3B]/40 to-transparent"></div>

        <div class="absolute bottom-0 left-0 right-0 p-12">
            <!-- Badge: Sentence Case -->
            <div class="flex items-center gap-2 mb-6">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
                </span>
                <span class="text-green-300 text-xs font-medium tracking-wide">Terpercaya di Indonesia</span>
            </div>

            <h2 class="text-white text-5xl font-extrabold leading-tight mb-4">
                Rumah bersih,<br>hati tenang.
            </h2>
            <p class="text-green-50/80 text-sm leading-relaxed max-w-sm mb-10">
                Layanan kebersihan profesional untuk hunian modern Anda. Kami hadir memberikan kesegaran di setiap sudut ruangan.
            </p>

            <!-- Stats Group -->
            <div class="flex items-center gap-8 border-t border-white/10 pt-8">
                <div>
                    <p class="text-white font-bold text-2xl">10k+</p>
                    <p class="text-green-300/80 text-[10px] uppercase tracking-wider font-semibold">Pelanggan puas</p>
                </div>
                <div class="h-8 w-px bg-white/20"></div>
                <div>
                    <p class="text-white font-bold text-2xl">4.9★</p>
                    <p class="text-green-300/80 text-[10px] uppercase tracking-wider font-semibold">Rating rata-rata</p>
                </div>
                <div class="h-8 w-px bg-white/20"></div>
                <div>
                    <p class="text-white font-bold text-2xl">500+</p>
                    <p class="text-green-300/80 text-[10px] uppercase tracking-wider font-semibold">Petugas ahli</p>
                </div>
            </div>
        </div>
    </section>

    <!-- BAGIAN KANAN: FORM MASUK -->
    <main class="w-full lg:w-1/2 flex flex-col bg-white">
        <!-- Header Logo -->
        <nav class="p-8 lg:px-12">
            <a href="/" class="text-[#064E3B] font-extrabold text-2xl tracking-tighter">BersihIn</a>
        </nav>

        <!-- Form Container -->
        <div class="flex-1 flex items-center justify-center px-8 sm:px-16 pb-20">
            <div class="w-full max-w-sm">
                <header class="mb-10 text-center lg:text-left">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Selamat datang kembali</h1>
                    <p class="text-gray-500 text-sm">Silakan masuk untuk ke akun anda.</p>
                </header>

                <form action="/bersihin/login" method="POST" class="space-y-5">
    @csrf
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 group-focus-within:text-[#064E3B]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input type="email" id="email" name="email" placeholder="nama@email.com" 
                                class="input-focus w-full bg-gray-50 border border-gray-200 rounded-2xl pl-12 pr-4 py-3.5 text-sm transition-all">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="passwordInput" class="block text-sm font-semibold text-gray-700 mb-2">Kata sandi</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 group-focus-within:text-[#064E3B]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input type="password" id="passwordInput" name="password" placeholder="••••••••" 
                                class="input-focus w-full bg-gray-50 border border-gray-200 rounded-2xl pl-12 pr-12 py-3.5 text-sm transition-all">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Helpers -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-[#064E3B] focus:ring-[#064E3B]">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Ingat saya</span>
                        </label>
                        <a href="/bersihin/lupa-password" class="text-sm font-bold text-[#064E3B] hover:text-emerald-800 transition-colors">Lupa password?</a>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-[#064E3B] hover:bg-[#043d2e] text-white font-bold py-4 rounded-2xl shadow-lg shadow-emerald-900/10 transition-all active:scale-[0.98]">
                        Masuk ke akun
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        Belum punya akun? 
                       <a href="/bersihin/register" class="text-[#064E3B] font-bold hover:underline decoration-2 underline-offset-4">Daftar sekarang</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="p-8 text-center border-t border-gray-50">
            <p class="text-[10px] text-gray-400 tracking-widest uppercase mb-2">© 2026 BersihIn Indonesia</p>
            <div class="flex justify-center gap-6">
                <a href="#" class="text-xs text-gray-400 hover:text-gray-600 transition-colors">Privasi</a>
                <a href="#" class="text-xs text-gray-400 hover:text-gray-600 transition-colors">Bantuan</a>
            </div>
        </footer>
    </main>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            const isPassword = input.type === 'password';
            
            input.type = isPassword ? 'text' : 'password';
            icon.innerHTML = isPassword 
                ? `<path stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
                : `<path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        }
    </script>
</body>
</html>