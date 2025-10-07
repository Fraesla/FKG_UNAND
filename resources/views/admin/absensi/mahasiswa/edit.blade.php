@extends('admin.layouts.app', [
'activePage' => 'absensi',
'activeDrop' => 'absmahasiswa',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Absensi Mahasiswa</h2>
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
                                    Pengeditan Data Absensi Mahasiswa
                                </h3>
                                <a href="/admin/absmahasiswa/" class="btn btn-secondary btn-sm">
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
                                <form action="/admin/absmahasiswa/update/{{$absmahasiswa->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
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
                                                <input class="form-control" placeholder="Masukkan Tanggal" id="datepicker-icon-prepend" name="tgl" value="{{$absmahasiswa->tgl}}">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">Jam Masuk</label>
                                            <input type="text" name="jam_masuk" class="form-control" data-mask="00:00" data-mask-visible="true" placeholder="00:00" autocomplete="off" value="{{$absmahasiswa->jam_masuk}}">
                                        </div>
                                        <div>
                                            <label class="form-label">Jam Pulang</label>
                                            <input type="text" name="jam_pulang" class="form-control" data-mask="00:00" data-mask-visible="true" placeholder="00:00" autocomplete="off" value="{{$absmahasiswa->jam_pulang}}">
                                        </div>
                                        <div>
                                            <label class="form-label">Mahasiswa</label>
                                             <select class="form-select" name="id_mahasiswa">
                                                <option>
                                                    Pilih Mahasiswa
                                                </option>
                                                @foreach($mahasiswa as $data)
                                                <option value="{{$data->id}}"
                                                    {{ $absmahasiswa->id_mahasiswa == $data->id ? 'selected' : '' }}>
                                                    {{$data->nama}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="status" value="belum absen">
                                        <div>
                                            <label class="form-label">Jadwal Mahasiswa</label>
                                             <select class="form-select" name="id_jadwal_mahasiswa">
                                                <option>Pilih Jadwal Mahasiswa</option>
                                                @foreach($jadmakul as $data)
                                                    <option value="{{$data->id}}"
                                                        {{ $absmahasiswa->id_jadwal_mahasiswa == $data->id ? 'selected' : '' }}>
                                                        Mata Kuliah: {{$data->nama_makul}} | Ruangan: {{$data->nama_ruangan}}
                                                    </option>
                                                @endforeach
                                            </select>
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