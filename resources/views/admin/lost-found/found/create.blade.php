@extends('admin.layout')
@section('title', 'Tambah Barang - Lost & Found')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-duotone fa-box-open-full me-2"></i> Tambah Barang Temuan</h2>
        <a href="{{ route('admin.barang-hilang') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-regular fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.barang-hilang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="item_name" class="form-control" required>
                    @error('item_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                    @error('description')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi Ditemukan <span class="text-danger">*</span></label>
                    <input type="text" name="location_found" class="form-control" required value="{{ old('location_found') }}">
                    @error('location_found')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="kendaraan" {{ old('category') == 'kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                        <option value="elektronik" {{ old('category') == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                        <option value="aksesoris" {{ old('category') == 'aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                        <option value="dokumen" {{ old('category') == 'dokumen' ? 'selected' : '' }}>Dokumen</option>
                        <option value="lain-lain" {{ old('category') == 'lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                    </select>
                    @error('category')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="Tersedia" {{ old('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Diambil" {{ old('status') == 'Diambil' ? 'selected' : '' }}>Diambil</option>
                    </select>
                    @error('status')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Barang <span class="text-danger">*</span></label>
                    <input type="file" name="featured_images[]" class="form-control" accept="image/*" multiple required>
                    <small class="text-muted">Pilih minimal 1 foto. Bisa pilih banyak sekaligus.</small>
                    @error('featured_images')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    @error('featured_images.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fa-regular fa-floppy-disk me-1"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
