@extends('dosen.layouts.app', ['activePage' => 'dashboard'])

@section('content')

<style>
    .password-box {
        background: #111827;
        padding: 25px;
        border-radius: 14px;
        border: 1px solid #1f2937;
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
        position: relative;
    }
    label {
        color: #e5e7eb;
    }
    .btn-back-inside {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 4px 10px;
    }
</style>

<!-- PAGE HEADER -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Aplikasi FKG</div>
                <h2 class="page-title">Ubah Password Akun Mahasiswa</h2>
            </div>
        </div>
    </div>
</div>

<!-- PAGE BODY -->
<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <!-- Alert Success -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Alert Error -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- CARD -->
                <div class="password-box">

                    <!-- TOMBOL BACK DI DALAM CARD -->
                    <a href="/mahasiswa/dashboard" class="btn btn-secondary btn-sm btn-back-inside">
                        ← Back
                    </a>

                    <h4 class="text-white mb-4">Form Ubah Password</h4>

                    <form action="/mahasiswa/updatePass" method="POST">
                        @csrf

                        <!-- PASSWORD LAMA -->
                        <div class="mb-3">
                            <label class="form-label">Masukkan Password Lama</label>

                            <div class="input-group">
                                <input type="password" name="old_password" id="old_password"
                                       class="form-control" placeholder="Masukkan Password Lama">

                                <span class="input-group-text" style="cursor:pointer;"
                                      onclick="togglePassword('old_password', this)">
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
                        </div>

                        <!-- PASSWORD BARU -->
                        <div class="mb-3">
                            <label class="form-label">Masukkan Password Baru</label>

                            <div class="input-group">
                                <input type="password" name="new_password" id="new_password"
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
                        </div>

                        <!-- KONFIRMASI PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>

                            <div class="input-group">
                                <input type="password" name="confirm_password" id="confirm_password"
                                       class="form-control" placeholder="Masukkan Konfirmasi Password Baru">

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
                        </div>

                        <button class="btn btn-primary w-100 mt-3">
                            Ubah Password
                        </button>
                    </form>

                </div> <!-- END CARD -->

            </div>
        </div>
    </div>
</div>

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