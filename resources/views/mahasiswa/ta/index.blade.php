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
                  <h2 class="page-title">Tugas Akhir</h2>
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
                                    Pengisian Tugas Akhir
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
                                            <label class="form-label">Nama Mahasiswa</label>
                                            <input type="text" placeholder="Masukkan Nama Mahasiswa" class="form-control"
                                            />
                                        </div>
                                        <div>
                                            <label class="form-label">Dosen Pembimbing</label>
                                            <input type="text" placeholder="Masukkan Nama Dosen Pembimbing" class="form-control"
                                            />
                                        </div>
                                        <div>
                                            <label class="form-label"> Tanggal Bimbingan </label>
                                            <div>
                                              <div class="input-icon">
                                                <input class="form-control" placeholder="Masukkan Tanggal Bimbingan" id="datepicker-birth-date" value="2020-06-20" />
                                                <span class="input-icon-addon"
                                                  ><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                  <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-1"
                                                  >
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                                    <path d="M16 3v4" />
                                                    <path d="M8 3v4" />
                                                    <path d="M4 11h16" />
                                                    <path d="M11 15h1" />
                                                    <path d="M12 15v3" /></svg
                                                ></span>
                                              </div>
                                            </div>
                                        </div>
                                        <!-- <div>
                                            <label class="form-label">Email</label>
                                            <input type="email" placeholder="Enter email address" class="form-control"
                                            />
                                        </div> -->
                                        <div>
                                            <label class="form-label">Catatan Bimbingan</label>
                                            <textarea placeholder="Masukkan Catatan Bimbingan" rows="6" class="form-control">
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