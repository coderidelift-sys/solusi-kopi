<div class="d-flex align-items-center gap-2">
    {{-- Edit button --}}
    <a href="{{ route('categories.edit', $id) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect"
        data-bs-toggle="tooltip" title="Edit Kategori">
        <i class="ri-edit-box-line ri-20px"></i>
    </a>

    {{-- Dropdown menu --}}
    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ri-more-2-line ri-20px"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="{{ route('categories.edit', $id) }}" class="dropdown-item">
            <i class="ri-edit-box-line me-2"></i><span>Edit</span>
        </a>

        {{-- Delete trigger via JS --}}
        <a href="javascript:;" class="dropdown-item delete-record" data-id="{{ $id }}"
            data-url="{{ route('categories.destroy', $id) }}">
            <i class="ri-delete-bin-7-line me-2 text-danger"></i><span>Hapus</span>
        </a>
    </div>
</div>
