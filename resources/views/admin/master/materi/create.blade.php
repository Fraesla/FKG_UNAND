@extends('admin.layouts.app', [
'activePage' => 'master',
'activeDrop' => 'materi',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <div class="page-pretitle">Aplikasi FKG</div>
            <h2 class="page-title">Data Materi</h2>

            {{-- Flash Message Error (Validasi) --}}
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
   </div>
</div>
<!-- END PAGE HEADER -->

<!-- BEGIN PAGE BODY -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards row-cols-1 row-cols-md-12">
            <div class="col">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Penambahan Data Materi</h3>
                                <a href="/admin/materi/" class="btn btn-secondary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="icon icon-tabler icon-tabler-arrow-left">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l14 0" />
                                        <path d="M5 12l6 6" />
                                        <path d="M5 12l6 -6" />
                                    </svg>
                                    Back
                                </a>
                            </div>

                            <div class="card-body">
                                <form action="/admin/materi/create" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">

                                        {{-- Judul --}}
                                        <div>
                                            <label class="form-label">Judul Materi</label>
                                            <input type="text" placeholder="Masukkan Judul Materi" class="form-control" name="judul" />
                                            @error('judul')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Jadwal Blok --}}
                                        <div class="mb-3">
                                            <label class="form-label">Pilih Jadwal Blok</label>
                                            <select class="form-select" id="jadwal_blok" name="id_jadwal_blok">
                                                <option value="">-- Pilih Jadwal Blok --</option>
                                                @foreach ($jadwalBlok as $blok)
                                                    <option value="{{ $blok->id }}">{{ $blok->hari }} - {{ $blok->jam_mulai }} s/d {{ $blok->jam_selesai }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Jadwal Metopen --}}
                                        <div class="mb-3">
                                            <label class="form-label">Pilih Jadwal Metopen</label>
                                            <select class="form-select" id="jadwal_metopen" name="id_jadwal_metopen">
                                                <option value="">-- Pilih Jadwal Metopen --</option>
                                                @foreach ($jadwalMetopen as $metopen)
                                                    <option value="{{ $metopen->id }}">{{ $metopen->hari }} - {{ $metopen->jam_mulai }} s/d {{ $metopen->jam_selesai }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Upload Materi --}}
                                        <div class="mb-3">
                                          <div class="form-label">Materi</div>
                                          <input type="file" class="form-control" accept="file/*" name="file">
                                        </div>

                                        {{-- Tombol Simpan --}}
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-4 w-100">
                                                Simpan
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="icon icon-right icon-2">
                                                    <path d="M5 12l14 0" />
                                                    <path d="M13 18l6 -6" />
                                                    <path d="M13 6l6 6" />
                                                </svg>
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT: Disable salah satu select --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const blok = document.getElementById('jadwal_blok');
    const metopen = document.getElementById('jadwal_metopen');

    blok.addEventListener('change', function () {
        if (this.value) {
            metopen.disabled = true;
        } else {
            metopen.disabled = false;
        }
    });

    metopen.addEventListener('change', function () {
        if (this.value) {
            blok.disabled = true;
        } else {
            blok.disabled = false;
        }
    });
});
</script>
@endsection