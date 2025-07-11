@extends('layouts.guest-order')

@section('title', $title ?? 'Pesanan Berhasil!')

@section('content')

    <div class="card p-4 p-md-5 mb-4">
        <div class="card-body">
            <div class="app-brand justify-content-center mb-5">
                <a href="{{ route('welcome') }}" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo">
                        <!-- SVG Logo here -->
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

            {{-- Notifikasi (akan ditangani via Toastr JS) --}}
            <div id="payment-status-message" class="alert d-none" role="alert"></div>

            @if($order->status === 'cancelled')
                <h4 class="mb-2 text-center text-danger">Pesanan Dibatalkan</h4>
                <p class="mb-4 text-center">Nomor Pesanan: <span class="fw-bold">{{ $order->order_number }}</span></p>
                <div class="alert alert-danger text-center mb-4">
                    <i class="ri-close-circle-line me-2"></i>
                    <strong>Pesanan Dibatalkan</strong><br>
                    Pesanan ini telah dibatalkan dan tidak dapat diproses kembali.
                </div>
            @else
                <h4 class="mb-2 text-center">Pesanan Berhasil Dibuat!</h4>
                <p class="mb-4 text-center">Nomor Pesanan Anda: <span class="fw-bold">{{ $order->order_number }}</span></p>
            @endif

            <ul class="list-group mb-4">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Outlet</span>
                    <span>{{ $order->outlet->name }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Meja</span>
                    <span>{{ $order->table->table_number }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Status Pesanan</span>
                    <span class="badge bg-label-{{
                        $order->status === 'completed' ? 'success' :
                        ($order->status === 'cancelled' ? 'danger' : 'warning')
                    }}">{{ $statusOrderIndo[$order->status] ?? ucfirst($order->status) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Status Pembayaran</span>
                    <span class="badge bg-label-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ $statusBayarIndo[$order->payment_status] ?? ucfirst($order->payment_status) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Metode Pembayaran</span>
                    <span>{{ Str::ucfirst($order->payment_method) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Total Belanja</span>
                    <span class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </li>
            </ul>

            @if($order->status !== 'cancelled')
            <h5 class="mb-3">Detail Item Pesanan:</h5>
            <ul class="list-group mb-4">
                @foreach ($order->orderItems as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                            <small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($item->price_at_order, 0, ',', '.') }}</small>
                        </div>
                        <span class="fw-bold">Rp {{ number_format($item->quantity * $item->price_at_order, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            @endif

            <div class="d-grid">
                @if($order->status !== 'cancelled')
                    {{-- <button onclick="window.print()" class="btn btn-primary mb-3">
                        <i class="ri-printer-line me-2"></i>Print Struk
                    </button> --}}
                    <a href="{{ route('order.history') }}" class="btn btn-outline-primary mb-3">Lihat Riwayat Pesanan</a>
                @else
                    <a href="{{ route('order.history') }}" class="btn btn-outline-secondary mb-3">Kembali ke Riwayat Pesanan</a>
                @endif
                <a href="{{ route('welcome') }}" class="btn btn-secondary">Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    {{-- Print Styles --}}
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-section, .print-section * {
                visibility: visible;
            }
            .print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>

    {{-- Print Section --}}
    @if($order->status !== 'cancelled')
    <div class="print-section d-none">
        <div class="text-center p-4">
            <h4>{{ config('app.name') }}</h4>
            <p class="mb-1">{{ $order->outlet->name }}</p>
            <p class="mb-3">Meja: {{ $order->table->table_number }}</p>
            <hr>
            <p class="mb-1">Order #: {{ $order->order_number }}</p>
            <p class="mb-1">Tanggal: {{ $order->ordered_at->format('d/m/Y H:i') }}</p>
            <p class="mb-1">Status Pesanan: {{ Str::ucfirst($order->status) }}</p>
            <p class="mb-3">Status Pembayaran: {{ Str::ucfirst($order->payment_status) }}</p>
            <hr>
            @if($order->status !== 'cancelled')
            @foreach ($order->orderItems as $item)
                <div class="d-flex justify-content-between mb-1">
                    <span>{{ $item->product->name }} ({{ $item->quantity }}x)</span>
                    <span>Rp {{ number_format($item->quantity * $item->price_at_order, 0, ',', '.') }}</span>
                </div>
            @endforeach
            <hr>
            @endif
            @if($order->status !== 'cancelled')
            <div class="d-flex justify-content-between mb-1">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span>Pajak:</span>
                <span>Rp {{ number_format($order->other_fee, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span>Biaya Layanan:</span>
                <span>Rp {{ number_format($order->additional_fee, 0, ',', '.') }}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between fw-bold">
                <span>Total:</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <hr>
            <p class="mb-1">Metode: {{ Str::ucfirst($order->payment_method) }}</p>
            @if($order->note)
                <p class="mb-1">Catatan: {{ $order->note }}</p>
            @endif
            <hr>
            @if($order->status === 'cancelled')
                <p class="mb-0 text-danger fw-bold">PESANAN DIBATALKAN</p>
            @else
                <p class="mb-0">Terima kasih atas pesanan Anda!</p>
            @endif
        </div>
    </div>
    @endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check for session messages (e.g., from OrderController redirect)
        const successMessage = '{{ session('success') }}';
        const errorMessage = '{{ session('error') }}';
        const infoMessage = '{{ session('info') }}';
        const warningMessage = '{{ session('warning') }}';

        if (successMessage) {
            toastr.success(successMessage);
        }
        if (errorMessage) {
            toastr.error(errorMessage);
        }
        if (infoMessage) {
            toastr.info(infoMessage);
        }
        if (warningMessage) {
            toastr.warning(warningMessage);
        }
    });
</script>
@endpush
@endsection
