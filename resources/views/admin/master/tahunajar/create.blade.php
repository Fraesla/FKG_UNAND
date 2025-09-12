@extends('admin.layouts.app', [
'activePage' => 'master',
'activeDrop' => 'tahun',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Tahun Ajaran</h2>
              </div>
              <!-- Page title actions -->
      </div>
   </div>
</div>
<!-- END PAGE HEADER -->
<!-- BEGIN PAGE BODY -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards row-cols-1 row-cols-md-12">
            <div class="col">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Penambahan Data Tahun Ajaran
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/tahunajar/create" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Tahun Ajaran</label>
                                            <input type="text" placeholder="Masukkan Tahun Ajaran" class="form-control" name="nama" />
                                        </div>
                                        <div>
                                            <label class="form-label">Status</label>
                                            <div>
                                                <label class="form-check form-check-inline">
                                                  <input class="form-check-input" type="radio" name="status" checked="" value="1"> 
                                                  <span class="form-check-label">Aktif</span>
                                                </label>
                                                <label class="form-check form-check-inline">
                                                  <input class="form-check-input" type="radio" name="status" value="0">
                                                  <span class="form-check-label">Noaktif</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-4 w-100">
                                                Simpan
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/arrow-right -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="icon icon-right icon-2">
                                                    <path d="M5 12l14 0" />
                                                    <path d="M13 18l6 -6" />
                                                    <path d="M13 6l6 6" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>
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