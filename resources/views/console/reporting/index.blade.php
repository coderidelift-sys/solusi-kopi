@extends('layouts.app')

@section('title', 'Reporting Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Reporting Dashboard</h4>
                    <div>
                        <a href="{{ route('console.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ri-arrow-left-line me-1"></i>Order Management
                        </a>
                        <button class="btn btn-outline-success btn-sm" onclick="exportReport()">
                            <i class="ri-download-line me-1"></i>Export Report
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Date Filter -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="GET" class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Date From</label>
                                            <input type="date" name="date_from" class="form-control"
                                                   value="{{ $dateFrom->format('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Date To</label>
                                            <input type="date" name="date_to" class="form-control"
                                                   value="{{ $dateTo->format('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Outlet</label>
                                            <select name="outlet_id" class="form-select">
                                                <option value="">All Outlets</option>
                                                @foreach(\App\Models\Outlet::all() as $outlet)
                                                <option value="{{ $outlet->id }}" {{ $outletId == $outlet->id ? 'selected' : '' }}>
                                                    {{ $outlet->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary d-block w-100">
                                                <i class="ri-search-line me-1"></i>Apply Filter
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Status Alert -->
                    @if($revenueStats['total_orders'] == 0)
                    <div class="alert alert-info mb-4">
                        <i class="ri-information-line me-2"></i>
                        <strong>Info:</strong> Tidak ada data order untuk periode yang dipilih.
                        Data yang ditampilkan adalah sample data untuk demonstrasi.
                        <a href="{{ route('console.orders.index') }}" class="alert-link">Kelola Order</a> untuk menambahkan data real.
                    </div>
                    @endif

                    <!-- Revenue Statistics -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Total Revenue</h6>
                                            <h3 class="mb-0">Rp {{ number_format($revenueStats['total_revenue']) }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="ri-money-dollar-circle-line ri-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Total Orders</h6>
                                            <h3 class="mb-0">{{ number_format($revenueStats['total_orders']) }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="ri-shopping-cart-line ri-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Paid Orders</h6>
                                            <h3 class="mb-0">{{ number_format($revenueStats['paid_orders']) }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="ri-check-line ri-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Avg Order Value</h6>
                                            <h3 class="mb-0">Rp {{ number_format($revenueStats['average_order_value']) }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="ri-bar-chart-line ri-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-lg-8 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Daily Revenue Trend</h6>
                                </div>
                                <div class="card-body">
                                    @if($dailyRevenue->count() > 0)
                                        <canvas id="revenueChart" height="300"></canvas>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="ri-bar-chart-line ri-3x text-muted"></i>
                                            <p class="text-muted mt-2">Tidak ada data revenue untuk ditampilkan</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Order Status Distribution</h6>
                                </div>
                                <div class="card-body">
                                    @if(count($orderStatusStats) > 0)
                                        <canvas id="statusChart" height="300"></canvas>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="ri-pie-chart-line ri-3x text-muted"></i>
                                            <p class="text-muted mt-2">Tidak ada data status order</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Products & Payment Methods -->
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Top Selling Products</h6>
                                </div>
                                <div class="card-body">
                                    @if($topProducts->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Qty Sold</th>
                                                        <th>Revenue</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($topProducts as $product)
                                                    <tr>
                                                        <td>{{ $product->name }}</td>
                                                        <td>{{ number_format($product->total_quantity) }}</td>
                                                        <td>Rp {{ number_format($product->total_revenue) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="ri-cup-line ri-2x text-muted"></i>
                                            <p class="text-muted mt-2">Tidak ada data produk terjual</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Payment Method Distribution</h6>
                                </div>
                                <div class="card-body">
                                    @if($paymentMethodStats->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Method</th>
                                                        <th>Orders</th>
                                                        <th>Revenue</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($paymentMethodStats as $method)
                                                    <tr>
                                                        <td>{{ ucfirst($method->payment_method) }}</td>
                                                        <td>{{ number_format($method->count) }}</td>
                                                        <td>Rp {{ number_format($method->total) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="ri-bank-card-line ri-2x text-muted"></i>
                                            <p class="text-muted mt-2">Tidak ada data metode pembayaran</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Outlet Performance -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Outlet Performance</h6>
                                </div>
                                <div class="card-body">
                                    @if($outletPerformance->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Outlet</th>
                                                        <th>Total Orders</th>
                                                        <th>Revenue</th>
                                                        <th>Avg Order Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($outletPerformance as $outlet)
                                                    <tr>
                                                        <td>{{ $outlet->name }}</td>
                                                        <td>{{ number_format($outlet->orders_count) }}</td>
                                                        <td>Rp {{ number_format($outlet->orders_sum_total_amount ?? 0) }}</td>
                                                        <td>
                                                            @if($outlet->orders_count > 0)
                                                                Rp {{ number_format(($outlet->orders_sum_total_amount ?? 0) / $outlet->orders_count) }}
                                                            @else
                                                                Rp 0
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="ri-store-line ri-2x text-muted"></i>
                                            <p class="text-muted mt-2">Tidak ada data performa outlet</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="mb-3">
                        <label class="form-label">Report Type</label>
                        <select name="report_type" class="form-select" required>
                            <option value="orders">Orders Report</option>
                            <option value="products">Products Report</option>
                            <option value="revenue">Revenue Report</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control"
                               value="{{ $dateFrom->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control"
                               value="{{ $dateTo->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Outlet (Optional)</label>
                        <select name="outlet_id" class="form-select">
                            <option value="">All Outlets</option>
                            @foreach(\App\Models\Outlet::all() as $outlet)
                            <option value="{{ $outlet->id }}" {{ $outletId == $outlet->id ? 'selected' : '' }}>
                                {{ $outlet->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="downloadReport()">Export</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
@if($dailyRevenue->count() > 0)
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($dailyRevenue->pluck('date')),
        datasets: [{
            label: 'Revenue',
            data: @json($dailyRevenue->pluck('revenue')),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                    }
                }
            }
        }
    }
});
@endif

// Status Chart
@if(count($orderStatusStats) > 0)
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: @json(array_keys($orderStatusStats)),
        datasets: [{
            data: @json(array_values($orderStatusStats)),
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
@endif

function exportReport() {
    new bootstrap.Modal(document.getElementById('exportModal')).show();
}

function downloadReport() {
    const form = document.getElementById('exportForm');
    const formData = new FormData(form);

    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }

    window.open(`/console/reporting/export?${params.toString()}`, '_blank');

    bootstrap.Modal.getInstance(document.getElementById('exportModal')).hide();
}
</script>
@endpush
