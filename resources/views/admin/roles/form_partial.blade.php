<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Role (System Name)</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $role->name ?? '') }}" required placeholder="Contoh: humas">
            <small class="text-muted">Gunakan huruf kecil dan underscore (snake_case).</small>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="alias" class="form-label">Alias Role (Display Name)</label>
            <input type="text" class="form-control @error('alias') is-invalid @enderror" id="alias"
                name="alias" value="{{ old('alias', $role->alias ?? '') }}" required
                placeholder="Contoh: Bidang Humas">
            @error('alias')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Deskripsi</label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
        rows="3">{{ old('description', $role->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<hr>

<h5 class="mb-3">Hak Akses (Permissions)</h5>

<div class="row">
    @foreach ($permissions as $group => $perms)
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-left-primary shadow-sm">
                <div class="card-header py-2 bg-light border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $group }}</h6>
                </div>
                <div class="card-body">
                    @foreach ($perms as $perm)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                value="{{ $perm->id }}" id="perm_{{ $perm->id }}"
                                {{ (is_array(old('permissions')) && in_array($perm->id, old('permissions'))) || (isset($rolePermissions) && in_array($perm->id, $rolePermissions)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_{{ $perm->id }}">
                                {{ $perm->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
