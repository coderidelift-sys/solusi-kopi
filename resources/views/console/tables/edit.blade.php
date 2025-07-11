@extends('layouts.app')

@section('title', 'Edit Meja')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Konsol / Manajemen Meja /</span> Edit</h4>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Form Edit Meja</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tables.update', $table->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="outlet_id" value="{{ 1 }}">
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Meja</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                            name="code" value="{{ old('code', $table->table_number) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                            required>
                            <option value="available" {{ old('status', $table->status) == 'available' ? 'selected' : '' }}>
                                Tersedia</option>
                            <option value="occupied" {{ old('status', $table->status) == 'occupied' ? 'selected' : '' }}>
                                Terisi</option>
                            <option value="unavailable" {{ old('status', $table->status) == 'unavailable' ? 'selected' : '' }}>
                                Tidak Tersedia</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">QR Code</label>
                        @if ($table->qr_code_url)
                            <div class="mb-2">
                                <img src="{{ Storage::url($table->qr_code_url) }}" alt="QR Code" class="img-thumbnail"
                                    width="100">
                            </div>
                        @else
                            <p>Tidak ada QR Code saat ini.</p>
                        @endif
                    </div>
                    <a href="{{ route('tables.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
