@extends('admin.layout')

@section('title', 'Tambah Barang - Lost & Found')

@push('styles')
    <style>
        .file-uploader {
            padding: 2rem;
            border-radius: 1rem;
            border: 2px dashed #dee2e6;
            background: #fafafa;
            text-align: center;
            cursor: pointer;
            color: #666;
            transition: .2s ease-in-out;
            display: block !important;
        }

        .file-uploader.on-drag {
            background: #f3f3f3;
            border-color: #CE9138 !important;
        }

        .img-preview-box {
            position: relative;
            display: inline-block;
            width: 80px;
            height: 80px;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        .img-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endpush

@section('content') <section class="p-3 container">

        <form action="{{ route('admin.barang-hilang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.barang-hilang') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-semibold mb-0">Tambah Barang Temuan</h4>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    {{-- Detail Barang --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Informasi Barang</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="item_name" class="form-control input-lg" required>
                            @error('item_name')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lokasi Ditemukan <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="location_found" class="form-control input-lg" required
                                value="{{ old('location_found') }}">
                            @error('location_found')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Status, Kategori & Foto --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Detail & Foto</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="category" class="form-select form-control form-control-lg" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="kendaraan" {{ old('category') == 'kendaraan' ? 'selected' : '' }}>Kendaraan
                                </option>
                                <option value="elektronik" {{ old('category') == 'elektronik' ? 'selected' : '' }}>
                                    Elektronik</option>
                                <option value="aksesoris" {{ old('category') == 'aksesoris' ? 'selected' : '' }}>Aksesoris
                                </option>
                                <option value="dokumen" {{ old('category') == 'dokumen' ? 'selected' : '' }}>Dokumen
                                </option>
                                <option value="lain-lain" {{ old('category') == 'lain-lain' ? 'selected' : '' }}>Lain-lain
                                </option>
                            </select>
                            @error('category')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-control form-control-lg" required>
                                <option value="Tersedia" {{ old('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia
                                </option>
                                <option value="Diambil" {{ old('status') == 'Diambil' ? 'selected' : '' }}>Diambil
                                </option>
                            </select>
                            @error('status')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Foto Barang <span class="text-danger">*</span></label>

                            <label for="featured_images" id="file-uploader" class="file-uploader">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <div class="fw-semibold">Upload Foto</div>
                                <div class="small text-muted">Klik atau drag & drop (Min 1)</div>
                            </label>

                            <input type="file" name="featured_images[]" id="featured_images" accept="image/*" multiple
                                class="d-none" required>
                            @error('featured_images')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror

                            <div id="new-images-preview" class="d-flex flex-wrap gap-2 mt-3"></div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            /* ======================== IMAGE UPLOADER =========================*/
            const uploader = document.getElementById("file-uploader");
            const input = document.getElementById("featured_images");
            const previewContainer = document.getElementById("new-images-preview");

            uploader.addEventListener("dragover", e => {
                e.preventDefault();
                uploader.classList.add("on-drag");
            });

            uploader.addEventListener("dragleave", () => {
                uploader.classList.remove("on-drag");
            });

            uploader.addEventListener("drop", e => {
                e.preventDefault();
                uploader.classList.remove("on-drag");
                input.files = e.dataTransfer.files;
                showNewPreviews(input.files);
            });

            input.addEventListener("change", () => {
                if (input.files) showNewPreviews(input.files);
            });

            function showNewPreviews(files) {
                previewContainer.innerHTML = ""; // Reset preview
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const div = document.createElement("div");
                        div.className = "img-preview-box";
                        div.innerHTML = `<img src="${e.target.result}">`;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
@endpush
