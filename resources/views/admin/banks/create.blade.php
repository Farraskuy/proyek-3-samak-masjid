@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">Tambah Rekening Baru</div>
        <div class="card-body">
            <form action="{{ route('admin.banks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Nama Bank</label>
                    <input type="text" name="bank_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Nomor Rekening</label>
                    <input type="text" name="account_number" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Atas Nama</label>
                    <input type="text" name="account_holder_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Kategori Tampil</label>
                    <select name="category" class="form-control">
                        <option value="all">Semua (Zakat & Infak)</option>
                        <option value="zakat">Hanya Zakat</option>
                        <option value="infaq">Hanya Infak</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Logo Bank</label>
                    <input type="file" name="logo" class="form-control" required>
                </div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection