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
                  <h2 class="page-title">Data Tabel Daftar Yudisium</h2>
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
                                                <th>Judul Skripsi</th>
                                                <th>Tgl S.Proposal</th>
                                                <th>Tgl S.Hasil</th>
                                                <th>Hasil Turnitin</th>
                                                <th>Lunas UKT</th>
                                                <th>KHS</th>
                                                <th>KBS</th>
                                                <th>B.R.Sempro</th>
                                                <th>B.R.Semhas</th>
                                                <th>Full Skripsi</th>
                                                <th>Penyerahan Skripsi</th>
                                                <th>TOEFL</th>
                                                <th class="text-center">Action</th>
                                                <th class="w-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-secondary">Testing</td>
                                                <td class="text-secondary">1934289</td>
                                                <td class="text-secondary">judul</td>
                                                <td class="text-secondary">11-06-2024</td>
                                                <td class="text-secondary">13-07-2024</td>
                                                <td class="text-secondary"><a href="#" class="text-reset">turniti.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">ukt.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">khs.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">kbs.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">brsempro.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">brsemhas.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">fullskripsi.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">penyerahan.pdf</a></td>
                                                <td class="text-secondary"><a href="#" class="text-reset">toefl.pdf</a></td>
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