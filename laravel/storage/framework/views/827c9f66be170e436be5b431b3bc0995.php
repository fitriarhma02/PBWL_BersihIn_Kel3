

<?php $__env->startSection('page-title', 'Promo & Voucher'); ?>
<?php $__env->startSection('page-subtitle', 'Hemat lebih banyak dengan penawaran eksklusif'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .promo-hero {
        background: linear-gradient(135deg, #0d3d2e 0%, #1a5c42 40%, #0f4d35 100%);
        position: relative; overflow: hidden; border-radius: 20px;
    }
    .promo-hero::before {
        content: ''; position: absolute; top: -50%; right: -10%;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(52,211,153,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .featured-badge {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: white; font-size: 10px; font-weight: 700;
        letter-spacing: 1.5px; text-transform: uppercase;
        padding: 3px 10px; border-radius: 20px;
    }
    .voucher-card {
        position: relative; background: white; border-radius: 16px;
        overflow: hidden; transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #f0fdf4;
    }
    .voucher-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(15,77,53,0.15); border-color: #bbf7d0; }
    .voucher-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: linear-gradient(180deg, #10b981, #059669); }
    .voucher-card.blue::before { background: linear-gradient(180deg, #3b82f6, #2563eb); }
    .voucher-card.amber::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
    .voucher-card.purple::before { background: linear-gradient(180deg, #8b5cf6, #7c3aed); }
    .klaim-btn {
        background: linear-gradient(135deg, #059669, #10b981);
        color: white; border: none; border-radius: 8px;
        padding: 6px 18px; font-size: 12px; font-weight: 700;
        letter-spacing: 0.5px; cursor: pointer; transition: all 0.2s;
    }
    .klaim-btn:hover { background: linear-gradient(135deg, #047857, #059669); transform: scale(1.03); }
    .tab-btn {
        padding: 8px 20px; border-radius: 99px; font-size: 13px;
        font-weight: 600; cursor: pointer; transition: all 0.2s;
        border: none; background: transparent; color: #6b7280;
    }
    .tab-btn.active { background: linear-gradient(135deg, #059669, #10b981); color: white; box-shadow: 0 4px 12px rgba(5,150,105,0.25); }
    .tab-btn:hover:not(.active) { background: #f0fdf4; color: #059669; }
    .countdown-box { background: rgba(0,0,0,0.08); border-radius: 8px; padding: 4px 10px; font-weight: 800; font-size: 16px; color: #1a5c42; }
    .stat-card { background: white; border-radius: 14px; border: 1px solid #f0fdf4; transition: all 0.2s; }
    .stat-card:hover { border-color: #bbf7d0; box-shadow: 0 4px 16px rgba(16,185,129,0.1); }
</style>

<div class="space-y-6">

    
    <div class="promo-hero flex flex-col md:flex-row overflow-hidden" style="min-height:200px">
        <div class="flex-1 p-8 z-10 relative flex flex-col justify-center">
            <span class="featured-badge inline-block w-fit mb-3">⚡ Flash Deal · Hari Ini Saja</span>
            <h2 class="text-white font-bold text-2xl md:text-3xl leading-tight mb-2">
                Deep Cleaning Special<br>
                <span class="text-emerald-300">Diskon 30%</span> Semua Layanan
            </h2>
            <p class="text-emerald-100 text-sm mb-5 max-w-sm">Rumah bersih bintang lima kini lebih terjangkau. Berlaku untuk pemesanan hari ini hingga pukul 23:59.</p>
            <div class="flex items-center gap-4 flex-wrap">
                <a href="/bersihin/booking" class="bg-white text-emerald-800 font-bold text-sm px-6 py-2.5 rounded-full hover:bg-emerald-50 transition inline-block">
                    Pesan Sekarang →
                </a>
                <div class="flex items-center gap-2">
                    <span class="text-emerald-300 text-xs font-medium">Berakhir dalam</span>
                    <div class="flex gap-1">
                        <span class="countdown-box" id="jam">00</span>
                        <span class="text-white font-bold text-lg">:</span>
                        <span class="countdown-box" id="menit">00</span>
                        <span class="text-white font-bold text-lg">:</span>
                        <span class="countdown-box" id="detik">00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="md:w-80 relative overflow-hidden" style="min-height:180px">
            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500&q=80"
                 alt="Cleaning" class="w-full h-full object-cover opacity-80" style="position:absolute;inset:0">
            <div class="absolute inset-0" style="background:linear-gradient(90deg,#0d3d2e 0%,transparent 40%)"></div>
        </div>
    </div>

    
    <div>
        <div class="flex items-center justify-between mb-4">
            <p class="font-bold text-gray-900 text-base">Voucher Tersedia <span class="text-emerald-600">(<?php echo e($promos->count()); ?>)</span></p>
            <div class="flex gap-1 bg-gray-50 p-1 rounded-full border border-gray-100">
                <button class="tab-btn active" onclick="filterVoucher(this,'semua')">Semua</button>
                <button class="tab-btn" onclick="filterVoucher(this,'diskon')">Diskon</button>
                <button class="tab-btn" onclick="filterVoucher(this,'gratis')">Gratis Ongkir</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="voucherGrid">
            <?php $__empty_1 = true; $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $colorMap = [
                    'green'  => ['bg' => '#dcfce7', 'text' => 'text-emerald-600', 'badge' => 'bg-emerald-50 text-emerald-600', 'btn' => 'linear-gradient(135deg,#059669,#10b981)'],
                    'blue'   => ['bg' => '#dbeafe', 'text' => 'text-blue-600',    'badge' => 'bg-blue-50 text-blue-600',       'btn' => 'linear-gradient(135deg,#2563eb,#3b82f6)'],
                    'amber'  => ['bg' => '#fef3c7', 'text' => 'text-amber-600',   'badge' => 'bg-amber-50 text-amber-600',     'btn' => 'linear-gradient(135deg,#d97706,#f59e0b)'],
                    'purple' => ['bg' => '#ede9fe', 'text' => 'text-purple-600',  'badge' => 'bg-purple-50 text-purple-600',   'btn' => 'linear-gradient(135deg,#7c3aed,#8b5cf6)'],
                ];
                $c = $colorMap[$p->color] ?? $colorMap['green'];
            ?>
            <div class="voucher-card <?php echo e($p->color); ?> p-4" data-type="<?php echo e($p->type); ?>">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:<?php echo e($c['bg']); ?>">
                        <svg class="w-5 h-5 <?php echo e($c['text']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-gray-900 text-sm"><?php echo e($p->title); ?></p>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium <?php echo e($c['badge']); ?>">Aktif</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5"><?php echo e($p->subtitle); ?></p>
                    </div>
                </div>
                <div class="border-t border-dashed border-gray-100 pt-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Berlaku hingga</p>
                        <p class="text-xs font-semibold text-gray-600"><?php echo e($p->valid_until); ?></p>
                    </div>
                    <button class="klaim-btn" style="background:<?php echo e($c['btn']); ?>"
                        onclick="klaimVoucher(this)">KLAIM</button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 bg-white rounded-2xl border border-gray-100 p-12 text-center">
                <p class="text-gray-400 text-sm">Belum ada promo tersedia</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="grid grid-cols-2 gap-3">
        <div class="stat-card p-4 text-center">
            <p class="text-2xl font-black text-emerald-700"><?php echo e($promos->count()); ?></p>
            <p class="text-xs text-gray-500 mt-1 font-medium">Promo Aktif</p>
        </div>
        <div class="stat-card p-4 text-center">
            <p class="text-2xl font-black text-blue-600"><?php echo e($promos->where('type','diskon')->count()); ?></p>
            <p class="text-xs text-gray-500 mt-1 font-medium">Voucher Diskon</p>
        </div>
    </div>

</div>

<script>
function updateCountdown() {
    const now = new Date();
    const end = new Date();
    end.setHours(23, 59, 59, 0);
    let diff = Math.max(0, Math.floor((end - now) / 1000));
    const j = Math.floor(diff / 3600); diff %= 3600;
    const m = Math.floor(diff / 60);
    const s = diff % 60;
    document.getElementById('jam').textContent = String(j).padStart(2,'0');
    document.getElementById('menit').textContent = String(m).padStart(2,'0');
    document.getElementById('detik').textContent = String(s).padStart(2,'0');
}
updateCountdown();
setInterval(updateCountdown, 1000);

function filterVoucher(btn, type) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('#voucherGrid .voucher-card').forEach(card => {
        card.style.display = (type === 'semua' || card.dataset.type === type) ? '' : 'none';
    });
}

function klaimVoucher(btn) {
    const orig = btn.textContent;
    btn.textContent = '✓ Diklaim!';
    btn.style.background = 'linear-gradient(135deg,#059669,#10b981)';
    setTimeout(() => { btn.textContent = orig; }, 2000);
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('bersihin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\prak-web-lanjut-2407051023\PBWL_BersihIn_Kel3\laravel\resources\views/bersihin/customer/promo.blade.php ENDPATH**/ ?>