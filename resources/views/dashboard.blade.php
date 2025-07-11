@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @role('admin')
            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Quick Actions</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('console.orders.index') }}" class="btn btn-primary mb-2">
                                    <i class="ri-shopping-cart-line me-1"></i> Manage Orders
                                </a>
                                <a href="{{ route('console.reporting.index') }}" class="btn btn-info mb-2">
                                    <i class="ri-bar-chart-line me-1"></i> View Reports
                                </a>
                                <a href="{{ route('products.index') }}" class="btn btn-success mb-2">
                                    <i class="ri-cup-line me-1"></i> Manage Products
                                </a>
                                <a href="{{ route('outlets.index') }}" class="btn btn-warning mb-2">
                                    <i class="ri-store-line me-1"></i> Manage Outlets
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- KPI Cards -->
            <div class="row mb-4">
                <div class="col-md-4 col-6 mb-3">
                    <div class="card text-center bg-success text-white">
                        <div class="card-body">
                            <div class="mb-2"><i class="ri-money-dollar-circle-line ri-2x"></i></div>
                            <h6 class="mb-1">Omzet Hari Ini</h6>
                            <h4 class="mb-0">Rp {{ number_format($omzetToday, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6 mb-3">
                    <div class="card text-center bg-primary text-white">
                        <div class="card-body">
                            <div class="mb-2"><i class="ri-bar-chart-line ri-2x"></i></div>
                            <h6 class="mb-1">Omzet Bulan Ini</h6>
                            <h4 class="mb-0">Rp {{ number_format($omzetMonth, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6 mb-3">
                    <div class="card text-center bg-warning text-white">
                        <div class="card-body">
                            <div class="mb-2"><i class="ri-shopping-cart-2-line ri-2x"></i></div>
                            <h6 class="mb-1">Pesanan Hari Ini</h6>
                            <h4 class="mb-0">{{ $ordersToday }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6 mb-3">
                    <div class="card text-center bg-info text-white">
                        <div class="card-body">
                            <div class="mb-2"><i class="ri-user-add-line ri-2x"></i></div>
                            <h6 class="mb-1">Pelanggan Baru Bulan Ini</h6>
                            <h4 class="mb-0">{{ $newCustomersMonth }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6 mb-3">
                    <div class="card text-center bg-secondary text-white">
                        <div class="card-body">
                            <div class="mb-2"><i class="ri-cup-line ri-2x"></i></div>
                            <h6 class="mb-1">Produk Tersedia</h6>
                            <h4 class="mb-0">{{ $availableProducts }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6 mb-3">
                    <div class="card text-center bg-outline-primary text-dark">
                        <div class="card-body">
                            <div class="mb-2"><i class="ri-table-line ri-2x"></i></div>
                            <h6 class="mb-1">Meja Tersedia</h6>
                            <h4 class="mb-0">{{ $availableTables }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Omzet & Pesanan 7 Hari Terakhir -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header"><b>Grafik Omzet 7 Hari Terakhir</b></div>
                        <div class="card-body">
                            <canvas id="omzetChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header"><b>Grafik Pesanan 7 Hari Terakhir</b></div>
                        <div class="card-body">
                            <canvas id="ordersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Produk Terlaris & Produk Jarang Terjual -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header"><b>Top 5 Produk Terlaris</b></div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-end">Terjual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td class="text-end">{{ $product->total_sold ?? 0 }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center">Tidak ada data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header"><b>Produk yang Jarang Terjual</b></div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-end">Terjual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rarelySoldProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td class="text-end">{{ $product->total_sold ?? 0 }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center">Aman</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Terbaru -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><b>5 Pesanan Terbaru</b></div>
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nomor Pesanan</th>
                                        <th>Pelanggan</th>
                                        <th>Outlet</th>
                                        <th>Meja</th>
                                        <th>Total</th>
                                        <th>Status Pembayaran</th>
                                        <th>Waktu Pesan</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse ($latestOrders as $order)
                                        <tr>
                                            <td><span class="fw-medium">{{ $order->order_number }}</span></td>
                                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                                            <td>{{ $order->outlet->name }}</td>
                                            <td>{{ $order->table->table_number }}</td>
                                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                            <td><span class="badge bg-label-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ $statusBayarIndo[$order->payment_status] ?? ucfirst($order->payment_status) }}</span></td>
                                            <td>{{ $order->ordered_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada pesanan terbaru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart.js CDN & Script -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const omzetCtx = document.getElementById('omzetChart').getContext('2d');
                const ordersCtx = document.getElementById('ordersChart').getContext('2d');
                const omzetChart = new Chart(omzetCtx, {
                    type: 'line',
                    data: {
                        labels: @json($dates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
                        datasets: [{
                            label: 'Omzet',
                            data: @json(array_values($omzet7days->toArray())),
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40,167,69,0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } }
                    }
                });
                const ordersChart = new Chart(ordersCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($dates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
                        datasets: [{
                            label: 'Pesanan',
                            data: @json(array_values($orders7days->toArray())),
                            backgroundColor: '#17a2b8',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } }
                    }
                });
            </script>
        @endrole

        @role('kasir')
            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Quick Actions</h6>
                            <div class="d-flex gap-2">
                                <a href="{{ route('console.orders.index') }}" class="btn btn-primary">
                                    <i class="ri-shopping-cart-line me-1"></i>Manage Orders
                                </a>
                                <a href="{{ route('console.reporting.index') }}" class="btn btn-info">
                                    <i class="ri-bar-chart-line me-1"></i>View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-12 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Pending Orders</h6>
                                    <h3 class="mb-0">{{ $quickStats['pending_orders'] }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="ri-time-line ri-2x"></i>
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
                                    <h6 class="card-title">Processing</h6>
                                    <h3 class="mb-0">{{ $quickStats['processing_orders'] }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="ri-settings-3-line ri-2x"></i>
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
                                    <h6 class="card-title">Today Revenue</h6>
                                    <h3 class="mb-0">Rp {{ number_format($quickStats['today_revenue']) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="ri-money-dollar-circle-line ri-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Available Tables</h6>
                                    <h3 class="mb-0">{{ $quickStats['available_tables'] }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="ri-table-line ri-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Pesanan Menunggu Pembayaran -->
                <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="card-title mb-0">
                                    <h5 class="mb-0">Pesanan Menunggu Pembayaran</h5>
                                    <small class="text-muted">Total</small>
                                </div>
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="ri-time-line ri-24px"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h3 class="mb-0">{{ $pendingOrdersCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Omzet Hari Ini (Kasir) -->
                <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="card-title mb-0">
                                    <h5 class="mb-0">Total Omzet Hari Ini</h5>
                                    <small class="text-muted">{{ \Carbon\Carbon::now()->format('d M Y') }}</small>
                                </div>
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="ri-money-dollar-circle-line ri-24px"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h3 class="mb-0">Rp {{ number_format($completedOrdersToday, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mb-4 order-0">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Pesanan Menunggu Pembayaran</h5>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nomor Pesanan</th>
                                        <th>Pelanggan</th>
                                        <th>Meja</th>
                                        <th>Total</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Waktu Pesan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse ($pendingOrders as $order)
                                        <tr>
                                            <td><span class="fw-medium">{{ $order->order_number }}</span></td>
                                            <td>{{ $order->user->name ?? ($order->guest_info['name'] ?? 'Guest') }}</td>
                                            <td>{{ $order->table->table_number }}</td>
                                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                            <td>{{ Str::ucfirst($order->payment_method) }}</td>
                                            <td>{{ $order->ordered_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('console.orders.show', $order->id) }}" class="btn btn-info btn-sm" title="Lihat Detail"><i class="ri-eye-line"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada pesanan menunggu pembayaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endrole

        {{-- For 'user' role, DashboardController redirects to order.history --}}

        @if (!auth()->user()->hasAnyRole('admin', 'kasir', 'user'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="card-title">Selamat Datang!</h4>
                            <p class="card-text">Anda belum memiliki peran. Silakan hubungi administrator.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
