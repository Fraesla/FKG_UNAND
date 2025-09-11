@extends('auth.template', [
'activePage' => 'dashboard',
])
@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">
            Login Page
        </h2>
        <form action="/admin/dashboard" method="get" autocomplete="off" novalidate>
            <div class="mb-3">
                <label class="form-label">
                    Username
                </label>
                <input type="email" class="form-control" placeholder="username" autocomplete="off"
                />
            </div>
            <div class="mb-2">
                <label class="form-label">
                    Password
                    <!-- <span class="form-label-description">
                    <a href="./forgot-password.html">I forgot password</a>
                    </span> -->
                </label>
                <div class="input-group input-group-flat">
                    <input type="password" class="form-control" placeholder="Password" autocomplete="off"
                    />
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/eye -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-1">
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"
                                />
                            </svg>
                        </a>
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-label">
                    Status
                </div>
                <select class="form-select">
                    <option value="0">
                        Pilih Status
                    </option>
                    <option value="1">
                        Admin
                    </option>
                    <option value="2">
                        Mahasiswa
                    </option>
                    <option value="3">
                        Dosen
                    </option>
                    <option value="4">
                        Pimpinan
                    </option>
                </select>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </div>
        </form>
    </div>
</div>
<div class="text-center text-secondary mt-3">Don't have account yet?<a href="#" tabindex="-1"> Sign up</a></div>
@endsection