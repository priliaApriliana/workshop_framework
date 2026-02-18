<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verify Email - Purple Admin</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <style>
        .auth-form-btn { width: 100%; padding: 12px; font-size: 14px; font-weight: 600; }
        .brand-logo { margin-bottom: 20px; }
        .brand-logo .logo-icon { font-size: 32px; color: #7B4BFF; }
        .brand-logo .logo-text { font-size: 24px; font-weight: 600; color: #7B4BFF; margin-left: 8px; }
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
                            <h4>Verify Your Email</h4>
                            <h6 class="font-weight-light">Please check your email for a verification link.</h6>
                            
                            @if (session('resent'))
                                <div class="alert alert-success mt-3" role="alert">A fresh verification link has been sent to your email address.</div>
                            @endif
                            
                            <div class="pt-3">
                                <p class="text-muted">Before proceeding, please check your email for a verification link.</p>
                                <p class="text-muted">If you did not receive the email:</p>
                                
                                <form method="POST" action="{{ route('verification.resend') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">RESEND VERIFICATION EMAIL</button>
                                </form>
                                
                                <div class="text-center mt-4 font-weight-light">
                                    <a href="{{ route('logout') }}" class="text-primary" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                                </div>
                            </div>
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
