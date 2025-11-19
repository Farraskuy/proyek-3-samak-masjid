@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Edit Rekening Bank
        </div>
        <div class="card-body">
            <form action="{{ route('admin.banks.update', $bank->account_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') 
                <div class="mb-3">
                    <label class="form-label">Nama Bank</label>
                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $bank->bank_name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Rekening</label>
                    <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $bank->account_number) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Atas Nama</label>
                    <input type="text" name="account_holder_name" class="form-control" value="{{ old('account_holder_name', $bank->account_holder_name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori Tampil</label>
                    <select name="category" class="form-select">
                        <option value="all" {{ $bank->category == 'all' ? 'selected' : '' }}>Semua (Zakat & Infak)</option>
                        <option value="zakat" {{ $bank->category == 'zakat' ? 'selected' : '' }}>Hanya Zakat</option>
                        <option value="infaq" {{ $bank->category == 'infaq' ? 'selected' : '' }}>Hanya Infak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Logo Bank</label>
                    <div class="mb-2">
                        <img src="{{ asset($bank->logo_url) }}" alt="Logo Lama" width="100" class="img-thumbnail">
                        <small class="d-block text-muted">Logo saat ini</small>
                    </div>
                    <input type="file" name="logo" class="form-control">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengganti logo.</small>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ $bank->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">
                        Status Aktif (Tampilkan di Halaman Donasi)
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Rekening</button>
                    <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection