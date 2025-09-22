@extends('admin.layouts.app', [
'activePage' => 'seminarproposal',
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
                  <h2 class="page-title">Data Seminar Proposal</h2>
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
                                    Penambahan Data Seminar Proposal
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/seminarproposal/create" method="POST" enctype="multipart/form-data">
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
                                            <label class="form-label">No.HP</label>
                                            <input type="text" placeholder="Masukkan No.HP" class="form-control" name="no_hp" />
                                        </div>
                                        <div>
                                            <label class="form-label">Dosen Pembimbing 1</label>
                                            <input type="text" placeholder="Masukkan Dosen Pembimbing 1" class="form-control" name="dosen_pembimbing_1" />
                                        </div>
                                        <div>
                                            <label class="form-label">Dosen Pembimbing 2</label>
                                            <input type="text" placeholder="Masukkan Dosen Pembimbing 2" class="form-control" name="dosen_pembimbing_2" />
                                        </div>
                                         <div>
                                            <label class="form-label">Penguji 1</label>
                                            <input type="text" placeholder="Masukkan Penguji 1" class="form-control" name="penguji_1" />
                                        </div>
                                        <div>
                                            <label class="form-label">Penguji 2</label>
                                            <input type="text" placeholder="Masukkan Penguji 2" class="form-control" name="penguji_2" />
                                        </div>
                                        <div>
                                            <label class="form-label">Penguji 3</label>
                                            <input type="text" placeholder="Masukkan Penguji 3" class="form-control" name="penguji_3" />
                                        </div>
                                        <div>
                                            <label class="form-label">Surat Seminar Proposal</label>
                                            <input type="file" class="form-control" name="surat_proposal" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">File Draft Skripsi</label>
                                            <input type="file" class="form-control" name="file_draft" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Bukti Izin</label>
                                            <input type="file" class="form-control" name="bukti_izin" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Lembar Jadwal</label>
                                            <input type="file" class="form-control" name="lembar_jadwal" accept=".pdf,.doc,.docx,.jpg,.png"/>
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