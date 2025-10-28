<aside class="sidebar bg-white py-2" style="line-height: 1.25">
    <div class="logo">
        <a href="/" class="logo-details">
            <div class="img">
                <img src="/assets/img/logo.png" alt="Logo" width="35" height="35"
                    class="d-inline-block align-text-top">
            </div>
            <span class="logo_name h6 m-0 fw-semibold wrap-text">Dimas</span>
        </a>
    </div>
    <ul class="nav-links" style="padding-bottom: 115px;">
        <li>
            <div class="nav-button {{ request()->is('/admin') ? 'active' : '' }}">
                <a href="/">
                    <i class="fa-regular fa-house"></i>
                    <span class="link_name">Home</span>
                </a>
            </div>
            <ul class="sub-menu blank">
                <li class="fw-semibold link_name">Home</li>
            </ul>
        </li>

        <li class="{{ request()->is('barang*') ? 'showMenu' : '' }}">
            <div class="nav-button {{ request()->is('barang*') ? 'active' : '' }}">
                <div class="iocn-link" onclick="expandMenu(this)">
                    <a>
                        <i class="fa-light fa-box"></i>
                        <span class="link_name">Data Barang</span>
                    </a>
                    <i class='fa-regular fa-angle-down arrow'></i>
                </div>
            </div>
            <ul class="sub-menu">
                <li><span class="link_name fw-semibold">Data Barang</span></li>

                <li class="nav-button {{ request()->is('barang') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="">
                        <span class="fa-regular fa-book"></span>
                        Daftar Barang
                    </a>
                </li>


                <li><span class="link_name fw-semibold d-block">Master Data</span></li>


                <li class="nav-button {{ request()->is('barang/kategori') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="">
                        <span class="fa-regular fa-hashtag"></span>
                        Daftar Kategori
                    </a>
                </li>
                <li class="nav-button {{ request()->is('barang/satuan') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="">
                        <span class="fa-regular fa-ruler"></span>
                        Daftar Satuan
                    </a>
                </li>

            </ul>
        </li>

        <li class="position-absolute w-100 bg-white" style="bottom: 0">
            <div class="bg-white me-2">
                <div class="nav-button {{ request()->is('pengaturan*') ? 'active' : '' }}">
                    <a href="">
                        <i class="fa-regular fa-gear"></i>
                        <span class="link_name">Pengaturan</span>
                    </a>
                </div>
            </div>
            <ul class="sub-menu blank">
                <li class="fw-semibold link_name">Pengaturan</li>
            </ul>
        </li>
    </ul>
</aside>
