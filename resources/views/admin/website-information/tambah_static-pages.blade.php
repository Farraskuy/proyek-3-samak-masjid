@extends('admin.layout')

@section('title', 'Tambah Halaman Statis')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        /* --- STYLE SAMA PERSIS DENGAN EDIT --- */
        .section-wrapper { max-width: 1450px; margin: 0 auto; }
        .card-modern { border: 0 !important; background: #fff; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .input-lg { padding: .85rem 1rem !important; font-size: .95rem !important; }
        
        .btn-main {
            background-color: #CE9138 !important; color: white !important;
            border: none !important; padding: .75rem 1rem !important;
            border-radius: .75rem !important; font-weight: 600 !important;
        }

        .file-uploader {
            padding: 2rem; border-radius: 1rem; border: 2px dashed #dee2e6;
            background: #fafafa; text-align: center; cursor: pointer;
            color: #666; transition: .2s ease-in-out; display: block !important;
        }
        .file-uploader:hover, .file-uploader.on-drag { background: #f3f3f3; border-color: #CE9138 !important; }

        /* Image Preview Styles */
        #image-preview-container { position: relative; display: none; /* Default Hidden untuk Create */ }
        #image-preview { width: 100%; border-radius: 1rem; border: 1px solid #ddd; object-fit: cover; }
        #remove-image-btn {
            position: absolute; top: 10px; right: 10px; width: 32px; height: 32px;
            border-radius: 50%; border: none; background: rgba(0, 0, 0, .55);
            color: white; font-size: 18px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }

        /* Quill Overrides */
        .ql-toolbar.ql-snow { border-radius: 1rem 1rem 0 0 !important; border-color: #dee2e6 !important; background-color: #f8f9fa; }
        .ql-container.ql-snow { border-radius: 0 0 1rem 1rem !important; border-color: #dee2e6 !important; min-height: 400px !important; font-size: 1rem; }
    </style>
@endpush

@section('content')
    <section class="p-3 section-wrapper">

        {{-- Form Action ke STORE (Tanpa ID) --}}
        <form id="form-create-page" method="post" action="{{ route('admin.static-pages.store') }}" enctype="multipart/form-data">
            @csrf
            {{-- Tidak perlu @method('PUT') karena Create pakai POST --}}

            {{-- Header Section --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.static-pages.index') }}" class="btn btn-light btn-sm rounded-4 px-3">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <h4 class="fw-bold mb-0">Tambah Halaman Statis Baru</h4>
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
                            {{-- Value pakai old() saja --}}
                            <input type="text" class="form-control input-lg @error('title') is-invalid @enderror" 
                                id="title" name="title" 
                                placeholder="Masukkan judul halaman" 
                                value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Deskripsi Singkat </label>
                            {{-- Value pakai old() saja --}}
                            <textarea class="form-control input-lg @error('description') is-invalid @enderror" 
                                id="description" name="description" 
                                placeholder="Masukkan deskripsi untuk meta description" 
                                rows="3" >{{ old('description') }}</textarea>
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
                            <i class="fas fa-paper-plane me-1"></i> Terbitkan Halaman
                        </button>
                    </div>

                    {{-- Card Featured Image --}}
                    <div class="card-modern rounded-3 p-4">
                        <h5 class="fw-semibold mb-3">Gambar Utama</h5>

                        {{-- 1. Uploader Area (Default Muncul) --}}
                        <label for="featured_image_url" id="file-uploader" class="file-uploader">
                            <i class="fas fa-image fa-2x mb-2"></i>
                            <div class="fw-semibold">Upload Gambar</div>
                            <div class="small text-muted">Klik atau drag file kesini</div>
                        </label>

                        <input type="file" class="d-none" id="featured_image_url" name="featured_image_url" accept="image/*" >

                        {{-- 2. Preview Area (Default Hidden) --}}
                        <div id="image-preview-container" class="mt-2">
                            <img id="image-preview" src="#" alt="Preview Image">
                            <button type="button" id="remove-image-btn" title="Ganti Gambar">&times;</button>
                        </div>
                        
                        <div class="mt-2 text-center">
                            <small class="text-muted fst-italic" style="font-size: 0.8rem">*Maksimal ukuran 5mb (jpeg,png,jpg,gif,webp)</small>
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
            
            /* ================= 1. CONFIG QUILL ================= */
            const toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                [{ 'indent': '-1' }, { 'indent': '+1' }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ];

            const quill = new Quill('#editor-content', {
                theme: 'snow',
                modules: { toolbar: toolbarOptions },
                placeholder: 'Tulis konten halaman disini...'
            });

            /* ================= 2. LOAD OLD DATA (JIKA VALIDASI ERROR) ================= */
            // Mengambil data old input jika submit gagal, agar user tidak ngetik ulang
            const oldContent = {!! json_encode(old('content')) !!};
            
            if (oldContent) {
                quill.clipboard.dangerouslyPasteHTML(0, oldContent);
            }

            /* ================= 3. SAVE DATA SAAT SUBMIT ================= */
            const form = document.getElementById('form-create-page');
            const hiddenInput = document.getElementById('content');

            if (form) {
                form.addEventListener('submit', function(e) {
                    // A. Populate hidden input (Ambil HTML dari Quill taruh di textarea sembunyi)
                    hiddenInput.value = quill.root.innerHTML;

                    // B. VALIDASI MANUAL KONTEN
                    // Kita cek apakah teks di dalam editor kosong?
                    // quill.getText() mengambil teks polos tanpa tag HTML.
                    // .trim() menghapus spasi kosong di awal/akhir.
                    if (quill.getText().trim().length === 0) {
                        e.preventDefault(); // 1. Batalkan pengiriman form
                        
                        // 2. Beri peringatan ke user
                        alert('Konten halaman tidak boleh kosong!'); 
                        
                        // 3. (Opsional) Focus ke editor biar user langsung ngetik
                        quill.focus(); 
                        
                        return; // Stop script disini
                    }
                });
            }

            /* ================= 4. IMAGE PREVIEW & DRAG-DROP UI ================= */
            const uploader = document.getElementById("file-uploader");
            const input = document.getElementById("featured_image_url");
            const preview = document.getElementById("image-preview");
            const container = document.getElementById("image-preview-container");
            const removeBtn = document.getElementById("remove-image-btn");

            // Logic Drag & Drop
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

            // Input Change
            if(input) {
                input.addEventListener("change", () => {
                    if (input.files[0]) showPreview(input.files[0]);
                });
            }

            // Remove/Reset Logic
            if(removeBtn) {
                removeBtn.addEventListener("click", () => {
                    input.value = ""; 
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
                    uploader.style.display = "none"; 
                    uploader.style.setProperty('display', 'none', 'important');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush