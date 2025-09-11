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
                  <h2 class="page-title">Bimbingan Akademik</h2>
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
                                    Pengisian Bimbingan Akademik
                                </h3>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">No.BP</label>
                                            <input type="text" placeholder="Masukkan No.BP" class="form-control"
                                            />
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" placeholder="Masukkan Nama Lengkap" class="form-control"
                                            />
                                        </div>
                                        <div>
                                            <label class="form-label">Dosen PA</label>
                                            <input type="text" placeholder="Masukkan Nama Dosen PA" class="form-control"
                                            />
                                        </div>
                                        <!-- <div>
                                            <label class="form-label">Email</label>
                                            <input type="email" placeholder="Enter email address" class="form-control"
                                            />
                                        </div> -->
                                        <div>
                                            <label class="form-label">Tahun Ajaran</label>
                                            <select class="form-select">
                                                <option>
                                                    Pilih Tahun Ajaran
                                                </option>
                                                <option>
                                                    2025-2026
                                                </option>
                                                <option>
                                                    2024-2025
                                                </option>
                                                <option>
                                                    2023-2024
                                                </option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Catatan Hasil Bimbingan</label>
                                            <textarea placeholder="Masukkan Catatan Hasil Bimbingan" rows="6" class="form-control">
                                            </textarea>
                                        </div>
                                        <div>
                                            <a href="#" class="btn btn-primary btn-4 w-100">
                                                Simpan
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/arrow-right -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="icon icon-right icon-2">
                                                    <path d="M5 12l14 0" />
                                                    <path d="M13 18l6 -6" />
                                                    <path d="M13 6l6 6" />
                                                </svg>
                                            </a>
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