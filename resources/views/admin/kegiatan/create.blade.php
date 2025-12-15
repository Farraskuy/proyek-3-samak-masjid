@extends('admin.layout')

@section('title', 'Tambah Kegiatan')

@push('styles')
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
            display: block;
        }

        .file-uploader.on-drag {
            background: #f3f3f3;
            border-color: #CE9138 !important;
        }

        /* Image Preview */
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

        <form action="{{ route('admin.kegiatan.store') }}" method="POST" enctype="multipart/form-data" id="formKegiatan">
            @csrf

            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-semibold mb-0">Tambah Kegiatan Baru</h4>
            </div>

            <div class="row g-4">
                {{-- Left Column --}}
                <div class="col-lg-8">

                    {{-- Informasi Utama --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Informasi Utama</h5>

                        <div class="mb-3">
                            <label for="eventNameInput" class="form-label fw-semibold">Nama Kegiatan <span
                                    class="text-danger">*</span></label>
                            <input id="eventNameInput" type="text" name="event_name" class="form-control input-lg"
                                placeholder="Contoh: Kajian Rutin Sabtu Sore" required>
                        </div>

                        <div class="mb-3">
                            <label for="themeInput" class="form-label fw-semibold">Tema / Deskripsi</label>
                            <textarea id="themeInput" name="theme" class="form-control" rows="3"
                                placeholder="Deskripsi singkat mengenai kegiatan..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="locationInput" class="form-label fw-semibold">Lokasi <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fas fa-map-marker-alt text-muted"></i></span>
                                <input id="locationInput" type="text" name="location"
                                    class="form-control border-start-0 ps-0" placeholder="Contoh: Masjid Utama, Lantai 1"
                                    required>
                            </div>
                        </div>
                    </div>

                    {{-- Waktu Pelaksanaan --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Waktu Pelaksanaan</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="startTimeInput" class="form-label fw-semibold">Waktu Mulai <span
                                        class="text-danger">*</span></label>
                                <input id="startTimeInput" type="datetime-local" name="start_time" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="endTimeInput" class="form-label fw-semibold">Waktu Selesai <span
                                        class="text-danger">*</span></label>
                                <input id="endTimeInput" type="datetime-local" name="end_time" class="form-control"
                                    required>
                            </div>
                        </div>
                    </div>

                    {{-- Tamu Undangan --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold mb-0">Pembicara / Tamu</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="checkTamu" name="is_have_tamu_undangan">
                            </div>
                        </div>

                        <div id="daftarTamuContainer" style="display:none;">
                            <p class="text-muted small mb-3">Tambahkan nama pembicara atau tamu undangan khusus.</p>
                            <div id="inputTamuWrapper">
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="daftar_tamu[]" class="form-control"
                                        placeholder="Nama pembicara">
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btnTambahTamu">
                                <i class="fas fa-plus me-1"></i> Tambah Pembicara
                            </button>
                        </div>
                        <div id="noTamuMessage" class="text-muted small fst-italic">
                            Tidak ada pembicara khusus untuk kegiatan ini.
                        </div>
                    </div>

                </div>

                {{-- Right Column --}}
                <div class="col-lg-4">

                    {{-- Action --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Aksi</h5>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="fas fa-save me-2"></i> Simpan Kegiatan
                        </button>
                    </div>

                    {{-- Poster --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Poster Kegiatan</h5>

                        <label for="file-input" id="file-uploader" class="file-uploader">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <div class="fw-semibold">Drag & drop gambar</div>
                            <div class="small text-muted">atau klik untuk memilih</div>
                            <div class="small text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>Maks. 2 MB | JPG, JPEG, PNG, WEBP
                            </div>
                        </label>

                        <input type="file" name="poster" id="file-input" accept="image/*" class="d-none">

                        <div id="image-preview-container" class="mt-3">
                            <img id="image-preview" src="#" alt="Preview">
                            <button type="button" id="remove-image-btn">&times;</button>
                        </div>
                    </div>

                    {{-- Integrasi Form --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Integrasi Formulir</h5>

                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="checkRegForm"
                                    name="has_registration_form" value="1">
                                <label class="form-check-label fw-medium" for="checkRegForm">Form Pendaftaran</label>
                            </div>
                            <div id="regFormContainer" style="display:none;" class="ps-4 border-start ms-2">
                                <select name="registration_form_id" class="form-select form-select-sm">
                                    <option value="">-- Pilih Formulir --</option>
                                    @foreach ($forms as $form)
                                        <option value="{{ $form->id }}">{{ $form->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="checkCloseForm"
                                    name="has_closing_form" value="1">
                                <label class="form-check-label fw-medium" for="checkCloseForm">Form Penutupan
                                    (Kuisioner)</label>
                            </div>
                            <div id="closeFormContainer" style="display:none;" class="ps-4 border-start ms-2">
                                <select name="closing_form_id" class="form-select form-select-sm">
                                    <option value="">-- Pilih Formulir --</option>
                                    @foreach ($forms as $form)
                                        <option value="{{ $form->id }}">{{ $form->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Penanggung Jawab --}}
                    <div class="card bg-white border-0 rounded-3 p-4">
                        <h5 class="fw-semibold mb-3">Penanggung Jawab</h5>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="checkPJ" name="has_pj"
                                value="1">
                            <label class="form-check-label fw-medium" for="checkPJ">Buat Akun PJ Otomatis</label>
                        </div>

                        <div id="pjInfoContainer" class="alert alert-info small mb-0" style="display:none;">
                            <i class="fas fa-info-circle me-1"></i> Akun PJ akan dibuatkan. Email & Password muncul setelah
                            disimpan.
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let counter = 1;

            // --- Tamu Undangan Logic ---
            const checkTamu = document.getElementById('checkTamu');
            const daftarTamuContainer = document.getElementById('daftarTamuContainer');
            const noTamuMessage = document.getElementById('noTamuMessage');

            function toggleTamu() {
                if (checkTamu.checked) {
                    daftarTamuContainer.style.display = 'block';
                    noTamuMessage.style.display = 'none';
                } else {
                    daftarTamuContainer.style.display = 'none';
                    noTamuMessage.style.display = 'block';
                }
            }
            checkTamu.addEventListener('change', toggleTamu);
            // Init state
            toggleTamu();

            document.getElementById('btnTambahTamu').addEventListener('click', function() {
                // Count existing inputs dynamically
                const currentCount = document.querySelectorAll('[name="daftar_tamu[]"]').length;
                const div = document.createElement('div');
                div.className = 'input-group mb-2';
                div.innerHTML = `
                    <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="daftar_tamu[]" class="form-control" placeholder="Nama pembicara ${currentCount + 1}">
                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>`;
                document.getElementById('inputTamuWrapper').appendChild(div);
            });

            // --- Form Toggles ---
            document.getElementById('checkRegForm').addEventListener('change', function() {
                document.getElementById('regFormContainer').style.display = this.checked ? 'block' : 'none';
            });

            document.getElementById('checkCloseForm').addEventListener('change', function() {
                document.getElementById('closeFormContainer').style.display = this.checked ? 'block' :
                    'none';
            });

            // --- PJ Toggle ---
            document.getElementById('checkPJ').addEventListener('change', function() {
                document.getElementById('pjInfoContainer').style.display = this.checked ? 'block' : 'none';
            });

            // --- Image Uploader Logic (Copied & Adapted) ---
            const uploader = document.getElementById("file-uploader");
            const input = document.getElementById("file-input");
            const preview = document.getElementById("image-preview");
            const container = document.getElementById("image-preview-container");
            const removeBtn = document.getElementById("remove-image-btn");
            let droppedFile = null;

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
                droppedFile = e.dataTransfer.files[0];
                
                // Use DataTransfer API to properly set files
                try {
                    const dt = new DataTransfer();
                    dt.items.add(droppedFile);
                    input.files = dt.files;
                } catch (err) {
                    console.log('DataTransfer not supported, using fallback');
                }
                
                showPreview(droppedFile);
            });

            input.addEventListener("change", () => {
                if (input.files[0]) {
                    droppedFile = null; // Clear dropped file when using file picker
                    showPreview(input.files[0]);
                }
            });

            removeBtn.addEventListener("click", () => {
                input.value = "";
                droppedFile = null;
                container.style.display = "none";
                uploader.classList.remove('d-none');
            });

            function showPreview(file) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    container.style.display = "block";
                    uploader.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            }

            // --- Validation & File Upload ---
            document.getElementById('formKegiatan').addEventListener('submit', function(e) {
                // Ensure dropped file is included in submission
                if (droppedFile && !input.files.length) {
                    try {
                        const dt = new DataTransfer();
                        dt.items.add(droppedFile);
                        input.files = dt.files;
                    } catch (err) {
                        console.error('Cannot add file:', err);
                    }
                }
                
                // Date validation
                const start = new Date(document.querySelector('[name="start_time"]').value);
                const end = new Date(document.querySelector('[name="end_time"]').value);
                if (start >= end) {
                    e.preventDefault();
                    alert('Waktu selesai harus lebih besar dari waktu mulai!');
                }
            });
        });
    </script>
@endpush
