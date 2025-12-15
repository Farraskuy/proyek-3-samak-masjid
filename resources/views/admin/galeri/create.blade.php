@extends('admin.layout')

@section('title', 'Tambah Album')

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

    /* Image Preview Styles */
    #coverPreviewWrapper {
        position: relative;
    }

    #coverPreview {
        width: 100%;
        border-radius: 1rem;
        border: 1px solid #ddd;
    }

    /* Photos Preview Grid */
    #photosPreview .position-relative {
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 10px;
    }

    #photosPreview img {
        border-radius: 0.5rem;
        height: 120px;
        width: 120px;
        object-fit: cover;
    }
</style>

@endpush

@section('content') <section class="p-3 container">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Ada masalah:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" id="form-album">
        @csrf

        {{-- Header --}}
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('admin.galeri.index') }}" class="btn btn-light btn-sm rounded-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="fw-semibold mb-0">Tambah Album Baru</h4>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Detail Album --}}
                <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                    <h5 class="fw-semibold mb-3">Informasi Album</h5>

                    {{-- Judul Album --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Album <span class="text-danger">*</span></label>
                        <input type="text" name="album_name" class="form-control input-lg" required maxlength="100"
                            value="{{ old('album_name') }}" placeholder="Masukkan judul album">
                    </div>

                    {{-- Foto Isi Album --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto Isi Album (Opsional)</label>

                        <label for="photos" class="file-uploader" id="photos-uploader">
                            <i class="fas fa-images fa-2x mb-2"></i>
                            <div class="fw-semibold">Upload Foto Album</div>
                            <div class="small text-muted">Drag & drop atau klik untuk pilih banyak</div>
                            <div class="small text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>Maks. 4 MB/foto | JPG, JPEG, PNG, WEBP
                            </div>
                        </label>

                        <input id="photos" type="file" accept="image/*" multiple class="d-none">

                        <div id="photosPreview" class="d-flex flex-wrap gap-2 mt-3"></div>

                        <div id="photosContainer"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Cover Album --}}
                <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                    <h5 class="fw-semibold mb-3">Cover Album</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto Cover <span class="text-danger">*</span></label>

                        <label for="cover_photo" class="file-uploader" id="cover-uploader">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <div class="fw-semibold">Upload Cover</div>
                            <div class="small text-muted">Drag & drop atau klik</div>
                            <div class="small text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>Maks. 2 MB | JPG, JPEG, PNG, WEBP
                            </div>
                        </label>

                        <input type="file" id="cover_photo" name="cover_photo" accept="image/*" class="d-none">

                        <div id="coverPreviewWrapper" class="mt-3" style="display:none;">
                            <div class="position-relative">
                                <img id="coverPreview" src="#" alt="Preview Cover">
                                {{-- Tombol remove akan di-inject via JS --}}
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-3">
                        <i class="fas fa-cloud-upload-alt me-1"></i> Simpan & Upload
                    </button>
                </div>
            </div>
        </div>
    </form>

</section>

@endsection

@push('scripts') <script> document.addEventListener("DOMContentLoaded", function() { /* ======================== DRAG & DROP STYLING =========================*/ const uploaders = [{ id: 'cover-uploader', input: 'cover_photo' }, { id: 'photos-uploader', input: 'photos' }];

        uploaders.forEach(obj => {
            const uploader = document.getElementById(obj.id);
            const input = document.getElementById(obj.input);

            if (uploader && input) {
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
                    const event = new Event('change');
                    input.dispatchEvent(event);
                });
            }
        });

        /* ========================  LOGIC ORIGINAL =========================*/

        // PREVIEW COVER ALBUM
        const coverInput = document.getElementById('cover_photo');
        const coverPreview = document.getElementById('coverPreview');
        const coverWrapper = document.getElementById('coverPreviewWrapper');
        const coverUploaderLabel = document.getElementById('cover-uploader');

        coverInput.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (!file) {
                coverWrapper.style.display = 'none';
                coverUploaderLabel.classList.remove('d-none');
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                coverPreview.src = e.target.result;
                coverWrapper.style.display = 'block';
                coverUploaderLabel.classList.add('d-none');

                // ambil container yang bener (yang position-relative)
                const imgContainer = coverWrapper.querySelector('.position-relative');

                // hapus tombol lama bila ada
                let removeBtn = imgContainer.querySelector('#removeCoverBtn');
                if (removeBtn) removeBtn.remove();

                // buat tombol baru
                removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.id = "removeCoverBtn";
                removeBtn.className = "btn btn-dark btn-sm position-absolute top-0 end-0 m-2";
                removeBtn.style.borderRadius = "50%";
                removeBtn.style.cursor = "pointer";
                removeBtn.style.width = "32px";
                removeBtn.style.height = "32px";
                removeBtn.innerHTML = "&times;";

                removeBtn.onclick = function() {
                    coverInput.value = "";
                    coverPreview.src = "#";
                    coverWrapper.style.display = "none";
                    coverUploaderLabel.classList.remove('d-none');
                };

                imgContainer.appendChild(removeBtn);
            };

            reader.readAsDataURL(file);
        });


        // PREVIEW MULTIPLE PHOTOS
        let selectedFiles = [];
        let captions = {}; // Object to store captions

        const photosInput = document.getElementById('photos');
        const photosPreview = document.getElementById('photosPreview');
        const photosContainer = document.getElementById('photosContainer');

        // ketika pilih foto baru
        photosInput.addEventListener('change', function(e) {
            const newFiles = Array.from(e.target.files);
            selectedFiles.push(...newFiles);

            // reset input agar bisa pilih lagi
            photosInput.value = "";
            renderPhotos();
        });

        function renderPhotos() {
            photosPreview.innerHTML = "";
            photosContainer.innerHTML = "";

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = function(ev) {
                    // preview gambar
                    const wrapper = document.createElement("div");
                    wrapper.className = "position-relative";
                    wrapper.style.width = "120px";

                    const img = document.createElement("img");
                    img.src = ev.target.result;
                    img.className = "img-thumbnail w-100";
                    img.style.height = "120px";
                    img.style.objectFit = "cover";

                    // caption foto
                    const captionInput = document.createElement("input");
                    captionInput.type = "text";
                    captionInput.name = `captions[${index}]`;
                    captionInput.className = "form-control form-control-sm mt-1";
                    captionInput.placeholder = "Caption";

                    // Restore caption value if it exists
                    if (captions[file.name]) {
                        captionInput.value = captions[file.name];
                    }

                    // Save caption value on input change
                    captionInput.addEventListener('input', function() {
                        captions[file.name] = captionInput.value;
                    });

                    // tombol hapus
                    const removeBtn = document.createElement("button");
                    removeBtn.type = "button";
                    removeBtn.className = "btn btn-danger btn-sm position-absolute top-0 end-0";
                    removeBtn.style.borderRadius = "50%";
                    removeBtn.style.padding = "0";
                    removeBtn.style.width = "24px";
                    removeBtn.style.height = "24px";
                    removeBtn.style.margin = "5px";
                    removeBtn.innerHTML = "&times;";

                    removeBtn.onclick = function() {
                        // Tambahkan efek transisi sebelum menghapus
                        wrapper.style.transition = "opacity 0.3s ease, transform 0.3s ease";
                        wrapper.style.opacity = "0";
                        wrapper.style.transform = "scale(0.9)";

                        setTimeout(() => {
                            // Remove file and caption setelah animasi selesai
                            selectedFiles.splice(index, 1);
                            delete captions[file.name];
                            renderPhotos();
                        }, 300); // Waktu sesuai dengan durasi transisi
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    wrapper.appendChild(captionInput);
                    photosPreview.appendChild(wrapper);
                };

                reader.readAsDataURL(file);

                // hidden input untuk dikirim ke server
                const dt = new DataTransfer();
                dt.items.add(file);

                const hiddenInput = document.createElement("input");
                hiddenInput.type = "file";
                hiddenInput.name = "photos[]";
                hiddenInput.files = dt.files;
                hiddenInput.hidden = true;

                photosContainer.appendChild(hiddenInput);
            });
        }
    });
</script>

@endpush