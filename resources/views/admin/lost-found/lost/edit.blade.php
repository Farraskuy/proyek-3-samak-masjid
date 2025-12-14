@extends('admin.layout')
@section('title', 'Edit Laporan Barang Hilang')

@section('content')
<section class="p-3 container">

    <form action="{{ route('admin.lost-items.update', $lostItem->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Header --}}
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('admin.lost-items.index') }}" class="btn btn-light btn-sm rounded-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="fw-semibold mb-0">Edit Laporan Barang Hilang</h4>
        </div>

        <div class="row g-4">
            {{-- Kolom Kiri: Info Utama --}}
            <div class="col-lg-8">
                <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                    <h5 class="fw-semibold mb-3">Informasi Barang</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="item_name" class="form-control input-lg"
                            value="{{ old('item_name', $lostItem->item_name) }}" required>
                        @error('item_name')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $lostItem->description) }}</textarea>
                        @error('description')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi Perkiraan Hilang <span class="text-danger">*</span></label>
                        <input type="text" name="location_lost" class="form-control input-lg"
                            value="{{ old('location_lost', $lostItem->location_lost) }}" required>
                        @error('location_lost')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Detail & Aksi --}}
            <div class="col-lg-4">
                <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                    <h5 class="fw-semibold mb-3">Detail & Kategori</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select form-control form-control-lg" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $lostItem->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Hilang <span class="text-danger">*</span></label>
                        <input type="date" name="lost_at" class="form-control input-lg"
                            value="{{ old('lost_at', $lostItem->lost_at?->format('Y-m-d')) }}" required>
                        @error('lost_at')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3 fw-semibold">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>

</section>
@endsection