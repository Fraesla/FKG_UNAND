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
                                                <th>No.BP</th>
                                                <th>Nama </th>
                                                <th>No.HP</th>
                                                <th>Pembimbing 1</th>
                                                <th>Pembimbing 2</th>
                                                <th>Penguji 1</th>
                                                <th>Penguji 2</th>
                                                <th>Penguji 3</th>
                                                <th>Surat PUS.Hasil</th>
                                                <th>Draft Skripsi</th>
                                                <th>BIS.Hasil</th>
                                                <th>LCJS.Hasil</th>
                                                <th>Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-secondary">1078423</td>
                                                <td class="text-secondary">testing</td>
                                                <td class="text-secondary">08792734</td>
                                                <td class="text-secondary">contoh 1</td>
                                                <td class="text-secondary">contoh 2</td>
                                                <td class="text-secondary">tester 1</td>
                                                <td class="text-secondary">tester 2</td>
                                                <td class="text-secondary">tester 3</td>
                                                <td class="text-secondary"><a href="#" class="text-reset">surat.pdf</td>
                                                <td class="text-secondary"><a href="#" class="text-reset">Draft.pdf</td>
                                                <td class="text-secondary"><a href="#" class="text-reset">BIS.pdf</td>
                                                <td class="text-secondary"><a href="#" class="text-reset">LCJS.pdf</td>
                                                <td class="text-secondary">accepted</td>
                                                <td><a href="#">Download</a></td>
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