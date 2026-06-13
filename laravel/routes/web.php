<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

if (!function_exists('kirimNotif')) {
    function kirimNotif($userId, $title, $message, $type = 'info', $icon = 'info') {
        \DB::table('notifications')->insert([
            'user_id'    => $userId,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'icon'       => $icon,
            'is_read'    => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}


Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::middleware('auth')->post('/bersihin/notifikasi/baca-semua', function () {
    \DB::table('notifications')
        ->where('user_id', auth()->id())
        ->where('is_read', false)
        ->update(['is_read' => true, 'updated_at' => now()]);
    return back();
});

// ===== AUTH BERSIHIN =====
Route::get('/bersihin/login', function () {
    return view('bersihin.auth.login');
});

Route::post('/bersihin/login', function () {
    $credentials = request()->only('email', 'password');

    if (Auth::attempt($credentials)) {
        request()->session()->regenerate();
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect('/bersihin/admin');
        } elseif ($user->hasRole('petugas')) {
            return redirect('/bersihin/petugas/dashboard');
        } elseif ($user->hasRole('customer')) {
            return redirect('/bersihin/customer/dashboard');
        } else {
            return redirect('/bersihin');
        }
    }

    return back()->withErrors(['email' => 'Email atau password salah.']);
});

Route::get('/bersihin/register', function () {
    return view('bersihin.auth.register');
});

Route::post('/bersihin/register', function () {
    $data = request()->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'phone'    => 'nullable|string|max:20',
        'password' => 'required|min:8',
    ]);

    $user = \App\Models\User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'phone'    => $data['phone'] ?? null,
        'password' => bcrypt($data['password']),
    ]);

    $user->assignRole('customer');
    Auth::login($user);
    request()->session()->regenerate();

    return redirect('/bersihin/customer/dashboard')
        ->with('success', 'Selamat datang di BersihIn! 🎉');
});

Route::get('/bersihin/lupa-password', function () {
    return view('bersihin.auth.lupa-password');
});

// ===== LANDING PAGE =====
Route::get('/bersihin', function () {
    return view('bersihin.customer.landing');
});

// ===== LOGOUT =====
Route::post('/bersihin/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/bersihin/login');
});

// ===== ADMIN ONLY =====
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/bersihin/admin', function () {
        $totalPendapatan   = \DB::table('payments')->where('payment_status', 'paid')->sum('amount');
        $perluVerifikasi   = \DB::table('bookings')
            ->join('payments', 'payments.booking_id', '=', 'bookings.id')
            ->where('payments.payment_status', 'pending')->count();
        $belumDijadwalkan  = \DB::table('bookings')
            ->where('status', 'confirmed')
            ->whereNull('petugas_id')->count();
        $petugasAktif      = \App\Models\User::role('petugas')->count();
        $daftarPetugas     = \App\Models\User::role('petugas')->limit(4)->get();
        $pesananTerbaru    = \DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->select('bookings.*', 'services.service_name', 'users.name as customer_name')
            ->latest('bookings.created_at')->limit(5)->get();

        return view('bersihin.admin.dashboardAdmin', compact(
            'totalPendapatan', 'perluVerifikasi', 'belumDijadwalkan',
            'petugasAktif', 'daftarPetugas', 'pesananTerbaru'
        ));
    });

    // ===== VERIFIKASI =====
   Route::get('/bersihin/admin/verifikasi', function () {
    // Antrean: payment pending
    $antrean = \DB::table('bookings')
        ->join('users', 'bookings.user_id', '=', 'users.id')
        ->join('payments', 'payments.booking_id', '=', 'bookings.id')
        ->where('payments.payment_status', 'pending')
        ->select(
            'bookings.*',
            'users.name as customer_name',
            'payments.amount',
            'payments.payment_method',
            'payments.payment_proof',
            'payments.id as payment_id'
        )
        ->latest('bookings.created_at')->get();

        // Sudah diverifikasi (confirmed) — ada atau belum petugas
        $sudahVerifikasi = \DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->leftJoin('users as petugas', 'bookings.petugas_id', '=', 'petugas.id')
            ->where('bookings.status', 'confirmed')
            ->select(
                'bookings.*',
                'users.name as customer_name',
                'services.service_name',
                'petugas.name as petugas_name'
            )
            ->latest('bookings.created_at')->get();

     // Daftar petugas untuk modal assign
        $daftarPetugas = \App\Models\User::role('petugas')->get();

        $riwayat = \DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->whereIn('payments.payment_status', ['paid', 'failed'])
            ->select('payments.*', 'payments.id as payment_id', 'users.name as customer_name', 'services.service_name', 'bookings.id as booking_id')
            ->latest('payments.updated_at')->get();

        return view('bersihin.admin.verifikasi', compact('antrean', 'sudahVerifikasi', 'daftarPetugas', 'riwayat'));
    });

    // Approve + assign petugas sekaligus (dari modal)
    Route::post('/bersihin/admin/verifikasi/approve-assign', function () {
        $bookingId   = request('booking_id');
        $paymentId   = request('payment_id');
        $petugasId   = request('petugas_id');
        $skipPayment = request('skip_payment', '0');

        // Kalau dari antrean pending, update payment jadi paid
        if ($skipPayment === '0' && $paymentId) {
            \DB::table('payments')->where('id', $paymentId)->update([
                'payment_status' => 'paid',
                'payment_date'   => now(),
                'updated_at'     => now(),
            ]);
        }

        // Update booking: confirmed + assign petugas
        \DB::table('bookings')->where('id', $bookingId)->update([
            'status'     => 'confirmed',
            'petugas_id' => $petugasId,
            'updated_at' => now(),
        ]);

        $petugas = \App\Models\User::find($petugasId);

// Notif ke customer
    $booking = \DB::table('bookings')->where('id', $bookingId)->first();
    if ($booking) {
    kirimNotif(
        $booking->user_id,
        'Pembayaran Dikonfirmasi ✅',
        'Pesanan #BRS-' . $bookingId . ' diverifikasi. Petugas ' . ($petugas->name ?? '') . ' ditugaskan.',
        'success',
        'check'
    );
}

// Notif ke petugas
    if ($petugasId) {
    kirimNotif(
        $petugasId,
        'Tugas Baru Diterima 📋',
        'Kamu mendapat tugas baru untuk pesanan #BRS-' . $bookingId . '. Cek jadwalmu!',
        'info',
        'person'
    );
}

        return redirect('/bersihin/admin/verifikasi')
            ->with('success', 'Pesanan #BRS-' . $bookingId . ' diverifikasi & ditugaskan ke ' . ($petugas->name ?? 'petugas') . '! ✅');
    });

    // Reject pembayaran
    Route::post('/bersihin/admin/verifikasi/reject', function () {
        $bookingId = request('booking_id');
        $paymentId = request('payment_id');

        \DB::table('payments')->where('id', $paymentId)->update([
            'payment_status' => 'failed',
            'updated_at'     => now(),
        ]);

        \DB::table('bookings')->where('id', $bookingId)->update([
            'status'     => 'cancelled',
            'updated_at' => now(),
        ]);

        return redirect('/bersihin/admin/verifikasi')
            ->with('error', 'Pembayaran #BRS-' . $bookingId . ' ditolak.');
    });

    // ===== PETUGAS (CRUD) =====
    Route::get('/bersihin/admin/petugas', function () {
        $daftarPetugas = \App\Models\User::role('petugas')->get();
        $totalPetugas  = $daftarPetugas->count();
        return view('bersihin.admin.petugas', compact('daftarPetugas', 'totalPetugas'));
    });

    Route::post('/bersihin/admin/petugas', function () {
        $data = request()->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:8',
        ]);

        $user = \App\Models\User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => bcrypt($data['password']),
        ]);

        $user->assignRole('petugas');

        return redirect('/bersihin/admin/petugas')
            ->with('success', 'Petugas ' . $data['name'] . ' berhasil ditambahkan! ✅');
    });

   Route::post('/bersihin/admin/petugas/hapus', function () {
    $userId = request('user_id');
    $user   = \App\Models\User::find($userId);

    if ($user && $user->hasRole('petugas')) {
        // Set petugas_id ke NULL dulu di semua booking yang terkait
        \DB::table('bookings')
            ->where('petugas_id', $userId)
            ->update([
                'petugas_id' => null,
                'updated_at' => now(),
            ]);

        $user->delete();
        return redirect('/bersihin/admin/petugas')
            ->with('success', 'Petugas berhasil dihapus.');
    }

    return redirect('/bersihin/admin/petugas')
        ->with('error', 'Petugas tidak ditemukan.');
});

    // ===== LAPORAN =====
   Route::get('/bersihin/admin/laporan', function () {
    $totalPendapatan = \DB::table('payments')->where('payment_status', 'paid')->sum('amount');
    $totalTransaksi  = \DB::table('payments')->where('payment_status', 'paid')->count();
    $totalPending    = \DB::table('payments')->where('payment_status', 'pending')->count();
    $estimasiLaba    = $totalPendapatan * 0.3;
    $layananList     = \DB::table('services')->get();
    $transaksi       = \DB::table('payments')
        ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
        ->join('users', 'bookings.user_id', '=', 'users.id')
        ->join('services', 'bookings.service_id', '=', 'services.id')
        ->select('payments.*', 'users.name as customer_name', 'services.service_name', 'bookings.created_at as booking_date')
        ->latest('payments.created_at')->get();

    return view('bersihin.admin.laporan', compact(
        'totalPendapatan', 'totalTransaksi', 'totalPending',
        'estimasiLaba', 'layananList', 'transaksi'
    ));
});
        Route::get('/bersihin/admin/laporan/unduh', function () {
        $transaksi = \DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->select('payments.*', 'users.name as customer_name', 'services.service_name')
            ->latest('payments.created_at')->get();

        $totalPendapatan = $transaksi->where('payment_status', 'paid')->sum('amount');
        $totalTransaksi  = $transaksi->where('payment_status', 'paid')->count();

       $html = view('bersihin.admin.laporan-pdf', compact('transaksi', 'totalPendapatan', 'totalTransaksi'))->render();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);

        return $pdf->download('laporan-transaksi-' . date('Y-m-d') . '.pdf');
    });

    // ===== LAYANAN =====
    Route::get('/bersihin/admin/layanan', function () {
    $layanan = \DB::table('services')->get();
    $promos  = \DB::table('promos')->get();
    return view('bersihin.admin.layanan', compact('layanan', 'promos'));
});
// Tambah layanan
    Route::post('/bersihin/admin/layanan', function () {
        \DB::table('services')->insert([
            'service_name' => request('service_name'),
            'description'  => request('description'),
            'price'        => request('price'),
            'duration'     => request('duration') ?? 60,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        return redirect('/bersihin/admin/layanan')->with('success', 'Layanan berhasil ditambahkan! ✅');
    });

    // Edit layanan
    Route::post('/bersihin/admin/layanan/edit', function () {
        \DB::table('services')->where('id', request('layanan_id'))->update([
            'service_name' => request('service_name'),
            'description'  => request('description'),
            'price'        => request('price'),
            'duration'     => request('duration') ?? 60,
            'updated_at'   => now(),
        ]);
        return redirect('/bersihin/admin/layanan')->with('success', 'Layanan berhasil diupdate! ✅');
    });

    // Hapus layanan
    Route::post('/bersihin/admin/layanan/hapus', function () {
        \DB::table('services')->where('id', request('layanan_id'))->delete();
        return redirect('/bersihin/admin/layanan')->with('success', 'Layanan berhasil dihapus!');
    });

    // ===== PENGATURAN =====
    Route::get('/bersihin/admin/pengaturan', function () {
        return view('bersihin.admin.pengaturan');
    });

   Route::get('/bersihin/admin/detail-pesanan', function () {
    $id = request('id');
    $booking = \DB::table('bookings')
        ->join('users', 'bookings.user_id', '=', 'users.id')
        ->join('services', 'bookings.service_id', '=', 'services.id')
        ->leftJoin('users as petugas', 'bookings.petugas_id', '=', 'petugas.id')
        ->where('bookings.id', $id)
        ->select('bookings.*', 'users.name as customer_name', 'users.phone as customer_phone', 'services.service_name', 'petugas.name as petugas_name', 'petugas.phone as petugas_phone')
        ->first();

    $payment = \DB::table('payments')->where('booking_id', $id)->first();

    return view('bersihin.admin.detail-pesanan', compact('booking', 'payment'));
});
});

// ===== PETUGAS ONLY =====
Route::middleware(['auth', 'role:petugas'])->group(function () {

    Route::get('/bersihin/petugas/dashboard', function () {
        $user             = \Auth::user();
        $sisaTugas        = \DB::table('bookings')
            ->where('petugas_id', $user->id)
            ->whereIn('status', ['confirmed', 'on_the_way', 'in_progress'])
            ->count();
        $totalSelesai     = \DB::table('bookings')
            ->where('petugas_id', $user->id)
            ->where('status', 'done')
            ->count();
        $tugasSelanjutnya = \DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.petugas_id', $user->id)
            ->whereIn('bookings.status', ['confirmed', 'on_the_way', 'in_progress'])
            ->select('bookings.*', 'services.service_name', 'users.name as customer_name')
            ->first();

        return view('bersihin.petugas.dashboard', compact('user', 'sisaTugas', 'totalSelesai', 'tugasSelanjutnya'));
    });

    Route::get('/bersihin/petugas/jadwal', function () {
        $user   = \Auth::user();
        $jadwal = \DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.petugas_id', $user->id)
            ->select('bookings.*', 'services.service_name', 'users.name as customer_name')
            ->orderBy('bookings.booking_date')
            ->orderBy('bookings.booking_time')
            ->get();

        return view('bersihin.petugas.jadwal', compact('jadwal'));
    });

    // ===== UPDATE STATUS OLEH PETUGAS =====
    Route::post('/bersihin/petugas/update-status', function () {
        $bookingId = request('booking_id');
        $status    = request('status');
        $user      = \Auth::user();

        // Pastikan booking ini milik petugas yang login
        $booking = \DB::table('bookings')
            ->where('id', $bookingId)
            ->where('petugas_id', $user->id)
            ->first();

        if (!$booking) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        // Validasi urutan status
        $allowedTransitions = [
            'confirmed'   => 'on_the_way',
            'on_the_way'  => 'in_progress',
            'in_progress' => 'done',
        ];

        if (!isset($allowedTransitions[$booking->status]) || $allowedTransitions[$booking->status] !== $status) {
            return back()->with('error', 'Status tidak valid.');
        }

        \DB::table('bookings')->where('id', $bookingId)->update([
            'status'     => $status,
            'updated_at' => now(),
        ]);

        $labelStatus = [
            'on_the_way'  => 'Dalam Perjalanan 🚗',
            'in_progress' => 'Sedang Dikerjakan 🧹',
            'done'        => 'Selesai ✅',
        ];

        // Notif ke customer saat status berubah
        $customerBooking = \DB::table('bookings')->where('id', $bookingId)->first();
        $notifMap = [
         'on_the_way'  => ['Petugas Dalam Perjalanan 🚗', 'Petugas sedang menuju lokasimu untuk pesanan #BRS-' . $bookingId, 'info', 'car'],
        'in_progress' => ['Sedang Dikerjakan 🧹', 'Petugas sedang mengerjakan pesanan #BRS-' . $bookingId . ' di lokasimu.', 'warning', 'info'],
        'done'        => ['Layanan Selesai ✅', 'Pesanan #BRS-' . $bookingId . ' selesai. Jangan lupa beri ulasan!', 'success', 'check'],
];
        if (isset($notifMap[$status])) {
        [$t, $m, $ty, $i] = $notifMap[$status];
        kirimNotif($customerBooking->user_id, $t, $m, $ty, $i);
}
        return back()->with('success', 'Status pesanan #BRS-' . $bookingId . ' diperbarui: ' . ($labelStatus[$status] ?? $status));
    });

    Route::get('/bersihin/petugas/performance', function () {
        $user         = \Auth::user();
        $totalSelesai = \DB::table('bookings')
            ->where('petugas_id', $user->id)
            ->where('status', 'done')
            ->count();
        $riwayat      = \DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('reviews', 'reviews.booking_id', '=', 'bookings.id')
            ->where('bookings.petugas_id', $user->id)
            ->where('bookings.status', 'done')
            ->select('bookings.*', 'services.service_name', 'users.name as customer_name', 'reviews.rating', 'reviews.comment')
            ->latest('bookings.created_at')
            ->get();

        return view('bersihin.petugas.performance', compact('totalSelesai', 'riwayat'));
    });

    Route::get('/bersihin/petugas/pengaturan', function () {
        $user = \Auth::user();
        $totalSelesai = \DB::table('bookings')
            ->where('petugas_id', $user->id)
            ->where('status', 'done')
            ->count();
        $rating = \DB::table('reviews')
            ->whereIn('booking_id', \DB::table('bookings')->where('petugas_id', $user->id)->pluck('id'))
            ->avg('rating');
        return view('bersihin.petugas.pengaturan', compact('user', 'totalSelesai', 'rating'));
    });

    Route::post('/bersihin/petugas/pengaturan', function () {
        $user = \Auth::user();
        $data = request()->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);
        if (request()->hasFile('avatar')) {
            $path = request()->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }
        \App\Models\User::where('id', $user->id)->update($data);
        if (request('password_baru') && request('password_lama')) {
            if (!\Hash::check(request('password_lama'), $user->password)) {
                return back()->with('error', 'Password lama tidak sesuai!');
            }
            \App\Models\User::where('id', $user->id)->update([
                'password' => \Hash::make(request('password_baru')),
            ]);
        }
        return back()->with('success', 'Profil berhasil diperbarui! ✅');
    });
});

// ===== CUSTOMER ONLY =====
Route::middleware(['auth', 'role:customer'])->group(function () {

   Route::get('/bersihin/customer/dashboard', function () {
    $user          = \Auth::user();
    $totalPesanan  = \DB::table('bookings')->where('user_id', $user->id)->count();
    $pesananAktif  = \DB::table('bookings')
        ->where('user_id', $user->id)
        ->whereIn('status', ['confirmed', 'on_the_way', 'in_progress', 'pending'])
        ->first();
    $totalVoucher  = \DB::table('promos')->where('is_active', true)->count();

    return view('bersihin.customer.dashboard', compact('user', 'totalPesanan', 'pesananAktif', 'totalVoucher'));
});

    Route::get('/bersihin/customer/pesanan', function () {
        $user           = \Auth::user();
        $totalPesanan   = \DB::table('bookings')->where('user_id', $user->id)->count();
        $pesananProses  = \DB::table('bookings')
            ->where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'on_the_way', 'in_progress', 'pending'])
            ->count();
        $pesananSelesai = \DB::table('bookings')
            ->where('user_id', $user->id)
            ->where('status', 'done')
            ->count();
        $pesanan        = \DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->leftJoin('users as petugas', 'bookings.petugas_id', '=', 'petugas.id')
            ->where('bookings.user_id', $user->id)
            ->select('bookings.*', 'services.service_name', 'services.price', 'petugas.name as petugas_name')
            ->latest('bookings.created_at')
            ->get();

        return view('bersihin.customer.pesanan', compact('totalPesanan', 'pesananProses', 'pesananSelesai', 'pesanan'));
    });

    Route::get('/bersihin/customer/riwayat-pesanan', function () {
        $user          = \Auth::user();
        $pesanan       = \DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->leftJoin('users as petugas', 'bookings.petugas_id', '=', 'petugas.id')
            ->leftJoin('reviews', 'reviews.booking_id', '=', 'bookings.id')
            ->where('bookings.user_id', $user->id)
            ->select('bookings.*', 'services.service_name', 'services.price', 'petugas.name as petugas_name', 'reviews.rating', 'reviews.comment')
            ->latest('bookings.created_at')
            ->get();
        $totalBulanIni = \DB::table('bookings')
            ->join('payments', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.user_id', $user->id)
            ->where('payments.payment_status', 'paid')
            ->whereMonth('bookings.created_at', now()->month)
            ->sum('payments.amount');

        return view('bersihin.customer.riwayat-pesanan', compact('pesanan', 'totalBulanIni'));
    });

    Route::get('/bersihin/layanan', function () {
        $layanan = \DB::table('services')->get();
        return view('bersihin.customer.layanan', compact('layanan'));
    });

    Route::get('/bersihin/booking', function () {
        $serviceId = request('service_id');
        $service   = null;
        if ($serviceId) {
            $service = \DB::table('services')->where('id', $serviceId)->first();
        }
        $layanan = \DB::table('services')->get();
        return view('bersihin.customer.booking', compact('service', 'layanan'));
    });

    Route::post('/bersihin/booking', function () {
        $user      = \Auth::user();
        $bookingId = \DB::table('bookings')->insertGetId([
            'user_id'      => $user->id,
            'service_id'   => request('service_id'),
            'booking_date' => request('booking_date'),
            'booking_time' => request('booking_time'),
            'address'      => request('address'),
            'status'       => 'pending',
            'petugas_id'   => null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect('/bersihin/pembayaran?booking_id=' . $bookingId);
    });

    Route::get('/bersihin/pembayaran', function () {
        $bookingId = request('booking_id');
        $booking   = null;
        if ($bookingId) {
            $booking = \DB::table('bookings')
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->where('bookings.id', $bookingId)
                ->where('bookings.user_id', \Auth::id())
                ->select('bookings.*', 'services.service_name', 'services.price')
                ->first();
        }

        return view('bersihin.customer.pembayaran', compact('booking'));
    });

    // Pembayaran POST — kirim bukti, status tetap pending sampai admin approve
   Route::post('/bersihin/pembayaran', function () {
    $bookingId = request('booking_id');
    $payment   = \DB::table('payments')->where('booking_id', $bookingId)->first();

    // Handle upload bukti transfer
    $proofPath = null;
    if (request()->hasFile('payment_proof')) {
        $proofPath = request()->file('payment_proof')->store('payment_proofs', 'public');
    }

    if ($payment) {
        \DB::table('payments')
            ->where('booking_id', $bookingId)
            ->update([
                'payment_status' => 'pending',
                'payment_proof'  => $proofPath ?? $payment->payment_proof,
                'updated_at'     => now(),
            ]);
    }
        else {
            $booking = \DB::table('bookings')
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->where('bookings.id', $bookingId)
                ->select('services.price')
                ->first();

           \DB::table('payments')->insert([
            'booking_id'     => $bookingId,
             'amount'         => $booking->price ?? 0,
            'payment_method' => 'transfer',
            'payment_status' => 'pending',
            'payment_proof'  => $proofPath,
            'payment_date'   => null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
        }

        // Status booking tetap pending, menunggu verifikasi admin
        \DB::table('bookings')
            ->where('id', $bookingId)
            ->update([
                'status'     => 'pending',
                'updated_at' => now(),
            ]);

        // Notif ke admin
        $customerName = auth()->user()->name;
        $adminUsers = \App\Models\User::role('admin')->get();
        foreach ($adminUsers as $admin) {
        kirimNotif(
        $admin->id,
        'Pembayaran Baru Masuk 💳',
        $customerName . ' mengirim bukti transfer untuk pesanan #BRS-' . $bookingId . '. Segera verifikasi!',
        'info',
        'check'
    );
}
        return redirect('/bersihin/customer/pesanan')
            ->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu verifikasi admin. ✅');
    });

    Route::get('/bersihin/customer/ulasan', function () {
        $bookingId = request('booking_id');
        $booking   = null;
        if ($bookingId) {
            $booking = \DB::table('bookings')
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->where('bookings.id', $bookingId)
                ->where('bookings.user_id', \Auth::id())
                ->where('bookings.status', 'done')
                ->select('bookings.*', 'services.service_name')
                ->first();
        }

        return view('bersihin.customer.ulasan', compact('booking'));
    });

    Route::post('/bersihin/customer/ulasan', function () {
        $user      = \Auth::user();
        $bookingId = request('booking_id');
        $booking   = \DB::table('bookings')
            ->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->where('status', 'done')
            ->first();

        if (!$booking) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $existing = \DB::table('reviews')->where('booking_id', $bookingId)->first();
        if ($existing) {
            return back()->with('error', 'Pesanan ini sudah diberi ulasan.');
        }

        \DB::table('reviews')->insert([
            'booking_id' => $bookingId,
            'user_id'    => $user->id,
            'rating'     => request('rating'),
            'comment'    => request('comment'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/bersihin/customer/riwayat-pesanan')
            ->with('success', 'Ulasan berhasil dikirim! ⭐');
    });
    // Batalkan pesanan
    Route::post('/bersihin/customer/pesanan/{id}/cancel', function ($id) {
        $user = \Auth::user();

        $booking = \DB::table('bookings')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Pesanan tidak ditemukan.'], 404);
        }

        \DB::table('bookings')->where('id', $id)->update([
            'status'     => 'cancelled',
            'updated_at' => now(),
        ]);

        \DB::table('payments')->where('booking_id', $id)->update([
            'payment_status' => 'failed',
            'updated_at'     => now(),
        ]);

        return response()->json(['message' => 'Pesanan berhasil dibatalkan.']);
    });
    Route::post('/bersihin/customer/pengaturan', function () {
        $user = \Auth::user();
        $data = request()->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        \App\Models\User::where('id', $user->id)->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);

        if (request('password_baru') && request('password_lama')) {
            if (!\Hash::check(request('password_lama'), $user->password)) {
                return back()->with('error', 'Password lama tidak sesuai!');
            }
            if (request('password_baru') !== request('password_konfirm')) {
                return back()->with('error', 'Konfirmasi password tidak cocok!');
            }
            \App\Models\User::where('id', $user->id)->update([
                'password' => \Hash::make(request('password_baru')),
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui! ✅');
    });

    Route::get('/bersihin/customer/promo', function () {
        $promos = \DB::table('promos')->where('is_active', true)->get();
        return view('bersihin.customer.promo', compact('promos'));
    });

    Route::get('/bersihin/customer/pengaturan', function () {
        return view('bersihin.customer.pengaturan');
    });
});