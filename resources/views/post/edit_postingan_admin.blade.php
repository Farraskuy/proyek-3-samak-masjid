<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<button id="backButton" 
        style="margin-bottom: 15px; padding: 6px 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; margin-top: 1%; margin-left: 2%; margin-bottom: 1%;">
    ← Kembali
</button>

<div style="max-width: 90%; margin: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px; ">
    
    <h3>Edit Artikel</h3>

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

    <form action="{{ route('postingan.admin.update', $post->post_id) }}" method="POST" id="form-postingan" enctype="multipart/form-data">
        @csrf
        @method('PUT') <label for="headerImage">Gambar Header: (Kosongkan jika tidak ingin ganti)</label><br>
        <input type="file" name="image_view" id="headerImage" accept="image/*" style="margin-bottom: 15px;"><br>
        
        <img id="preview" 
             src="{{ $post->featured_image_url ? Storage::url($post->featured_image_url) : '' }}" 
             alt="Preview" 
             style="max-width: 200px; display: {{ $post->featured_image_url ? 'block' : 'none' }};">

        <label for="title">Judul Artikel:</label><br>
        <input type="text" name="title_view" id="title" placeholder="Tulis judul di sini"
               value="{{ old('title_view', $post->title) }}"
               style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;"><br>

        <label for="keterangan">Keterangan:</label><br>
        <input type="text" name="keterangan_view" id="keterangan" placeholder="Tuliskan keterangan di sini"
               value="{{ old('keterangan_view', $post->keterangan) }}"
               style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;"><br>

        <label for="kategori">Kategori Konten:</label><br>
        <select name="kategori_view" id="kategori" style="margin-bottom: 10px;" required>
            <option value="" hidden disabled>Pilih kategori...</option>
            
            <option value="artikel" {{ old('kategori_view', $post->kategori) == 'artikel' ? 'selected' : '' }}>
                Artikel dakwah
            </option>
            <option value="berita" {{ old('kategori_view', $post->kategori) == 'berita' ? 'selected' : '' }}>
                Berita
            </option>
            <option value="tausiyah" {{ old('kategori_view', $post->kategori) == 'tausiyah' ? 'selected' : '' }}>
                Tausiyah singkat
            </option>
        </select><br>

        <div id="editorContainer" style="width: 100%; min-height: 250px; border: 1px solid #ccc; border-radius: 8px; margin-bottom: 10px;">
            <div id="editor" style="height: 300px;"></div>
        </div>

        <input type="hidden" name="content_view" id="content_hidden">

        <button type="submit" 
                style="margin-top: 15px; padding: 8px 15px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Update Artikel
        </button>
    </form>
</div>

<script>
    // Inisialisasi Quill Editor
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

    // --- BARU: Mengisi editor dengan konten dari database ---
    // Gunakan json_encode(...)  untuk mengubah string PHP (yang berisi HTML)
    // menjadi string JavaScript yang aman.
    const existingContent = {!! json_encode($post->content) !!};
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }
    // --- Selesai bagian baru ---


    // Isi hidden input sebelum submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function () {
        const html = quill.root.innerHTML;
        // Cek jika editor kosong (hanya berisi <p><br></p>), anggap saja kosong
        if (html === '<p><br></p>') {
            document.getElementById('content_hidden').value = '';
        } else {
            document.getElementById('content_hidden').value = html;
        }
    });

    // Tombol kembali
    const backButton = document.getElementById('backButton');
    backButton.addEventListener('click', function () {
        window.location.href = '/admin/artikel'; // Arahkan kembali ke daftar artikel
    });

    // Preview Gambar
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
        // Jangan sembunyikan jika tidak ada file, biarkan gambar lama terlihat
    });

</script>