<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AbsensiController;
// use App\Http\Controllers\Mahasiswa\HomeController;
// use App\Http\Controllers\Dosen\HomeController;
// use App\Http\Controllers\Pimpinan\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\Master\FakultasController;
use App\Http\Controllers\Admin\Master\JurusanController;
use App\Http\Controllers\Admin\Master\ProdiController;
use App\Http\Controllers\Admin\Master\KelasController;
use App\Http\Controllers\Admin\Master\RuanganController;
use App\Http\Controllers\Admin\Master\MakulController;
use App\Http\Controllers\Admin\Master\TahunAjaranController;
use App\Http\Controllers\Admin\Jadwal\JadMakulController;
use App\Http\Controllers\Admin\Jadwal\JadDosenController;
use App\Http\Controllers\Admin\Jadwal\JadMahasiwaController;
use App\Http\Controllers\Admin\Absensi\AbsMahasiswaController;
use App\Http\Controllers\Admin\Absensi\AbsDosenController;
use App\Http\Controllers\Admin\Akun\MahasiswaController;
use App\Http\Controllers\Admin\Akun\DosenController;

//Clear All:
Route::get('/clear', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('optimize');
    $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');
    return '<h1>Berhasil dibersihkan</h1>';
});

Route::get('/', function () {
    return view('auth.login');
});

// Authentication
Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard.index');
});

Route::get('/mahasiswa/dashboard', function () {
    return view('mahasiswa.dashboard.index');
});

Route::get('/dosen/dashboard', function () {
    return view('dosen.dashboard.index');
});

Route::get('/pimpinan/dashboard', function () {
    return view('pimpinan.dashboard.index');
});

// Fakultas
Route::prefix('admin/fakultas')
    ->name('admin.master.fakultas.')
    ->controller(FakultasController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

Route::get('/mahasiswa/akademik', function () {
    return view('mahasiswa.akademik.index');
});

Route::get('/dosen/akademik', function () {
    return view('dosen.akademik.index');
});

// Jurusan
Route::prefix('admin/jurusan')
    ->name('admin.master.jurusan.')
    ->controller(JurusanController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

Route::get('/mahasiswa/absensi', function () {
    return view('mahasiswa.absensi.index');
});

Route::get('/mahasiswa/absensi/absen', function () {
    return view('mahasiswa.absensi.absen');
});

Route::get('/dosen/absensi', function () {
    return view('dosen.absensi.index');
});

Route::get('/dosen/absensi/add', function () {
    return view('dosen.absensi.add');
});

Route::get('/dosen/absensi/edit', function () {
    return view('dosen.absensi.edit');
});

Route::get('/dosen/absensi/editnilai', function () {
    return view('dosen.absensi.editnilai');
});

Route::get('/dosen/absensi/addnilai', function () {
    return view('dosen.absensi.addnilai');
});

// Prodi
Route::prefix('admin/prodi')
    ->name('admin.master.prodi.')
    ->controller(ProdiController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

Route::get('/mahasiswa/penguji', function () {
    return view('mahasiswa.penguji.index');
});

Route::get('/mahasiswa/penguji/pengajuan', function () {
    return view('mahasiswa.penguji.penguji');
});

Route::get('/pimpinan/penguji', function () {
    return view('pimpinan.penguji.index');
});

Route::get('/pimpinan/penguji/add', function () {
    return view('pimpinan.penguji.add');
});

// Kelas
Route::prefix('admin/kelas')
    ->name('admin.master.kelas.')
    ->controller(KelasController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

Route::get('/mahasiswa/proposal', function () {
    return view('mahasiswa.proposal.index');
});

Route::get('/mahasiswa/proposal/seminar', function () {
    return view('mahasiswa.proposal.proposal');
});

Route::get('/dosen/proposal', function () {
    return view('dosen.proposal.index');
});

// Ruangan
Route::prefix('admin/ruangan')
    ->name('admin.master.ruangan.')
    ->controller(RuanganController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

Route::get('/mahasiswa/seminar', function () {
    return view('mahasiswa.seminar.index');
});

Route::get('/mahasiswa/seminar/hasil', function () {
    return view('mahasiswa.seminar.seminar');
});

Route::get('/dosen/seminar', function () {
    return view('dosen.seminar.index');
});

// Mata Kuliah
Route::prefix('admin/makul')
    ->name('admin.master.makul.')
    ->controller(MakulController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

Route::get('/mahasiswa/ta', function () {
    return view('mahasiswa.ta.index');
});

Route::get('/dosen/ta', function () {
    return view('dosen.ta.index');
});

Route::get('/mahasiswa/penelitian', function () {
    return view('mahasiswa.penelitian.index');
});

Route::get('/mahasiswa/penelitian/surat', function () {
    return view('mahasiswa.penelitian.surat');
});

Route::get('/dosen/penelitian', function () {
    return view('dosen.penelitian.index');
});

// Tahun Ajaran
Route::prefix('admin/tahunajar')
    ->name('admin.master.tahunajar.')
    ->controller(TahunAjaranController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

Route::get('/mahasiswa/yudisium', function () {
    return view('mahasiswa.yudisium.index');
});

Route::get('/mahasiswa/yudisium/file', function () {
    return view('mahasiswa.yudisium.yudisium');
});

// Jadwal Mata Kuliah
Route::prefix('admin/jadmakul')
    ->name('admin.jadwal.makul.')
    ->controller(JadMakulController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Mahasiswa
Route::prefix('admin/mahasiswa')
    ->name('admin.akun.mahasiwswa.')
    ->controller(MahasiswaController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Jadwal Mahasiswa
Route::prefix('admin/jadmahasiswa')
    ->name('admin.jadwal.mahasiwswa.')
    ->controller(JadMahasiwaController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });


// Absen Mahasiswa
Route::prefix('admin/absmahasiswa')
    ->name('admin.absensi.mahasiwswa.')
    ->controller(AbsMahasiswaController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Dosen
Route::prefix('admin/dosen')
    ->name('admin.akun.dosen.')
    ->controller(DosenController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Jadwal Dosen
Route::prefix('admin/jaddosen')
    ->name('admin.jadwal.dosen.')
    ->controller(JadDosenController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });


// Absen Dosen
Route::prefix('admin/absdosen')
    ->name('admin.absensi.dosen.')
    ->controller(AbsDosenController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });