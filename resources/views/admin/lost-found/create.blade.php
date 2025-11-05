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
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi Ditemukan <span class="text-danger">*</span></label>
                    <input type="text" name="location_found" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Diambil">Diambil</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Barang (Opsional)</label>
                    <input type="file" name="featured_image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fa-regular fa-floppy-disk me-1"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection