@extends('admin.layout')
@section('title', 'Edit Laporan Barang Hilang')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Laporan Barang Hilang</h2>
        <a href="{{ route('admin.lost-items.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-regular fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.lost-items.update', $lostItem->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="item_name" class="form-control" value="{{ old('item_name', $lostItem->item_name) }}" required>
                </div>
                <div class="mb-3">
                    <label>Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="3" required>{{ old('description', $lostItem->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label>Lokasi Perkiraan Hilang <span class="text-danger">*</span></label>
                    <input type="text" name="location_lost" class="form-control" value="{{ old('location_lost', $lostItem->location_lost) }}" required>
                </div>
                <div class="mb-3">
                    <label>Tanggal Hilang <span class="text-danger">*</span></label>
                    <input type="date" name="lost_at" class="form-control" value="{{ old('lost_at', $lostItem->lost_at?->format('Y-m-d')) }}" required>
                </div>
                <div class="mb-3">
                    <label>Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $lostItem->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection
