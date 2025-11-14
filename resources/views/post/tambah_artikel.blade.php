
@extends('client.layout')

@section('title', 'Beranda - SAMAK-Kampus')

@push('styles')
    <style>
        * {
            font-family: 'Poppins', "Lexend", Geneva, Verdana, sans-serif;
        }

        .bg-pattern {
            background-image: radial-gradient(circle at 2px 2px, rgba(255, 255, 255, 0.5) 1px, transparent 1px);
            background-size: 100% 100%;
        }

        .feature-card:hover {
            border-color: #2dd4bf !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .btn-amber {
            background-color: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }

        .btn-amber:hover {
            background-color: #d97706;
            border-color: #d97706;
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
        }

        .feature-card {
            transition: all 0.3s ease-in-out;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }







        /* ini untuk modal  */
        .modal-overlay {
            /* Latar belakang gelap transparan */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            
            /* Menengahkan konten (modal-box) */
            display: flex;
            justify-content: center;
            align-items: center;
            
            /* Tampil di paling depan */
            z-index: 1000; 
        }

        .modal-box {
            /* Kotak putihnya */
            background: white;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px; /* Lebar maksimum */
        }

        .modal-box h3 {
            margin-top: 0;
            color: #333;
        }

        .modal-box p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        .modal-box .modal-ok-button {
            /* Tombol OK */
            background: #0d6efd; /* Warna biru */
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .modal-box .modal-ok-button:hover {
            background: #0b5ed7; /* Warna biru lebih gelap */
        }








    </style>
@endpush

@section('content')
    <!-- Tambahkan CSS dan JS Quill -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tombol Back -->
    <button id="backButton" 
            style="margin-bottom: 15px; padding: 6px 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; margin-top: 1%; margin-left: 2%; margin-bottom: 1%;">
      ← Kembali
    </button>

    <!-- Bagian Header Artikel -->
    <div style="max-width: 90%; margin: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px; ">
      <h3>Tambah Artikel Baru</h3>

      <!-- Form biasa (non-async) -->
      <form action="/posts" method="POST" id="form-postingan" enctype="multipart/form-data">
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
            <option value="" hidden disabled selected>Pilih kategori...</option>
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
            [{ 'align': [] }], 
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
@endsection

