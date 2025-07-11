<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $outlets = Outlet::get();
        return view('welcome', compact('outlets'));
    }

    public function searchTable(Request $request)
    {
        $request->validate([
            'table_code' => 'required|string|max:10'
        ]);

        $table = Table::where('table_code', $request->table_code)
            ->orWhere('table_number', $request->table_code)
            ->where('status', '!=', 'unavailable')
            ->with('outlet')
            ->first();

        if (!$table) {
            return back()->with('error', 'Kode meja tidak ditemukan atau tidak aktif.');
        }

        return redirect()->route('welcome.select-table', ['table_code' => $table->table_code]);
    }

    public function selectTable($table_code)
    {
        $table = Table::where('table_code', $table_code)
            ->orWhere('table_number', $table_code)
            ->where('status', '!=', 'unavailable')
            ->with('outlet')
            ->first();

        if (!$table) {
            return redirect('/')->with('error', 'Meja tidak ditemukan.');
        }

        return view('select-login', compact('table'));
    }
}
