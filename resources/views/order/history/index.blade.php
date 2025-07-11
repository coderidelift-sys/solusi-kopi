<x-order-layout>
    <x-slot name="title">
        Riwayat Pesanan
    </x-slot>

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Riwayat Pesanan Anda</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($orders->isEmpty())
                            <p class="text-center text-muted">Belum ada riwayat pesanan.</p>
                            <div class="d-grid">
                                <a href="{{ route('order.menu', ['table_code' => session('current_table_code', '57')]) }}" class="btn btn-primary mt-3">Mulai Pesan Sekarang</a>
                            </div>
                        @else
                            <div class="list-group">
                                @foreach ($orders as $order)
                                    <a href="{{ route('order.detail', $order->order_number) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">#{{ $order->order_number }} - {{ $order->outlet->name }} (Meja {{ $order->table->table_number }})</h6>
                                            <small class="text-muted">Tanggal: {{ $order->ordered_at->format('d M Y H:i') }}</small><br>
                                            <small class="text-muted">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</small>
                                            @if($order->status === 'cancelled')
                                                <br><small class="text-danger"><i class="ri-close-circle-line me-1"></i>Dibatalkan</small>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <div class="badge bg-label-{{
                                                $order->status === 'completed' ? 'success' :
                                                ($order->status === 'cancelled' ? 'danger' :
                                                ($order->status === 'served' ? 'info' :
                                                ($order->status === 'ready' ? 'secondary' :
                                                ($order->status === 'preparing' ? 'warning' : 'primary'))))
                                            }} mb-1">
                                                {{ $statusOrderIndo[$order->status] ?? ucfirst($order->status) }}
                                            </div>
                                            <br>
                                            <small class="text-muted">
                                                {{ $statusBayarIndo[$order->payment_status] ?? ucfirst($order->payment_status) }}
                                            </small>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Navigasi Mobile --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-bottom py-2" id="bottom-navigation">
        <div class="container-fluid">
            <div class="d-flex justify-content-around w-100 mt-5">
                <a href="{{ route('welcome') }}" class="btn btn-link text-white d-flex flex-column align-items-center">
                    <i class="ri-home-line ri-2x"></i>
                    <span class="fs-6">Beranda</span>
                </a>
                <a href="{{ route('order.menu', ['table_code' => session('current_table_code', '57')]) }}" class="btn btn-link text-white d-flex flex-column align-items-center">
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
</x-order-layout>

{{-- CSS untuk padding bottom --}}
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
