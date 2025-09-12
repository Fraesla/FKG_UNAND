@extends('admin.layouts.app', [
'activePage' => 'master',
'activeDrop' => 'fakultas',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Fakultas</h2>
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
                    <div class="card-header">
                      <div class="row w-full">
                        <div class="col">
                          <h3 class="card-title mb-0">Tabel Fakultas </h3>
                        </div>
                        <div class="col-md-auto col-sm-12">
                          <div class="ms-auto d-flex flex-wrap btn-list">
                            <a href="/admin/fakultas/add" class="btn btn-0 btn btn-primary"> ADD </a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Nama Fakultas</th>
                                                <th class="text-center" colspan="2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            @foreach($fakultas as $data)
                                            <tr>
                                                <td class="text-secondary">{{$no++}}</td>
                                                <td class="text-secondary">{{$data->nama}}</td>
                                                <td class="w-0"><a href="/admin/fakultas/edit/{{$data->id}}" class="btn btn-warning w-10">Edit</a></td>
                                                <td class="w-0"><a href="/admin/fakultas/delete/{{$data->id}}" class="btn btn-danger w-10">Delete</a></td>
                                            </tr>
                                            @endforeach
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