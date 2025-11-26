@extends('admin.layout')

@section('title', 'Tambah Album')

@section('content')

<div class="container-fluid p-3">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4 class="mb-0">Tambah Album</h4>

        <a href="{{ route('admin.galeri') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Daftar Album</a>

    </div>



    <div class="card shadow-sm mb-4">

        <div class="card-body">



            @if (session('success'))

                <div class="alert alert-success alert-dismissible fade show" role="alert">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif



            @if ($errors->any())

                <div class="alert alert-danger">

                    <strong>Ada masalah:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif



            <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">

                @csrf



                {{-- Judul Album --}}

                <div class="mb-3">

                    <label class="form-label">Judul Album <span class="text-danger">*</span></label>

                    <input type="text" name="album_name" class="form-control" required maxlength="100"

                           value="{{ old('album_name') }}" placeholder="Masukkan judul album">

                </div>



                {{-- Cover Album --}}

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">Foto Cover Album <span class="text-danger">*</span></label>

                        <input type="file" id="cover_photo" name="cover_photo" accept="image/*" class="form-control">

                        <small class="text-muted">Format: jpg, jpeg, png. Maks 2MB.</small>



                        <div id="coverPreviewWrapper" class="mt-2" style="display:none;">

                            <label class="form-label small">Preview Cover</label>

                            <img id="coverPreview" class="img-thumbnail" style="max-width:240px;">

                        </div>

                    </div>



                    {{-- Foto Isi Album --}}

                    <div class="col-md-6">

                        <label class="form-label">Foto Isi Album (opsional)</label>

                        <input id="photos" type="file" accept="image/*" multiple class="form-control">

                        <small class="text-muted d-block mb-2">Bisa pilih banyak gambar. Maks 4MB per foto.</small>



                        <!-- Preview Foto -->

                        <div id="photosPreview" class="d-flex flex-wrap gap-2 mt-2"></div>



                        <!-- Hidden container to inject photo inputs -->

                        <div id="photosContainer"></div>

                    </div>

                </div>



                <div class="mt-4 d-flex gap-2">

                    <button type="submit" class="btn btn-primary">

                        <i class="bi bi-cloud-upload me-1"></i> Simpan & Upload Album

                    </button>

                    <a href="{{ route('admin.galeri') }}" class="btn btn-outline-secondary">Batal</a>

                </div>



            </form>

        </div>

    </div>

</div>



@endsection



@push('scripts')

<script>
// PREVIEW COVER ALBUM
const coverInput = document.getElementById('cover_photo');

const coverPreview = document.getElementById('coverPreview');

const coverWrapper = document.getElementById('coverPreviewWrapper');



coverInput.addEventListener('change', function(e) {

    const file = e.target.files[0];

    if (!file) {

        coverWrapper.style.display = 'none';

        return;

    }

    const reader = new FileReader();

    reader.onload = e => {

        coverPreview.src = e.target.result;

        coverWrapper.style.display = 'block';

    };

    reader.readAsDataURL(file);

});

// PREVIEW MULTIPLE PHOTOS

let selectedFiles = [];



const photosInput = document.getElementById('photos');

const photosPreview = document.getElementById('photosPreview');

const photosContainer = document.getElementById('photosContainer');



// ketika pilih foto baru

photosInput.addEventListener('change', function (e) {
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

            wrapper.style.position = "relative";



            const img = document.createElement("img");

            img.src = ev.target.result;

            img.className = "img-thumbnail";

            img.style.maxWidth = "120px";



            // caption foto

            const captionInput = document.createElement("input");

            captionInput.type = "text";

            captionInput.name = `captions[${index}]`;

            captionInput.className = "form-control form-control-sm mt-1";

            captionInput.placeholder = "Caption foto (opsional)";



            // tombol hapus

            const removeBtn = document.createElement("span");

            removeBtn.innerHTML = "×";

            removeBtn.style.position = "absolute";

            removeBtn.style.top = "-6px";

            removeBtn.style.right = "2px";

            removeBtn.style.cursor = "pointer";

            removeBtn.style.fontSize = "20px";

            removeBtn.style.color = "red";



            removeBtn.onclick = function() {

                selectedFiles.splice(index, 1);

                renderPhotos();

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

</script>

@endpush