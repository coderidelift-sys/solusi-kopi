<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Outlet;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('welcome');
        }

        // Pastikan method hasRole/hasAnyRole tersedia (Spatie Permission)
        // Jika belum, pastikan User model pakai trait HasRoles
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            // KPI Cards
            $today = Carbon::today();
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $omzetToday = Order::where('payment_status', 'paid')
                ->whereDate('ordered_at', $today)
                ->sum('total_amount');
            $omzetMonth = Order::where('payment_status', 'paid')
                ->whereBetween('ordered_at', [$startOfMonth, $endOfMonth])
                ->sum('total_amount');
            $ordersToday = Order::whereDate('ordered_at', $today)->count();
            $newCustomersMonth = User::role('user')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
            $availableProducts = Product::where('is_available', true)->count();
            $availableTables = Table::where('status', 'available')->count();

            // Grafik omzet & pesanan 7 hari terakhir
            $dates = collect(range(0, 6))->map(function($i) {
                return Carbon::today()->subDays(6 - $i)->format('Y-m-d');
            });
            $omzet7days = $dates->mapWithKeys(function($date) {
                $sum = Order::where('payment_status', 'paid')
                    ->whereDate('ordered_at', $date)
                    ->sum('total_amount');
                return [$date => $sum];
            });
            $orders7days = $dates->mapWithKeys(function($date) {
                $count = Order::whereDate('ordered_at', $date)->count();
                return [$date => $count];
            });

            // Top 5 produk terlaris
            $topProducts = Product::select('products.id', 'products.name')
                ->withCount(['orderItems as total_sold' => function($q) {
                    $q->select(DB::raw('SUM(quantity)'));
                }])
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();

            // 5 pesanan terbaru
            $latestOrders = Order::with(['user', 'outlet', 'table'])
                ->orderBy('ordered_at', 'desc')
                ->limit(5)
                ->get();

            // (Opsional) Produk yang jarang terjual (top 5, exclude yang total_sold == 0 jika ingin)
            $rarelySoldProducts = Product::select('products.id', 'products.name')
                ->withCount(['orderItems as total_sold' => function($q) {
                    $q->select(DB::raw('SUM(quantity)'));
                }])
                ->orderBy('total_sold', 'asc')
                ->limit(5)
                ->get();

            return view('dashboard', compact(
                'omzetToday',
                'omzetMonth',
                'ordersToday',
                'newCustomersMonth',
                'availableProducts',
                'availableTables',
                'omzet7days',
                'orders7days',
                'topProducts',
                'latestOrders',
                'rarelySoldProducts',
                'dates'
            ));
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('kasir')) {
            // Data untuk Kasir Dashboard
            $today = Carbon::today();
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $omzetToday = Order::where('payment_status', 'paid')
                ->whereDate('ordered_at', $today)
                ->sum('total_amount');
            $omzetMonth = Order::where('payment_status', 'paid')
                ->whereBetween('ordered_at', [$startOfMonth, $endOfMonth])
                ->sum('total_amount');
            $ordersToday = Order::whereDate('ordered_at', $today)->count();
            $availableProducts = Product::where('is_available', true)->count();
            $availableTables = Table::where('status', 'available')->count();

            $pendingOrders = Order::where('payment_status', 'pending')
                                  ->whereIn('payment_method', ['cashier', 'qris'])
                                  ->orderBy('ordered_at', 'asc')
                                  ->with(['user', 'outlet', 'table', 'orderItems.product'])
                                  ->get();
            $pendingOrdersCount = $pendingOrders->count();

            $completedOrdersToday = $omzetToday;

            // Quick stats untuk kasir (sinkron dengan admin)
            $quickStats = [
                'omzet_today' => $omzetToday,
                'omzet_month' => $omzetMonth,
                'orders_today' => $ordersToday,
                'available_products' => $availableProducts,
                'available_tables' => $availableTables,
                'pending_orders' => Order::where('status', 'pending')->count(),
                'processing_orders' => Order::whereIn('status', ['preparing', 'ready', 'served'])->count(),
                'today_revenue' => $omzetToday,
            ];

            return view('dashboard', compact('pendingOrders', 'pendingOrdersCount', 'completedOrdersToday', 'quickStats', 'omzetToday', 'omzetMonth', 'ordersToday', 'availableProducts', 'availableTables'));
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('user')) {
            // Untuk user biasa, redirect ke riwayat pesanan
            return redirect()->route('order.history');
        }

        // Fallback jika tidak ada role
        return view('dashboard');
    }
}
