@extends('admin.layout')

@section('title', 'Edit Program Infaq')

@section('content')
    <section class="p-3">
        <form action="{{ route('admin.infaqs.update', $infaq->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.infaqs.index') }}" class="btn btn-light btn-sm rounded-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h4 class="fw-semibold mb-0">Edit Program Infaq</h4>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.infaqs.index') }}" class="btn btn-light border">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>

            <div class="row">
                {{-- Left Column: Main Info --}}
                <div class="col-lg-8">
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-bold mb-3">Informasi Program</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Program <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                placeholder="Contoh: Infaq Pembangunan Masjid" value="{{ old('name', $infaq->name) }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Jelaskan detail program infaq ini...">{{ old('description', $infaq->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Settings & Poster --}}
                <div class="col-lg-4">
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-bold mb-3">Pengaturan</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rekening Tujuan <span class="text-danger">*</span></label>
                            <select name="bank_account_id" class="form-select" required>
                                <option value="" disabled>Pilih Rekening</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->account_id }}"
                                        {{ old('bank_account_id', $infaq->bank_account_id) == $bank->account_id ? 'selected' : '' }}>
                                        {{ $bank->bank_name }} - {{ $bank->account_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status Program</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                    name="is_active" {{ old('is_active', $infaq->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Aktifkan Program</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Poster Program</label>

                            {{-- Image Uploader --}}
                            <div class="image-uploader-wrapper text-center p-3 border rounded bg-light position-relative"
                                style="border-style: dashed !important; min-height: 200px; display: flex; flex-direction: column; justify-content: center;">

                                <input type="file" name="poster" id="poster"
                                    class="position-absolute w-100 h-100 opacity-0 top-0 start-0 cursor-pointer"
                                    accept="image/*" onchange="previewImage(this)">

                                <div id="placeholder-content" class="{{ $infaq->poster_url ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                    <p class="small text-muted mb-0">Klik atau seret gambar ke sini</p>
                                    <p class="small text-muted mb-0">(Max 2MB)</p>
                                </div>

                                <div id="preview-container"
                                    class="{{ $infaq->poster_url ? '' : 'd-none' }} h-100 w-100 position-absolute top-0 start-0 bg-white rounded overflow-hidden">
                                    <img id="preview-image" src="{{ $infaq->poster_url ? asset($infaq->poster_url) : '' }}"
                                        class="w-100 h-100 object-fit-cover">
                                    <button type="button"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle"
                                        onclick="removeImage()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

    @push('scripts')
        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('preview-image').src = e.target.result;
                        document.getElementById('preview-container').classList.remove('d-none');
                        document.getElementById('placeholder-content').classList.add('d-none');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function removeImage() {
                document.getElementById('poster').value = '';
                document.getElementById('preview-image').src = '';
                document.getElementById('preview-container').classList.add('d-none');
                document.getElementById('placeholder-content').classList.remove('d-none');
            }
        </script>
    @endpush
@endsection
