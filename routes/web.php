<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AkadamikController;
use App\Http\Controllers\Admin\AbsensiController;
// use App\Http\Controllers\Mahasiswa\HomeController;
// use App\Http\Controllers\Dosen\HomeController;
// use App\Http\Controllers\Pimpinan\HomeController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('auth.login');
});

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

Route::get('/admin/akademik', function () {
    return view('admin.akademik.index');
});

Route::get('/mahasiswa/akademik', function () {
    return view('mahasiswa.akademik.index');
});

Route::get('/dosen/akademik', function () {
    return view('dosen.akademik.index');
});

Route::get('/admin/absensi', function () {
    return view('admin.absensi.index');
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

Route::get('/admin/penguji', function () {
    return view('admin.penguji.index');
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

Route::get('/admin/proposal', function () {
    return view('admin.proposal.index');
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

Route::get('/admin/seminar', function () {
    return view('admin.seminar.index');
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

Route::get('/admin/ta', function () {
    return view('admin.ta.index');
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

Route::get('/admin/yudisium', function () {
    return view('admin.yudisium.index');
});

Route::get('/mahasiswa/yudisium', function () {
    return view('mahasiswa.yudisium.index');
});

Route::get('/mahasiswa/yudisium/file', function () {
    return view('mahasiswa.yudisium.yudisium');
});