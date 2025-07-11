<x-order-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Detail Pesanan #{{ $order->order_number }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Informasi Pesanan</h5>
                                <p><strong>Outlet:</strong> {{ $order->outlet->name ?? 'N/A' }}</p>
                                <p><strong>Meja:</strong> {{ $order->table->table_number ?? 'N/A' }}</p>
                                <p><strong>Tipe Pesanan:</strong> {{ ucfirst($order->order_type) }}</p>
                                <p><strong>Status Pesanan:</strong>
                                    <span class="badge bg-label-{{
                                        $order->status === 'completed' ? 'success' :
                                        ($order->status === 'cancelled' ? 'danger' :
                                        ($order->status === 'served' ? 'info' :
                                        ($order->status === 'ready' ? 'secondary' :
                                        ($order->status === 'preparing' ? 'warning' : 'primary'))))
                                    }}">
                                        {{ $statusOrderIndo[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                </p>
                                <p><strong>Catatan:</strong> {{ $order->note ?? '-' }}</p>
                                <p><strong>Waktu Pesan:</strong> {{ $order->ordered_at->format('d F Y, H:i') }}</p>
                                @if($order->completed_at)
                                    <p><strong>Waktu Selesai:</strong> {{ $order->completed_at->format('d F Y, H:i') }}</p>
                                @endif
                                @if($order->status === 'cancelled')
                                    <div class="alert alert-danger mt-3">
                                        <i class="ri-close-circle-line me-2"></i>
                                        <strong>Pesanan Dibatalkan</strong><br>
                                        Pesanan ini telah dibatalkan dan tidak dapat diproses kembali.
                                    </div>
                                @endif
                                @if($order->promotion)
                                    <p><strong>Promo:</strong> <span class="badge bg-success">{{ $order->promotion->code }} - {{ $order->promotion->name }}</span></p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Informasi Pembayaran</h5>
                                @php
                                    $payment = $order->payments->sortByDesc('created_at')->first();
                                @endphp
                                @if($payment)
                                    <div class="border-bottom pb-2 mb-2">
                                        <p><strong>Metode:</strong> {{ $payment->method }}</p>
                                        <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 2, ',', '.') }}</p>
                                        <p><strong>Status:</strong> <span class="badge bg-label-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($payment->status) }}</span></p>
                                        @if($payment->paid_at)
                                            <p><strong>Waktu Bayar:</strong> {{ $payment->paid_at->format('d F Y, H:i') }}</p>
                                        @endif
                                        @if($payment->payment_gateway_ref)
                                            <p><strong>Ref. Pembayaran:</strong> {{ $payment->payment_gateway_ref }}</p>
                                        @endif
                                    </div>
                                @endif
                                @if(!$payment)
                                    <p>Belum ada informasi pembayaran.</p>
                                @endif
                                <h4 class="text-primary mt-3">Total Akhir: Rp {{ number_format($order->total_amount, 2, ',', '.') }}</h4>
                            </div>
                        </div>

                        <!-- Order Timeline -->
                        <div class="mt-4">
                            <h5 class="mb-3">Progress Pesanan</h5>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Pesanan Dibuat</h6>
                                        <p class="text-muted mb-0">{{ $order->ordered_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>

                                @if($order->status !== 'pending')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Pesanan Dikonfirmasi</h6>
                                        <p class="text-muted mb-0">Pembayaran diterima, pesanan dikonfirmasi</p>
                                    </div>
                                </div>
                                @endif

                                @if(in_array($order->status, ['preparing', 'ready', 'served', 'completed']))
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Sedang Disiapkan</h6>
                                        <p class="text-muted mb-0">Dapur sedang menyiapkan pesanan Anda</p>
                                    </div>
                                </div>
                                @endif

                                @if(in_array($order->status, ['ready', 'served', 'completed']))
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-secondary"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Siap Diantar</h6>
                                        <p class="text-muted mb-0">Pesanan siap diantar ke meja Anda</p>
                                    </div>
                                </div>
                                @endif

                                @if(in_array($order->status, ['served', 'completed']))
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Sudah Diantar</h6>
                                        <p class="text-muted mb-0">Pesanan sudah diantar ke meja Anda</p>
                                    </div>
                                </div>
                                @endif

                                @if($order->status === 'completed')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-dark"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Selesai</h6>
                                        <p class="text-muted mb-0">{{ $order->completed_at ? $order->completed_at->format('d/m/Y H:i') : 'Pesanan selesai' }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($order->status === 'cancelled')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-danger"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Dibatalkan</h6>
                                        <p class="text-muted mb-0">Pesanan telah dibatalkan</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5 class="mb-3">Item Pesanan</h5>
                            @if ($order->orderItems->isEmpty())
                                <p>Tidak ada item dalam pesanan ini.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th class="text-center">Kuantitas</th>
                                                <th class="text-end">Harga Satuan</th>
                                                <th class="text-end">Subtotal Item</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->orderItems as $item)
                                                <tr>
                                                    <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-end">Rp {{ number_format($item->price_at_order, 2, ',', '.') }}</td>
                                                    <td class="text-end">Rp {{ number_format($item->price_at_order * $item->quantity, 2, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold">Subtotal Pesanan:</td>
                                                <td class="text-end fw-bold">Rp {{ number_format($order->subtotal, 2, ',', '.') }}</td>
                                            </tr>
                                            @if($order->promotion)
                                                <tr class="table-success">
                                                    <td colspan="3" class="text-end fw-bold text-success">Diskon ({{ $order->promotion->code }}):</td>
                                                    <td class="text-end fw-bold text-success">- Rp {{ number_format($order->discount_amount ?? ($order->promotion->discount_type === 'percentage' ? ($order->subtotal * $order->promotion->discount_value / 100) : $order->promotion->discount_value), 2, ',', '.') }}</td>
                                                </tr>
                                                <tr class="table-success">
                                                    <td colspan="3" class="text-end fw-bold text-success">Setelah Diskon:</td>
                                                    <td class="text-end fw-bold text-success">Rp {{ number_format(max(0, $order->subtotal - ($order->discount_amount ?? ($order->promotion->discount_type === 'percentage' ? ($order->subtotal * $order->promotion->discount_value / 100) : $order->promotion->discount_value))), 2, ',', '.') }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold">Biaya Layanan:</td>
                                                <td class="text-end fw-bold">Rp {{ number_format($order->additional_fee, 2, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold">Pajak:</td>
                                                <td class="text-end fw-bold">Rp {{ number_format($order->other_fee, 2, ',', '.') }}</td>
                                            </tr>
                                            <tr class="table-primary">
                                                <td colspan="3" class="text-end fw-bold fs-5">Total Akhir:</td>
                                                <td class="text-end fw-bold fs-5">Rp {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 text-end">
                            <a href="{{ route('order.history') }}" class="btn btn-secondary">Kembali ke Riwayat Pesanan</a>
                        </div>
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

{{-- CSS untuk padding bottom dan timeline --}}
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

    /* Timeline styles */
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-marker {
        position: absolute;
        left: -35px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }

    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: -29px;
        top: 12px;
        width: 2px;
        height: calc(100% + 8px);
        background-color: #e9ecef;
    }

    .timeline-content h6 {
        margin-bottom: 5px;
        font-size: 14px;
    }

    .timeline-content p {
        font-size: 12px;
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
