@extends('admin.layout')

@section('title', 'Edit Barang - Lost & Found')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-duotone fa-box-open-full me-2"></i> Edit Barang Temuan</h2>
        <a href="{{ route('admin.barang-hilang') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-regular fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.barang-hilang.update', $item->item_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="item_name" class="form-control" value="{{ old('item_name', $item->item_name) }}" required>
                    @error('item_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="3" required>{{ old('description', $item->description) }}</textarea>
                    @error('description')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi Ditemukan <span class="text-danger">*</span></label>
                    <input type="text" name="location_found" class="form-control" value="{{ old('location_found', $item->location_found) }}" required>
                    @error('location_found')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="kendaraan" {{ (old('category', $item->category) == 'kendaraan') ? 'selected' : '' }}>Kendaraan</option>
                        <option value="elektronik" {{ (old('category', $item->category) == 'elektronik') ? 'selected' : '' }}>Elektronik</option>
                        <option value="aksesoris" {{ (old('category', $item->category) == 'aksesoris') ? 'selected' : '' }}>Aksesoris</option>
                        <option value="dokumen" {{ (old('category', $item->category) == 'dokumen') ? 'selected' : '' }}>Dokumen</option>
                        <option value="lain-lain" {{ (old('category', $item->category) == 'lain-lain') ? 'selected' : '' }}>Lain-lain</option>
                    </select>
                    @error('category')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="Tersedia" {{ (old('status', $item->status) == 'Tersedia') ? 'selected' : '' }}>Tersedia</option>
                        <option value="Diambil" {{ (old('status', $item->status) == 'Diambil') ? 'selected' : '' }}>Diambil</option>
                    </select>
                    @error('status')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Saat Ini</label>
                    <div class="d-flex flex-wrap gap-3 mt-2">
                        @foreach($item->photos as $photo)
                        <div class="position-relative" style="width: 100px; height: 100px;">
                            <img src="{{ asset('storage/' . $photo->image_url) }}" class="rounded w-100 h-100" style="object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: -8px; right: -8px; width: 24px; height: 24px; padding: 0; border-radius: 50%;"
                                onclick="this.closest('.position-relative').style.display='none'; this.previousElementSibling.value=1;">
                                <i class="fa-solid fa-xmark" style="font-size: 10px;"></i>
                            </button>
                            <input type="hidden" name="remove_photos[{{ $photo->photo_id }}]" value="0">
                        </div>
                        @endforeach
                    </div>
                    <small class="text-muted">Klik tanda × untuk menghapus foto tertentu.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tambah Foto Baru</label>
                    <input type="file" name="new_featured_images[]" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">Pilih foto tambahan (opsional).</small>
                    @error('new_featured_images')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    @error('new_featured_images.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fa-regular fa-floppy-disk me-1"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .position-relative {
        display: inline-block;
    }

    .btn-sm {
        line-height: 1;
    }
</style>
@endsection