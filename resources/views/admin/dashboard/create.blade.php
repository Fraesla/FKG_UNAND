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

                    {{-- Flash Message Sukses --}}
                    @if (session('success'))
                    <div id="alert-success" class="alert alert-success alert-dismissible fade show position-relative" role="alert">
                        ✅ {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <div class="progress position-absolute bottom-0 start-0 w-100" style="height: 3px;">
                            <div id="progress-bar-success" class="progress-bar bg-success" role="progressbar"></div>
                        </div>
                    </div>
                    @endif

                    {{-- Flash Message Error (Validasi) --}}
                    @if ($errors->any())
                    <div id="alert-error" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                        <strong>⚠️ Terjadi Kesalahan pada Pengisian Formulir:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <div class="progress position-absolute bottom-0 start-0 w-100" style="height: 3px;">
                            <div id="progress-bar-error" class="progress-bar bg-danger" role="progressbar"></div>
                        </div>
                    </div>
                    @endif
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
                                    Penambahan Data Users
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
                                <form action="/admin/user/create" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Username</label>
                                            <input type="text" placeholder="Masukkan Username" class="form-control" name="username" value="{{ old('username') }}" />
                                            @error('username')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                         <!-- PASSWORD BARU -->
                                        <div class="mb-3">
                                            <label class="form-label">Masukkan Password</label>

                                            <div class="input-group">
                                                <input type="password" name="password" id="new_password"
                                                       class="form-control" placeholder="Masukkan Password Baru">

                                                <span class="input-group-text" style="cursor:pointer;"
                                                      onclick="togglePassword('new_password', this)">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         width="20" height="20" viewBox="0 0 24 24"
                                                         fill="none" stroke="currentColor" stroke-width="2"
                                                         stroke-linecap="round" stroke-linejoin="round"
                                                         class="icon icon-tabler icon-tabler-eye">
                                                         <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                         <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                    </svg>
                                                </span>
                                            </div>
                                            @error('password')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- KONFIRMASI PASSWORD -->
                                        <div class="mb-3">
                                            <label class="form-label">Konfirmasi Password</label>

                                            <div class="input-group">
                                                <input type="password" name="confirm_password" id="confirm_password"
                                                       class="form-control" placeholder="Masukkan Konfirmasi Password">

                                                <span class="input-group-text" style="cursor:pointer;"
                                                      onclick="togglePassword('confirm_password', this)">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         width="20" height="20" viewBox="0 0 24 24"
                                                         fill="none" stroke="currentColor" stroke-width="2"
                                                         stroke-linecap="round" stroke-linejoin="round"
                                                         class="icon icon-tabler icon-tabler-eye">
                                                         <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                         <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                    </svg>
                                                </span>
                                            </div>
                                            @error('confirm_password')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="level">
                                                <option value="">
                                                    Pilih Status
                                                </option>
                                                <option value="admin"{{ old('level') == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="dosen"{{ old('level') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                                <option value="mahasiswa"{{ old('level') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                                <option value="pimpinan"{{ old('pimpinan') == 'admin' ? 'selected' : '' }}>Pimpinan</option>
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
<script>
function togglePassword(id, icon) {
    const input = document.getElementById(id);

    if (input.type === "password") {
        input.type = "text";
        icon.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-eye-off">
                 <path d="M3 3l18 18" />
                 <path d="M10.584 10.587a2 2 0 0 0 2.829 2.828" />
                 <path d="M9.88 4.245a9.956 9.956 0 0 1 2.12 -.245c3.6 0 6.6 2 9 6
                 c-.575 .958 -1.175 1.772 -1.8 2.442" />
                 <path d="M6.166 6.172c-1.27 .984 -2.373 2.29 -3.305 3.828
                 c2.4 4 5.4 6 9 6c1.223 0 2.397 -.22 3.52 -.643" />
            </svg>
        `;
    } else {
        input.type = "password";
        icon.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-eye">
                 <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                 <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6
                 c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
            </svg>
        `;
    }
}
</script>
@endsection
