@extends('admin.layouts.app', [
'activePage' => 'dashboard',
'activeDrop' => '',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Users</h2>
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">
                                    Pengeditan Data Users
                                </h3>
                                <a href="/admin/user/" class="btn btn-secondary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                         class="icon icon-tabler icon-tabler-arrow-left">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l14 0" />
                                        <path d="M5 12l6 6" />
                                        <path d="M5 12l6 -6" />
                                    </svg>
                                    Back
                                </a>
                            </div>
                            <div class="card-body">
                                <form action="/admin/user/update/{{$user->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Username</label>
                                            <input type="text" placeholder="Masukkan Username" class="form-control" name="username" value="{{$user->username}}"/>
                                            @error('username')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Status </label>
                                            <select class="form-select" name="level">
                                                <option value="">
                                                    Pilih Status
                                                </option>
                                                <option value="admin"  {{ $user->level == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="dosen" {{ $user->level == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                                <option value="mahasiswa"   {{ $user->level == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                                <option value="pimpinan"  {{ $user->level == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                            </select>
                                             @error('level')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
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