<!-- Tambahkan CSS dan JS Quill -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<!-- Tombol Back -->
<button id="backButton" style="margin-bottom: 15px; padding: 6px 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
  ← Kembali
</button>

<!-- Bagian Header Artikel -->
<div style="max-width: 800px; margin: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
  <h3>Tambah Artikel Baru</h3>

  <!-- Upload Gambar Header -->
  <label for="headerImage">Gambar Header:</label><br>
  <input type="file" id="headerImage" accept="image/*" style="margin-bottom: 15px;"><br>


  <!-- Input Judul -->
  <label for="title">Judul Artikel:</label><br>
  <input type="text" id="title" placeholder="Tulis judul di sini" style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;"><br>
  
  

  
    <label for="keterangan">keterangan</label><br>
    <input type="text" id="keterangan" placeholder="Tulis judul di sini" style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;"><br>

  <!-- Elemen Editor -->
  <div id="editorContainer" style="width: 100%; height: 30%; border: 1px solid #ccc; border-radius: 8px;">
    <div id="editor" style="height: 95%;">
      <p>Tulis isi artikel di sini...</p>
    </div>
  </div>

  <!-- Tombol Submit -->
  <button id="submitBtn" style="margin-top: 15px; padding: 8px 15px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
    Simpan Artikel
  </button>
</div>

<script>
  // --- Inisialisasi Quill Editor ---
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

  // --- Tombol "Kembali" ---
  const backButton = document.getElementById('backButton');
  backButton.addEventListener('click', function() {
    window.location.href = '/admin/artikel';
  });

  // --- Tombol "Simpan Artikel" ---
  const submitBtn = document.getElementById('submitBtn');
  submitBtn.addEventListener('click', function() {
    // Ambil judul dari input
    const title = document.getElementById('title').value;

    // Ambil isi dari Quill editor dalam bentuk HTML
    const content = quill.root.innerHTML;

    // Ambil gambar (jika ada)
    const headerImage = document.getElementById('headerImage').files[0];

    // Cek isi
    console.log("Judul:", title);
    console.log("Isi artikel (HTML):", content);
    console.log("Gambar Header:", headerImage);

    // --- Kirim ke backend (contoh sederhana pakai FormData) ---
    const formData = new FormData();
    formData.append('title', title);
    formData.append('content', content);
    if (headerImage) {
      formData.append('headerImage', headerImage);
    }

    // Misalnya endpoint backend-nya adalah /admin/artikel/save
    fetch('/admin/artikel/save', {
      method: 'POST',
      body: formData
    })
    .then(response => {
      if (response.ok) {
        alert('Artikel berhasil disimpan!');
      } else {
        alert('Terjadi kesalahan saat menyimpan artikel.');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  });
</script>
