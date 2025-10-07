@extends('auth.template', [
'activePage' => 'dashboard',
])
@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">
            Login Page
        </h2>
        <!-- @if (session('error'))
            <div class="alert alert-primary">
                {{ session('error')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
             </div>
        @endif -->
        @include('components.alert')
        <form action="{{ route('login') }}" method="POST" autocomplete="off" novalidate>
            @csrf
            <div class="mb-3">
                <label class="form-label">
                    Username
                </label>
                <input type="email" class="form-control" placeholder="username" autocomplete="off" name="username"/>
            </div>
            <div class="mb-2">
                <label class="form-label">
                    Password
                    <!-- <span class="form-label-description">
                    <a href="./forgot-password.html">I forgot password</a>
                    </span> -->
                </label>
                <div class="input-group input-group-flat">
                    <input type="password" class="form-control" placeholder="Password" autocomplete="off" name="password" id="password"/>
                    <span class="input-group-text" id="toggleIcon">
                        <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip" onclick="togglePassword()">
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
           <!--  <div class="mb-3">
                <div class="form-label">
                    Status
                </div>
                <select class="form-select" name="level">
                    <option>
                        Pilih Status
                    </option>
                    <option value="admin">
                        Admin
                    </option>
                    <option value="mahasiswa">
                        Mahasiswa
                    </option>
                    <option value="dosen">
                        Dosen
                    </option>
                    <option value="pimpinan">
                        Pimpinan
                    </option>
                </select>
            </div> -->
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </div>
        </form>
    </div>
</div>
<div class="text-center text-secondary mt-3">Don't have account yet?<a href="#" tabindex="-1"> Sign up</a></div>
<!-- js -->
<script>
    function togglePassword() {
        var passwordInput = document.getElementById("password");
        var toggleIcon = document.getElementById("toggleIcon");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("dw-padlock1");
            toggleIcon.classList.add("dw-unlock1");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("dw-unlock1");
            toggleIcon.classList.add("dw-padlock1");
        }
    }
</script>
@endsection