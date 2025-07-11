@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Konsol / Manajemen Kategori /</span> Edit</h4>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Form Edit Kategori</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="outlet_id" value="{{1}}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Saat Ini</label>
                        @if ($category->image)
                            <div class="mb-2">
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                    class="img-thumbnail" width="100">
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image"
                                    value="1">
                                <label class="form-check-label" for="remove_image">Hapus Gambar</label>
                            </div>
                        @else
                            <p>Tidak ada gambar saat ini.</p>
                        @endif
                        <label for="new_image" class="form-label">Upload Gambar Baru (Opsional)</label>
                        <input class="form-control @error('image') is-invalid @enderror" type="file" id="new_image"
                            name="image">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                            required>
                            <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
