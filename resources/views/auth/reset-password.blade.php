<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Reset Password - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Asset Management & General Affair System" name="description" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .auth-header-box {
            background: linear-gradient(135deg, #2b3d51 0%, #1a2632 100%);
        }
        .account-body {
            background: #f0f2f5;
        }
        .accountbg {
            background: #f0f2f5;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .auth-header-box {
            border-radius: 15px 15px 0 0;
        }
        .btn-primary {
            background: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background: #0069d9;
            border-color: #0062cc;
        }
        .logo-admin {
            display: inline-block;
        }
        .auth-logo {
            max-height: 50px;
        }
    </style>
</head>

<body class="account-body accountbg">

    <div class="container">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="row">
                    <div class="col-lg-5 mx-auto">
                        <div class="card">
                            <div class="card-body p-0 auth-header-box">
                                <div class="text-center p-3">
                                    <a href="{{ url('/') }}" class="logo logo-admin">
                                        <img src="{{ asset('assets/images/logo-sm.png') }}" height="50" alt="logo" class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 font-weight-semibold text-white font-18">Reset Password</h4>
                                    <p class="text-light mb-0">Masukkan password baru Anda.</p>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <form class="form-horizontal auth-form my-4" method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="form-group">
                                        <label for="email">Alamat Email</label>
                                        <div class="input-group mb-3">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   name="email" id="email" value="{{ $email ?? old('email') }}"
                                                   placeholder="Masukkan email Anda" required readonly>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password Baru</label>
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                   name="password" id="password" placeholder="Masukkan password baru" required>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">Konfirmasi Password</label>
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control"
                                                   name="password_confirmation" id="password_confirmation"
                                                   placeholder="Konfirmasi password baru" required>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 row">
                                        <div class="col-12 mt-2">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">
                                                Reset Password <i class="fas fa-key ml-1"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="text-center mt-3">
                                        <a href="{{ route('login') }}" class="text-muted">
                                            <i class="fas fa-arrow-left"></i> Kembali ke Login
                                        </a>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body bg-light-alt text-center">
                                <span class="text-muted d-none d-sm-inline-block">&copy; {{ date('Y') }} {{ config('app.name') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery  -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/waves.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>

</body>
</html>
