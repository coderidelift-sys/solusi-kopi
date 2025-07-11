<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-content-navbar" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('/materialize') }}/assets/"
    data-template="vertical-menu-template-no-customizer" data-style="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Solusi Kopi') }} - Pilih Login</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/materialize') }}/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/fonts/remixicon/remixicon.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/fonts/flag-icons.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/css/rtl/theme-default.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="{{ asset('/materialize') }}/assets/vendor/libs/toastr/toastr.css" />

    @stack('styles')
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <!-- Header -->
                    <div class="text-center mb-5">
                        <div class="app-brand justify-content-center mb-5">
                            <a href="{{ route('welcome') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <span style="color: var(--bs-primary)">
                                        <svg width="268" height="150" viewBox="0 0 38 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M30.0944 2.22569C29.0511 0.444187 26.7508 -0.172113 24.9566 0.849138C23.1623 1.87039 22.5536 4.14247 23.5969 5.92397L30.5368 17.7743C31.5801 19.5558 33.8804 20.1721 35.6746 19.1509C37.4689 18.1296 38.0776 15.8575 37.0343 14.076L30.0944 2.22569Z"
                                                fill="currentColor" />
                                            <path
                                                d="M30.171 2.22569C29.1277 0.444187 26.8274 -0.172113 25.0332 0.849138C23.2389 1.87039 22.6302 4.14247 23.6735 5.92397L30.6134 17.7743C31.6567 19.5558 33.957 20.1721 35.7512 19.1509C37.5455 18.1296 38.1542 15.8575 37.1109 14.076L30.171 2.22569Z"
                                                fill="url(#paint0_linear_2989_100980)" fill-opacity="0.4" />
                                            <path
                                                d="M22.9676 2.22569C24.0109 0.444187 26.3112 -0.172113 28.1054 0.849138C29.8996 1.87039 30.5084 4.14247 29.4651 5.92397L22.5251 17.7743C21.4818 19.5558 19.1816 20.1721 17.3873 19.1509C15.5931 18.1296 14.9843 15.8575 16.0276 14.076L22.9676 2.22569Z"
                                                fill="currentColor" />
                                            <path
                                                d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                                                fill="url(#paint1_linear_2989_100980)" fill-opacity="0.4" />
                                            <path
                                                d="M7.82901 2.22569C8.87231 0.444187 11.1726 -0.172113 12.9668 0.849138C14.7611 1.87039 15.3698 4.14247 14.3265 5.92397L7.38656 17.7743C6.34325 19.5558 4.04298 20.1721 2.24875 19.1509C0.454514 18.1296 -0.154233 15.8575 0.88907 14.076L7.82901 2.22569Z"
                                                fill="currentColor" />
                                            <defs>
                                                <linearGradient id="paint0_linear_2989_100980" x1="5.36642" y1="0.849138"
                                                    x2="10.532" y2="24.104" gradientUnits="userSpaceOnUse">
                                                    <stop offset="0" stop-opacity="1" />
                                                    <stop offset="1" stop-opacity="0" />
                                                </linearGradient>
                                                <linearGradient id="paint1_linear_2989_100980" x1="5.19475" y1="0.849139"
                                                    x2="10.3357" y2="24.1155" gradientUnits="userSpaceOnUse">
                                                    <stop offset="0" stop-opacity="1" />
                                                    <stop offset="1" stop-opacity="0" />
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </span>
                                </span>
                                <span class="app-brand-text demo text-body fw-semibold">{{ config('app.name') }}</span>
                            </a>
                        </div>
                        <h2 class="mb-2">Pilih Cara Akses</h2>
                        <p class="text-muted">Meja {{ $table->table_number }} - {{ $table->outlet->name }}</p>
                    </div>

                    <!-- Table Info -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="ri-table-line text-primary" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="mb-2">Meja {{ $table->table_number }}</h5>
                                    <p class="text-muted mb-0">{{ $table->outlet->name }}</p>
                                    <small class="text-muted">{{ $table->outlet->address }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Login Options -->
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Pilih Cara Akses</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Guest Option -->
                                    <div class="mb-4">
                                        <a href="{{ route('order.menu', ['table_code' => $table->table_code ?? $table->table_number]) }}"
                                           class="btn btn-primary btn-lg w-100 mb-3">
                                            <i class="ri-user-line me-2"></i>
                                            Lanjutkan sebagai Tamu
                                        </a>
                                        <small class="text-muted d-block text-center">
                                            Anda dapat memesan tanpa login. Data pesanan akan disimpan sementara.
                                        </small>
                                    </div>

                                    {{-- <div class="text-center mb-3">
                                        <span class="text-muted">atau</span>
                                    </div>

                                    <!-- Social Login Options -->
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <a href="{{ route('auth.google') }}"
                                               class="btn btn-outline-danger w-100">
                                                <i class="ri-google-fill me-2"></i>
                                                Google
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('auth.facebook') }}"
                                               class="btn btn-outline-primary w-100">
                                                <i class="ri-facebook-fill me-2"></i>
                                                Facebook
                                            </a>
                                        </div>
                                    </div>

                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            Login untuk menyimpan riwayat pesanan dan akses fitur lainnya
                                        </small>
                                    </div> --}}
                                </div>
                            </div>

                            <!-- Back Button -->
                            <div class="text-center mt-4">
                                <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">
                                    <i class="ri-arrow-left-line me-2"></i>
                                    Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Navigasi Mobile -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-bottom py-2" id="bottom-navigation">
        <div class="container-fluid">
            <div class="d-flex justify-content-around w-100 mt-5">
                <a href="{{ route('welcome') }}" class="btn btn-link text-white d-flex flex-column align-items-center">
                    <i class="ri-home-line ri-2x"></i>
                    <span class="fs-6">Beranda</span>
                </a>
                <a href="{{ route('order.menu', ['table_code' => $table->table_code ?? $table->table_number]) }}" class="btn btn-link text-white d-flex flex-column align-items-center">
                    <i class="ri-restaurant-line ri-2x"></i>
                    <span class="fs-6">Menu</span>
                </a>
                <a href="{{ route('order.history') }}" class="btn btn-link text-white d-flex flex-column align-items-center">
                    <i class="ri-history-line ri-2x"></i>
                    <span class="fs-6">Riwayat</span>
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-link text-white d-flex flex-column align-items-center">
                        <i class="ri-dashboard-line ri-2x"></i>
                        <span class="fs-6">Dashboard</span>
                    </a>
                    <button class="btn btn-link text-white d-flex flex-column align-items-center" onclick="logout()">
                        <i class="ri-logout-box-line ri-2x"></i>
                        <span class="fs-6">Logout</span>
                    </button>
                @else
                    <button class="btn btn-link text-white d-flex flex-column align-items-center" onclick="clearSession()">
                        <i class="ri-delete-bin-line ri-2x"></i>
                        <span class="fs-6">Hapus Sesi</span>
                    </button>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Core JS -->
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/js/menu.js"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="{{ asset('/materialize') }}/assets/vendor/libs/toastr/toastr.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('/materialize') }}/assets/js/main.js"></script>

    <script>
        // Toastr Options
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function logout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("logout") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    }).then(() => {
                        window.location.href = '{{ route("welcome") }}';
                    }).catch(() => {
                        window.location.href = '{{ route("welcome") }}';
                    });
                }
            });
        }

        function clearSession() {
            Swal.fire({
                title: 'Konfirmasi Hapus Sesi',
                text: 'Apakah Anda yakin ingin menghapus semua sesi?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("clear.session") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    }).then(() => {
                        window.location.href = '{{ route("welcome") }}';
                    }).catch(() => {
                        window.location.href = '{{ route("welcome") }}';
                    });
                }
            });
        }
    </script>

    <!-- CSS untuk padding bottom -->
    <style>
        @media (max-width: 768px) {
            .container-xxl {
                padding-bottom: 80px !important;
            }
            #bottom-navigation {
                z-index: 1030;
            }
            .layout-wrapper {
                padding-bottom: 80px;
            }
            /* Memastikan footer navigasi tampil di atas konten lain */
            .navbar.fixed-bottom {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                z-index: 1030 !important;
            }
            /* CSS untuk bottom navigation dengan banyak menu */
            #bottom-navigation .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            #bottom-navigation .ri-2x {
                font-size: 1.2rem !important;
            }
            #bottom-navigation .fs-6 {
                font-size: 0.7rem !important;
            }
            #bottom-navigation .d-flex {
                gap: 0.25rem;
            }
        }
    </style>
</body>
</html>
