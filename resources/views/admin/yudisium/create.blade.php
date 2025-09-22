@extends('admin.layouts.app', [
'activePage' => 'yudisium',
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
                  <h2 class="page-title">Data Yudisium</h2>
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
                                    Penambahan Data Yudisium
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/yudisium/create" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Nama Mahasiswa</label>
                                            <input type="text" placeholder="Masukkan Nama Mahasiswa" class="form-control" name="nama" />
                                        </div>
                                        <div>
                                            <label class="form-label">NO.BP</label>
                                            <input type="text" placeholder="Masukkan No.BP" class="form-control" name="no_bp" />
                                        </div>
                                        <div>
                                            <label class="form-label">Judul</label>
                                            <input type="text" placeholder="Masukkan Judul" class="form-control" name="judul" />
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Seminar Proposal</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path></svg></span>
                                                <input class="form-control" placeholder="Masukkan Tanggal Seminar Proposal" id="datepicker-icon-prepend" name="tgl_semi_proposal">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Seminar Hasil</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path></svg></span>
                                                <input class="form-control" placeholder="Masukkan Tanggal Seminar Hasil" id="datepicker-icon-prepend_2" name="tgl_semi_hasil">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">Hasil Turnitin</label>
                                            <input type="file" class="form-control" name="hasil_turnitin" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Bukti Lunas</label>
                                            <input type="file" class="form-control" name="bukti_lunas" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">KHS</label>
                                            <input type="file" class="form-control" name="khs" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">KBS</label>
                                            <input type="file" class="form-control" name="kbs" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">BrSempro</label>
                                            <input type="file" class="form-control" name="brsempro" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Brsemhas</label>
                                            <input type="file" class="form-control" name="brsemhas" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Full Skripsi</label>
                                            <input type="file" class="form-control" name="full_skripsi" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Matriks</label>
                                            <input type="file" class="form-control" name="matriks" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">TOEFL</label>
                                            <input type="file" class="form-control" name="toefl" accept=".pdf,.doc,.docx,.jpg,.png"/>
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