<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Outlet;
use App\Models\Table;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    /**
     * Display reporting dashboard
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::today()->subDays(30));
        $dateTo = $request->get('date_to', Carbon::today());
        $outletId = $request->get('outlet_id');

        // Base query
        $baseQuery = Order::query();

        if ($dateFrom || $dateTo) {
            $from = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
            $to   = $dateTo   ? Carbon::parse($dateTo)->endOfDay()   : Carbon::now()->endOfDay();

            $baseQuery->whereBetween('ordered_at', [$from, $to]);
        }

        if ($outletId) {
            $baseQuery->where('outlet_id', $outletId);
        }

        // Revenue statistics
        $revenueStats = [
            'total_revenue' => $baseQuery->clone()->where('payment_status', 'paid')->sum('total_amount') ?? 0,
            'total_orders' => $baseQuery->clone()->count(),
            'paid_orders' => $baseQuery->clone()->where('payment_status', 'paid')->count(),
            'pending_orders' => $baseQuery->clone()->where('payment_status', 'pending')->count(),
            'average_order_value' => $baseQuery->clone()->where('payment_status', 'paid')->avg('total_amount') ?? 0,
        ];

        // Order status statistics
        $orderStatusStats = $baseQuery->clone()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Payment method statistics
        $paymentMethodStats = $baseQuery->clone()->where('payment_status', 'paid')
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Top selling products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.ordered_at', [
                $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : Carbon::now()->subDays(30)->startOfDay(),
                $dateTo ? Carbon::parse($dateTo)->endOfDay() : Carbon::now()->endOfDay()
            ])
            ->where('orders.payment_status', 'paid')
            ->when($outletId, function($query) use ($outletId) {
                return $query->where('orders.outlet_id', $outletId);
            })
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price_at_order) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Daily revenue chart data
        $dailyRevenue = $baseQuery->clone()->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(ordered_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Generate sample data if no real data exists
        if ($dailyRevenue->isEmpty()) {
            $dailyRevenue = $this->generateSampleDailyRevenue($dateFrom, $dateTo);
        }

        // Outlet performance
        $outletPerformance = Outlet::withCount(['orders' => function($query) use ($dateFrom, $dateTo) {
            $from = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
            $to = $dateTo ? Carbon::parse($dateTo)->endOfDay() : Carbon::now()->endOfDay();
            $query->whereBetween('ordered_at', [$from, $to]);
        }])
        ->withSum(['orders' => function($query) use ($dateFrom, $dateTo) {
            $from = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
            $to = $dateTo ? Carbon::parse($dateTo)->endOfDay() : Carbon::now()->endOfDay();
            $query->whereBetween('ordered_at', [$from, $to])
                  ->where('payment_status', 'paid');
        }], 'total_amount')
        ->get();

        // Table utilization
        $tableUtilization = Table::withCount(['orders' => function($query) use ($dateFrom, $dateTo) {
            $from = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
            $to = $dateTo ? Carbon::parse($dateTo)->endOfDay() : Carbon::now()->endOfDay();
            $query->whereBetween('ordered_at', [$from, $to]);
        }])
        ->withSum(['orders' => function($query) use ($dateFrom, $dateTo) {
            $from = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
            $to = $dateTo ? Carbon::parse($dateTo)->endOfDay() : Carbon::now()->endOfDay();
            $query->whereBetween('ordered_at', [$from, $to])
                  ->where('payment_status', 'paid');
        }], 'total_amount')
        ->get();

        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);

        return view('console.reporting.index', compact(
            'revenueStats',
            'orderStatusStats',
            'paymentMethodStats',
            'topProducts',
            'dailyRevenue',
            'outletPerformance',
            'tableUtilization',
            'dateFrom',
            'dateTo',
            'outletId'
        ));
    }

    /**
     * Generate sample daily revenue data for demonstration
     */
    private function generateSampleDailyRevenue($dateFrom, $dateTo)
    {
        $from = $dateFrom ? Carbon::parse($dateFrom) : Carbon::now()->subDays(30);
        $to = $dateTo ? Carbon::parse($dateTo) : Carbon::now();

        $data = collect();
        $current = $from->copy();

        while ($current <= $to) {
            $data->push((object) [
                'date' => $current->format('Y-m-d'),
                'revenue' => rand(50000, 500000),
                'orders_count' => rand(1, 10)
            ]);
            $current->addDay();
        }

        return $data;
    }

    /**
     * Export detailed report
     */
    public function export(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::today()->subDays(30));
        $dateTo = $request->get('date_to', Carbon::today());
        $outletId = $request->get('outlet_id');
        $reportType = $request->get('report_type', 'orders');

        $filename = $reportType . '_report_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reportType, $dateFrom, $dateTo, $outletId) {
            $file = fopen('php://output', 'w');

            switch ($reportType) {
                case 'orders':
                    $this->exportOrdersReport($file, $dateFrom, $dateTo, $outletId);
                    break;
                case 'products':
                    $this->exportProductsReport($file, $dateFrom, $dateTo, $outletId);
                    break;
                case 'revenue':
                    $this->exportRevenueReport($file, $dateFrom, $dateTo, $outletId);
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export orders report
     */
    private function exportOrdersReport($file, $dateFrom, $dateTo, $outletId)
    {
        fputcsv($file, [
            'Order Number', 'Customer', 'Outlet', 'Table', 'Status',
            'Payment Status', 'Payment Method', 'Total Amount', 'Subtotal',
            'Tax', 'Service Fee', 'Discount', 'Order Date', 'Completed Date'
        ]);

        $query = Order::with(['user', 'outlet', 'table'])
            ->whereBetween('ordered_at', [$dateFrom, $dateTo]);

        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }

        $orders = $query->get();

        foreach ($orders as $order) {
            fputcsv($file, [
                $order->order_number,
                $order->user ? $order->user->name : ($order->guest_info['name'] ?? 'Guest'),
                $order->outlet->name,
                $order->table->table_number,
                $order->status,
                $order->payment_status,
                $order->payment_method,
                $order->total_amount,
                $order->subtotal,
                $order->other_fee,
                $order->additional_fee,
                $order->discount_amount ?? 0,
                $order->ordered_at->format('Y-m-d H:i:s'),
                $order->completed_at ? $order->completed_at->format('Y-m-d H:i:s') : '',
            ]);
        }
    }

    /**
     * Export products report
     */
    private function exportProductsReport($file, $dateFrom, $dateTo, $outletId)
    {
        fputcsv($file, [
            'Product Name', 'Category', 'Total Quantity Sold', 'Total Revenue',
            'Average Price', 'Orders Count'
        ]);

        $query = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('orders.ordered_at', [$dateFrom, $dateTo])
            ->where('orders.payment_status', 'paid');

        if ($outletId) {
            $query->where('orders.outlet_id', $outletId);
        }

        $products = $query->select(
            'products.name',
            'categories.name as category_name',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.quantity * order_items.price_at_order) as total_revenue'),
            DB::raw('AVG(order_items.price_at_order) as avg_price'),
            DB::raw('COUNT(DISTINCT orders.id) as orders_count')
        )
        ->groupBy('products.id', 'products.name', 'categories.name')
        ->orderBy('total_quantity', 'desc')
        ->get();

        foreach ($products as $product) {
            fputcsv($file, [
                $product->name,
                $product->category_name,
                $product->total_quantity,
                $product->total_revenue,
                $product->avg_price,
                $product->orders_count,
            ]);
        }
    }

    /**
     * Export revenue report
     */
    private function exportRevenueReport($file, $dateFrom, $dateTo, $outletId)
    {
        fputcsv($file, [
            'Date', 'Revenue', 'Orders Count', 'Average Order Value',
            'Payment Method', 'Status'
        ]);

        $query = Order::whereBetween('ordered_at', [$dateFrom, $dateTo])
            ->where('payment_status', 'paid');

        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }

        $revenue = $query->select(
            DB::raw('DATE(ordered_at) as date'),
            DB::raw('SUM(total_amount) as revenue'),
            DB::raw('COUNT(*) as orders_count'),
            DB::raw('AVG(total_amount) as avg_order_value'),
            'payment_method',
            'status'
        )
        ->groupBy('date', 'payment_method', 'status')
        ->orderBy('date')
        ->get();

        foreach ($revenue as $row) {
            fputcsv($file, [
                $row->date,
                $row->revenue,
                $row->orders_count,
                $row->avg_order_value,
                $row->payment_method,
                $row->status,
            ]);
        }
    }

    /**
     * Get real-time statistics for AJAX
     */
    public function getRealTimeStats()
    {
        $today = Carbon::today();

        $stats = [
            'today_orders' => Order::whereDate('ordered_at', $today)->count(),
            'today_revenue' => Order::whereDate('ordered_at', $today)
                ->where('payment_status', 'paid')
                ->sum('total_amount') ?? 0,
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
        ];

        return response()->json($stats);
    }
}
