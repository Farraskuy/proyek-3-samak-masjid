<div class="col-lg-3 mb-4 mb-lg-0">
    <div class="list-group list-group-flush">
        <h6 class="fw-bold text-dark mb-3 px-0">General</h6>
        <a href="{{ route('admin.profile.index') }}"
            class="list-group-item list-group-item-action border-0 px-0 py-2 mb-1 {{ Route::is('admin.profile.index') ? 'fw-bold text-dark' : 'text-muted bg-transparent' }}">
            Akun
        </a>
        <a href="{{ route('admin.profile.edit') }}"
            class="list-group-item list-group-item-action border-0 px-0 py-2 mb-1 {{ Route::is('admin.profile.edit') ? 'fw-bold text-dark' : 'text-muted bg-transparent' }}">
            Edit Profile
        </a>
        <a href="{{ route('admin.profile.password') }}"
            class="list-group-item list-group-item-action border-0 px-0 py-2 mb-1 {{ Route::is('admin.profile.password') ? 'fw-bold text-dark' : 'text-muted bg-transparent' }}">
            Password
        </a>

        <form action="{{ route('logout') }}" method="POST" class="d-none d-lg-inline mt-3">
            @csrf
            <button type="submit" class="btn btn-link text-danger text-decoration-none px-0 fw-medium">
                Logout
            </button>
        </form>
    </div>
</div>

<style>
    .list-group-item {
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .list-group-item:hover {
        color: #000 !important;
        background-color: transparent !important;
    }
</style>
