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
                  <h2 class="page-title">Pengisian Absensi Perkuliahan</h2>
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
                          <h3 class="card-title mb-0">Tabel Jadwal Pertemuan </h3>
                        </div>
                        <div class="col-md-auto col-sm-12">
                          <div class="ms-auto d-flex flex-wrap btn-list">
                            <a href="/dosen/absensi/add" class="btn btn-0 btn btn-primary"> ADD </a>
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
                                                <th>Tanggal Perkuliahan</th>
                                                <th>Kelompok</th>
                                                <th>Jumlah Mahasiswa</th>
                                                <th>Jam Penutupan Absen</th>
                                                <th class="text-center">Action</th>
                                                <th class="w-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-secondary">11-02-2024</td>
                                                <td class="text-secondary">testing</td>
                                                <td class="text-secondary">30</td>
                                                <td class="text-secondary">16:00</td>
                                                <td><a href="/dosen/absensi/edit">Edit</a></td>
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

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                      <div class="row w-full">
                        <div class="col">
                          <h3 class="card-title mb-0">Tabel Penilaian </h3>
                        </div>
                        <div class="col-md-auto col-sm-12">
                          <div class="ms-auto d-flex flex-wrap btn-list">
                            <a href="/dosen/absensi/addnilai" class="btn btn-0 btn btn-primary"> ADD </a>
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
                                                <th>Nama Mahasiswa & No BP</th>
                                                <th>Nilai</th>
                                                <th class="text-center">Action</th>
                                                <th class="w-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-secondary">Test | 102831</td>
                                                <td class="text-secondary">95</td>
                                                <td><a href="/dosen/absensi/editnilai">Edit</a></td>
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