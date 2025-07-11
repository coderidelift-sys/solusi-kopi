@extends('layouts.app')

@section('title', 'Manajemen Promosi')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Konsol /</span> Manajemen Promosi</h4>

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
                <h5 class="mb-0">Daftar Promosi</h5>
                {{-- <a href="{{ route('promotions.create') }}" class="btn btn-primary">Tambah Promosi</a> --}}
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
        var urlDeletePromotion = "{{ route('promotions.destroy', ':id') }}";
    </script>
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to handle delete record confirmation
            const handleDeleteRecord = (e) => {
                const target = e.target.closest(".delete-record");
                if (!target) return;

                e.preventDefault();

                const recordId = target.getAttribute("data-id");
                if (!recordId) {
                    console.error("Record ID not found.");
                    return;
                }

                const form = document.createElement("form");
                form.method = "POST";
                form.action = urlDeletePromotion.replace(":id", recordId);
                form.style.display = "none";

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const csrfInput = document.createElement("input");
                    csrfInput.type = "hidden";
                    csrfInput.name = "_token";
                    csrfInput.value = csrfToken.getAttribute("content");
                    form.appendChild(csrfInput);
                }

                const methodInput = document.createElement("input");
                methodInput.type = "hidden";
                methodInput.name = "_method";
                methodInput.value = "DELETE";
                form.appendChild(methodInput);

                document.body.appendChild(form);

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        form.remove();
                    }
                });
            };

            // Attach event listener for delete record
            document.addEventListener("click", handleDeleteRecord);
        });
    </script>
@endpush
