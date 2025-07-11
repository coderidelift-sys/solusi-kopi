<div class="d-flex align-items-center gap-2">
    {{-- Delete button (triggered via JavaScript confirmation) --}}
    <a href="javascript:;" class="btn btn-sm btn-icon btn-text-danger rounded-pill waves-effect delete-record d-none"
        data-id="{{ $id }}" data-url="{{ route('outlets.destroy', $id) }}" data-bs-toggle="tooltip"
        title="Hapus Outlet">
        <i class="ri-delete-bin-7-line ri-20px"></i>
    </a>

    <a href="{{ route('outlets.show', $id) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect d-none"
        data-bs-toggle="tooltip" title="Preview">
        <i class="ri-eye-line ri-20px"></i>
    </a>

    <a href="{{ route('outlets.edit', $id) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect"
        data-bs-toggle="tooltip" title="Edit">
        <i class="ri-edit-box-line ri-20px"></i>
    </a>

    {{-- Dropdown menu --}}
    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow"
        data-bs-toggle="dropdown">
        <i class="ri-more-2-line ri-20px"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="{{ route('outlets.edit', $id) }}" class="dropdown-item">
            <i class="ri-edit-box-line me-2"></i><span>Edit</span>
        </a>
        <a href="javascript:;" class="dropdown-item delete-record d-none" data-id="{{ $id }}"
            data-url="{{ route('outlets.destroy', $id) }}">
            <i class="ri-delete-bin-7-line me-2"></i><span>Hapus</span>
        </a>
    </div>
</div>
