@extends('mahasiswa.layouts.app', [
'activePage' => 'dashboard',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Seminar Proposal</h2>
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
                                    Pengisian Seminar Proposal
                                </h3>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">No.BP</label>
                                            <input type="text" placeholder="Masukkan No.BP" class="form-control"
                                            />
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" placeholder="Masukkan Nama Lengkap" class="form-control"
                                            />
                                        </div>
                                        <div>
                                            <label class="form-label">No.HP</label>
                                            <input type="text" placeholder="Masukkan No.HP" class="form-control"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Pembimbing 1</label>
                                            <input type="text" placeholder="Masukkan Nama Pembimbing 1" class="form-control"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Pembimbing 2</label>
                                            <input type="text" placeholder="Masukkan Nama Pembimbing 2" class="form-control"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Penguji 1</label>
                                            <input type="text" placeholder="Masukkan Nama Penguji 1" class="form-control"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Penguji 2</label>
                                            <input type="text" placeholder="Masukkan Nama Penguji 2" class="form-control"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Penguji 3</label>
                                            <input type="text" placeholder="Masukkan Nama Penguji 3" class="form-control"/>
                                        </div>
                                        <div class="mb-3">
                                          <div class="form-label">Surat Permohonan Undangan Seminar Proposal</div>
                                          <input type="file" class="form-control" />
                                        </div>
                                        <div class="mb-3">
                                          <div class="form-label">File Draft Skripsi</div>
                                          <input type="file" class="form-control" />
                                        </div>
                                        <div class="mb-3">
                                          <div class="form-label">Bukti Izin Seminar Proposal</div>
                                          <input type="file" class="form-control" />
                                        </div>
                                        <div class="mb-3">
                                          <div class="form-label">Lembar ceklis Jadwal Seminar Proposal</div>
                                          <input type="file" class="form-control" />
                                        </div>
                                        <div>
                                            <a href="/mahasiswa/proposal/seminar" class="btn btn-primary btn-4 w-100">
                                                Simpan
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/arrow-right -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="icon icon-right icon-2">
                                                    <path d="M5 12l14 0" />
                                                    <path d="M13 18l6 -6" />
                                                    <path d="M13 6l6 6" />
                                                </svg>
                                            </a>
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