@extends('mahasiswa.layouts.app', ['activePage' => 'dashboard'])

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">

            <!-- KOLOM KIRI -->
            <div class="col">
                <div class="page-pretitle">Aplikasi FKG</div>
                <h2 class="page-title">Edit Profile Mahasiswa</h2>
            </div>

            <!-- KOLOM KANAN (BACK BUTTON) -->
            <div class="col-auto">
                <a href="/mahasiswa/dashboard/" class="btn btn-secondary btn-sm d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icon-tabler-arrow-left me-1">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M5 12l14 0" />
                        <path d="M5 12l6 6" />
                        <path d="M5 12l6 -6" />
                    </svg>
                    Back
                </a>
            </div>

        </div>
        @include('components.alert')
        @if ($errors->any())
            <div id="alert-error" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                <strong>⚠️ Terjadi Kesalahan pada Pengisian Formulir:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <div class="progress position-absolute bottom-0 start-0 w-100" style="height: 3px;">
                    <div id="progress-bar-error" class="progress-bar bg-danger" role="progressbar"></div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .profile-box {
        background: #111827;
        padding: 20px;
        border-radius: 14px;
        border: 1px solid #1f2937;
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
    }
    .profile-label {
        font-size: 15px;
        color: #9ca3af;
        font-weight: 500;
        padding: 6px 0;
        white-space: nowrap;
    }
    .profile-value {
        font-size: 16px;
        font-weight: 600;
        color: #fff;
        padding: 6px 0;
    }
    isi {
        font-size: 16px;
        font-weight: 600;
        color: #fff;
    }

    /* Hidden table styling */
    .info-table {
        width: 100%;
        border-collapse: collapse;
    }
    .info-table td {
        padding: 4px 6px;
        vertical-align: top;
    }
</style>

<div class="page-body">
    <div class="container-xl">

        <div class="row">

            <!-- ================= LEFT PANEL — DETAIL MAHASISWA ================= -->
            <div class="col-md-6">
                <div class="profile-box mb-3">

                    <!-- FOTO + NAMA -->
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $mahasiswa->foto ? asset('storage/'.$mahasiswa->foto) : asset('assets/images/default-fkg.jpg') }}"
                             width="70" height="70" class="rounded-circle me-3"
                             style="border: 2px solid #4b5563; object-fit: cover;">
                        <div>
                            <div class="text-white fw-bold" style="font-size:18px;">
                                Nama : <isi>{{ $mahasiswa->nama ?? '-'}}</isi>
                            </div>
                            <div class="text-muted" style="font-size: 14px">
                                No.BP : <isi>{{ $mahasiswa->nobp ?? '-'}}</isi>
                            </div>
                        </div>
                    </div>

                    <!-- ===================== TABEL HIDDEN RAPI ===================== -->
                    <table class="info-table">

                        <tr>
                            <td class="profile-label">Jenis Kelamin</td>
                            <td class="profile-value">: &nbsp;&nbsp;<isi>{{ $mahasiswa->gender ?? '-'}}</isi></td>
                        </tr>

                        <tr>
                            <td class="profile-label">Level UKT</td>
                            <td class="profile-value">: &nbsp;&nbsp;<isi>{{ $mahasiswa->ukt ?? '-'}}</isi></td>
                        </tr>

                        <tr>
                            <td class="profile-label">Semester</td>
                            <td class="profile-value">: &nbsp;&nbsp;<isi>{{ $mahasiswa->semester ?? '-'}}</isi></td>
                        </tr>

                        <tr>
                            <td class="profile-label">Tahun Ajaran</td>
                            <td class="profile-value">: &nbsp;&nbsp;<isi>{{ $mahasiswa->tahun_ajaran ?? '-'}}</isi></td>
                        </tr>

                        <tr>
                            <td class="profile-label">Kontak</td>
                            <td class="profile-value">: &nbsp;&nbsp;<isi>{{ $mahasiswa->contact ?? '-'}}</isi></td>
                        </tr>

                        <tr>
                            <td class="profile-label">Alamat</td>
                            <td class="profile-value">: &nbsp;&nbsp;<isi>{{ $mahasiswa->alamat ?? '-'}}</isi></td>
                        </tr>

                        <tr>
                            <td class="profile-label">Status</td>
                            <td class="profile-value">: &nbsp;
                                <isi>
                                        <?php if ($mahasiswa->status=="1"): ?>
                                            Aktif
                                        <?php else: ?>
                                            Noaktif
                                        <?php endif ?>
                                </isi>
                            </td>
                        </tr>

                    </table>

                    <hr class="text-secondary">

                    <div class="text-muted" style="font-size:12px;">
                        *Informasi ini hanya untuk tampilan. Untuk mengubah data, gunakan form sebelah kanan.
                    </div>

                </div>
            </div>  

            <!-- ================= RIGHT PANEL — EDIT FORM ================= -->
            <div class="col-md-6">
                <div class="profile-box">

                    <form action="/mahasiswa/update/{{$mahasiswa->id}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- @method('PUT') -->

                        <h4 class="text-white mb-3">Form Edit Profile</h4>

                        <!-- FOTO -->
                        <div class="mb-3">
                            <div class="form-label text-white">Foto Profile</div>
                            {{-- tampilkan foto lama kalau ada --}}
                            @if($mahasiswa->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$mahasiswa->foto) }}" alt="Foto mahasiswa" width="120" class="img-thumbnail">
                                </div>
                            @endif
                            {{-- input upload foto baru --}}
                            <input type="file" class="form-control" accept="image/*" name="foto">
                        </div>

                        <div class="row">
                            <!-- NAMA -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-white">Nama Mahasiswa</label>
                                <input type="text" name="nama" class="form-control" value="{{ $mahasiswa->nama }}">
                                @error('nama')
                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                @enderror
                            </div>

                            <!-- No.BP -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-white">No.BP</label>
                                <input type="text" name="nobp" class="form-control" value="{{ $mahasiswa->nobp }}">
                                @error('nobp')
                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                @enderror
                            </div>

                            <!-- GENDER -->
                            <div class="col-md-4 mb-3">
                                <div class="form-label text-white">Jenis Kelamin</div>
                                <div>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="Laki-Laki" {{ $mahasiswa->gender == 'Laki-Laki' ? 'checked' : '' }}>
                                        <span class="form-check-label">Laki-Laki</span>
                                    </label>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="Perempuan" {{ $mahasiswa->gender == 'Perempuan' ? 'checked' : '' }}>
                                        <span class="form-check-label">Perempuan</span>
                                    </label>
                                </div>
                                @error('gender')
                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kontak -->
                        <div class="mb-3">
                            <label class="form-label text-white">Kontak</label>
                            <input type="text" name="contact" class="form-control" value="{{ $mahasiswa->contact }}">
                            @error('contact')
                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                             @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label class="form-label text-white">Alamat</label>
                            <textarea name="alamat" rows="3" class="form-control">{{ $mahasiswa->alamat }}</textarea>
                        </div>

                        <!-- Tahun Ajaran -->
                            <div class="col-md-10 mb-3 ">
                                <label class="form-label">Tahun Ajaran</label>
                                <select class="form-select" name="id_tahun_ajaran">
                                    <option>Pilih Tahun Ajaran</option>
                                    @foreach($tahun as $data)
                                        <option value="{{$data->id}}" {{ $mahasiswa->id_tahun_ajaran == $data->id ? 'selected' : '' }}>
                                            Tahun Ajaran : {{$data->nama}} |Level UKT : {{$data->ukt}}|Semester : {{$data->semester}} | Status : 
                                            <?php if ($data->status=="1"): ?>
                                                Aktif
                                            <?php else: ?>
                                                Noaktif
                                            <?php endif ?>
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_tahun_ajaran')
                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                @enderror
                            </div>

                        <button type="submit" class="btn btn-primary w-100 mt-2">
                            Simpan Perubahan
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection