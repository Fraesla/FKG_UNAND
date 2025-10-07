@extends('dosen.layouts.app', [
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
                  <h2 class="page-title">Data Tabel Surat Izin Penilitian</h2>
                  @include('components.alert')
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
                                                <th>ALamat</th>
                                                <th>Judul</th>
                                                <th>Gmail</th>
                                                <th>No.HP</th>
                                                <th>Pembimbing 1</th>
                                                <th>Pembimbing 2</th>
                                                <th>Isi Surat</th>
                                                <th>Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-secondary">1078423</td>
                                                <td class="text-secondary">testing</td>
                                                <td class="text-secondary">terserah</td>
                                                <td class="text-secondary">judul teliti</td>
                                                <td class="text-secondary">test@test</td>
                                                <td class="text-secondary">08792734</td>
                                                <td class="text-secondary">tester 1</td>
                                                <td class="text-secondary">tester 2</td>
                                                <td class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</td>
                                                <td class="text-secondary">Belum ACC</td>
                                                <td><a href="#">ACC</a></td>
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