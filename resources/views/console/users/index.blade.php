@extends('layouts.app')
@section('title', 'Users')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Filters</h5>
                <div class="d-flex justify-content-between align-items-center row gx-5 pt-4 gap-5 gap-md-0">
                    <div class="col-md-4 user_role">
                        <select id="user-role-filter" class="form-select" data-filter="role" name="role_filter">
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                {{ $dataTable->table(['class' => 'datatables-permissions table']) }}
            </div>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser"
                aria-labelledby="offcanvasAddUserLabel">
                <div class="offcanvas-header border-bottom">
                    <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body mx-0 flex-grow-0 h-100">
                    <form class="add-new-user pt-0" id="addNewUserForm" method="POST" onsubmit="return false"
                        action="{{ route('users.store') }}">
                        @csrf

                        <div class="form-floating form-floating-outline mb-5">
                            <input type="text" class="form-control" id="add-user-name" placeholder="John Doe"
                                name="name" aria-label="John Doe" />
                            <label for="add-user-name">Name</label>
                        </div>

                        <div class="form-floating form-floating-outline mb-5">
                            <input type="text" id="add-user-email" class="form-control"
                                placeholder="john.doe@example.com" aria-label="john.doe@example.com" name="email" />
                            <label for="add-user-email">Email</label>
                        </div>

                        <div class="form-floating form-floating-outline mb-5">
                            <select id="user-role" class="form-select" name="role_id">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <label for="user-role">User Role</label>
                        </div>

                        <div class="form-floating form-floating-outline mb-5">
                            <input type="password" id="add-user-password" class="form-control"
                                placeholder="{!! passwordPlainText() !!}" aria-label="{!! passwordPlainText() !!}"
                                name="password" />
                            <label for="add-user-password">Password</label>
                        </div>

                        <div class="form-floating form-floating-outline mb-5">
                            <input type="password" id="add-user-password-confirm" class="form-control"
                                placeholder="{!! passwordPlainText() !!}" aria-label="{!! passwordPlainText() !!}"
                                name="password_confirmation" />
                            <label for="add-user-password-confirm">Confirm Password</label>
                        </div>

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}

    <script>
        var urlDeleteUser = "{{ route('users.destroy', ':id') }}";
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
                form.action = urlDeleteUser.replace(":id", recordId);
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
