@extends('admin.layout')

@section('title', 'Informasi Website')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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
    </style>
@endpush

@section('content')
    <section class="p-3 container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form
            action="{{ isset($page) ? route('admin.website-information.update', $page->id) : route('admin.website-information.store') }}"
            method="POST" id="form-website-information" enctype="multipart/form-data">
            @csrf
            @if (isset($page))
                @method('PUT')
            @endif

            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="fw-semibold mb-0">Informasi Website & Tentang Kami</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('tentang-kami') }}" target="_blank" class="btn btn-outline-primary fw-semibold">
                        <i class="fas fa-eye me-1"></i> Preview Halaman
                    </a>
                    <button type="submit" class="btn btn-success fw-semibold">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Halaman Tentang Kami</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Halaman</label>
                            <input type="text" name="title" class="form-control input-lg"
                                value="{{ old('title', $page->title ?? 'Tentang Kami') }}" required>
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi Singkat</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $page->description ?? '') }}</textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konten Halaman</label>
                            <div id="editor" style="min-height:400px;">
                                {!! old('content', $page->content ?? '') !!}
                            </div>
                            <input type="hidden" id="quill_content" name="content"
                                value="{{ old('content', $page->content ?? '') }}">
                            @error('content')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Gambar Utama (Hero)</h5>

                        <label for="file-input" id="file-uploader" class="file-uploader"
                            style="{{ isset($page) && $page->featured_image_url ? 'display:none!important' : '' }}">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <div class="fw-semibold">Drag & drop gambar</div>
                            <div class="small text-muted">atau klik untuk memilih</div>
                        </label>

                        <input type="file" name="featured_image_url" id="file-input" accept="image/*" class="d-none">

                        <div id="image-preview-container" class="mt-3"
                            style="{{ isset($page) && $page->featured_image_url ? 'display:block' : '' }}">
                            <img id="image-preview"
                                src="{{ isset($page) && $page->featured_image_url ? asset('/storage/'.$page->featured_image_url) : '#' }}"
                                alt="Preview">
                            <button type="button" id="remove-image-btn">&times;</button>
                        </div>
                        @error('featured_image_url')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card bg-white border-0 rounded-3 p-4">
                        <h5 class="fw-semibold mb-3">Informasi Footer</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="footer_address" class="form-control" rows="3">{{ old('footer_address', $page->footer_address ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" name="footer_phone" class="form-control"
                                value="{{ old('footer_phone', $page->footer_phone ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="footer_email" class="form-control"
                                value="{{ old('footer_email', $page->footer_email ?? '') }}">
                        </div>

                        <hr>
                        <h6 class="fw-semibold mb-2">Media Sosial</h6>
                        <div id="social-media-container">
                            @php
                                // 1. Ambil data mentah (bisa dari old input saat validasi error, atau dari database)
                                $rawSocials = old('footer_social_links', $page->footer_social_links ?? []);

                                // 2. PENYELAMAT: Jika entah kenapa data masih String (JSON), paksa decode jadi Array
                                if (is_string($rawSocials)) {
                                    $rawSocials = json_decode($rawSocials, true);
                                }

                                // 3. Pastikan outputnya Array, kalau null jadikan array kosong
                                $socials = is_array($rawSocials) ? $rawSocials : [];
                            @endphp

                            @foreach ($socials as $index => $social)
                                <div class="social-item d-flex gap-2 mb-2">
                                    <select name="footer_social_links[{{ $index }}][platform]"
                                        class="form-select form-select-sm" style="width: 120px;">
                                        <option value="facebook"
                                            {{ ($social['platform'] ?? '') == 'facebook' ? 'selected' : '' }}>Facebook
                                        </option>
                                        <option value="instagram"
                                            {{ ($social['platform'] ?? '') == 'instagram' ? 'selected' : '' }}>Instagram
                                        </option>
                                        <option value="twitter"
                                            {{ ($social['platform'] ?? '') == 'twitter' ? 'selected' : '' }}>Twitter/X
                                        </option>
                                        <option value="youtube"
                                            {{ ($social['platform'] ?? '') == 'youtube' ? 'selected' : '' }}>YouTube
                                        </option>
                                        <option value="tiktok"
                                            {{ ($social['platform'] ?? '') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                                        <option value="linkedin"
                                            {{ ($social['platform'] ?? '') == 'linkedin' ? 'selected' : '' }}>LinkedIn
                                        </option>
                                    </select>
                                    <input type="url" name="footer_social_links[{{ $index }}][url]"
                                        class="form-control form-control-sm" placeholder="https://..."
                                        value="{{ $social['url'] ?? '' }}" required>
                                    <button type="button" class="btn btn-danger btn-sm remove-social-btn"><i
                                            class="fas fa-trash"></i></button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-social-btn" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                            <i class="fas fa-plus me-1"></i> Tambah Media Sosial
                        </button>
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
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{
                    'header': 1
                }, {
                    'header': 2
                }],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'script': 'sub'
                }, {
                    'script': 'super'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'color': []
                }, {
                    'background': []
                }],
                [{
                    'align': []
                }],
                ['link', 'image'],
                ['clean']
            ];

            const quill = new Quill("#editor", {
                theme: "snow",
                modules: {
                    toolbar: toolbarOptions
                }
            });

            const form = document.getElementById("form-website-information");
            const hidden = document.getElementById("quill_content");

            form.addEventListener("submit", function(e) {
                hidden.value = quill.root.innerHTML;

                // Optional: Check if content is empty
                // if (quill.getLength() < 2) { ... }

                const submitButtons = form.querySelectorAll("button[type='submit']");
                submitButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
                });
            });

            /* ======================== IMAGE UPLOADER =========================*/
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
                // If there was an existing image, we might want to handle that differently
                // For now, this just clears the preview of the NEW image
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
            /* ======================== DYNAMIC SOCIAL MEDIA =========================*/
            const socialContainer = document.getElementById('social-media-container');
            const addSocialBtn = document.getElementById('add-social-btn');

            addSocialBtn.addEventListener('click', function() {
                const index = socialContainer.children.length;
                const template = `
                    <div class="social-item d-flex gap-2 mb-2">
                        <select name="footer_social_links[${index}][platform]" class="form-select form-select-sm" style="width: 120px;">
                            <option value="facebook">Facebook</option>
                            <option value="instagram">Instagram</option>
                            <option value="twitter">Twitter/X</option>
                            <option value="youtube">YouTube</option>
                            <option value="tiktok">TikTok</option>
                            <option value="linkedin">LinkedIn</option>
                        </select>
                        <input type="url" name="footer_social_links[${index}][url]" class="form-control form-control-sm" placeholder="https://..." required>
                        <button type="button" class="btn btn-danger btn-sm remove-social-btn"><i class="fas fa-trash"></i></button>
                    </div>
                `;
                socialContainer.insertAdjacentHTML('beforeend', template);
            });

            socialContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-social-btn')) {
                    e.target.closest('.social-item').remove();
                    // Optional: Re-index inputs if needed, but Laravel handles non-sequential indices fine usually.
                    // But to be safe with array validation, we might want to keep them unique.
                    // Simple append is fine as long as we don't rely on strict sequential 0,1,2 in validation rules that expect array keys.
                    // Laravel's 'array' validation doesn't care about keys.
                }
            });
        });
    </script>
@endpush