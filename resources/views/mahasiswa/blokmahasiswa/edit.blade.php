@extends('mahasiswa.layouts.app', [
'activePage' => 'blokmahasiswa',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Blok Mahasiswa</h2>
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
                                    Pengeditan Data Blok Mahasiswa
                                </h3>
                                <a href="/mahasiswa/blokmahasiswa/" class="btn btn-secondary btn-sm">
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
                                <form action="/mahasiswa/blokmahasiswa/update/{{$blok->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Blok</label>
                                            <select class="form-select" name="id_blok">
                                                <option>
                                                    Pilih Nama Blok
                                                </option>
                                                @foreach($kelas as $data)
                                                <option value="{{ $data->id }}" 
                                                    {{ $blok->id_blok == $data->id ? 'selected' : '' }}>
                                                    {{ $data->nama }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Tahun Ajaran</label>
                                            <select class="form-select" name="id_tahun_ajaran">
                                                <option>
                                                    Pilih Tahun Ajaran
                                                </option>
                                                @foreach($tahun_ajaran as $data)
                                                <option value="{{ $data->id }}" 
                                                    {{ $blok->id_tahun_ajaran == $data->id ? 'selected' : '' }}>
                                                    Tahun Ajaran : {{ $data->nama }} | Semester : {{$data->semester}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Mahasiswa</label>
                                            <select class="form-select" name="id_mahasiswa">
                                                <option>
                                                    Pilih Mahasiswa
                                                </option>
                                                @foreach($mahasiswa as $data)
                                                <option value="{{ $data->id }}" 
                                                    {{ $blok->id_mahasiswa == $data->id ? 'selected' : '' }}>
                                                    NIM : {{$data->nim}}| Nama Mahasiswa : {{ $data->nama }}
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