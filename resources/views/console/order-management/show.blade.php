@extends('layouts.app')

@section('title', 'Order Detail - ' . $order->order_number)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Order Detail</h4>
                    <a href="{{ route('console.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ri-arrow-left-line me-1"></i>Back to Orders
                    </a>
                </div>
                <div class="card-body">
                    <!-- Order Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Order Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120">Order Number:</td>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Order Date:</td>
                                    <td>{{ $order->ordered_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td>Status:</td>
                                    <td>
                                        <span class="badge bg-label-{{
                                            $order->status === 'completed' ? 'success' :
                                            ($order->status === 'cancelled' ? 'danger' :
                                            ($order->status === 'served' ? 'info' :
                                            ($order->status === 'ready' ? 'secondary' :
                                            ($order->status === 'preparing' ? 'warning' : 'primary'))))
                                        }}">
                                            {{ $statuses[$order->status] ?? ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Payment Status:</td>
                                    <td>
                                        <span class="badge bg-label-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ $paymentStatuses[$order->payment_status] ?? ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Payment Method:</td>
                                    <td>{{ ucfirst($order->payment_method) }}</td>
                                </tr>
                                @if($order->completed_at)
                                <tr>
                                    <td>Completed At:</td>
                                    <td>{{ $order->completed_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Customer Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120">Name:</td>
                                    <td>
                                        @if($order->user)
                                            {{ $order->user->name }}
                                        @else
                                            {{ $order->guest_info['name'] ?? 'Guest' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>
                                        @if($order->user)
                                            {{ $order->user->email }}
                                        @else
                                            {{ $order->guest_info['email'] ?? '-' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Phone:</td>
                                    <td>
                                        @if($order->user)
                                            {{ $order->user->phone ?? '-' }}
                                        @else
                                            {{ $order->guest_info['phone'] ?? '-' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Outlet:</td>
                                    <td>{{ $order->outlet->name }}</td>
                                </tr>
                                <tr>
                                    <td>Table:</td>
                                    <td>
                                        <span class="badge bg-label-{{ $order->table->status === 'available' ? 'success' : 'warning' }}">
                                            {{ $order->table->table_number }}
                                        </span>
                                        <small class="text-muted">({{ ucfirst($order->table->status) }})</small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Order Items</h6>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $item->product->name }}</strong>
                                                @if($item->note)
                                                    <br><small class="text-muted">{{ $item->note }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->price_at_order, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($order->other_fee > 0)
                                <tr>
                                    <td>Tax:</td>
                                    <td class="text-end">Rp {{ number_format($order->other_fee, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                @if($order->additional_fee > 0)
                                <tr>
                                    <td>Service Fee:</td>
                                    <td class="text-end">Rp {{ number_format($order->additional_fee, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td>Discount:</td>
                                    <td class="text-end text-danger">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr class="border-top">
                                    <td><strong>Total:</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($order->note)
                    <div class="mt-4">
                        <h6 class="fw-bold">Order Note</h6>
                        <p class="text-muted">{{ $order->note }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4 order-1">
            <!-- Status Update -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Update Status</h6>
                </div>
                <div class="card-body">
                    <form id="statusForm">
                        <div class="mb-3">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-select" required>
                                @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Payment Status Update -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Update Payment Status</h6>
                </div>
                <div class="card-body">
                    <form id="paymentForm">
                        <div class="mb-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select" required>
                                @foreach($paymentStatuses as $value => $label)
                                <option value="{{ $value }}" {{ $order->payment_status === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            Update Payment Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Order Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Order Placed</h6>
                                <p class="text-muted mb-0">{{ $order->ordered_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        @if($order->status !== 'pending')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Order Confirmed</h6>
                                <p class="text-muted mb-0">Payment received, order confirmed</p>
                            </div>
                        </div>
                        @endif

                        @if(in_array($order->status, ['preparing', 'ready', 'served', 'completed']))
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Preparing</h6>
                                <p class="text-muted mb-0">Kitchen is preparing the order</p>
                            </div>
                        </div>
                        @endif

                        @if(in_array($order->status, ['ready', 'served', 'completed']))
                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Ready to Serve</h6>
                                <p class="text-muted mb-0">Order is ready to be served</p>
                            </div>
                        </div>
                        @endif

                        @if(in_array($order->status, ['served', 'completed']))
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Served</h6>
                                <p class="text-muted mb-0">Order has been served to customer</p>
                            </div>
                        </div>
                        @endif

                        @if($order->status === 'completed')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-dark"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Completed</h6>
                                <p class="text-muted mb-0">{{ $order->completed_at ? $order->completed_at->format('d/m/Y H:i') : 'Order completed' }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->status === 'cancelled')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cancelled</h6>
                                <p class="text-muted mb-0">Order has been cancelled</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Helper untuk validasi status
function canUpdateStatus(currentStatus, currentPaymentStatus, newStatus) {
    // Tidak bisa dibatalkan jika sudah paid atau completed
    if ((currentPaymentStatus === 'paid' || currentStatus === 'completed') && newStatus === 'cancelled') {
        return false;
    }
    // Tidak bisa ubah status jika sudah completed/cancelled
    if (['completed', 'cancelled'].includes(currentStatus)) {
        return false;
    }
    // Tidak bisa mundur status (misal dari served ke preparing)
    const orderFlow = ['pending', 'preparing', 'ready', 'served', 'completed'];
    const idxCurrent = orderFlow.indexOf(currentStatus);
    const idxNew = orderFlow.indexOf(newStatus);
    if (idxNew < idxCurrent && newStatus !== 'cancelled') {
        return false;
    }
    return true;
}

// Update Status
const statusForm = document.getElementById('statusForm');
statusForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const newStatus = formData.get('status');
    const currentStatus = "{{ $order->status }}";
    const currentPaymentStatus = "{{ $order->payment_status }}";
    if (!canUpdateStatus(currentStatus, currentPaymentStatus, newStatus)) {
        Swal.fire('Tidak Diizinkan', 'Status tidak bisa diubah ke status tersebut karena sudah dibayar/selesai/dibatalkan atau urutan status tidak valid.', 'error');
        return;
    }
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin mengubah status pesanan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/console/orders/{{ $order->id }}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil', 'Status pesanan berhasil diubah.', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal', 'Gagal mengubah status pesanan.', 'error');
                }
            })
            .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan saat mengubah status.', 'error'));
        }
    });
});

// Update Payment Status
const paymentForm = document.getElementById('paymentForm');
paymentForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const newPaymentStatus = formData.get('payment_status');
    const currentStatus = "{{ $order->status }}";
    const currentPaymentStatus = "{{ $order->payment_status }}";
    // Tidak bisa ubah payment status jika order sudah dibatalkan/selesai
    if (['completed', 'cancelled'].includes(currentStatus)) {
        Swal.fire('Tidak Diizinkan', 'Status pembayaran tidak bisa diubah karena pesanan sudah selesai/dibatalkan.', 'error');
        return;
    }
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin mengubah status pembayaran?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/console/orders/{{ $order->id }}/payment`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ payment_status: newPaymentStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil', 'Status pembayaran berhasil diubah.', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal', 'Gagal mengubah status pembayaran.', 'error');
                }
            })
            .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan saat mengubah status pembayaran.', 'error'));
        }
    });
});
</script>

<style>
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
@endpush
