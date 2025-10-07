@extends('admin.layouts.app', [
'activePage' => 'yudisium',
'activeDrop' => '',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Seminar Hasil</h2>
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
                                    Pengeditan Data Seminar Hasil
                                </h3>
                                <a href="/admin/yudisium/" class="btn btn-secondary btn-sm">
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
                                <form action="/admin/yudisium/update/{{$yudisium->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Mahasiswa</label>
                                             <select class="form-select" name="id_mahasiswa">
                                                <option>Pilih Data Mahasiswa</option>
                                                @foreach($mahasiswa as $data)
                                                    <option value="{{$data->id}}"
                                                        {{ $yudisium->id_mahasiswa == $data->id ? 'selected' : '' }}>
                                                        No.BP : {{$data->nim}} | Nama Mahasiswa : {{$data->nama}}  
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Judul</label>
                                            <input type="text" placeholder="Masukkan Judul" class="form-control" name="judul" value="{{$yudisium->judul}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Seminar Proposal</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path></svg></span>
                                                <input class="form-control" placeholder="Masukkan Tanggal Seminar Proposal" id="datepicker-icon-prepend" name="tgl_semi_proposal" value="{{$yudisium->tgl_semi_proposal}}">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Seminar Hasil</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path></svg></span>
                                                <input class="form-control" placeholder="Masukkan Tanggal Seminar Hasil" id="datepicker-icon-prepend_2" name="tgl_semi_hasil" value="{{$yudisium->tgl_semi_hasil}}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Hasil Turnitin</label>
                                            <input type="file" name="hasil_turnitin" class="form-control">
                                            @if($yudisium->hasil_turnitin)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$yudisium->hasil_turnitin) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Bukti Lunas</label>
                                            <input type="file" name="bukti_lunas" class="form-control">
                                            @if($yudisium->bukti_lunas)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$yudisium->bukti_lunas) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="mb-3">>
                                            <label class="form-label">KHS</label>
                                            <input type="file" name="khs" class="form-control">
                                            @if($yudisium->khs)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$yudisium->khs) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">KBS</label>
                                            <input type="file" name="kbs" class="form-control">
                                            @if($yudisium->kbs)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$yudisium->kbs) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Brsempro</label>
                                            <input type="file" name="brsempro" class="form-control">
                                            @if($yudisium->brsempro)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$yudisium->brsempro) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Brsemhas</label>
                                            <input type="file" name="brsemhas" class="form-control">
                                            @if($yudisium->brsemhas)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$yudisium->brsemhas) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Full Skripsi</label>
                                            <input type="file" name="full_skripsi" class="form-control">
                                            @if($yudisium->full_skripsi)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$yudisium->full_skripsi) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Matriks</label>
                                            <input type="file" name="matriks" class="form-control">
                                            @if($yudisium->matriks)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$yudisium->matriks) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">TOEFL</label>
                                            <input type="file" name="toefl" class="form-control">
                                            @if($yudisium->toefl)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$yudisium->toefl) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
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