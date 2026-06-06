<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

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

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
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

// ===== LANDING PAGE - Bisa diakses tanpa login =====
Route::get('/bersihin', function () {
    return view('bersihin.customer.landing');
});

// ===== ADMIN ONLY =====
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/bersihin/admin', function () {
        $totalPendapatan = \DB::table('payments')->where('payment_status', 'paid')->sum('amount');
        $perluVerifikasi = \DB::table('bookings')->where('status', 'pending')->count();
        $belumDijadwalkan = \DB::table('bookings')->whereNull('petugas_id')->count();
        $petugasAktif = \App\Models\User::role('petugas')->count();
        $daftarPetugas = \App\Models\User::role('petugas')->limit(4)->get();
        $pesananTerbaru = \DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->select('bookings.*', 'services.service_name', 'users.name as customer_name')
            ->latest('bookings.created_at')->limit(5)->get();
        return view('bersihin.admin.dashboardAdmin', compact(
            'totalPendapatan', 'perluVerifikasi', 'belumDijadwalkan',
            'petugasAktif', 'daftarPetugas', 'pesananTerbaru'
        ));
    });

    Route::get('/bersihin/admin/verifikasi', function () {
        $antrean = \DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('payments', 'payments.booking_id', '=', 'bookings.id')
            ->where('payments.payment_status', 'pending')
            ->select('bookings.*', 'users.name as customer_name', 'payments.amount', 'payments.payment_method', 'payments.id as payment_id')
            ->latest('bookings.created_at')->get();
        return view('bersihin.admin.verifikasi', compact('antrean'));
    });

    Route::get('/bersihin/admin/petugas', function () {
        $daftarPetugas = \App\Models\User::role('petugas')->get();
        $totalPetugas = $daftarPetugas->count();
        return view('bersihin.admin.petugas', compact('daftarPetugas', 'totalPetugas'));
    });

    Route::get('/bersihin/admin/laporan', function () {
        $totalPendapatan = \DB::table('payments')->where('payment_status', 'paid')->sum('amount');
        $totalTransaksi = \DB::table('payments')->where('payment_status', 'paid')->count();
        $transaksi = \DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->select('payments.*', 'users.name as customer_name', 'services.service_name', 'bookings.created_at as booking_date')
            ->latest('payments.created_at')->get();
        return view('bersihin.admin.laporan', compact('totalPendapatan', 'totalTransaksi', 'transaksi'));
    });

    Route::get('/bersihin/admin/layanan', function () {
        $layanan = \DB::table('services')->get();
        return view('bersihin.admin.layanan', compact('layanan'));
    });

    Route::get('/bersihin/admin/pengaturan', function () {
        return view('bersihin.admin.pengaturan');
    });

    Route::get('/bersihin/admin/detail-pesanan', function () {
        return view('bersihin.admin.detail-pesanan');
    });

    Route::post('/bersihin/admin/petugas/tambah', function () {

    $user = \App\Models\User::create([
        'name' => request('name'),
        'email' => request('email'),
        'password' => bcrypt('password123')
    ]);

    $user->assignRole('petugas');

    return redirect('/bersihin/admin/petugas')
        ->with('success', 'Petugas berhasil ditambahkan');
    });

    Route::post('/bersihin/admin/layanan/tambah', function () {

   \DB::table('services')->insert([
    'service_name' => request('service_name'),
    'description'  => request('description'),
    'price'        => request('price'),
    'duration'     => request('duration'),
    'created_at'   => now(),
    'updated_at'   => now(),
    ]);

    return redirect('/bersihin/admin/layanan')
        ->with('success', 'Layanan berhasil ditambahkan');
    });
});

// ===== PETUGAS ONLY =====
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/bersihin/petugas/dashboard', function () {
        $user = \Auth::user();
        $sisaTugas = \DB::table('bookings')
            ->where('petugas_id', $user->id)
            ->whereIn('status', ['confirmed', 'progress'])
            ->count();
        $totalSelesai = \DB::table('bookings')
            ->where('petugas_id', $user->id)
            ->where('status', 'done')
            ->count();
        $tugasSelanjutnya = \DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.petugas_id', $user->id)
            ->whereIn('bookings.status', ['confirmed', 'progress'])
            ->select('bookings.*', 'services.service_name', 'users.name as customer_name')
            ->first();
        return view('bersihin.petugas.dashboard', compact('user', 'sisaTugas', 'totalSelesai', 'tugasSelanjutnya'));
    });

    Route::get('/bersihin/petugas/jadwal', function () {
        $user = \Auth::user();
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

    Route::get('/bersihin/petugas/performance', function () {
        $user = \Auth::user();
        $totalSelesai = \DB::table('bookings')
            ->where('petugas_id', $user->id)
            ->where('status', 'done')
            ->count();
        $riwayat = \DB::table('bookings')
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
        return view('bersihin.petugas.pengaturan');
    });
});

// ===== CUSTOMER ONLY =====
Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/bersihin/customer/dashboard', function () {
        $user = \Auth::user();
        $totalPesanan = \DB::table('bookings')->where('user_id', $user->id)->count();
        $pesananAktif = \DB::table('bookings')->where('user_id', $user->id)->whereIn('status', ['confirmed', 'progress'])->first();
        return view('bersihin.customer.dashboard', compact('user', 'totalPesanan', 'pesananAktif'));
    });

    Route::get('/bersihin/customer/pesanan', function () {
        $user = \Auth::user();
        $totalPesanan = \DB::table('bookings')->where('user_id', $user->id)->count();
        $pesananProses = \DB::table('bookings')->where('user_id', $user->id)->whereIn('status', ['confirmed', 'progress', 'pending'])->count();
        $pesananSelesai = \DB::table('bookings')->where('user_id', $user->id)->where('status', 'done')->count();
        $pesanan = \DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->leftJoin('users as petugas', 'bookings.petugas_id', '=', 'petugas.id')
            ->where('bookings.user_id', $user->id)
            ->select('bookings.*', 'services.service_name', 'services.price', 'petugas.name as petugas_name')
            ->latest('bookings.created_at')
            ->get();
        return view('bersihin.customer.pesanan', compact('totalPesanan', 'pesananProses', 'pesananSelesai', 'pesanan'));
    });

    Route::get('/bersihin/customer/riwayat-pesanan', function () {
        $user = \Auth::user();
        $pesanan = \DB::table('bookings')
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
        $service = null;
        if ($serviceId) {
            $service = \DB::table('services')->where('id', $serviceId)->first();
        }
        $layanan = \DB::table('services')->get();
        return view('bersihin.customer.booking', compact('service', 'layanan'));
    });

    Route::post('/bersihin/booking', function () {
        $user = \Auth::user();
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
        $booking = null;
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

    Route::post('/bersihin/pembayaran', function () {
        $bookingId = request('booking_id');
        $payment = \DB::table('payments')->where('booking_id', $bookingId)->first();

        if ($payment) {
            \DB::table('payments')
                ->where('booking_id', $bookingId)
                ->update([
                    'payment_status' => 'paid',
                    'payment_date'   => now(),
                    'updated_at'     => now(),
                ]);
        } else {
            $booking = \DB::table('bookings')
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->where('bookings.id', $bookingId)
                ->select('services.price')
                ->first();

            \DB::table('payments')->insert([
                'booking_id'     => $bookingId,
                'amount'         => $booking->price ?? 0,
                'payment_method' => 'transfer',
                'payment_status' => 'paid',
                'payment_date'   => now(),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        \DB::table('bookings')
            ->where('id', $bookingId)
            ->update([
                'status'     => 'confirmed',
                'updated_at' => now(),
            ]);

        return redirect('/bersihin/customer/pesanan')
            ->with('success', 'Pembayaran berhasil dikirim! ✅');
    });

    Route::get('/bersihin/customer/ulasan', function () {
        $bookingId = request('booking_id');
        $booking = null;
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
        $user = \Auth::user();
        $bookingId = request('booking_id');
        $booking = \DB::table('bookings')
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

        return redirect('/bersihin/customer/riwayat-pesanan')->with('success', 'Ulasan berhasil dikirim! ⭐');
    });

    Route::get('/bersihin/customer/promo', function () {
        return view('bersihin.customer.promo');
    });

    Route::get('/bersihin/customer/pengaturan', function () {
        return view('bersihin.customer.pengaturan');
    });
});