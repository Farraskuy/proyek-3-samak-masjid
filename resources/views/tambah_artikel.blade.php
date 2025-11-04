<!-- Tambahkan CSS dan JS Quill -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<!-- Tombol Back -->
<button id="backButton" 
        style="margin-bottom: 15px; padding: 6px 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
  ← Kembali
</button>

<!-- Bagian Header Artikel -->
<div style="max-width: 800px; margin: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
  <h3>Tambah Artikel Baru</h3>

  <!-- Form biasa (non-async) -->
  <form action="/posts" method="POST" enctype="multipart/form-data">
    @csrf

    <label for="headerImage">Gambar Header:</label><br>
    <input type="file" name="image_view" id="headerImage" accept="image/*" style="margin-bottom: 15px;"><br>
  <img id="preview" src="" alt="Preview" style="max-width: 200px; display:none;">


    <label for="title">Judul Artikel:</label><br>
    <input type="text" name="title_view" id="title" placeholder="Tulis judul di sini"
           style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;"><br>

    <label for="keterangan">Keterangan:</label><br>
    <input type="text" name="keterangan_view" id="keterangan" placeholder="Tuliskan keterangan di sini"
           style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;"><br>

    <label for="kategori">Kategori Konten:</label><br>
    <select name="kategori_view" id="kategori" style="margin-bottom: 10px;" required>
        <option value=""hidden disabled selected>Pilih kategori...</option>
      <option value="artikel">Artikel dakwah</option>
      <option value="berita">Berita</option>
      <option value="tausiyah">Tausiyah singkat</option>
    </select><br>

<div id="editorContainer" style="width: 100%; min-height: 250px; border: 1px solid #ccc; border-radius: 8px; margin-bottom: 10px;">
  <div id="editor" style="height: 300px;">
    <p>Tulis isi artikel di sini...</p>
  </div>
</div>

    <!-- Hidden input untuk isi Quill -->
    <input type="hidden" name="content_view" id="content_hidden">

    <button type="submit" 
            style="margin-top: 15px; padding: 8px 15px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
      Simpan Artikel
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
        ['clean']
      ]
    }
  });

  // Isi hidden input sebelum submit
  const form = document.querySelector('form');
  form.addEventListener('submit', function () {
    const html = quill.root.innerHTML;
    document.getElementById('content_hidden').value = html;
  });

  // Tombol kembali
  const backButton = document.getElementById('backButton');
  backButton.addEventListener('click', function () {
    window.location.href = '/admin/artikel';
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
    } else {
        preview.src = "";
        preview.style.display = 'none';
    }
});



</script>
