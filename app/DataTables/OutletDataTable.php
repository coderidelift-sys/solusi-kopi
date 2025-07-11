<?php

namespace App\DataTables;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OutletDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', 'console.outlets.action')
            ->editColumn('updated_at', function ($outlet) {
                return $outlet->updated_at->format('d F Y H:i');
            })
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Outlet $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('updated_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        // Konfigurasi DOM untuk DataTables (mengikuti referensi users)
        $dom = '<"row mx-1"' .
            '<"col-sm-12 col-md-3 mt-5 mt-md-0" l>' .
            '<"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap me-1"<"me-4"f>B>>' .
            '>t' .
            '<"row mx-2"' .
            '<"col-sm-12 col-md-6"i>' .
            '<"col-sm-12 col-md-6"p>' .
            '>';

        // Konfigurasi bahasa untuk DataTables
        $language = [
            'sLengthMenu' => 'Tampilkan _MENU_',
            'search' => '',
            'searchPlaceholder' => 'Cari Outlet...',
            'paginate' => [
                'next' => '<i class="ri-arrow-right-s-line"></i>',
                'previous' => '<i class="ri-arrow-left-s-line"></i>'
            ]
        ];

        // Konfigurasi tombol
        $buttons = [
            // [
            //     'text' => '<i class="ri-add-line me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Tambah Outlet</span>',
            //     'className' => 'add-new btn btn-primary mb-5 mb-md-0 me-3 waves-effect waves-light',
            //     'action' => 'function (e, dt, node, config) { window.location = "' . route('outlets.create') . '"; }'
            // ],
            [
                'text' => '<i class="ri-refresh-line me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Muat Ulang</span>',
                'className' => 'btn btn-secondary mb-5 mb-md-0 waves-effect waves-light',
                'action' => 'function (e, dt, node, config) { dt.ajax.reload(); $("#outlets-table_filter input").val("").keyup(); }'
            ]
        ];

        return $this->builder()
            ->setTableId('outlets-table')
            ->columns($this->getColumns())
            ->parameters([
                'order' => [[0, 'desc']],
                'dom' => $dom,
                'language' => $language,
                'buttons' => $buttons,
                'responsive' => false,
                'autoWidth' => false,
            ])
            ->ajax([
                'url'  => route('outlets.index'),
                'type' => 'GET',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false)->searchable(false),
            Column::make('name')->title('Nama Outlet'),
            Column::make('address')->title('Alamat'),
            Column::make('opening_hours')->title('Jam Buka'),
            Column::make('updated_at')->title('Terakhir Diperbarui')
                ->searchable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->title('Aksi'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Outlets_' . date('YmdHis');
    }
}
