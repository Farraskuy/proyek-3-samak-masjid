@extends('admin.layout')

@section('title', 'Edit Postingan - SAMAK Kampus')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        .section-wrapper {
            max-width: 1450px;
            margin: 0 auto;
        }

        .card-modern {
            border: 0 !important;
            background: #fff;
        }

        .input-lg {
            padding: .85rem 1rem !important;
            font-size: .95rem !important;
        }

        .btn-main {
            background-color: #CE9138 !important;
            color: white !important;
            border: none !important;
            padding: .75rem 1rem !important;
            border-radius: .75rem !important;
            font-weight: 600 !important;
        }

        .btn-main:hover {
            background-color: #b88027 !important;
        }

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
            /* Default hidden, will be controlled by JS/PHP */
            display: none; 
            position: relative;
        }

        #image-preview {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid #ddd;
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

        .ql-toolbar.ql-snow {
            border-radius: 1rem 1rem 0 0 !important;
            border-color: #ccc !important;
        }

        .ql-container.ql-snow {
            border-radius: 0 0 1rem 1rem !important;
            border-color: #ccc !important;
            min-height: 300px !important;
        }
    </style>
@endpush

@section('content')
    <section class="p-3 section-wrapper">

        {{-- Form Action Update & Method PUT --}}
        <form action="{{ route('postingan.admin.update', $post->id) }}" method="POST" id="form-postingan" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ url('admin/postingan') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <h4 class="fw-semibold mb-0">Edit Artikel</h4>
            </div>

            {{-- Error Handling --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-3 mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-8">

                    {{-- Judul --}}
                    <div class="card-modern rounded-3 p-4 mb-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Artikel</label>
                            {{-- Value diambil dari $post->title --}}
                            <input type="text" name="title" class="form-control input-lg"
                                placeholder="Tulis judul artikel..." 
                                value="{{ old('title', $post->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan Singkat (Opsional)</label>
                            {{-- Value diambil dari $post->keterangan --}}
                            <textarea type="text" name="keterangan" class="form-control input-lg" placeholder="Tuliskan keterangan singkat...">{{ old('keterangan', $post->keterangan) }}</textarea>
                        </div>
                    </div>

                    {{-- Editor --}}
                    <div class="card-modern rounded-3 p-4 mb-4">
                        <label class="form-label fw-semibold">Isi Konten</label>

                        <div id="editor" style="min-height:400px;">
                            {{-- Konten awal akan diinject via JS --}}
                        </div>

                        {{-- Input Hidden untuk menyimpan data Quill --}}
                        <input type="hidden" id="quill_content" name="content">
                    </div>
                </div>

                <div class="col-lg-4">

                    {{-- Publikasi --}}
                    <div class="card-modern rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Publikasi</h5>

                        {{-- Tombol Submit --}}
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="fas fa-save me-1"></i> Update Postingan
                        </button>
                    </div>

                    {{-- Kategori --}}
                    <div class="card-modern rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Kategori</h5>

                        <select name="kategori" class="form-select input-lg" required>
                            <option value="" disabled>Pilih kategori...</option>
                            <option value="Artikel" {{ old('kategori', $post->kategori) == 'artikel' ? 'selected' : '' }}>Artikel Dakwah</option>
                            <option value="Berita" {{ old('kategori', $post->kategori) == 'berita' ? 'selected' : '' }}>Berita</option>
                            <option value="Tausiyah" {{ old('kategori', $post->kategori) == 'tausiyah' ? 'selected' : '' }}>Tausiyah Singkat</option>
                        </select>
                    </div>

                    {{-- Gambar Unggulan --}}
                    <div class="card-modern rounded-3 p-4">
                        <h5 class="fw-semibold mb-3">Gambar Unggulan</h5>

                        {{-- Logic PHP untuk cek apakah gambar sudah ada --}}
                        @php
                            $hasImage = $post->featured_image_url ? true : false;
                            $imageUrl = $hasImage ? Storage::url($post->featured_image_url) : '#';
                        @endphp

                        {{-- Container Uploader (Disembunyikan jika sudah ada gambar) --}}
                        <label for="file-input" id="file-uploader" class="file-uploader" style="{{ $hasImage ? 'display:none !important;' : 'display:block !important;' }}">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <div class="fw-semibold">Drag & drop gambar</div>
                            <div class="small text-muted">atau klik untuk mengganti</div>
                        </label>

                        <input type="file" name="featured_image_url" id="file-input" accept="image/*" class="d-none">

                        {{-- Preview Container (Ditampilkan jika sudah ada gambar) --}}
                        <div id="image-preview-container" class="mt-3" style="{{ $hasImage ? 'display:block;' : 'display:none;' }}">
                            <img id="image-preview" src="{{ $imageUrl }}" alt="Preview">
                            <button type="button" id="remove-image-btn">&times;</button>
                        </div>

                        <div class="mt-3">
                           <small class="text-muted fst-italic">*Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        </div>
                    </div>

                </div>
            </div>
        </form>

    </section>
@endsection

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            /* ======================== QUILL CONFIG =========================*/
            const toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                [{ 'indent': '-1' }, { 'indent': '+1' }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ];

            const quill = new Quill("#editor", {
                theme: "snow",
                modules: {
                    toolbar: toolbarOptions
                }
            });

            // LOAD EXISTING CONTENT FROM DATABASE
            // Mengambil konten lama dan memasukkannya ke editor
            const existingContent = {!! json_encode($post->content) !!};
            if (existingContent) {
                quill.root.innerHTML = existingContent;
            }

            const form = document.getElementById("form-postingan");
            const hidden = document.getElementById("quill_content");

            form.addEventListener("submit", function(e) {
                hidden.value = quill.root.innerHTML;
                
                // Validasi sederhana jika kosong (kecuali tag p br bawaan quill)
                if (quill.getText().trim().length === 0 && hidden.value.includes('<p><br></p>')) {
                    // Opsional: block submit atau biarkan
                    // e.preventDefault();
                    // alert("Konten tidak boleh kosong!");
                }
            });

            /* ========================  IMAGE UPLOADER =========================*/
            const uploader = document.getElementById("file-uploader");
            const input = document.getElementById("file-input");
            const preview = document.getElementById("image-preview");
            const container = document.getElementById("image-preview-container");
            const removeBtn = document.getElementById("remove-image-btn");

            // Drag & Drop Effects
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
                if (input.files[0]) showPreview(input.files[0]);
            });

            // Input Change
            input.addEventListener("change", () => {
                if (input.files[0]) showPreview(input.files[0]);
            });

            // Remove Image Logic
            removeBtn.addEventListener("click", () => {
                input.value = ""; // Reset input file
                preview.src = "#";
                container.style.display = "none";
                uploader.style.display = "block"; // Munculkan kembali area upload
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