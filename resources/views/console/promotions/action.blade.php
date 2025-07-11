<div class="d-flex align-items-center gap-2">
    {{-- Tombol Edit --}}
    <a href="{{ route('promotions.edit', $id) }}" class="btn btn-sm btn-icon rounded-pill waves-effect"
        data-bs-toggle="tooltip" title="Edit Promo">
        <i class="ri-edit-box-line ri-20px"></i>
    </a>

    {{-- Tombol Delete (gunakan JS listener) --}}
    <a href="javascript:;"
       class="btn btn-sm btn-icon rounded-pill waves-effect delete-record"
       data-id="{{ $id }}"
       data-url="{{ route('promotions.destroy', $id) }}"
       data-bs-toggle="tooltip"
       title="Hapus Promo">
        <i class="ri-delete-bin-7-line ri-20px"></i>
    </a>
</div>
