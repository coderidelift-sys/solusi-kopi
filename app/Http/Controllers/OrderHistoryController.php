<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $orders = collect(); // Inisialisasi koleksi kosong

        if (Auth::check()) {
            // Pengguna login, ambil pesanan berdasarkan user_id
            $orders = Auth::user()->orders()->with(['outlet', 'table', 'items.product', 'payments'])
                                   ->latest()
                                   ->paginate(10); // Gunakan paginate untuk pengguna login
        } else {
            // Pengguna guest, ambil pesanan dari sesi
            $guestOrderIds = Session::get('guest_order_history', []);
            if (!empty($guestOrderIds)) {
                $orders = Order::whereIn('id', $guestOrderIds)
                               ->whereNull('user_id') // Pastikan ini benar-benar pesanan tamu
                               ->with(['outlet', 'table', 'items.product', 'payments'])
                               ->latest()
                               ->get();
                // Untuk guest, kita tidak menggunakan pagination karena berbasis sesi.
                // Jika daftar terlalu panjang dalam satu sesi, mungkin perlu solusi lebih kompleks.
            }
        }

        return view('order.history.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Pastikan pengguna (login atau tamu) memiliki akses ke pesanan ini
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403); // Terlarang
        } elseif (!Auth::check() && !in_array($order->id, Session::get('guest_order_history', []))) {
            abort(403); // Terlarang
        }

        $order->load(['outlet', 'table', 'items.product', 'payments']); // Eager load relasi
        return view('order.history.show', compact('order'));
    }
}
