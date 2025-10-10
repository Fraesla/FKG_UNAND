<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Dosen\HomeController as DosenHomeController;
use App\Http\Controllers\Dosen\AbsenController;
use App\Http\Controllers\Mahasiswa\HomeController as MahasiswaHomeController;
use App\Http\Controllers\Pimpinan\HomeController as PimpinanHomeController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\Master\FakultasController;
use App\Http\Controllers\Admin\Master\JurusanController;
use App\Http\Controllers\Admin\Master\ProdiController;
use App\Http\Controllers\Admin\Master\KelasController;
use App\Http\Controllers\Admin\Master\RuanganController;
use App\Http\Controllers\Admin\Master\MakulController;
use App\Http\Controllers\Admin\Master\NilaiController;
use App\Http\Controllers\Admin\Master\TahunAjaranController;
use App\Http\Controllers\Admin\Jadwal\JadMakulController;
use App\Http\Controllers\Admin\Jadwal\JadMetopenController;
use App\Http\Controllers\Admin\Jadwal\JadDosenController;
use App\Http\Controllers\Admin\Jadwal\JadMahasiwaController;
use App\Http\Controllers\Admin\Absensi\AbsMahasiswaController;
use App\Http\Controllers\Admin\Absensi\AbsDosenController;
use App\Http\Controllers\Admin\Akun\MahasiswaController;
use App\Http\Controllers\Admin\Akun\DosenController;
use App\Http\Controllers\Mahasiswa\JadwalController;
use App\Http\Controllers\Mahasiswa\BlokMahasiswaController;
use App\Http\Controllers\Admin\TaController;
use App\Http\Controllers\Admin\SkripsiController;
use App\Http\Controllers\Admin\SuratIzinController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Admin\SeminarProposalController;
use App\Http\Controllers\Admin\SeminarHasilController;
use App\Http\Controllers\Admin\YudisiumController;
use App\Http\Controllers\Admin\SuratAktifKuliahController;
use App\Http\Controllers\Admin\SAPSController;

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

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'checkrole:admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('admin.dashboard');
        // contoh tambahan route Admin
        // Route::resource('/users', \App\Http\Controllers\Admin\UserController::class);
    });


/*
|--------------------------------------------------------------------------
| Dosen Routes
|--------------------------------------------------------------------------
*/
Route::prefix('dosen')
    ->middleware(['auth', 'checkrole:dosen'])
    ->group(function () {
        Route::get('/dashboard', [DosenHomeController::class, 'index'])->name('dosen.dashboard');
        // contoh tambahan route Dosen
        // Route::get('/jadwal', [\App\Http\Controllers\Dosen\JadwalController::class, 'index'])->name('dosen.jadwal');
    });


/*
|--------------------------------------------------------------------------
| Mahasiswa Routes
|--------------------------------------------------------------------------
*/
Route::prefix('mahasiswa')
    ->middleware(['auth', 'checkrole:mahasiswa'])
    ->group(function () {
        Route::get('/dashboard', [MahasiswaHomeController::class, 'index'])->name('mahasiswa.dashboard');
        // contoh tambahan route Mahasiswa
        // Route::get('/profile', [\App\Http\Controllers\Mahasiswa\ProfileController::class, 'index'])->name('mahasiswa.profile');
    });


/*
|--------------------------------------------------------------------------
| Pimpinan Routes
|--------------------------------------------------------------------------
*/
Route::prefix('pimpinan')
    ->middleware(['auth', 'checkrole:pimpinan'])
    ->group(function () {
        Route::get('/dashboard', [PimpinanHomeController::class, 'index'])->name('pimpinan.dashboard');
        // contoh tambahan route Pimpinan
        // Route::get('/laporan', [\App\Http\Controllers\Pimpinan\LaporanController::class, 'index'])->name('pimpinan.laporan');
    });

// Fakultas
Route::prefix('admin/fakultas')
    ->name('admin.master.fakultas.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(FakultasController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Jurusan
Route::prefix('admin/jurusan')
    ->name('admin.master.jurusan.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(JurusanController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
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
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(ProdiController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
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
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(KelasController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
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
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(RuanganController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
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
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(MakulController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Nilai
Route::prefix('admin/nilai')
    ->name('admin.master.nilai.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(NilaiController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
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
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(TahunAjaranController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
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
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(JadMakulController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    }); 

// Jadwal Mata Kuliah (Data Metopen)
Route::prefix('admin/jadmetopen')
    ->name('admin.jadwal.metopen.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(JadMetopenController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    }); 

// Mahasiswa
Route::prefix('admin/mahasiswa')
    ->name('admin.akun.mahasiwswa.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(MahasiswaController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Jadwal Mahasiswa
Route::prefix('admin/jadmahasiswa')
    ->name('admin.jadwal.mahasiwswa.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(JadMahasiwaController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });


Route::prefix('mahasiswa/jadwal')
    ->name('mahasiswa.jadwal.')
    ->middleware(['auth', 'checkrole:mahasiswa'])
    ->controller(JadwalController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        // fitur tambahan
        Route::get('/feature', 'feature')->name('feature');

        // scan + simpan absensi
        Route::get('/scan/{id}', 'scan')->name('scan');
        Route::post('/simpan', 'simpan')->name('simpan'); // <-- FIX: tanpa {id}
    });


// Absen Mahasiswa
Route::prefix('admin/absmahasiswa')
    ->name('admin.absensi.mahasiwswa.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(AbsMahasiswaController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Blok Mahasiswa
Route::prefix('mahasiswa/blokmahasiswa')
    ->name('mahasiswa.blokmahasiswa.')
    ->middleware(['auth', 'checkrole:mahasiswa'])
    ->controller(BlokMahasiswaController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Dosen
Route::prefix('admin/dosen')
    ->name('admin.akun.dosen.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(DosenController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Jadwal Dosen
Route::prefix('admin/jaddosen')
    ->name('admin.jadwal.dosen.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(JadDosenController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });


// Absen Dosen
Route::prefix('admin/absdosen')
    ->name('admin.absensi.dosen.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(AbsDosenController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Data Skripsi
Route::prefix('admin/skripsi')
    ->name('admin.skripsi.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(SkripsiController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Data Bimbingan Tugas Akhir
Route::prefix('admin/ta')
    ->name('admin.ta.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(TaController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Data Surat Izin
Route::prefix('admin/suratizin')
    ->name('admin.suratizin.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(SuratIzinController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Data Pengajuan
Route::prefix('admin/pengajuan')
    ->name('admin.pengajuan.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(PengajuanController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Data Proposal
Route::prefix('admin/seminarproposal')
    ->name('admin.seminarproposal.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(SeminarProposalController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Data Hasil
Route::prefix('admin/seminarhasil')
    ->name('admin.seminarhasil.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(SeminarHasilController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Data Yudisium
Route::prefix('admin/yudisium')
    ->name('admin.yudisium.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(YudisiumController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Data Surat Aktif Kuliah
Route::prefix('admin/surataktifkuliah')
    ->name('admin.surataktifkuliah.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(SuratAKtifKuliahController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Data SAPS
Route::prefix('admin/saps')
    ->name('admin.saps.')
    ->middleware(['auth', 'checkrole:admin'])
    ->controller(SAPSController::class)
    ->group(function () {
        Route::get('/', 'read')->name('read');
        Route::get('/add', 'add')->name('add');
        Route::get('/feature', 'feature')->name('feature');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

// Absen Dosen di Akun Dosen
Route::prefix('dosen/absendosen')
    ->name('dosen.absen')
    ->middleware(['auth', 'checkrole:dosen'])
    ->controller(AbsenController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'add')->name('add');
        Route::post('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/isi/{id}', 'isi')->name('isi');
        Route::post('/absen/{id}', 'absen')->name('absen');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });