<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'AM & GA System') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Asset Management & General Affair System" name="description" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- GANTI: app-rtl.min.css -> app.min.css (LTR) -->
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
        .logo-admin {
            display: inline-block;
        }
        .auth-logo {
            max-height: 50px;
        }
        .nav-border.nav-pills .nav-link.active {
            background: #007bff;
            color: #fff;
        }
        .nav-border.nav-pills .nav-link {
            border-radius: 30px;
            padding: 8px 25px;
        }
        .nav-border {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
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
        /* Tambahan untuk memastikan form rata kiri */
        .auth-form .form-group label {
            text-align: left;
            display: block;
            font-weight: 500;
        }
        .auth-form .form-group .input-group {
            text-align: left;
        }
        .text-start {
            text-align: left !important;
        }
        .text-end {
            text-align: right !important;
        }
        .me-1 {
            margin-right: 0.25rem !important;
        }
        .ms-1 {
            margin-left: 0.25rem !important;
        }
    </style>
</head>

<body class="account-body accountbg">

    <!-- Log In page -->
    <div class="container">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="row">
                    <div class="col-lg-5 mx-auto">
                        <div class="card">
                            <div class="card-body p-0 auth-header-box">
                                <div class="text-center p-3">
                                    <a href="{{ url('/') }}" class="logo logo-admin">
                                        <img src="{{ asset('assets/images/logo.sm.png') }}" height="50" alt="logo" class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 font-weight-semibold text-white font-18">Asset Management & GA</h4>
                                    <p class="text-light mb-0">Sign in to continue to your dashboard.</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="nav-border nav nav-pills" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active font-weight-semibold" data-toggle="tab" href="#LogIn_Tab" role="tab">Log In</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link font-weight-semibold" data-toggle="tab" href="#Register_Tab" role="tab">Register</a>
                                    </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <!-- LOGIN TAB -->
                                    <div class="tab-pane active p-3 pt-3" id="LogIn_Tab" role="tabpanel">
                                        <form class="form-horizontal auth-form my-4 text-start" method="POST" action="{{ route('login') }}">
                                            @csrf

                                            <div class="form-group">
                                                <label for="email">Email Address</label>
                                                <div class="input-group mb-3">
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                           name="email" id="email" value="{{ old('email') }}"
                                                           placeholder="Enter email" required autofocus>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div><!--end form-group-->

                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <div class="input-group mb-3">
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                           name="password" id="password" placeholder="Enter password" required>
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div><!--end form-group-->

                                            <div class="form-group row mt-4">
                                                <div class="col-sm-6 text-start">
                                                    <div class="custom-control custom-switch switch-success">
                                                        <input type="checkbox" class="custom-control-input" id="customSwitchSuccess" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                        <label class="custom-control-label text-muted" for="customSwitchSuccess">Remember me</label>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-sm-6 text-end">
                                                    @if (Route::has('password.request'))
                                                        <a href="{{ route('password.request') }}" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>
                                                    @endif
                                                </div><!--end col-->
                                            </div><!--end form-group-->

                                            <div class="form-group mb-0 row">
                                                <div class="col-12 mt-2">
                                                    <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">
                                                        Log In <i class="fas fa-sign-in-alt ms-1"></i>
                                                    </button>
                                                </div><!--end col-->
                                            </div> <!--end form-group-->
                                        </form><!--end form-->
                                    </div>
                                    <!-- REGISTER TAB -->
                                    <div class="tab-pane px-3 pt-3" id="Register_Tab" role="tabpanel">
                                        <form class="form-horizontal auth-form my-4 text-start" method="POST" action="{{ route('register') }}">
                                            @csrf

                                            <div class="form-group">
                                                <label for="name">Full Name</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                           name="name" id="name" value="{{ old('name') }}"
                                                           placeholder="Enter full name" required>
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div><!--end form-group-->

                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <div class="input-group mb-3">
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                           name="email" id="email" value="{{ old('email') }}"
                                                           placeholder="Enter Email" required>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div><!--end form-group-->

                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <div class="input-group mb-3">
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                           name="password" id="password" placeholder="Enter password" required>
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div><!--end form-group-->

                                            <div class="form-group">
                                                <label for="password_confirmation">Confirm Password</label>
                                                <div class="input-group mb-3">
                                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                                           name="password_confirmation" id="password_confirmation"
                                                           placeholder="Enter Confirm Password" required>
                                                    @error('password_confirmation')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div><!--end form-group-->

                                            <div class="form-group row mt-4">
                                                <div class="col-sm-12">
                                                    <div class="custom-control custom-switch switch-success">
                                                        <input type="checkbox" class="custom-control-input" id="customSwitchSuccess2" name="terms" required>
                                                        <label class="custom-control-label text-muted" for="customSwitchSuccess2">
                                                            You agree to the <a href="#" class="text-primary">Terms of Use</a>
                                                        </label>
                                                    </div>
                                                </div><!--end col-->
                                            </div><!--end form-group-->

                                            <div class="form-group mb-0 row">
                                                <div class="col-12 mt-2">
                                                    <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">
                                                        Register <i class="fas fa-sign-in-alt ms-1"></i>
                                                    </button>
                                                </div><!--end col-->
                                            </div> <!--end form-group-->
                                        </form><!--end form-->
                                        <p class="mb-0 text-muted text-center">Already have an account? <a href="#" class="text-primary ms-2" onclick="$('#LogIn_Tab').tab('show')">Log in</a></p>
                                    </div>
                                </div>
                            </div><!--end card-body-->
                            <div class="card-body bg-light-alt text-center">
                                <span class="text-muted d-none d-sm-inline-block">&copy; {{ date('Y') }} {{ config('app.name') }}</span>
                            </div>
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
    <!-- End Log In page -->

    <!-- jQuery  -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/waves.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>

    <script>
        // Menangani tab register -> login
        $(document).ready(function() {
            // Jika ada error di register, aktifkan tab register
            @if($errors->has('name') || $errors->has('email') || $errors->has('password') || $errors->has('terms'))
                $('#Register_Tab').tab('show');
            @endif
        });
    </script>

</body>

</html>
