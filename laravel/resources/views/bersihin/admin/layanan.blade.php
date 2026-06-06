@extends('bersihin.layouts.app')
@section('page-title', 'Layanan & Promo')
@section('page-subtitle', 'Kelola daftar layanan dan promosi')

@section('content')

{{-- Header --}}
<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Layanan & Promo</h1>
        <p class="text-sm text-gray-400 mt-0.5">Kelola daftar layanan dan program promosi aktif di platform BersihIn</p>
    </div>
    <button
    onclick="document.getElementById('modalTambahLayanan').classList.remove('hidden')"
    class="flex items-center gap-2 px-4 py-2.5 bg-[#064E3B] text-white text-sm font-semibold rounded-lg hover:bg-emerald-800 transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Baru
    </button>
</div>

{{-- ===== TABS ===== --}}
<div class="flex gap-0 border-b border-gray-200 mb-6">
    <button
        id="tab-layanan"
        onclick="switchTab('layanan')"
        class="px-4 pb-3 text-sm font-semibold text-[#064E3B] border-b-2 border-[#064E3B] -mb-px transition">
        Daftar Layanan
    </button>
    <button
        id="tab-promo"
        onclick="switchTab('promo')"
        class="px-4 pb-3 text-sm font-medium text-gray-400 hover:text-gray-600 border-b-2 border-transparent -mb-px transition">
        Manajemen Promo
    </button>
</div>

{{-- ===== KONTEN TAB: DAFTAR LAYANAN ===== --}}
<div id="konten-layanan">

    <div class="grid grid-cols-3 gap-4">
        @forelse($layanan as $l)
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-sm transition">
    <div class="h-36 bg-emerald-50 flex items-center justify-center relative">
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm">
            <svg class="w-7 h-7 text-[#064E3B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <span class="absolute top-3 right-3 text-[10px] font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full uppercase tracking-wide">
            Aktif
        </span>
    </div>
    <div class="p-4">
        <h3 class="font-bold text-gray-800 text-sm">{{ $l->service_name }}</h3>
        <p class="text-xs text-gray-400 mt-0.5 mb-3">{{ $l->description }}</p>
        <div class="flex items-center justify-between">
            <span class="text-base font-extrabold text-[#064E3B]">Rp {{ number_format($l->price, 0, ',', '.') }}</span>
            <button class="flex items-center gap-1 text-xs font-semibold text-gray-500 hover:text-[#064E3B] transition">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </button>
        </div>
    </div>
</div>
@empty
<div class="col-span-3 text-center py-8 text-sm text-gray-400">Belum ada layanan</div>
@endforelse
    </div>
</div>

{{-- ===== KONTEN TAB: MANAJEMEN PROMO (hidden dulu) ===== --}}
<div id="konten-promo" class="hidden">

    <div class="bg-white rounded-xl border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="text-sm text-gray-400">Voucher aktif dan penawaran khusus untuk pelanggan.</p>
        </div>
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-5 py-3">Nama Promo</th>
                    <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-5 py-3">Kode</th>
                    <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-5 py-3">Berlaku Hingga</th>
                    <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-5 py-3">Status</th>
                    <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @php
                $promo = [
                    ['nama' => 'Diskon 30% Layanan Kedua', 'sub' => 'Min. Transaksi Rp 200rb', 'kode' => 'BERSIH30',  'berlaku' => '31 Des 2024', 'aktif' => true],
                    ['nama' => 'Cashback Rp 50.000',       'sub' => 'Khusus Pengguna Baru',    'kode' => 'HELLO50',   'berlaku' => '15 Nov 2024', 'aktif' => true],
                    ['nama' => 'Promo Gajian',              'sub' => 'Berakhir otomatis',       'kode' => 'GAJIANOKT', 'berlaku' => '30 Okt 2024', 'aktif' => false],
                ];
                @endphp

                @foreach($promo as $pr)
                <tr class="hover:bg-gray-50/60 transition">
                    <td class="px-5 py-4">
                        <p class="text-sm font-semibold text-gray-800">{{ $pr['nama'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $pr['sub'] }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-xs font-bold tracking-widest px-3 py-1.5 rounded-md
                            {{ $pr['aktif'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-400 border border-gray-200' }}">
                            {{ $pr['kode'] }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-sm text-gray-600">{{ $pr['berlaku'] }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-9 h-5 rounded-full flex items-center px-0.5 {{ $pr['aktif'] ? 'bg-[#064E3B]' : 'bg-gray-200' }}">
                                <div class="w-4 h-4 bg-white rounded-full shadow {{ $pr['aktif'] ? 'translate-x-4' : 'translate-x-0' }} transition"></div>
                            </div>
                            <span class="text-xs font-semibold {{ $pr['aktif'] ? 'text-emerald-600' : 'text-gray-400' }}">
                                {{ $pr['aktif'] ? 'Enable' : 'Disable' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <button class="p-1.5 rounded-lg hover:bg-gray-100">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

{{-- ===== JAVASCRIPT TAB ===== --}}
<script>
function switchTab(tab) {
    // Sembunyikan semua konten
    document.getElementById('konten-layanan').classList.add('hidden');
    document.getElementById('konten-promo').classList.add('hidden');

    // Reset style semua tombol tab
    document.getElementById('tab-layanan').className = 'px-4 pb-3 text-sm font-medium text-gray-400 hover:text-gray-600 border-b-2 border-transparent -mb-px transition';
    document.getElementById('tab-promo').className   = 'px-4 pb-3 text-sm font-medium text-gray-400 hover:text-gray-600 border-b-2 border-transparent -mb-px transition';

    // Tampilkan konten tab yang diklik
    document.getElementById('konten-' + tab).classList.remove('hidden');

    // Aktifkan style tombol tab yang diklik
    document.getElementById('tab-' + tab).className = 'px-4 pb-3 text-sm font-semibold text-[#064E3B] border-b-2 border-[#064E3B] -mb-px transition';
}
</script>

{{-- MODAL TAMBAH LAYANAN --}}
<div id="modalTambahLayanan"
     class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50"
     onclick="if(event.target===this)this.classList.add('hidden')">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4"
         onclick="event.stopPropagation()">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Tambah Layanan Baru</h3>

            <button
                onclick="document.getElementById('modalTambahLayanan').classList.add('hidden')"
                class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center">
                ✕
            </button>
        </div>

        <form action="/bersihin/admin/layanan/tambah" method="POST">
            @csrf

            <div class="px-6 py-5 space-y-4">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Nama Layanan
                    </label>
                    <input
                        type="text"
                        name="service_name"
                        required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Deskripsi
                    </label>
                    <textarea
                        name="description"
                        rows="3"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Durasi (menit)
                    </label>

                    <input
                        type="number"
                        name="duration"
                        required
                        placeholder="60"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Harga
                    </label>
                    <input
                        type="number"
                        name="price"
                        required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                <button
                    type="button"
                    onclick="document.getElementById('modalTambahLayanan').classList.add('hidden')"
                    class="flex-1 border border-gray-200 text-gray-600 text-sm font-semibold py-2.5 rounded-xl">
                    Batal
                </button>

                <button
                    type="submit"
                    class="flex-1 bg-[#064E3B] text-white text-sm font-semibold py-2.5 rounded-xl">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection