<div class="col-lg-3 mb-4 mb-lg-0">
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="list-group list-group-flush bg-transparent">
                <h6 class="fw-bold text-dark mb-2 px-3 mt-2">Menu</h6>
                <a href="{{ route('profile.edit') }}"
                    class="list-group-item list-group-item-action border-0 px-3 py-2.5 mb-1 d-flex align-items-center rounded-3 {{ Route::is('profile.edit') ? 'active-link' : 'text-secondary' }}">
                    <i class="fa-regular fa-circle-user me-3" style="width: 20px;"></i>
                    <span class="fw-medium">Preferensi Akun</span>
                </a>
                <a href="{{ route('profile.password') }}"
                    class="list-group-item list-group-item-action border-0 px-3 py-2.5 mb-1 d-flex align-items-center rounded-3 {{ Route::is('profile.password') ? 'active-link' : 'text-secondary' }}">
                    <i class="fa-regular fa-lock me-3" style="width: 20px;"></i>
                    <span class="fw-medium">Keamanan & Login</span>
                </a>
                <div class="my-2 border-top"></div>
                <a href="{{ route('client.consultations.history') }}"
                    class="list-group-item list-group-item-action border-0 px-3 py-2.5 mb-1 d-flex align-items-center rounded-3 {{ Route::is('client.consultations.history') ? 'active-link' : 'text-secondary' }}">
                    <i class="fa-regular fa-clock-rotate-left me-3" style="width: 20px;"></i>
                    <span class="fw-medium">Riwayat Konsultasi</span>
                </a>
                <a href="{{ route('client.consultations.create') }}"
                    class="list-group-item list-group-item-action border-0 px-3 py-2.5 mb-1 d-flex align-items-center rounded-3 {{ Route::is('client.consultations.create') ? 'active-link' : 'text-secondary' }}">
                    <i class="fa-regular fa-plus me-3" style="width: 20px;"></i>
                    <span class="fw-medium">Konsultasi Baru</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Sidebar Styling (Card Style) */
    .active-link {
        color: #175C9E !important;
        background-color: rgba(23, 92, 158, 0.08) !important;
        font-weight: 600 !important;
    }

    .list-group-item {
        background-color: transparent;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        color: #6c757d;
    }

    .list-group-item:hover {
        background-color: rgba(23, 92, 158, 0.05);
        color: #175C9E !important;
    }
</style>
