@php
    $footerInfo = \App\Models\WebsiteInformation::first();
    $socials = $footerInfo ? $footerInfo->footer_social_links : [];
@endphp
<footer class="text-white pt-5" style="background-color: #175C9E">
    <div class="container">
        <div class="row">

            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-uppercase fw-bold mb-4">
                    <i class="fas fa-mosque me-2 text-warning"></i>SAMAK-Masjid
                </h6>
                <p class="text-white-50">
                    Sistem Administrasi Masjid Masjid yang melayani sivitas akademika dengan berbagai program kegiatan
                    islami.
                </p>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-uppercase fw-bold mb-4 text-warning">Quick Links</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="/" class="footer-link">Beranda</a>
                    </li>
                    <li class="mb-2">
                        <a href="/jadwal-kegiatan" class="footer-link">Jadwal Kegiatan</a>
                    </li>
                    <li class="mb-2">
                        <a href="/donasi" class="footer-link">Donasi</a>
                    </li>
                    <li class="mb-2">
                        <a href="/laporan-keuangan" class="footer-link">Transparansi Keuangan</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h6 class="text-uppercase fw-bold mb-4 text-warning">Kontak</h6>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-2 d-flex">
                        <i class="fas fa-map-marker-alt me-2 pt-1"></i>
                        <span>{{ $footerInfo->footer_address ?? 'Jl. Masjid Raya No. 123, Kota' }}</span>
                    </li>
                    <li class="mb-2 d-flex">
                        <i class="fas fa-phone me-2 pt-1"></i>
                        <span>{{ $footerInfo->footer_phone ?? '+62 812-3456-7890' }}</span>
                    </li>
                    <li class="mb-2 d-flex">
                        <i class="fas fa-envelope me-2 pt-1"></i>
                        <span>{{ $footerInfo->footer_email ?? 'info@samak-Masjid.ac.id' }}</span>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold mb-4 text-warning">Ikuti Kami</h6>
                <p class="text-white-50">Dapatkan update terbaru dari kami.</p>
                <div class="d-flex gap-2">
                    @php
                        // Normalize socials to array of objects
                        if (is_array($socials) && !array_is_list($socials) && !empty($socials)) {
                            $newSocials = [];
                            foreach ($socials as $key => $val) {
                                if ($val) {
                                    $newSocials[] = ['platform' => $key, 'url' => $val];
                                }
                            }
                            $socials = $newSocials;
                        }
                    @endphp

                    @if (!empty($socials) && is_array($socials))
                        @foreach ($socials as $social)
                            @if (!empty($social['url']))
                                <a href="{{ $social['url'] }}" class="social-icon" target="_blank"
                                    title="{{ ucfirst($social['platform'] ?? 'link') }}">
                                    @if (($social['platform'] ?? '') == 'facebook')
                                        <i class="fab fa-facebook-f"></i>
                                    @elseif(($social['platform'] ?? '') == 'instagram')
                                        <i class="fab fa-instagram"></i>
                                    @elseif(($social['platform'] ?? '') == 'twitter')
                                        <i class="fab fa-twitter"></i>
                                    @elseif(($social['platform'] ?? '') == 'youtube')
                                        <i class="fab fa-youtube"></i>
                                    @elseif(($social['platform'] ?? '') == 'tiktok')
                                        <i class="fab fa-tiktok"></i>
                                    @elseif(($social['platform'] ?? '') == 'linkedin')
                                        <i class="fab fa-linkedin-in"></i>
                                    @else
                                        <i class="fas fa-link"></i>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

        </div>

        <div class="border-top border-white border-opacity-25 mt-4 py-4">
            <div class="row">
                <div class="col text-center text-white-50">
                    <p class="mb-0">&copy; 2025 SAMAK-Masjid. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>
