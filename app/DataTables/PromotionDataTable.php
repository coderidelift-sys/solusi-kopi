<?php

namespace App\DataTables;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PromotionDataTable extends DataTable
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
            ->addColumn('action', 'console.promotions.action')
            ->editColumn('discount_value', function (Promotion $promotion) {
                if ($promotion->discount_type === 'percentage') {
                    return $promotion->discount_value . '%';
                }
                return 'Rp ' . number_format($promotion->discount_value, 2, ',', '.');
            })
            ->editColumn('min_order_amount', function (Promotion $promotion) {
                return 'Rp ' . number_format($promotion->min_order_amount, 2, ',', '.');
            })
            ->editColumn('start_date', function (Promotion $promotion) {
                return $promotion->start_date ? $promotion->start_date->format('d F Y') : 'N/A';
            })
            ->editColumn('end_date', function (Promotion $promotion) {
                return $promotion->end_date ? $promotion->end_date->format('d F Y') : 'N/A';
            })
            ->editColumn('status', function (Promotion $promotion) {
                return $promotion->status == 'active' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
            })
            ->rawColumns(['action', 'status']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Promotion $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dom = '<"row mx-1"' .
            '<"col-sm-12 col-md-3 mt-5 mt-md-0" l>' .
            '<"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap me-1"<"me-4"f>B>>' .
            '>t' .
            '<"row mx-2"' .
            '<"col-sm-12 col-md-6"i>' .
            '<"col-sm-12 col-md-6"p>' .
            '>';

        $language = [
            'sLengthMenu' => 'Tampilkan _MENU_',
            'search' => '',
            'searchPlaceholder' => 'Cari Promo...',
            'paginate' => [
                'next' => '<i class="ri-arrow-right-s-line"></i>',
                'previous' => '<i class="ri-arrow-left-s-line"></i>'
            ]
        ];

        $buttons = [
            [
                'text' => '<i class="ri-add-line me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Tambah Promo</span>',
                'className' => 'add-new btn btn-primary mb-5 mb-md-0 me-3 waves-effect waves-light',
                'action' => 'function (e, dt, node, config) { window.location = "' . route('promotions.create') . '"; }'
            ],
            [
                'text' => '<i class="ri-refresh-line me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Muat Ulang</span>',
                'className' => 'btn btn-secondary mb-5 mb-md-0 waves-effect waves-light',
                'action' => 'function (e, dt, node, config) { dt.ajax.reload(); $("#promotions-table_filter input").val("").keyup(); }'
            ]
        ];

        return $this->builder()
            ->setTableId('promotions-table')
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
                'url'  => route('promotions.index'),
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
            Column::make('code')->title('Kode'),
            Column::make('name')->title('Nama Promo'),
            Column::make('discount_type')->title('Tipe Diskon'),
            Column::make('discount_value')->title('Nilai Diskon'),
            Column::make('min_order_amount')->title('Min. Order'),
            Column::make('start_date')->title('Mulai'),
            Column::make('end_date')->title('Berakhir'),
            Column::make('status')->title('Status'),
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
        return 'Promotions_' . date('YmdHis');
    }
}
