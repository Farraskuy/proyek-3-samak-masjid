@extends('admin.layout')

@section('title', 'Tambah Role Baru')

@section('content')
    <section class="p-3 container">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf

            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-semibold mb-0">Tambah Role Baru</h4>
            </div>

            <div class="row g-4">
                {{-- Kolom Kiri: Identitas & Permission --}}
                <div class="col-lg-8">

                    {{-- Card Identitas Role --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Identitas Role</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">Nama Role (System Name)</label>
                                <input type="text" class="form-control py-2 px-3 @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required
                                    placeholder="Contoh: humas">
                                <small class="text-muted">Gunakan huruf kecil dan underscore (snake_case).</small>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="alias" class="form-label fw-semibold">Alias Role (Display Name)</label>
                                <input type="text" class="form-control py-2 px-3 @error('alias') is-invalid @enderror"
                                    id="alias" name="alias" value="{{ old('alias') }}" required
                                    placeholder="Contoh: Bidang Humas">
                                @error('alias')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" placeholder="Deskripsi singkat tentang role ini...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Card Permissions --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Hak Akses (Permissions)</h5>

                        <div class="row">
                            @foreach ($permissions as $group => $perms)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        {{-- Header: Group Name & Select All --}}
                                        <div class="card-header py-2 border-bottom-0 d-flex justify-content-between align-items-center"
                                            style="background-color: #e3f2fd;">
                                            <h6 class="m-0 fw-semibold" style="font-size: 14px;">{{ $group }} Akses</h6>
                                            <div class="form-check">
                                                <input class="form-check-input select-all-group" type="checkbox"
                                                    id="select_all_{{ Str::slug($group) }}"
                                                    data-group="{{ Str::slug($group) }}">
                                                <label class="form-check-label small fw-semibold"
                                                    for="select_all_{{ Str::slug($group) }}">
                                                    Select All
                                                </label>
                                            </div>
                                        </div>

                                        {{-- Body: Permissions List --}}
                                        <div class="card-body bg-light bg-opacity-10">
                                            <div class="row">
                                                @foreach ($perms as $perm)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="form-check">
                                                            <input
                                                                class="form-check-input permission-item group-{{ Str::slug($group) }}"
                                                                type="checkbox" name="permissions[]"
                                                                value="{{ $perm->id }}" id="perm_{{ $perm->id }}"
                                                                {{ is_array(old('permissions')) && in_array($perm->id, old('permissions')) ? 'checked' : '' }}>
                                                            <label class="form-check-label small"
                                                                for="perm_{{ $perm->id }}">
                                                                {{ $perm->alias ? Str::title(str_replace('_', ' ', $perm->alias)) : Str::title(str_replace('_', ' ', $perm->name)) }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan: Aksi --}}
                <div class="col-lg-4">
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Simpan</h5>
                        <p class="text-muted small">Pastikan data yang dimasukkan sudah benar sebelum menyimpan.</p>

                        <button type="submit" class="btn btn-success w-100 mt-2">
                            <i class="fas fa-save me-1"></i> Simpan Role
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle "Select All" click
                document.querySelectorAll('.select-all-group').forEach(selectAllCheckbox => {
                    selectAllCheckbox.addEventListener('change', function() {
                        const group = this.getAttribute('data-group');
                        const checkboxes = document.querySelectorAll(`.group-${group}`);

                        checkboxes.forEach(cb => {
                            cb.checked = this.checked;
                        });
                    });
                });

                // Handle individual permission click to update "Select All" state
                document.querySelectorAll('.permission-item').forEach(itemCheckbox => {
                    itemCheckbox.addEventListener('change', function() {
                        // Find the group this checkbox belongs to
                        // We need to find the class that starts with 'group-'
                        let groupClass = Array.from(this.classList).find(cls => cls.startsWith('group-'));
                        if (groupClass) {
                            let group = groupClass.replace('group-', '');
                            let selectAllCheckbox = document.getElementById(`select_all_${group}`);
                            let allCheckboxes = document.querySelectorAll(`.${groupClass}`);

                            // Check if all are checked
                            let allChecked = Array.from(allCheckboxes).every(cb => cb.checked);

                            if (selectAllCheckbox) {
                                selectAllCheckbox.checked = allChecked;
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
