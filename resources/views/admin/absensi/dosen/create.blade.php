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
                            <div class="card-header">
                                <h3 class="card-title">
                                    Penambahan Data Absensi Dosen
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/absdosen/create" method="POST" enctype="multipart/form-data">
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
                                                <input class="form-control" placeholder="Masukkan Tanggal" id="datepicker-icon-prepend" name="tgl">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">Jam Masuk</label>
                                            <input type="text" name="jam_masuk" class="form-control" data-mask="00:00" data-mask-visible="true" placeholder="00:00" autocomplete="off">
                                        </div>
                                        <div>
                                            <label class="form-label">Jam Pulang</label>
                                            <input type="text" name="jam_pulang" class="form-control" data-mask="00:00" data-mask-visible="true" placeholder="00:00" autocomplete="off">
                                        </div>
                                        <div>
                                            <label class="form-label">Dosen</label>
                                             <select class="form-select" name="id_dosen">
                                                <option>
                                                    Pilih Dosen
                                                </option>
                                                @foreach($dosen as $data)
                                                <option value="{{$data->id}}">
                                                    {{$data->nama}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Jadwal Dosen</label>
                                             <select class="form-select" name="id_jadwal_dosen">
                                                <option>Pilih Jadwal Dosen</option>
                                                @foreach($jadmakul as $data)
                                                    <option value="{{$data->id}}">
                                                        Mata Kuliah: {{$data->nama_makul}} | Ruangan: {{$data->nama_ruangan}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="status" value="belum absen">
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