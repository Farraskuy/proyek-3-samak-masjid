@extends('admin.layout')

@section('title', 'Edit Rekening')

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

        /* Image Preview */
        #image-preview-container {
            display: none;
            position: relative;
        }

        #image-preview {
            width: 100%;
            border-radius: 1rem;/
            border: 1px solid #ddd;
            object-fit: contain;
            height: 200px;
            background: #eee;
        }

        #remove-image-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: rgba(0, 0, 0, .55);
            color: white;
            font-size: 18px;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <section class="p-3 container">

        <form action="{{ route('admin.banks.update', $bank->account_id) }}" method="POST" enctype="multipart/form-data"
            id="form-bank">
            @csrf
            @method('PUT')

            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.banks.index') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-semibold mb-0">Edit Rekening Bank</h4>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    {{-- Detail Rekening --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Informasi Rekening</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Bank</label>
                            <input type="text" name="bank_name" class="form-control input-lg"
                                value="{{ old('bank_name', $bank->bank_name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Rekening</label>
                            <input type="text" name="account_number" class="form-control input-lg"
                                value="{{ old('account_number', $bank->account_number) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Atas Nama (Pemilik)</label>
                            <input type="text" name="account_holder_name" class="form-control input-lg"
                                value="{{ old('account_holder_name', $bank->account_holder_name) }}" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Kategori & Logo --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Pengaturan</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori Tampil</label>
                            <select name="category" class="form-select form-control form-control-lg" required>
                                <option value="all" {{ $bank->category == 'all' ? 'selected' : '' }}>Semua (Zakat &
                                    Infak)</option>
                                <option value="zakat" {{ $bank->category == 'zakat' ? 'selected' : '' }}>Hanya Zakat
                                </option>
                                <option value="infaq" {{ $bank->category == 'infaq' ? 'selected' : '' }}>Hanya Infak
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="isActive"
                                    name="is_active" value="1" {{ $bank->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">Aktif (Tampilkan)</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Logo Bank</label>

                            <label for="file-input" id="file-uploader" class="file-uploader"
                                style="{{ $bank->logo_url ? 'display:none !important' : '' }}">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <div class="fw-semibold">Upload Logo</div>
                                <div class="small text-muted">Drag & drop atau klik</div>
                            </label>

                            <input type="file" name="logo" id="file-input" accept="image/*" class="d-none">

                            <div id="image-preview-container" class="mt-3"
                                style="{{ $bank->logo_url ? 'display:block' : '' }}">
                                <img id="image-preview" src="{{ $bank->logo_url ? asset($bank->logo_url) : '#' }}"
                                    alt="Preview">
                                <button type="button" id="remove-image-btn" title="Ganti Gambar">&times;</button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            <i class="fas fa-save me-1"></i> Update Rekening
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
            /* ========================  IMAGE UPLOADER =========================*/
            const uploader = document.getElementById("file-uploader");
            const input = document.getElementById("file-input");
            const preview = document.getElementById("image-preview");
            const container = document.getElementById("image-preview-container");
            const removeBtn = document.getElementById("remove-image-btn");


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
                showPreview(e.dataTransfer.files[0]);
            });

            input.addEventListener("change", () => {
                if (input.files[0]) showPreview(input.files[0]);
            });

            removeBtn.addEventListener("click", () => {
                input.value = "";
                // If there was an existing image, clearing input doesn't delete it on server unless we handle it.
                // But UI wise, we show uploader.
                container.style.display = "none";
                uploader.style.display = "block";
                preview.src = "#";
            });

            function showPreview(file) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    container.style.display = "block";
                    uploader.style.display = "none";
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
