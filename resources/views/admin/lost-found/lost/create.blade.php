@extends('admin.layout')

@section('title', 'Tambah Laporan Barang Hilang')

@section('content') <section class="p-3 container">

        <form action="{{ route('admin.lost-items.store') }}" method="POST">
            @csrf

            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.lost-items.index') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-semibold mb-0">Tambah Laporan Barang Hilang</h4>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    {{-- Detail Barang --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Informasi Barang</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="item_name" class="form-control input-lg" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lokasi Perkiraan Hilang <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="location_lost" class="form-control input-lg" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Kategori & Tanggal --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Detail & Kategori</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select form-control form-control-lg" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Hilang <span class="text-danger">*</span></label>
                            <input type="date" name="lost_at" class="form-control input-lg" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">
                            <i class="fas fa-save me-1"></i> Simpan Laporan
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </section>

@endsection
