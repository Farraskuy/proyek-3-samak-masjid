<nav class="navbar sticky-top navbar-expand p-0">
    <div class="container-fluid bg-white d-flex justify-content-between py-2">
        <button class="btn text-purple" type="button" onclick="toggleSidebar()"><i class="fa-solid fa-bars fa-lg"></i></button>
        <div class="ps-2 border-2 dropdown" style="cursor: pointer">
            <a class="text-decoration-none" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="true">
                <div style="height: 45px; width: 170px;" class="row g-0">
                    <div class="text-dark text-nowrap wrap-text col-9 d-flex flex-column justify-content-center">
                        <small class="p-0 m-0 fw-semibold wrap-text fs-13px">{{ Auth::user()?->username }}</small>
                        <small class="p-0 m-0 wrap-text w-75 fs-12px">{{ Auth::user()?->role }}</small>
                    </div>
                    <div class="h-100 col-3 text-center">
                        <img style="object-fit: cover;" class="rounded-circle" height="40" width="40" src="{{ Auth::user()?->image_url ? asset('storage/upload/' . Auth::user()?->image_url) : 'https://ui-avatars.com/api/?background=random&name=' . Auth::user()?->full_name }}" alt="">
                    </div>
                </div>
            </a>
            <div style="min-width: 300px;" class="dropdown-menu dropdown-menu-end p-3 dropdown-menu-profil" data-bs-popper="static">
                <div class="d-flex align-items-center flex-column">
                    <img style="object-fit: cover;" class="rounded-circle mb-3" height="70" width="70" src="{{ Auth::user()?->image_url ? asset('storage/upload/' . Auth::user()?->image_url) : 'https://ui-avatars.com/api/?background=random&name=' . Auth::user()?->full_name }}" alt="">
                    <p class="text-wrap fw-semibold fs-14px mb-0">{{ Auth::user()?->nama }}</p>
                    <p class="text-wrap text-secondary fw-semibold fs-13px mb-0">{{ Auth::user()?->username }}</p>
                    <p class="text-wrap fs-13px mb-0">{{ Auth::user()?->role }}</p>
                </div>
                <hr class="my-3 mb-2">
                <button type="button" data-bs-toggle="modal" data-bs-target="#logout" class="dropdown-item small btn btn-sm rounded">
                    <div class="row g-0 flex-nowrap align-items-center p-1 px-2">
                        <i class="fa-regular fa-right-from-bracket col-2"></i>
                        <p class="m-0 col">Logout</p>
                    </div>
                </button>
            </div>
        </div>
    </div>
</nav>
