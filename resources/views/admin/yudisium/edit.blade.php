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
                  <h2 class="page-title">Data Seminar Hasil</h2>
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
                                    Pengeditan Data Seminar Hasil
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/yudisium/update/{{$yudisium->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Nama Mahasiswa</label>
                                            <input type="text" placeholder="Masukkan Nama Mahasiswa" class="form-control" name="nama" value="{{$yudisium->nama}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">NO.BP</label>
                                            <input type="text" placeholder="Masukkan No.BP" class="form-control" name="no_bp" value="{{$yudisium->no_bp}}" />
                                        </div>
                                        <div>
                                            <label class="form-label">Judul</label>
                                            <input type="text" placeholder="Masukkan Judul" class="form-control" name="judul" value="{{$yudisium->judul}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Seminar Proposal</label>
                                            <input type="text" placeholder="Masukkan Tanggal Seminar Proposal" class="form-control" name="tgl_semi_proposal" value="{{$yudisium->tgl_semi_proposal}}" />
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Seminar Hasil</label>
                                            <input type="text" placeholder="Masukkan Tanggal Seminar Hasil" class="form-control" name="tgl_semi_hasil" value="{{$yudisium->tgl_semi_hasil}}"/>
                                        </div>
                                         <div>
                                            <label class="form-label">Hasil Turnitin</label>
                                            <input type="text" placeholder="Masukkan Hasil Turnitin" class="form-control" name="hasil_turnitin" value="{{$yudisium->hasil_turnitin}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Bukti Lunas</label>
                                            <input type="text" placeholder="Masukkan Bukti Lunas" class="form-control" name="bukti_lunas" value="{{$yudisium->bukti_lunas}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">KHS</label>
                                            <input type="text" placeholder="Masukkan KHS" class="form-control" name="khs" value="{{$yudisium->khs}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">KBS</label>
                                            <input type="text" placeholder="Masukkan KBS" class="form-control" name="kbs" value="{{$yudisium->kbs}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">BRSempro</label>
                                            <input type="text" placeholder="Masukkan BRSempro" class="form-control" name="brsempro"value="{{$yudisium->brsempro}}" />
                                        </div>
                                        <div>
                                            <label class="form-label">BRSemhas</label>
                                            <input type="text" placeholder="Masukkan BRSemhas" class="form-control" name="brsemhas" value="{{$yudisium->brsemhas}}" />
                                        </div>
                                        <div>
                                            <label class="form-label">Full Skripsi</label>
                                            <input type="text" placeholder="Masukkan Full Skripsi" class="form-control" name="full_skripsi" value="{{$yudisium->full_skripsi}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Matriks</label>
                                            <input type="text" placeholder="Masukkan Matriks" class="form-control" name="matriks" value="{{$yudisium->matriks}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">TOEFL</label>
                                            <input type="text" placeholder="Masukkan TOEFL" class="form-control" name="toefl" value="{{$yudisium->toefl}}"/>
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