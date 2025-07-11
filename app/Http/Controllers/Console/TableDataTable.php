<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Outlet;
use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;
use Illuminate\Http\Request;
use App\DataTables\TableDataTable;

class TableController extends Controller
{
    public function index(TableDataTable $dataTable)
    {
        return $dataTable->render('console.tables.index');
    }

    public function create()
    {
        $outlets = Outlet::all();
        return view('console.tables.create', compact('outlets'));
    }

    public function store(StoreTableRequest $request)
    {
        Table::create($request->validated());
        return redirect()->route('tables.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function show(Table $table)
    {
        return view('console.tables.show', compact('table'));
    }

    public function edit(Table $table)
    {
        $outlets = Outlet::all();
        return view('console.tables.edit', compact('table', 'outlets'));
    }

    public function update(UpdateTableRequest $request, Table $table)
    {
        $table->update($request->validated());
        return redirect()->route('tables.index')->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Meja berhasil dihapus.');
    }
}
