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
                                    Pengeditan Data Aktif Kuliah
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/surataktifkuliah/update/{{$surataktifkuliah->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Nama </label>
                                            <input type="text" placeholder="Masukkan Nama " class="form-control" name="nama" value="{{$surataktifkuliah->nama}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">NIP </label>
                                            <input type="text" placeholder="Masukkan NIP " class="form-control" name="nip" value="{{$surataktifkuliah->nip}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Pangkat / Golongan </label>
                                            <input type="text" placeholder="Masukkan Pangkat / Golongan " class="form-control" name="pango" value="{{$surataktifkuliah->pango}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Jabatan </label>
                                            <input type="text" placeholder="Masukkan Jabatan " class="form-control" name="jabatan" value="{{$surataktifkuliah->jabatan}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Mahasiswa</label>
                                            <input type="text" placeholder="Masukkan Nama Mahasiswa" class="form-control" name="nama_mhs" value="{{$surataktifkuliah->nama_mhs}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Tempat Lahir </label>
                                            <input type="text" placeholder="Masukkan Tempat Lahir Mahasiswa" class="form-control" name="tmp_lahir_mhs" value="{{$surataktifkuliah->tmp_lahir_mhs}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Lahir Mahasiswa</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path></svg></span>
                                                <input class="form-control" placeholder="Masukkan Tanggal Lahir Mahasiswa" id="datepicker-icon-prepend" name="tgl_lahir" value="{{$surataktifkuliah->tgl_lahir_mhs}}">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">NO.BP</label>
                                            <input type="text" placeholder="Masukkan No.BP" class="form-control" name="no_bp" value="{{$surataktifkuliah->no_bp}}" />
                                        </div>
                                        <div>
                                            <label class="form-label">Semester</label>
                                            <input type="text" placeholder="Masukkan Semester" class="form-control" name="semester" value="{{$surataktifkuliah->semester}}" />
                                        </div>
                                        <div>
                                            <label class="form-label">Tahun Akademik</label>
                                            <input type="text" placeholder="Masukkan Tahun Akademik" class="form-control" name="tahun_akademik" value="{{$surataktifkuliah->tahun_akademik}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Orang Tua / Wali</label>
                                            <input type="text" placeholder="Masukkan Nama Orang Tua / Wali" class="form-control" name="nama_ort" value="{{$surataktifkuliah->nama_ort}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Tempat Lahir </label>
                                            <input type="text" placeholder="Masukkan Tempat Lahir Orang Tua/ Wali" class="form-control" name="tmp_lahir_ort" value="{{$surataktifkuliah->tmp_lahir_ort}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Lahir </label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path></svg></span>
                                                <input class="form-control" placeholder="Masukkan Tanggal Lahir Orang Tua" id="datepicker-icon-prepend_2" name="tgl_lahir_ort" value="{{$surataktifkuliah->tgl_lahir_ort}}">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">NIP </label>
                                            <input type="text" placeholder="Masukkan NIP" class="form-control" name="nip_ort" value="{{$surataktifkuliah->nip_ort}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Pangkat / Golongan </label>
                                            <input type="text" placeholder="Masukkan Pangkat / Golongan" class="form-control" name="pango_ort" value="{{$surataktifkuliah->pango_ort}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Jabatan </label>
                                            <input type="text" placeholder="Masukkan Jabatan" class="form-control" name="jabatan_ort" value="{{$surataktifkuliah->jabatan_ort}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Instansi </label>
                                            <input type="text" placeholder="Masukkan Instansi" class="form-control" name="instansi_ort" value="{{$surataktifkuliah->instansi_ort}}"/>
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