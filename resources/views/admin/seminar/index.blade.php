@extends('admin.layouts.app', [
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
                  <h2 class="page-title">Data Tabel Daftar Seminar Hasil</h2>
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
                                                <th>Pembimbing 1</th>
                                                <th>Pembimbing 2</th>
                                                <th>Penguji 1</th>
                                                <th>Penguji 2</th>
                                                <th>Penguji 3</th>
                                                <th>Surat undangan</th>
                                                <th>Draft Skripsi</th>
                                                <th>Bukti Izin</th>
                                                <th>Jadwal Seminar</th>
                                                <th class="text-center">Action</th>
                                                <th class="w-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-secondary">Testing</td>
                                                <td class="text-secondary">1934289</td>
                                                <td class="text-secondary">08472837</td>
                                                <td class="text-secondary">contoh 1</td>
                                                <td class="text-secondary">contoh 2</td>
                                                <td class="text-secondary">tester 1</td>
                                                <td class="text-secondary">tester 2</td>
                                                <td class="text-secondary">tester 3</td>
                                                <td class="text-secondary"><a href="#" class="text-reset">surat.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">draft.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">bukti.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">jadwal.pdf</a></td>
                                                <td><a href="#">Edit</a></td>
                                                <td><a href="#">Delete</a></td>
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