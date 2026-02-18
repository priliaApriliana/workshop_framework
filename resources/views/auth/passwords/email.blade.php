<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Forgot Password - Purple Admin</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <style>
        .auth-form-btn { width: 100%; padding: 12px; font-size: 14px; font-weight: 600; }
        .brand-logo { margin-bottom: 20px; }
        .brand-logo .logo-icon { font-size: 32px; color: #7B4BFF; }
        .brand-logo .logo-text { font-size: 24px; font-weight: 600; color: #7B4BFF; margin-left: 8px; }
        .form-control { height: 50px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo d-flex align-items-center">
                                <i class="mdi mdi-cube-outline logo-icon"></i>
                                <span class="logo-text">Purple</span>
                            </div>
                            <h4>Forgot Password?</h4>
                            <h6 class="font-weight-light">Enter your email to reset password.</h6>
                            
                            @if (session('status'))
                                <div class="alert alert-success mt-3" role="alert">{{ session('status') }}</div>
                            @endif
                            
                            <form class="pt-3" method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                        id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">SEND RESET LINK</button>
                                </div>
                                <div class="text-center mt-4 font-weight-light">
                                    Remember your password? <a href="{{ route('login') }}" class="text-primary">Login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
</body>
</html>
