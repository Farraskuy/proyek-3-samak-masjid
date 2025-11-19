@extends('admin.layout')

@section('title', 'Edit Postingan')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <button id="backButton"
            style="margin-bottom: 15px; padding: 6px 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; margin-top: 1%; margin-left: 2%; margin-bottom: 1%;">
        ← Kembali
    </button>

    <div style="max-width: 90%; margin: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">

        <h3>Edit Postingan</h3>

        @if ($errors->any())
            <div style="color: red; margin-bottom: 15px;">
                <strong>Error:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('postingan.update', $post->id) }}" method="POST" id="form-postingan" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="headerImage">Gambar Header: (Kosongkan jika tidak ingin ganti)</label><br>
            <input type="file" name="featured_image_url" id="headerImage" accept="image/*" style="margin-bottom: 15px;"><br>

            <img id="preview"
                 src="{{ $post->featured_image_url ? Storage::url($post->featured_image_url) : '' }}"
                 alt="Preview"
                 style="max-width: 200px; display: {{ $post->featured_image_url ? 'block' : 'none' }}; margin-bottom: 15px;">

            <label for="title">Judul Postingan:</label><br>
            <input type="text" name="title" id="title" placeholder="Tulis judul di sini"
                   value="{{ old('title', $post->title) }}"
                   style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;" required><br>

            <label for="keterangan">Keterangan:</label><br>
            <input type="text" name="keterangan" id="keterangan" placeholder="Tuliskan keterangan di sini"
                   value="{{ old('keterangan', $post->keterangan) }}"
                   style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;"><br>

            <label for="kategori">Kategori:</label><br>
            <select name="kategori" id="kategori" style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;" required>
                <option value="" hidden disabled>Pilih kategori...</option>

                <option value="artikel" {{ old('kategori', $post->kategori) == 'artikel' ? 'selected' : '' }}>
                    Artikel dakwah
                </option>
                <option value="berita" {{ old('kategori', $post->kategori) == 'berita' ? 'selected' : '' }}>
                    Berita
                </option>
                <option value="tausiyah" {{ old('kategori', $post->kategori) == 'tausiyah' ? 'selected' : '' }}>
                    Tausiyah singkat
                </option>
            </select><br>

            <label for="editor">Konten:</label><br>
            <div id="editorContainer" style="width: 100%; min-height: 250px; border: 1px solid #ccc; border-radius: 8px; margin-bottom: 10px;">
                <div id="editor" style="height: 300px;"></div>
            </div>

            <input type="hidden" name="content" id="content_hidden">

            <button type="submit"
                    style="padding: 8px 15px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Update Postingan
            </button>
        </form>
    </div>

    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'header': [1, 2, false] }],
                    ['link', 'image'],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        });

        const existingContent = {!! json_encode($post->content) !!};
        if (existingContent) {
            quill.root.innerHTML = existingContent;
        }

        const form = document.querySelector('form');
        form.addEventListener('submit', function () {
            const html = quill.root.innerHTML;
            if (html === '<p><br></p>') {
                document.getElementById('content_hidden').value = '';
            } else {
                document.getElementById('content_hidden').value = html;
            }
        });

        const backButton = document.getElementById('backButton');
        backButton.addEventListener('click', function () {
            window.location.href = '/admin/postingan';
        });

        const input = document.getElementById('headerImage');
        const preview = document.getElementById('preview');

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
