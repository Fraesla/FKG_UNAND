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
                  <h2 class="page-title">Data Tabel Absensi Perkuliahan</h2>
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
                                                <th>Tahun Ajaran</th>
                                                <th>Nama Blok</th>
                                                <th>Jenis Perkuliahan</th>
                                                <th>Nama Dosen</th>
                                                <th>Nilai</th>
                                                <th class="text-center">Action</th>
                                                <th class="w-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-secondary">2025-2026</td>
                                                <td class="text-secondary">Block T</td>
                                                <td class="text-secondary">Coba</td>
                                                <td class="text-secondary">Testing</td>
                                                <td class="text-secondary"></td>
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