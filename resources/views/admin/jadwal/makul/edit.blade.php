@extends('admin.layouts.app', [
'activePage' => 'jadwal',
'activeDrop' => 'makul',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Jadwal Mata Kuliah</h2>
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
                                    Pengeditan Jadwal Data Mata Kuliah
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/jadmakul/update/{{$jadmakul->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Tahun Ajaran</label>
                                             <select class="form-select" name="id_tahun_ajaran">
                                                <option>
                                                    Pilih Tahun Ajaran
                                                </option>
                                                @foreach($tahunajar as $data)
                                                <option value="{{$data->id}}" 
                                                    {{ $jadmakul->id_tahun_ajaran == $data->id ? 'selected' : '' }}>
                                                    {{$data->nama}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Hari</label>
                                             <select class="form-select" name="hari">
                                                <option disabled>Pilih Hari</option>
                                                <option value="Senin"  {{ $jadmakul->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                                <option value="Selasa" {{ $jadmakul->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                                <option value="Rabu"   {{ $jadmakul->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                                <option value="Kamis"  {{ $jadmakul->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                                <option value="Jum\'at" {{ $jadmakul->hari == "Jum'at" ? 'selected' : '' }}>Jum'at</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Jam Mulai</label>
                                            <input type="text" name="jam_mulai" class="form-control" data-mask="00:00" data-mask-visible="true" placeholder="00:00" autocomplete="off" value="{{$jadmakul->jam_mulai}}">
                                        </div>
                                        <div>
                                            <label class="form-label">Jam Selesai</label>
                                            <input type="text" name="jam_selesai" class="form-control" data-mask="00:00" data-mask-visible="true" placeholder="00:00" autocomplete="off" value="{{$jadmakul->jam_selesai}}">
                                        </div>
                                        <div>
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
                                        </div>
                                        <div>
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