@extends('admin.layouts.app', [
'activePage' => 'suratizin',
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
                  <h2 class="page-title">Data Permohonan Surat Izin Penelitian</h2>
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
                                    Pengeditan Data Permohonan Surat Izin Penelitian
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/suratizin/update/{{$suratizin->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Jenis</label>
                                            <input type="text" placeholder="Masukkan Jenis" class="form-control" name="jenis" value="{{$suratizin->jenis}}" />
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Mahasiswa</label>
                                            <input type="text" placeholder="Masukkan Nama Mahasiswa" class="form-control" name="nama" value="{{$suratizin->nama}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">NO.BP</label>
                                            <input type="text" placeholder="Masukkan No.BP" class="form-control" name="no_bp" value="{{$suratizin->no_bp}}"/>
                                        </div>
                                        <div class="mb-3">
                                          <label class="form-label">Alamat</label>
                                          <textarea class="form-control" data-bs-toggle="autosize"  placeholder="Masukkan Alamat..." style="overflow: hidden; overflow-wrap: break-word; resize: none; text-align: start; height: 56px;" name="alamat" value="{{$suratizin->alamat}}"></textarea>
                                        </div>
                                        <div>
                                            <label class="form-label">Judul Penelitian</label>
                                            <input type="text" placeholder="Masukkan Judul Penelitian" class="form-control" name="judul_penelitian" value="{{$suratizin->judul_penelitian}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Gmail</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text"> @ </span>
                                                <input type="text" class="form-control" placeholder="Masukkan Gmail" value="{{$suratizin->gmail}}" autocomplete="off">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">Dosen Pembimbing 1</label>
                                            <input type="text" placeholder="Masukkan Dosen Pembimbing 1" class="form-control" name="dosen_pembimbing_1" value="{{$suratizin->dosen_pembimbing_1}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Dosen Pembimbing 2</label>
                                            <input type="text" placeholder="Masukkan Dosen Pembimbing 2" class="form-control" name="dosen_pembimbing_2" value="{{$suratizin->dosen_pembimbing_2}}"/>
                                        </div>
                                        <div class="mb-3">
                                          <label class="form-label">Isi Surat</label>
                                          <textarea class="form-control" data-bs-toggle="autosize"  placeholder="Masukkan Isi Surat..." style="overflow: hidden; overflow-wrap: break-word; resize: none; text-align: start; height: 56px;" name="isi_surat" value="{{$suratizin->isi_surat}}"></textarea>
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