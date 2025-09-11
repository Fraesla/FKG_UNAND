@extends('pimpinan.layouts.app', [
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
                  <h2 class="page-title">Data Tabel Pengajuan Penguji</h2>
              </div>
              <!-- Page title actions -->
      </div>
   </div>
</div>
<!-- END PAGE HEADER -->
<!-- BEGIN PAGE BODY -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>No.BP</th>
                                                <th>No.HP</th>
                                                <th>Dosen Pembimbing 1</th>
                                                <th>Dosen Pembimbing 2</th>
                                                <th>Surat Pengajuan</th>
                                                <th>Judul Skripsi</th>
                                                <th>KRS</th>
                                                <th>status</th>
                                                <th>Nama Dosen</th>
                                                <th class="text-center">Action</th>
                                                <th class="w-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>369</td>
                                                <td class="text-secondary">Testing</td>
                                                <td class="text-secondary">1934289</td>
                                                <td class="text-secondary">08472837</td>
                                                <td class="text-secondary">tester 1</td>
                                                <td class="text-secondary">tester 2</td>
                                                <td class="text-secondary"><a href="#" class="text-reset">test.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">judul.pdf</a></td>
                                                <td class="text-secondary">accepted</td>
                                                <td class="text-secondary"></td>
                                                <td><a href="/pimpinan/penguji/add">Input Dosen</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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