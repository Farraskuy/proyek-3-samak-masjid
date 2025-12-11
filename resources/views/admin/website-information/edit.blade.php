@extends('admin.layout')

@section('title', 'Edit Halaman Statis')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        /* --- COPY STYLE DARI FITUR POSTINGAN --- */
        .section-wrapper {
            max-width: 1450px;
            margin: 0 auto;
        }

        .card-modern {
            border: 0 !important;
            background: #fff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
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

        .file-uploader:hover, .file-uploader.on-drag {
            background: #f3f3f3;
            border-color: #CE9138 !important;
        }

        /* Image Preview Styles */
        #image-preview-container {
            position: relative;
        }

        #image-preview {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid #ddd;
            object-fit: cover;
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Quill Overrides */
        .ql-toolbar.ql-snow {
            border-radius: 1rem 1rem 0 0 !important;
            border-color: #dee2e6 !important;
            background-color: #f8f9fa;
        }

        .ql-container.ql-snow {
            border-radius: 0 0 1rem 1rem !important;
            border-color: #dee2e6 !important;
            min-height: 400px !important;
            font-size: 1rem;
        }
    </style>
@endpush

@section('content')
    <section class="p-3 section-wrapper">

        {{-- Form Action --}}
        <form id="form-edit-page" method="post" action="{{ route('admin.static-pages.update', $page->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Header Section --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.static-pages.index') }}" class="btn btn-light btn-sm rounded-4 px-3">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <h4 class="fw-bold mb-0">Edit Halaman: {{ $page->title }}</h4>
            </div>

            {{-- Error Validation Alert --}}
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
                {{-- COLUMN LEFT: EDITOR --}}
                <div class="col-lg-8">

                    {{-- Card Title & Description --}}
                    <div class="card-modern rounded-3 p-4 mb-4">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Judul Halaman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-lg @error('title') is-invalid @enderror" 
                                id="title" name="title" 
                                placeholder="Masukkan judul halaman" 
                                value="{{ old('title', $page->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Deskripsi Singkat</label>
                            <textarea class="form-control input-lg @error('description') is-invalid @enderror" 
                                id="description" name="description" 
                                placeholder="Masukkan deskripsi untuk meta description" 
                                rows="3" >{{ old('description', $page->description) }}</textarea>
                        </div>
                    </div>

                    {{-- Card Editor --}}
                    <div class="card-modern rounded-3 p-4 mb-4">
                        <label class="form-label fw-semibold mb-2">Konten Halaman  <span class="text-danger">*</span></label>
                        
                        {{-- Container Quill --}}
                        <div id="editor-content"></div>
                        
                        {{-- Hidden Input untuk Data --}}
                        <textarea name="content" id="content" style="display: none;"></textarea>
                    </div>
                </div>

                {{-- COLUMN RIGHT: SIDEBAR --}}
                <div class="col-lg-4">

                    {{-- Card Publish --}}
                    <div class="card-modern rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Aksi</h5>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold btn-main">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>

                    {{-- Card Featured Image --}}
                    <div class="card-modern rounded-3 p-4">
                        <h5 class="fw-semibold mb-3">Gambar Utama</h5>

                        @php
                            // Cek apakah ada gambar lama
                            // Asumsi backend mengirim full URL atau Anda menggunakan accessor
                            // Jika menggunakan path relative storage, gunakan asset('storage/'...)
                            $hasImage = !empty($page->featured_image_url);
                            $imageUrl = $hasImage ? asset('storage/' . $page->featured_image_url) : '#';
                        @endphp

                        {{-- 1. Uploader Area (Muncul jika belum ada gambar) --}}
                        <label for="featured_image_url" id="file-uploader" class="file-uploader" 
                               style="{{ $hasImage ? 'display:none !important;' : 'display:block !important;' }}">
                            <i class="fas fa-image fa-2x mb-2"></i>
                            <div class="fw-semibold">Upload Gambar</div>
                            <div class="small text-muted">Klik atau drag file kesini</div>
                        </label>

                        <input type="file" class="d-none" id="featured_image_url" name="featured_image_url" accept="image/*">

                        {{-- 2. Preview Area (Muncul jika ada gambar) --}}
                        <div id="image-preview-container" class="mt-2" style="{{ $hasImage ? 'display:block;' : 'display:none;' }}">
                            <img id="image-preview" src="{{ $imageUrl }}" alt="Preview Image">
                            <button type="button" id="remove-image-btn" title="Ganti Gambar">&times;</button>
                        </div>
                        
                        <div class="mt-2 text-center">
                            <small class="text-muted fst-italic" style="font-size: 0.8rem">*Maksimal ukuran 5mb (jpeg,png,jpg,gif,webp) apabila tidak ingin gambarnya diganti boleh memencet tombol <b>X</b> ataupun mengabaikan image</small>
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
        document.addEventListener('DOMContentLoaded', function() {
            
            /* ================= 1. CONFIG QUILL (SAMA SEPERTI POSTINGAN) ================= */
            const toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
                [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],         // Custom dropdown header
                [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']                                         // remove formatting button
            ];

            const quill = new Quill('#editor-content', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Tulis konten halaman disini...'
            });

            /* ================= 2. LOAD DATA (SOLUSI BUG H1/H2) ================= */
            // Menggunakan json_encode agar HTML tag (<h1>, <b>) terbaca sebagai string valid di JS
            // Ini mencegah browser merender tag sebelum masuk ke editor
            const existingContent = {!! json_encode($page->content) !!};
            
            if (existingContent) {
                // Masukkan ke Quill. Quill akan otomatis mengubah string HTML menjadi format visual
                quill.clipboard.dangerouslyPasteHTML(0, existingContent);
            }

            /* ================= 3. SAVE DATA SAAT SUBMIT ================= */
            const form = document.getElementById('form-edit-page'); // Pastikan ID ini sesuai dengan <form> di atas
            const hiddenInput = document.getElementById('content');

            if (form) {
                form.addEventListener('submit', function(e) {
                    // 1. Salin HTML dari Quill ke hidden input agar terkirim ke server
                    hiddenInput.value = quill.root.innerHTML;

                    // 2. Cek apakah isinya kosong
                    if (quill.getText().trim().length === 0) {
                        e.preventDefault(); 
                        
                        // Munculkan pesan error
                        alert('Konten halaman tidak boleh kosong!'); 
                        
                        // Arahkan kursor kembali ke editor
                        quill.focus(); 
                        
                        return;
                    }
                });
            }

            /* ================= 4. IMAGE PREVIEW & DRAG-DROP UI ================= */
            const uploader = document.getElementById("file-uploader");
            const input = document.getElementById("featured_image_url");
            const preview = document.getElementById("image-preview");
            const container = document.getElementById("image-preview-container");
            const removeBtn = document.getElementById("remove-image-btn");

            // Efek Drag
            if(uploader) {
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
            }

            // Input Change (Klik manual)
            if(input) {
                input.addEventListener("change", () => {
                    if (input.files[0]) showPreview(input.files[0]);
                });
            }

            // Remove/Ganti Gambar Logic
            if(removeBtn) {
                removeBtn.addEventListener("click", () => {
                    input.value = ""; // Reset input file
                    // Jangan hide container preview jika aslinya memang ada gambar dari DB
                    // Tapi karena ini UI ganti gambar, kita kembalikan ke mode uploader
                    container.style.display = "none";
                   uploader.style.removeProperty("display");  
                    preview.src = "#";
                });
            }

            function showPreview(file) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    container.style.display = "block";
                    uploader.style.display = "none"; // Hide uploader area
                    uploader.style.setProperty('display', 'none', 'important');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush