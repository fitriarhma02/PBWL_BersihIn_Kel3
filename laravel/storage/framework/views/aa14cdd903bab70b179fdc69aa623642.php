<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar - BersihIn</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Plus Jakarta Sans', sans-serif; }
  input:focus { outline: none; border-color: #064E3B; }
  .left-panel {
    background-image: url('https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=800&q=80');
    background-size: cover;
    background-position: center;
  }
</style>
</head>
<body class="min-h-screen flex">

  
  <div class="hidden lg:flex lg:w-1/2 relative left-panel">
    <div class="absolute inset-0 bg-gradient-to-t from-[#064E3B]/90 via-[#064E3B]/40 to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0 p-10">
      <div class="flex items-center gap-2 mb-6">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </div>
        <span class="text-white font-extrabold text-xl">BersihIn</span>
      </div>
      <h2 class="text-white text-3xl font-extrabold leading-tight mb-3">Bergabung &<br>Rasakan Bedanya.</h2>
      <p class="text-green-100 text-sm leading-relaxed max-w-sm mb-8">Layanan kebersihan profesional untuk hunian yang lebih sehat dan nyaman.</p>
      <div class="space-y-3">
        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 flex items-center gap-3">
          <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          </div>
          <div>
            <p class="text-white text-xs font-bold">Kualitas Standar Internasional</p>
            <p class="text-green-200 text-xs">Petugas terlatih & tersertifikasi</p>
          </div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 flex items-center gap-3">
          <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
          </div>
          <div>
            <p class="text-white text-xs font-bold">Aman & Terpercaya</p>
            <p class="text-green-200 text-xs">Data dan privasi Anda terlindungi</p>
          </div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 flex items-center gap-3">
          <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
          </div>
          <div>
            <p class="text-white text-xs font-bold">Rating 4.9 / 5.0</p>
            <p class="text-green-200 text-xs">Dipercaya 10.000+ pelanggan</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <div class="w-full lg:w-1/2 flex flex-col justify-between bg-white px-8 py-10 sm:px-16 overflow-y-auto">

    <div>
      <a href="/bersihin" class="text-[#064E3B] font-extrabold text-2xl">BersihIn</a>
    </div>

    <div class="w-full max-w-sm mx-auto py-8">
      <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Buat Akun Baru</h1>
      <p class="text-gray-400 text-sm mb-8">Bergabunglah dengan ribuan pelanggan puas dan nikmati hunian yang selalu bersih.</p>

      
      <?php if($errors->any()): ?>
      <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <p class="text-red-600 text-sm">⚠️ <?php echo e($error); ?></p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <?php endif; ?>

      
      <form method="POST" action="/bersihin/register">
        <?php echo csrf_field(); ?>

        
        <div class="mb-4">
          <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Nama Lengkap</label>
          <div class="relative">
            <div class="absolute left-4 top-1/2 -translate-y-1/2">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <input type="text" name="name" value="<?php echo e(old('name')); ?>" placeholder="Masukkan nama lengkap Anda"
              class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm text-gray-700 transition">
          </div>
        </div>

        
        <div class="mb-4">
          <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Email</label>
          <div class="relative">
            <div class="absolute left-4 top-1/2 -translate-y-1/2">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <input type="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="nama@email.com"
              class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm text-gray-700 transition">
          </div>
        </div>

        
        <div class="mb-4">
          <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Nomor Telepon</label>
          <div class="relative">
            <div class="absolute left-4 top-1/2 -translate-y-1/2">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="+62 812 3456 7890"
              class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm text-gray-700 transition">
          </div>
        </div>

        
        <div class="mb-5">
          <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Kata Sandi</label>
          <div class="relative">
            <div class="absolute left-4 top-1/2 -translate-y-1/2">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <input id="passwordInput" type="password" name="password" placeholder="Min. 8 karakter"
              class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-12 py-3 text-sm text-gray-700 transition">
            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
              <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
        </div>

        
        <div class="mb-6">
          <label class="flex items-start gap-2 cursor-pointer">
            <input type="checkbox" class="w-4 h-4 mt-0.5 accent-[#064E3B] rounded flex-shrink-0">
            <span class="text-sm text-gray-500 leading-relaxed">
              Saya menyetujui
              <a href="#" class="font-semibold text-[#064E3B] hover:underline">Syarat & Ketentuan</a>
              serta
              <a href="#" class="font-semibold text-[#064E3B] hover:underline">Kebijakan Privasi</a>
              BersihIn.
            </span>
          </label>
        </div>

        
        <button type="submit" class="w-full bg-[#064E3B] hover:bg-emerald-800 text-white font-bold py-3.5 rounded-xl text-sm transition mb-5 flex items-center justify-center gap-2">
          Daftar Sekarang
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </button>

      </form>

      <p class="text-center text-sm text-gray-500">
        Sudah punya akun?
        <a href="/bersihin/login" class="font-bold text-[#064E3B] hover:underline">Masuk di sini</a>
      </p>
    </div>

    <div class="text-center">
      <p class="text-xs text-gray-300">© 2024 BersihIn. Solusi Kebersihan Profesional & Terpercaya.</p>
    </div>

  </div>

</body>
<script>
function togglePassword() {
  const input = document.getElementById('passwordInput');
  const icon = document.getElementById('eyeIcon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.innerHTML = `<path stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
  } else {
    input.type = 'password';
    icon.innerHTML = `<path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
  }
}
</script>
</html><?php /**PATH C:\laragon\www\prak-web-lanjut-2407051023\PBWL_BersihIn_Kel3\laravel\resources\views/bersihin/auth/register.blade.php ENDPATH**/ ?>