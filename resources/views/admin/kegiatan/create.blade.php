@extends('admin.layout')

@section('title', 'Tambah Kegiatan')

@section('content')
    <section class="p-3">
        <h4 class="fw-bold mb-1" style="color: #175C9E;">Tambah Kegiatan Baru</h4>
        <p class="text-muted mb-4">Formulir penambahan kegiatan masjid kampus</p>

        <form action="{{ route('admin.kegiatan.store') }}" method="POST" enctype="multipart/form-data" id="formKegiatan">
            @csrf
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-body p-4">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tema / Deskripsi</label>
                        <textarea name="theme" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="location" class="form-control" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Waktu Mulai <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Waktu Selesai <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="end_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Poster Kegiatan</label>
                        <input type="file" name="poster" class="form-control" accept="image/*" id="posterInput">
                        <div id="imagePreview" class="mt-2" style="display:none;">
                            <img id="previewImg" src="" class="img-thumbnail" style="max-height: 150px;">
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2"
                                id="removeImage">Hapus</button>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="checkTamu" name="is_have_tamu_undangan">
                        <label class="form-check-label fw-semibold" for="checkTamu">Ada pembicara / tamu undangan</label>
                    </div>

                    <div id="daftarTamuContainer" style="display:none;">
                        <div id="inputTamuWrapper">
                            <input type="text" name="daftar_tamu[]" class="form-control mb-2"
                                placeholder="Nama pembicara">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="btnTambahTamu">+
                            Tambah</button>
                    </div>

                    <hr>

                    <!-- Form Integration -->
                    <h5 class="fw-bold mb-3 text-secondary">Integrasi Formulir</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="checkRegForm"
                                    name="has_registration_form" value="1">
                                <label class="form-check-label fw-semibold" for="checkRegForm">Butuh Formulir
                                    Pendaftaran</label>
                            </div>
                            <div id="regFormContainer" style="display:none;">
                                <select name="registration_form_id" class="form-select select2-form">
                                    <option value="">-- Pilih Formulir --</option>
                                    @foreach ($forms as $form)
                                        <option value="{{ $form->id }}">{{ $form->title }} ({{ $form->fields_count }}
                                            Pertanyaan)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="checkCloseForm" name="has_closing_form"
                                    value="1">
                                <label class="form-check-label fw-semibold" for="checkCloseForm">Butuh Formulir Penutupan
                                    (Kuisioner)</label>
                            </div>
                            <div id="closeFormContainer" style="display:none;">
                                <select name="closing_form_id" class="form-select select2-form">
                                    <option value="">-- Pilih Formulir --</option>
                                    @foreach ($forms as $form)
                                        <option value="{{ $form->id }}">{{ $form->title }} ({{ $form->fields_count }}
                                            Pertanyaan)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- PJ Feature -->
                    <h5 class="fw-bold mb-3 text-secondary">Penanggung Jawab (PJ)</h5>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="checkPJ" name="has_pj" value="1">
                        <label class="form-check-label fw-semibold" for="checkPJ">Aktifkan Penanggung Jawab Acara</label>
                    </div>

                    <div id="pjInfoContainer" class="alert alert-info" style="display:none;">
                        <i class="fas fa-info-circle me-2"></i> Akun Penanggung Jawab akan dibuat secara otomatis.
                        Kredensial (Email & Password) akan ditampilkan setelah kegiatan berhasil disimpan.
                    </div>

                    <hr>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.kegiatan.store') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </div>
        </form>
    </section>

    <style>
        .form-control:focus {
            border-color: #175C9E;
            box-shadow: 0 0 0 0.2rem rgba(23, 92, 158, 0.1);
        }

        .btn-primary {
            background-color: #175C9E;
            border-color: #175C9E;
        }

        .btn-primary:hover {
            background-color: #144a7a;
            border-color: #144a7a;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let counter = 1;

            document.getElementById('checkTamu').addEventListener('change', function() {
                document.getElementById('daftarTamuContainer').style.display = this.checked ? 'block' :
                    'none';
            });

            // Form Toggles
            document.getElementById('checkRegForm').addEventListener('change', function() {
                document.getElementById('regFormContainer').style.display = this.checked ? 'block' : 'none';
            });

            document.getElementById('checkCloseForm').addEventListener('change', function() {
                document.getElementById('closeFormContainer').style.display = this.checked ? 'block' :
                    'none';
            });

            // PJ Toggle
            document.getElementById('checkPJ').addEventListener('change', function() {
                document.getElementById('pjInfoContainer').style.display = this.checked ? 'block' : 'none';
            });

            document.getElementById('btnTambahTamu').addEventListener('click', function() {
                counter++;
                const div = document.createElement('div');
                div.className = 'input-group mb-2';
                div.innerHTML = `<input type="text" name="daftar_tamu[]" class="form-control" placeholder="Nama pembicara ${counter}">
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">×</button>`;
                document.getElementById('inputTamuWrapper').appendChild(div);
            });

            const input = document.getElementById('posterInput');
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('previewImg').src = e.target.result;
                        document.getElementById('imagePreview').style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById('removeImage').addEventListener('click', function() {
                input.value = '';
                document.getElementById('imagePreview').style.display = 'none';
            });

            document.getElementById('formKegiatan').addEventListener('submit', function(e) {
                const start = new Date(document.querySelector('[name="start_time"]').value);
                const end = new Date(document.querySelector('[name="end_time"]').value);
                if (start >= end) {
                    e.preventDefault();
                    alert('Waktu selesai harus lebih besar dari waktu mulai!');
                }
            });
        });
    </script>

@endsection
