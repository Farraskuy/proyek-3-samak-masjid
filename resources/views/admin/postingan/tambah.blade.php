@extends('admin.layout')

@section('title', 'Tambah Artikel - SAMAK Kampus')

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
            /* FIX layout pecah */
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

        <form action="/admin/postingan/store" method="POST" id="form-postingan" enctype="multipart/form-data">
            @csrf

            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ url('admin/postingan') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-semibold mb-0">Tambah Artikel Baru</h4>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">

                    {{-- Judul --}}
                    <div class="card-modern rounded-3 p-4 mb-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Artikel</label>
                            <input type="text" name="title_view" class="form-control input-lg"
                                placeholder="Tulis judul artikel..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan Singkat (Opsional)</label>
                            <textarea type="text" name="keterangan_view" class="form-control input-lg" placeholder="Tuliskan keterangan singkat..."></textarea>
                        </div>
                    </div>

                    {{-- Editor --}}
                    <div class="card-modern rounded-3 p-4 mb-4">
                        <label class="form-label fw-semibold">Isi Konten</label>

                        <div id="editor" style="min-height:400px;">
                            <p>Tulis isi artikel di sini...</p>
                        </div>

                        <input type="hidden" id="quill_content" name="content_view">
                    </div>
                </div>

                <div class="col-lg-4">

                    {{-- Publikasi --}}
                    <div class="card-modern rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Publikasi</h5>

                        <label class="form-label fw-semibold">Status</label>
                        <select name="status_view" class="form-select input-lg" required>
                            <option value="published">Ajukan Publikasikan</option>
                            <option value="draft">Simpan sebagai Draft</option>
                        </select>

                        <button type="submit" class="btn btn-success w-100 mt-3">
                            <i class="fas fa-save me-1"></i> Simpan Artikel
                        </button>
                    </div>

                    {{-- Kategori --}}
                    <div class="card-modern rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Kategori</h5>

                        <select name="kategori_view" class="form-select input-lg" required>
                            <option value="" disabled selected>Pilih kategori...</option>
                            <option value="Artikel">Artikel Dakwah</option>
                            <option value="Berita">Berita</option>
                            <option value="Tausiyah">Tausiyah Singkat</option>
                        </select>
                    </div>

                    {{-- Gambar Unggulan --}}
                    <div class="card-modern rounded-3 p-4">
                        <h5 class="fw-semibold mb-3">Gambar Unggulan</h5>

                        <label for="file-input" id="file-uploader" class="file-uploader">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <div class="fw-semibold">Drag & drop gambar</div>
                            <div class="small text-muted">atau klik untuk memilih</div>
                        </label>

                        <input type="file" name="image_view" id="file-input" accept="image/*" class="d-none">

                        <div id="image-preview-container" class="mt-3">
                            <img id="image-preview" src="#" alt="Preview">
                            <button type="button" id="remove-image-btn">&times;</button>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Caption (Opsional)</label>
                            <textarea name="image_caption" rows="2" class="form-control input-lg" placeholder="Tulis caption gambar..."></textarea>
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

            /* ======================== QUILL =========================*/

            const toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'], // Tombol dasar
                ['blockquote', 'code-block'],
                [{
                    'header': 1
                }, {
                    'header': 2
                }], // Header
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'script': 'sub'
                }, {
                    'script': 'super'
                }], // Superscript/Subscript
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }], // Indentasi
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'color': []
                }, {
                    'background': []
                }], // Warna
                [{
                    'align': []
                }],
                ['link', 'image'], // <-- 'video' sudah dihapus
                ['clean'] // Tombol bersihkan format
            ];

            const quill = new Quill("#editor", {
                theme: "snow",
                modules: {
                    toolbar: toolbarOptions
                }
            });

            const form = document.getElementById("form-postingan");
            const hidden = document.getElementById("quill_content");

            form.addEventListener("submit", function(e) {
                hidden.value = quill.root.innerHTML;
                if (quill.getLength() < 2) {
                    e.preventDefault();
                    alert("Isi artikel tidak boleh kosong.");
                }
            });

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
                container.style.display = "none";
                uploader.style.display = "block";
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
