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
                    <div class="card-body">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Perkuliahan</th>
                                                <th>Nama Dosen</th>
                                                <th>Jenis Perkuliahan</th>
                                                <th>Jam Penutupan Absen</th>
                                                <th>Keterangan</th>
                                                <th class="text-center">Action</th>
                                                <th class="w-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-secondary">11-02-2024</td>
                                                <td class="text-secondary">testing</td>
                                                <td class="text-secondary">test</td>
                                                <td class="text-secondary">16:00</td>
                                                <td class="text-secondary"></td>
                                                <td><a href="/mahasiswa/absensi/absen">Isi Absen</a></td>
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