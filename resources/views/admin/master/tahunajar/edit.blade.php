@extends('admin.layouts.app', [
'activePage' => 'master',
'activeDrop' => 'tahun',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Tahun Ajaran</h2>
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
                                    Pengeditan Data Tahun Ajaran
                                </h3>
                                <a href="/admin/tahunajar/" class="btn btn-secondary btn-sm">
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
                                <form action="/admin/tahunajar/update/{{$tahunajar->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Tahun Ajaran</label>
                                            <input type="text" placeholder="Masukkan Kode Tahun Ajaran" class="form-control" name="nama" value="{{$tahunajar->nama}}" />
                                            @error('nama')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Level UKT</label>
                                            <select name="ukt" class="form-select @error('ukt') is-invalid @enderror">
                                                <option> Pilih Level UKT</option>
                                                @for($no=1; $no<=8; $no++)
                                                    <option value="{{ $no }}" {{ $tahunajar->ukt == $no ? 'selected' : '' }}>
                                                         {{ $no }}
                                                    </option>
                                                @endfor
                                            </select>
                                            {{-- Pesan Error --}}
                                            @error('ukt')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Semester</label>
                                            <select name="semester" class="form-select @error('semester') is-invalid @enderror">
                                                <option> Pilih Semester</option>
                                                @for($no=1; $no<=8; $no++)
                                                    <option value="Semester {{ $no }}" {{ $tahunajar->semester == 'Semester '.$no ? 'selected' : '' }}>
                                                        Semester {{ $no }}
                                                    </option>
                                                @endfor
                                            </select>
                                            {{-- Pesan Error --}}
                                            @error('semester')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Status</label>
                                            <div>
                                                <label class="form-check form-check-inline">
                                                  <input class="form-check-input" type="radio" name="status"  value="1"
                                                  {{ $tahunajar->status == 1 ? 'checked' : '' }}> 
                                                  <span class="form-check-label">Aktif</span>
                                                </label>
                                                <label class="form-check form-check-inline">
                                                  <input class="form-check-input" type="radio" name="status"  value="0"
                                                  {{ $tahunajar->status == 0 ? 'checked' : '' }}>
                                                  <span class="form-check-label">Noaktif</span>
                                                </label>
                                            </div>
                                            @error('status')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
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