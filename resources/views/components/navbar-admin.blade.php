<nav class="navbar sticky-top navbar-expand p-0">
    <div class="container-fluid bg-white d-flex justify-content-between py-2">
        <button class="btn text-purple" type="button" onclick="toggleSidebar()"><i
                class="fa-solid fa-bars fa-lg"></i></button>
        <div class="ps-2 border-2 dropdown" style="cursor: pointer">
            <a class="text-decoration-none" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="true">
                <div style="height: 45px; width: 170px;" class="row g-0">
                    <div class="text-dark text-nowrap wrap-text col-9 d-flex flex-column justify-content-center">
                        <small class="p-0 m-0 fw-semibold wrap-text fs-13px">{{ Auth::user()?->username }}</small>
                        <small class="p-0 m-0 wrap-text w-75 fs-12px">{{ Auth::user()?->role->name }}</small>
                    </div>
                    <div class="h-100 col-3 text-center">
                        <img style="object-fit: cover;" class="rounded-circle" height="40" width="40"
                            src="{{ Auth::user()?->image_url ? asset(Auth::user()?->image_url) : 'https://ui-avatars.com/api/?background=random&name=' . Auth::user()?->full_name }}"
                            alt="">
                    </div>
                </div>
            </a>
            <div style="min-width: 280px;" class="dropdown-menu dropdown-menu-end p-0 border-0 shadow-lg rounded-4 mt-2"
                data-bs-popper="static">
                <!-- Header -->
                <div class="px-4 py-3 d-flex align-items-center gap-3 border-bottom">
                    <img style="object-fit: cover;" class="rounded-circle" height="45" width="45"
                        src="{{ Auth::user()?->image_url ? asset(Auth::user()?->image_url) : 'https://ui-avatars.com/api/?background=random&name=' . Auth::user()?->full_name }}"
                        alt="">
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-dark fs-14px">{{ Auth::user()?->full_name }}</span>
                        <span class="small text-muted fs-12px">{{ ucfirst(Auth::user()?->role->name) }}</span>
                    </div>
                </div>

                <!-- Menu Items -->
                <div class="p-2">
                    <a href="{{ route('admin.profile.index') }}"
                        class="dropdown-item d-flex align-items-center gap-3 p-2 rounded-3 mb-1">
                        <i class="fas fa-user text-secondary" style="width: 20px; text-align: center;"></i>
                        <span class="fs-14px fw-medium">Profile</span>
                    </a>

                    <button type="button" data-bs-toggle="modal" data-bs-target="#logout"
                        class="dropdown-item d-flex align-items-center gap-3 p-2 rounded-3 text-danger">
                        <i class="fas fa-sign-out-alt" style="width: 20px; text-align: center;"></i>
                        <span class="fs-14px fw-medium">Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
