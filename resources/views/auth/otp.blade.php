<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verifikasi OTP - Purple Admin</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <style>
        .auth-form-btn { width: 100%; padding: 12px; font-size: 14px; font-weight: 600; }
        .brand-logo { margin-bottom: 20px; }
        .brand-logo .logo-icon { font-size: 32px; color: #7B4BFF; }
        .brand-logo .logo-text { font-size: 24px; font-weight: 600; color: #7B4BFF; margin-left: 8px; }
        .otp-input {
            letter-spacing: 15px;
            font-size: 24px;
            text-align: center;
            font-weight: bold;
        }
        .otp-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .otp-info i {
            font-size: 40px;
            color: #7B4BFF;
        }
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
                            
                            <div class="otp-info text-center">
                                <i class="mdi mdi-email-check-outline"></i>
                                <h4 class="mt-2">Verifikasi OTP</h4>
                                <p class="text-muted mb-0">Masukkan kode 6 digit yang telah dikirim ke email Anda</p>
                            </div>

                            {{-- Alert Messages --}}
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('otp.verify') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="text" 
                                           class="form-control form-control-lg otp-input @error('otp') is-invalid @enderror" 
                                           id="otp" 
                                           name="otp" 
                                           maxlength="6" 
                                           placeholder="______"
                                           pattern="[0-9]{6}"
                                           required 
                                           autofocus
                                           autocomplete="off">
                                    @error('otp')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                        VERIFIKASI
                                    </button>
                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <p class="text-muted">Tidak menerima kode?</p>
                                <form method="POST" action="{{ route('otp.resend') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-primary p-0">Kirim Ulang OTP</button>
                                </form>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="text-muted">
                                    <i class="mdi mdi-arrow-left"></i> Kembali ke Login
                                </a>
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
    <script>
        // Auto focus dan hanya terima angka
        document.getElementById('otp').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>