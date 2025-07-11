@extends('layouts.app')

@section('title', 'Manajemen Outlet')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Konsol /</span> Manajemen Outlet</h4>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Daftar Outlet</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    {{ $dataTable->table(['class' => 'datatables-permissions table']) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}

    <script>
        var urlDeleteOutlet = "{{ route('outlets.destroy', ':id') }}";
    </script>
@endpush
