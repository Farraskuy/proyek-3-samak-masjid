@extends('admin.layout')

@section('title', 'Edit Halaman Statis')

@section('content')
    <section class="p-3">
        <h4 class="fw-semibold">Edit Halaman: {{ $page->title }}</h4>

        <div class="row g-0 gap-3 mt-3">
            <form id="form-edit-page" method="post" action="{{ route('admin.static-pages.update', $page->id) }}"
                class="col-12 col-lg-8 rounded-3 bg-white p-4"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" placeholder="Masukkan judul halaman" value="{{ old('title', $page->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                        name="description" placeholder="Masukkan deskripsi singkat" rows="3" required>{{ old('description', $page->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Featured Image --}}
                <div class="mb-3">
                    <label for="featured_image_url" class="form-label fw-semibold">Gambar Utama</label>
                    <input type="file" class="form-control @error('featured_image_url') is-invalid @enderror"
                        id="featured_image_url" name="featured_image_url" accept="image/*">

                    <img id="preview-image" class="img-fluid rounded mt-2" style="max-height: 200px; display: none;">

                    @if($page->featured_image_url)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $page->featured_image_url) }}" alt="Featured Image"
                                class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    @endif

                    @error('featured_image_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Content --}}
                <div class="mb-3">
                    <label for="content" class="form-label fw-semibold">Konten <span class="text-danger">*</span></label>
                    <div id="editor-content" class="border rounded"></div>
                    <textarea name="content" id="content" style="display: none;">{{ old('content', $page->content) }}</textarea>
                    @error('content')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.static-pages.index') }}" class="btn btn-outline-secondary fw-semibold">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary fw-semibold">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- Include Quill Editor --}}
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const quill = new Quill('#editor-content', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{ 'header': 1 }, { 'header': 2 }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    },
                    placeholder: 'Masukkan konten halaman...'
                });

                // Set initial content
                const contentTextarea = document.getElementById('content');
                if (contentTextarea.value) {
                    quill.root.innerHTML = contentTextarea.value;
                }

                // Update textarea before form submission
                const editForm = document.getElementById('form-edit-page'); // Ambil form berdasarkan ID

                if (editForm) {
                    editForm.addEventListener('submit', function() {
                        // Salin isi editor ke textarea tersembunyi
                        contentTextarea.value = quill.root.innerHTML;
                    });
                }
                
            });

            /*******************   untuk menampilkan preview   ***********************/
            document.getElementById('featured_image_url').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const preview = document.getElementById('preview-image');

                // cari gambar lama di container yang sama
                const container = event.target.closest('.mb-3');
                const oldImage = container.querySelector('img:not(#preview-image)');

                if (file) {
                    preview.src = URL.createObjectURL(file);
                    preview.style.display = 'block';

                    // sembunyikan gambar lama jika ada
                    if (oldImage) {
                        oldImage.style.display = 'none';
                    }
                }
            });

        </script>
    @endpush
@endsection
