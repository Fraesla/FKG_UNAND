@extends('admin.layouts.app', [
'activePage' => 'jadwal',
'activeDrop' => 'mahasiswa',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Jadwal Mahasiswa</h2>
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
                          <h3 class="card-title mb-0">Tabel Jadwal Mahasiswa </h3>
                        </div>
                        <div class="col-md-auto col-sm-12">
                          <div class="ms-auto d-flex flex-wrap btn-list">
                            <a href="/admin/jadmahasiswa/add" class="btn btn-0 btn btn-primary"> ADD </a>
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
                                                <!-- <th>ID Mata Kuliah</th> -->
                                                <th>Hari</th>
                                                <th class="text-center">Jam</th>
                                                <th>Mata Kuliah</th>
                                                <th>Ruangan</th>
                                                <th>Nama Dosen</th>
                                                <th>Nama Mahasiswa</th>
                                                <th class="text-center" colspan="2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            @foreach($jadmahasiswa as $data)
                                            <tr>
                                                <td class="text-secondary">{{$no++}}</td>
                                                <td>{{ $data->hari }}</td>
                                                <td class="text-center">{{ $data->jam_mulai }} - {{ $data->jam_selesai }}</td>
                                                <td>{{ $data->makul }}</td>
                                                <td>{{ $data->ruangan }}</td>
                                                <td>{{ $data->dosen}}</td>
                                                <td>{{ $data->nama_mahasiswa }}</td>
                                                <td class="w-0"><a href="/admin/jadmahasiswa/edit/{{$data->id}}" class="btn btn-warning w-10">Edit</a></td>
                                                <td class="w-0"><a href="/admin/jadmahasiswa/delete/{{$data->id}}" class="btn btn-danger w-10">Delete</a></td>
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