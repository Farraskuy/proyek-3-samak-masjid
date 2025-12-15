@extends('admin.layout')

@section('title', 'Tambah Pemasukan')

@section('content')
    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.keuangan') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-semibold mb-0">Tambah Pemasukan</h4>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.keuangan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="pemasukan">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Bank / Rekening Tujuan <span
                                            class="text-danger">*</span></label>
                                    <select name="bank_name" class="form-select" required>
                                        <option value="">-- Pilih Bank --</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->bank_name }}"
                                                {{ old('bank_name') == $bank->bank_name ? 'selected' : '' }}>
                                                {{ $bank->bank_name }} ({{ $bank->account_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Kategori <span
                                            class="text-danger">*</span></label>
                                    <select name="category" class="form-select" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Donasi" {{ old('category') == 'Donasi' ? 'selected' : '' }}>Donasi
                                        </option>
                                        <option value="Zakat" {{ old('category') == 'Zakat' ? 'selected' : '' }}>Zakat
                                        </option>
                                        <option value="Infaq" {{ old('category') == 'Infaq' ? 'selected' : '' }}>Infaq
                                        </option>
                                        <option value="Kotak Amal" {{ old('category') == 'Kotak Amal' ? 'selected' : '' }}>
                                            Kotak Amal</option>
                                        <option value="Lain-lain" {{ old('category') == 'Lain-lain' ? 'selected' : '' }}>
                                            Lain-lain</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tanggal Transaksi <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="transaction_date" class="form-control"
                                        value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Jumlah <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="amount" class="form-control" placeholder="0"
                                            min="1000" value="{{ old('amount') }}" required>
                                    </div>
                                    <small class="text-muted">Minimal Rp 1.000</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Keterangan</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi atau catatan transaksi...">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Bukti Transaksi <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="proof_file" class="form-control" id="proofInput"
                                    accept="image/jpeg,image/png,image/jpg,application/pdf" required
                                    onchange="previewImage(this)">
                                <small class="text-muted">Format: JPG, PNG, PDF. Maksimal 5MB</small>

                                <div id="imagePreview" class="mt-3" style="display: none;">
                                    <img id="previewImg" src="" alt="Preview" class="img-fluid rounded border"
                                        style="max-height: 200px;">
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.keuangan') }}" class="btn btn-light border">Batal</a>
                                <button type="submit" class="btn btn-success fw-semibold">
                                    <i class="fas fa-save me-1"></i> Simpan Pemasukan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            function previewImage(input) {
                const preview = document.getElementById('imagePreview');
                const previewImg = document.getElementById('previewImg');

                if (input.files && input.files[0]) {
                    const file = input.files[0];

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            preview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.style.display = 'none';
                    }
                } else {
                    preview.style.display = 'none';
                }
            }
        </script>
    @endpush
@endsection
