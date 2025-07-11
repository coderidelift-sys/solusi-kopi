<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OrderManagementController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'outlet', 'table', 'orderItems.product', 'promotion'])
            ->orderBy('ordered_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by outlet
        if ($request->filled('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('ordered_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('ordered_at', '<=', $request->date_to);
        }

        // Search by order number
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->paginate(7);

        // Get statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'preparing_orders' => Order::where('status', Order::STATUS_PREPARING)->count(),
            'ready_orders' => Order::where('status', Order::STATUS_READY)->count(),
            'served_orders' => Order::where('status', Order::STATUS_SERVED)->count(),
            'completed_orders' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'cancelled_orders' => Order::where('status', Order::STATUS_CANCELLED)->count(),
            'available_tables' => Table::where('status', 'available')->count(),
            'occupied_tables' => Table::where('status', 'occupied')->count(),
        ];

        // Get available statuses for filter
        $statuses = Order::getStatuses();
        $paymentStatuses = Order::getPaymentStatuses();
        $outlets = \App\Models\Outlet::all();

        return view('console.order-management.index', compact(
            'orders',
            'stats',
            'statuses',
            'paymentStatuses',
            'outlets'
        ));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'outlet', 'table', 'orderItems.product', 'promotion', 'payments']);

        // Get available statuses for update
        $statuses = Order::getStatuses();
        $paymentStatuses = Order::getPaymentStatuses();

        return view('console.order-management.show', compact('order', 'statuses', 'paymentStatuses'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses()))
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Check if the status transition is allowed
        if (!$order->canUpdateToStatus($newStatus)) {
            return response()->json([
                'success' => false,
                'message' => 'Status transition not allowed'
            ], 400);
        }

        // Update order status
        $order->update(['status' => $newStatus]);

        // Handle table status based on order status
        $this->updateTableStatus($order, $oldStatus, $newStatus);

        // Set completed_at if order is completed
        if ($newStatus === Order::STATUS_COMPLETED) {
            $order->update(['completed_at' => now()]);
        }

        // Log the status change
        Log::info("Order status updated", [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'updated_by' => auth()->user()->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status order berhasil diperbarui',
            'order' => $order->fresh()
        ]);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:' . implode(',', array_keys(Order::getPaymentStatuses()))
        ]);

        $oldPaymentStatus = $order->payment_status;
        $newPaymentStatus = $request->payment_status;

        $order->update(['payment_status' => $newPaymentStatus]);

        // If payment is successful, update order status to preparing
        if ($newPaymentStatus === Order::PAYMENT_STATUS_PAID && $order->status === Order::STATUS_PENDING) {
            $order->update(['status' => Order::STATUS_PREPARING]);
            $this->updateTableStatus($order, Order::STATUS_PENDING, Order::STATUS_PREPARING);
        }

        Log::info("Payment status updated", [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'old_payment_status' => $oldPaymentStatus,
            'new_payment_status' => $newPaymentStatus,
            'updated_by' => auth()->user()->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui',
            'order' => $order->fresh()
        ]);
    }

    /**
     * Update table status based on order status
     */
    private function updateTableStatus(Order $order, $oldStatus, $newStatus)
    {
        $table = $order->table;

        // Define status transitions that affect table availability
        $tableOccupiedStatuses = [Order::STATUS_PREPARING, Order::STATUS_READY, Order::STATUS_SERVED];
        $tableAvailableStatuses = [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED];

        if (in_array($newStatus, $tableOccupiedStatuses)) {
            // Table becomes occupied
            $table->update(['status' => 'occupied']);
            Log::info("Table status updated to occupied", [
                'table_id' => $table->id,
                'table_number' => $table->table_number,
                'order_id' => $order->id
            ]);
        } elseif (in_array($newStatus, $tableAvailableStatuses)) {
            // Table becomes available again
            $table->update(['status' => 'available']);
            Log::info("Table status updated to available", [
                'table_id' => $table->id,
                'table_number' => $table->table_number,
                'order_id' => $order->id
            ]);
        }
    }

    /**
     * Get real-time statistics
     */
    public function getStats()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'preparing_orders' => Order::where('status', Order::STATUS_PREPARING)->count(),
            'ready_orders' => Order::where('status', Order::STATUS_READY)->count(),
            'served_orders' => Order::where('status', Order::STATUS_SERVED)->count(),
            'completed_orders' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'cancelled_orders' => Order::where('status', Order::STATUS_CANCELLED)->count(),
            'available_tables' => Table::where('status', 'available')->count(),
            'occupied_tables' => Table::where('status', 'occupied')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Export orders data
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'outlet', 'table', 'orderItems.product'])
            ->orderBy('ordered_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('ordered_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('ordered_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $filename = 'orders_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Order Number', 'Customer', 'Outlet', 'Table', 'Status',
                'Payment Status', 'Payment Method', 'Total Amount', 'Subtotal',
                'Tax', 'Service Fee', 'Discount', 'Order Date', 'Completed Date'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user ? $order->user->name : ($order->guest_info['name'] ?? 'Guest'),
                    $order->outlet->name,
                    $order->table->table_number,
                    $this->getStatusLabel($order->status),
                    $this->getPaymentStatusLabel($order->payment_status),
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

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get status label
     */
    private function getStatusLabel($status)
    {
        $statuses = Order::getStatuses();
        return $statuses[$status] ?? $status;
    }

    /**
     * Get payment status label
     */
    private function getPaymentStatusLabel($status)
    {
        $paymentStatuses = Order::getPaymentStatuses();
        return $paymentStatuses[$status] ?? $status;
    }
}
