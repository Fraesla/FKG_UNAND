@extends('admin.layouts.app', [
'activePage' => 'gigi',
'activeDrop' => 'jadmakul',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Jadwal Mata Kuliah (Data Blok)</h2>
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
                                    Pengeditan Jadwal Data Mata Kuliah (Data Blok)
                                </h3>
                                <a href="/admin/jadmakul/" class="btn btn-secondary btn-sm">
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
                                <form action="/admin/jadmakul/update/{{$jadmakul->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Blok</label>
                                             <select class="form-select" name="id_kelas">
                                                <option value="">Pilih Blok</option>
                                                @foreach($blok as $data)
                                                <option value="{{$data->id}}" {{ $jadmakul->id_kelas == $data->id ? 'selected' : '' }}>
                                                    {{$data->nama}}
                                                </option>
                                                @endforeach
                                            </select>
                                             @error('id_kelas')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Minggu Ke-</label>
                                                <select name="minggu" class="form-select">
                                                    <option value="0" {{ $jadmakul->minggu == 0 ? 'selected' : '' }}> Pilih minggu
                                                    @for($no=1; $no<=6; $no++)
                                                        <option value="{{ $no }}" {{ old('minggu') == $no ? 'selected' : '' }} {{ $jadmakul->minggu == $no ? 'selected' : '' }}>
                                                            Minggu ke- {{ $no }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                 @error('minggu')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Tanggal</label>
                                                <div class="input-icon">
                                                    <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                        <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                        <path d="M16 3v4"></path>
                                                        <path d="M8 3v4"></path>
                                                        <path d="M4 11h16"></path>
                                                        <path d="M11 15h1"></path>
                                                        <path d="M12 15v3"></path></svg></span>
                                                    <input class="form-control" placeholder="Masukkan Tanggal" id="datepicker-icon-prepend" name="tgl" value="{{$jadmakul->tgl}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Hari</label>
                                                 <select class="form-select" name="hari">
                                                    <option value="">Pilih Hari</option>
                                                    <option value="Senin"  {{ $jadmakul->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                                    <option value="Selasa" {{ $jadmakul->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                                    <option value="Rabu"   {{ $jadmakul->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                                    <option value="Kamis"  {{ $jadmakul->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                                    <option value="Jum'at" {{ $jadmakul->hari == "Jum'at" ? 'selected' : '' }}>Jum'at</option>
                                                </select>
                                                 @error('hari')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Jam Mulai</label>
                                                <input type="time" name="jam_mulai" class="form-control" data-mask="00:00" data-mask-visible="true" placeholder="00:00" autocomplete="off" value="{{$jadmakul->jam_mulai}}">
                                                 @error('jam_mulai')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Jam Selesai</label>
                                                <input type="time" name="jam_selesai" class="form-control" data-mask="00:00" data-mask-visible="true" placeholder="00:00" autocomplete="off" value="{{$jadmakul->jam_selesai}}">
                                                 @error('jam_selesai')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Mata Kuliah</label>
                                                 <select class="form-select" name="id_makul">
                                                    <option>
                                                        Pilih Mata Kuliah
                                                    </option>
                                                    @foreach($makul as $data)
                                                    <option value="{{$data->id}}" 
                                                        {{ $jadmakul->id_makul == $data->id ? 'selected' : '' }}>
                                                        {{$data->nama}} 
                                                    </option>
                                                    @endforeach
                                                </select>
                                                 @error('id_makul')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Dosen</label>
                                                 <select class="form-select" name="id_dosen">
                                                    <option>
                                                        Pilih Dosen
                                                    </option>
                                                    @foreach($dosen as $data)
                                                    <option value="{{$data->id}}"
                                                        {{ $jadmakul->id_dosen == $data->id ? 'selected' : '' }}>
                                                        {{$data->nama}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                 @error('id_dosen')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Ruangan</label>
                                                 <select class="form-select" name="id_ruangan">
                                                    <option>
                                                        Pilih Ruangan
                                                    </option>
                                                    @foreach($ruangan as $data)
                                                    <option value="{{$data->id}}"
                                                        {{ $jadmakul->id_ruangan == $data->id ? 'selected' : '' }}>
                                                        {{$data->nama}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                 @error('id_ruangan')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
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