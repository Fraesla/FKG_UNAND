@extends('dosen.layouts.app', [
'activePage' => 'jadwal',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Absen Mahasiswa</h2>
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
        <div class="row row-cards row-cols-1 row-cols-md-12">
            <div class="col">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">
                                    Penambahan Data Absensi Mahasiswa
                                </h3>
                                <a href="/dosen/absendosen/isi/{{$absen->id}}" class="btn btn-secondary btn-sm">
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
                                <form action="/dosen/absendosen/create/{{$absen->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <input type="hidden" name="tgl" value="{{ $absen->tgl }}">
                                        <input type="hidden" name="jam_masuk" value="{{ $absen->jam_masuk }}">
                                        <input type="hidden" name="jam_pulang" value="{{ $absen->jam_pulang }}">
                                        <div>
                                            <label class="form-label">Nama Mahasiswa</label>
                                             <select class="form-select" name="id_mahasiswa">
                                                <option>
                                                    Pilih Nama Mahasiswa
                                                </option>
                                                @foreach($mahasiswa as $data)
                                                <option value="{{$data->id}}">
                                                    {{$data->nama}}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('id_mahasiswa')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <input type="hidden" name="id_jadwal_mahasiswa" value="{{ $absen->id_jadwal_dosen }}">
                                        <div class="row">
                                            <!-- Kolom Minggu -->
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status Kehadiran</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="hadir">Hadir</option>
                                                    <option value="izin">Izin</option>
                                                    <option value="sakit">Sakit</option>
                                                    <option value="alfa">Alfa</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="keterangan" class="form-label">Keterangan</label>
                                                <textarea name="keterangan" id="keterangan" class="form-control" rows="2" placeholder="Isi keterangan jika status kehadiran nya dipilih izin atau sakit "></textarea>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-4 w-100">
                                                Simpan
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/arrow-right -->
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
<!-- END PAGE BODY -->
@endsection