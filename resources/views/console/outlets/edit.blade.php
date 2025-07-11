@extends('layouts.app')

@section('title', 'Edit Outlet')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Konsol / Manajemen Outlet /</span> Edit</h4>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Form Edit Outlet</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('outlets.update', $outlet->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Outlet</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $outlet->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $outlet->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                            name="phone" value="{{ old('phone', $outlet->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $outlet->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @php
                        $days = [
                            'senin' => 'Senin',
                            'selasa' => 'Selasa',
                            'rabu' => 'Rabu',
                            'kamis' => 'Kamis',
                            'jumat' => 'Jumat',
                            'sabtu' => 'Sabtu',
                            'minggu' => 'Minggu',
                        ];

                        $openingHours = old('opening_hours', $outlet->parsed_opening_hours ?? []);
                    @endphp

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jam Operasional</label>
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            @foreach ($days as $dayKey => $dayLabel)
                                @php
                                    $isActive = isset($openingHours[$dayKey]['active']);
                                    $open = $openingHours[$dayKey]['open'] ?? '';
                                    $close = $openingHours[$dayKey]['close'] ?? '';
                                @endphp

                                <div class="col">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>{{ $dayLabel }}</strong>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-day" type="checkbox"
                                                    id="check_{{ $dayKey }}"
                                                    name="opening_hours[{{ $dayKey }}][active]" value="1"
                                                    {{ $isActive ? 'checked' : '' }}>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <input type="time" class="form-control open-input"
                                                name="opening_hours[{{ $dayKey }}][open]"
                                                value="{{ $open }}" {{ $isActive ? '' : 'disabled' }}
                                                placeholder="Jam Buka">

                                            <input type="time" class="form-control close-input"
                                                name="opening_hours[{{ $dayKey }}][close]"
                                                value="{{ $close }}" {{ $isActive ? '' : 'disabled' }}
                                                placeholder="Jam Tutup">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Lokasi (Latitude & Longitude)</label>
                        <div class="row g-2 align-items-center">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                                    <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                                        id="latitude" name="latitude" value="{{ old('latitude', $outlet->latitude) }}"
                                        placeholder="Latitude">
                                    @error('latitude')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                                    <input type="text" class="form-control @error('longitude') is-invalid @enderror"
                                        id="longitude" name="longitude" value="{{ old('longitude', $outlet->longitude) }}"
                                        placeholder="Longitude">
                                    @error('longitude')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="getLocation()">
                                <i class="bi bi-crosshair"></i> Ambil Lokasi Saya
                            </button>
                            <small class="text-muted d-block mt-1">Klik tombol di atas untuk mengisi lokasi secara otomatis
                                menggunakan GPS browser.</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo Saat Ini</label>
                        @if ($outlet->logo)
                            <div class="mb-2">
                                <img src="{{ asset($outlet->logo) }}" alt="{{ $outlet->name }}"
                                    class="img-thumbnail" width="100">
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo"
                                    value="1">
                                <label class="form-check-label" for="remove_logo">Hapus Logo</label>
                            </div>
                        @else
                            <p>Tidak ada logo saat ini.</p>
                        @endif
                        <label for="new_logo" class="form-label">Upload Logo Baru (Opsional)</label>
                        <input class="form-control @error('logo') is-invalid @enderror" type="file" id="new_logo"
                            name="logo">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <a href="{{ route('outlets.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    function previewLogo(event) {
        const output = document.getElementById('logo-preview');
        const file = event.target.files[0];

        if (file) {
            output.src = URL.createObjectURL(file);
            output.style.display = 'block';
        } else {
            output.src = '';
            output.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-day').forEach(function(toggle) {
            const parent = toggle.closest('.col');
            const openInput = parent.querySelector('.open-input');
            const closeInput = parent.querySelector('.close-input');

            toggle.addEventListener('change', function() {
                if (toggle.checked) {
                    openInput.removeAttribute('disabled');
                    closeInput.removeAttribute('disabled');
                } else {
                    openInput.setAttribute('disabled', true);
                    closeInput.setAttribute('disabled', true);
                    openInput.value = '';
                    closeInput.value = '';
                }
            });

            toggle.dispatchEvent(new Event('change'));
        });
    });

    // Validasi sebelum submit form
    document.querySelector('form').addEventListener('submit', function(e) {
        let valid = true;

        document.querySelectorAll('.toggle-day').forEach(function(toggle) {
            const parent = toggle.closest('.col');
            const openInput = parent.querySelector('.open-input');
            const closeInput = parent.querySelector('.close-input');

            if (toggle.checked) {
                if (!openInput.value || !closeInput.value) {
                    openInput.classList.add('is-invalid');
                    closeInput.classList.add('is-invalid');
                    valid = false;
                } else {
                    openInput.classList.remove('is-invalid');
                    closeInput.classList.remove('is-invalid');
                }
            }
        });

        if (!valid) {
            e.preventDefault();
            alert("Mohon isi jam buka dan tutup untuk hari yang aktif.");
        }
    });

    function getLocation() {
        if (!navigator.geolocation) {
            alert("Browser Anda tidak mendukung fitur geolokasi.");
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
            },
            function(error) {
                let message = "";
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        message = `Izin lokasi ditolak. Aktifkan di pengaturan browser.
                            ${/Chrome/.test(navigator.userAgent) ? 'Buka: chrome://settings/content/location' : ''}`;
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = "Informasi lokasi tidak tersedia. Periksa koneksi Anda.";
                        break;
                    case error.TIMEOUT:
                        message = "Permintaan lokasi terlalu lama. Coba lagi.";
                        break;
                    default:
                        message = "Terjadi kesalahan saat mengambil lokasi.";
                        break;
                }

                toastr.error(message);
                console.log('Geolocation error', error);
            }
        );
    }
</script>
