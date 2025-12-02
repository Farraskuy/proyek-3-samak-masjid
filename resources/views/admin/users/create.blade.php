@extends('admin.layout')

@section('title', 'Tambah Pengguna Baru')

@section('content')
    <section class="p-3">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-semibold mb-0">Tambah Pengguna Baru</h4>
            </div>

            <div class="row g-4">
                {{-- Kolom Kiri: Data Pengguna --}}
                <div class="col-lg-8">

                    {{-- Card Data Pengguna --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Data Pengguna</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text"
                                    class="form-control py-2 px-3 @error('full_name') is-invalid @enderror" id="full_name"
                                    name="full_name" value="{{ old('full_name') }}" required
                                    placeholder="Contoh: Ahmad Fulan">
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label fw-semibold">Username</label>
                                <input type="text" class="form-control py-2 px-3 @error('username') is-invalid @enderror"
                                    id="username" name="username" value="{{ old('username') }}" required
                                    placeholder="Contoh: ahmad.fulan">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control py-2 px-3 @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required
                                    placeholder="Contoh: email@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text"
                                    class="form-control py-2 px-3 @error('phone_number') is-invalid @enderror"
                                    id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                                    placeholder="Contoh: 081234567890">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control py-2 px-3 @error('password') is-invalid @enderror"
                                id="password" name="password" required placeholder="Minimal 8 karakter">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Card Role --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Peran (Role)</h5>
                        <div class="mb-3">
                            <label for="role_id" class="form-label fw-semibold">Pilih Role</label>
                            <select class="form-select py-2 px-3 @error('role_id') is-invalid @enderror" id="role_id"
                                name="role_id" required>
                                <option value="" disabled selected>Pilih Role...</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->alias ?? $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Role menentukan hak akses pengguna dalam sistem.</small>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan: Aksi --}}
                <div class="col-lg-4">
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Simpan</h5>
                        <p class="text-muted small">Pastikan data yang dimasukkan sudah benar sebelum menyimpan.</p>

                        <button type="submit" class="btn btn-success w-100 mt-2">
                            <i class="fas fa-save me-1"></i> Simpan Pengguna
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
