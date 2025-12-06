@extends('admin.layouts.app', [
'activePage' => 'absensi',
'activeDrop' => 'absdosen',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Absensi Dosen</h2>
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
              <!-- Page title actions -->
      </div>
   </div>
</div>
<!-- END PAGE HEADER -->
<!-- BEGIN PAGE BODY -->
<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">🧾 Edit Absensi</h3>
                        <a href="{{ url('/admin/absdosen') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left-circle"></i> Back
                        </a>
                    </div>

                    <div class="card-body bg-dark text-light rounded-bottom">
                        <div class="text-center mb-4">
                            <h4 class="text-info mb-2">Detail Absensi Dosen</h4>
                            <p class="text-muted mb-0">NIP: <strong>{{ $absdosen->nip_dosen ?? '-' }}</strong></p>
                            <p class="text-muted mb-0">Dosen: <strong>{{ $absdosen->nama_dosen ?? '-' }}</strong></p>
                        </div>

                        <div class="mb-3">
                            <p>📘 <strong>Prodi:</strong> {{ $absdosen->prodi ?? '-' }}</p>
                            <p>📘 <strong>Kode Kuliah:</strong> {{ $absdosen->kode_makul ?? '-' }}</p>
                            <p>📘 <strong>Mata Kuliah:</strong> {{ $absdosen->nama_makul ?? '-' }}</p>
                            <p>📅 <strong>Tanggal:</strong> {{ $absdosen->tgl }}</p>
                            <p>🕗 <strong>Jam Masuk:</strong> {{ $absdosen->jam_masuk ?? '-' }}</p>
                            <p>🕙 <strong>Jam Pulang:</strong> {{ $absdosen->jam_pulang ?? '-' }}</p>
                            <p>🏫 <strong>Ruangan:</strong> {{ $absdosen->ruangan ?? '-' }}</p>
                        </div>

                        <hr class="border-secondary">

                        <form method="POST" action="/admin/absdosen/update/{{$absdosen->id}}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3 text-start">
                                <label for="status" class="form-label text-light">Status Kehadiran</label>
                                <select name="status" id="status" class="form-select bg-dark text-light border-secondary">
                                    <option value="">Pilih Status Kehadiran</option>
                                    <option value="belum absen" {{ $absdosen->status == 'belum absen' ? 'selected' : '' }}>Belum Absen</option>
                                    <option value="hadir" {{ $absdosen->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="izin" {{ $absdosen->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ $absdosen->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="alfa" {{ $absdosen->status == 'alfa' ? 'selected' : '' }}>Alfa</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 text-start">
                                <label for="keterangan" class="form-label text-light">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="3" 
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="Masukkan keterangan jika izin atau sakit...">{{ $absdosen->keterangan }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div> {{-- end card body --}}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE BODY -->
@endsection