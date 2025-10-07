@extends('admin.layouts.app', [
'activePage' => 'jadwal',
'activeDrop' => 'jadmetopen',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Jadwal Mata Kuliah (Data Metopen)</h2>
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
                                    Penambahan Data Jadwal Mata Kuliah (Data Metopen)
                                </h3>
                                <a href="/admin/jadmetopen/" class="btn btn-secondary btn-sm">
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
                                <form action="/admin/jadmetopen/create" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <!-- <div>
                                            <label class="form-label">Blok</label>
                                             <select class="form-select" name="id_kelas">
                                                <option>
                                                    Pilih Blok
                                                </option>
                                                @foreach($blok as $data)
                                                <option value="{{$data->id}}">
                                                    {{$data->nama}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div> -->
                                        <div>
                                            <label class="form-label">Minggu ke-</label>
                                            <select class="form-select" name="minggu">
                                                <option value="0">Pilih Minggu ke-
                                                @for($no=1; $no<=6; $no++)
                                                    <option value="{{ $no }}">Minggu Ke-{{ $no }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Hari</label>
                                             <select class="form-select" name="hari">
                                                <option>
                                                    Pilih Hari
                                                </option>
                                                <option value="Senin">Senin</option>
                                                <option value="Selasa">Selasa</option>
                                                <option value="Rabu">Rabu</option>
                                                <option value="Kamis">Kamis</option>
                                                <option value="Jum'at">Jum'at</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Jam Mulai</label>
                                            <input type="text" name="jam_mulai" class="form-control" data-mask="00:00" data-mask-visible="true" placeholder="00:00" autocomplete="off">
                                        </div>
                                        <div>
                                            <label class="form-label">Jam Selesai</label>
                                            <input type="text" name="jam_selesai" class="form-control" data-mask="00:00" data-mask-visible="true" placeholder="00:00" autocomplete="off">
                                        </div>
                                        <div>
                                            <label class="form-label">Mata Kuliah</label>
                                             <select class="form-select" name="id_makul">
                                                <option>Pilih Mata Kuliah</option>
                                                @foreach($makul as $data)
                                                <option value="{{$data->id}}">
                                                    {{$data->nama}}
                                                </option>
                                                @endforeach
                                                <option value="Libur">Libur</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Ruangan</label>
                                             <select class="form-select" name="id_ruangan">
                                                <option>
                                                    Pilih Ruangan
                                                </option>
                                                @foreach($ruangan as $data)
                                                <option value="{{$data->id}}">
                                                    {{$data->nama}}
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