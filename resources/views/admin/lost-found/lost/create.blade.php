@extends('admin.layout')
@section('title', 'Tambah Laporan Barang Hilang')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tambah Laporan Barang Hilang</h2>
        <a href="{{ route('admin.lost-items.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-regular fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.lost-items.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="item_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Lokasi Perkiraan Hilang <span class="text-danger">*</span></label>
                    <input type="text" name="location_lost" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Tanggal Hilang <span class="text-danger">*</span></label>
                    <input type="date" name="lost_at" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Laporan</button>
            </form>
        </div>
    </div>
</div>
@endsection
