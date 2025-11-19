@extends('admin.layouts.app', [
'activePage' => 'surataktifkuliah',
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
                  <h2 class="page-title">Data Surat Aktif Kuliah</h2>
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
                                    Penambahan Data Surat Aktif Kuliah
                                </h3>
                                <a href="/admin/surataktifkuliah/" class="btn btn-secondary btn-sm">
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
                                <form action="/admin/surataktifkuliah/create" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Dosen</label>
                                            <select class="form-select" name="dosen_data">
                                                <option value="">Pilih Data Dosen</option>
                                                @foreach($dosen as $data)
                                                    <option value='@json($data)'>
                                                        NIP: {{$data->nip}} | Nama Dosen : {{$data->nama}} | Pangkat & Golongan : {{$data->pangol}} | Jabatan : {{$data->jf}} 
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('dosen_data')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="row">
                                             <div class="col-md-4">
                                                <label class="form-label">Mahasiswa</label>
                                                <select class="form-select" name="mahasiswa_data">
                                                    <option value="">Pilih Data Mahasiswa</option>
                                                    @foreach($mahasiswa as $data)
                                                        <option value='@json($data)'>
                                                            NO.BP: {{$data->nobp}} | Nama Mahasiswa : {{$data->nama}} | Semester : {{$data->ukt}} | Tahun Akademik : {{$data->tahun_ajaran}} 
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('mahasiswa_data')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
                                            </div> 
                                            <div class="col-md-8">
                                                <label class="form-label">Tempat & Tanggal Lahir Mahasiswa</label>
                                                <div class="row g-2">
                                                    <!-- Tempat Lahir -->
                                                    <div class="col-md-6">
                                                        <input type="text" 
                                                               class="form-control" 
                                                               name="tmp_lahir_mhs" 
                                                               placeholder="Masukkan Tempat Lahir Mahasiswa">
                                                        @error('tmp_lahir_mhs')
                                                            <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Tanggal Lahir -->
                                                    <div class="col-md-6 input-icon">
                                                        <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                            <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path></svg></span>
                                                        <input class="form-control" placeholder="Masukkan Tanggal Lahir Mahasiswa" id="datepicker-icon-prepend_2" name="tgl_lahir_mhs">
                                                        @error('tgl_lahir_mhs')
                                                            <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Nama Orang Tua / Wali</label>
                                                <input type="text" placeholder="Masukkan Nama Orang Tua / Wali" class="form-control" name="nama_ort" />
                                                @error('nama_ort')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label">Tempat & Tanggal Lahir Orang Tua / Wali</label>
                                                <div class="row g-2">
                                                    <!-- Tempat Lahir -->
                                                    <div class="col-md-6">
                                                        <input type="text" 
                                                               class="form-control" 
                                                               name="tmp_lahir_ort" 
                                                               placeholder="Masukkan Tempat Lahir Orang Tua / Wali">
                                                        @error('tmp_lahir_ort')
                                                            <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Tanggal Lahir -->
                                                    <div class="col-md-6 input-icon">
                                                        <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                            <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path></svg></span>
                                                        <input class="form-control" placeholder="Masukkan Tanggal Lahir Orang Tua / Wali" id="datepicker-icon-prepend_2" name="tgl_lahir_ort">
                                                        @error('tgl_lahir_ort')
                                                            <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">NIP </label>
                                                <input type="text" placeholder="Masukkan NIP" class="form-control" name="nip_ort" />
                                                @error('nip_ort')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Pangkat & Golongan</label>
                                                <div class="row g-2">
                                                    <!-- Pangkat -->
                                                    <div>
                                                        <input type="text" 
                                                               class="form-control" 
                                                               name="pango_ort" 
                                                               placeholder="Masukkan Pangkat & Golongan">
                                                    </div>
                                                </div>
                                                @error('pango_ort')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Jabatan </label>
                                                <input type="text" placeholder="Masukkan Jabatan" class="form-control" name="jabatan_ort" />
                                                @error('jabatan_ort')
                                                    <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Instansi </label>
                                                <input type="text" placeholder="Masukkan Instansi" class="form-control" name="instansi_ort" />
                                                @error('instansi_ort')
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