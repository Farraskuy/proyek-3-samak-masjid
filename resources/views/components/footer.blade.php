@php
$footerInfo = \App\Models\WebsiteInformation::first();
$rawSocials = $footerInfo ? $footerInfo->footer_social_links : [];
$socials = [];

// Logika pembersih data
if (!empty($rawSocials)) {
// Cek jika data masih string JSON, decode dulu
if (is_string($rawSocials)) {
$rawSocials = json_decode($rawSocials, true);
}

if (is_array($rawSocials)) {
foreach ($rawSocials as $item) {
$platform = is_array($item) ? ($item['platform'] ?? '') : ($item->platform ?? '');
$url = is_array($item) ? ($item['url'] ?? '') : ($item->url ?? '');

if (!empty($platform) && !empty($url)) {
$socials[] = [
'platform' => strtolower($platform),
'url' => $url
];
}
}
}
}
@endphp

<footer class="text-white pt-5" style="background-color: #175C9E">
    <div class="container">
        <div class="row">

            {{-- Kolom 1 --}}
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-uppercase fw-bold mb-4 d-flex align-items-center">
                    {{-- Icon Masjid (SVG) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-building me-2 text-warning" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694 1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z" />
                        <path d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z" />
                    </svg>
                    SAMAK-Masjid
                </h6>
                <p class="text-white-50">
                    Sistem Administrasi Masjid yang melayani sivitas akademika dengan berbagai program kegiatan islami.
                </p>
            </div>

            {{-- Kolom 2: Quick Links --}}
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-uppercase fw-bold mb-4 text-warning">Quick Links</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="/" class="footer-link text-white text-decoration-none">Beranda</a></li>
                    <li class="mb-2"><a href="/jadwal-kegiatan" class="footer-link text-white text-decoration-none">Jadwal Kegiatan</a></li>
                    <li class="mb-2"><a href="/donasi" class="footer-link text-white text-decoration-none">Donasi</a></li>
                    <li class="mb-2"><a href="/laporan-keuangan" class="footer-link text-white text-decoration-none">Transparansi Keuangan</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Kontak --}}
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h6 class="text-uppercase fw-bold mb-4 text-warning">Kontak</h6>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-2 d-flex">
                        {{-- Icon Map (SVG) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill me-2 mt-1" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                        </svg>
                        <span>{{ $footerInfo->footer_address ?? 'Jl. Masjid Raya No. 123, Kota' }}</span>
                    </li>
                    <li class="mb-2 d-flex">
                        {{-- Icon Phone (SVG) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill me-2 mt-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                        </svg>
                        <span>{{ $footerInfo->footer_phone ?? '+62 812-3456-7890' }}</span>
                    </li>
                    <li class="mb-2 d-flex">
                        {{-- Icon Email (SVG) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill me-2 mt-1" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z" />
                        </svg>
                        <span>{{ $footerInfo->footer_email ?? 'info@samak-Masjid.ac.id' }}</span>
                    </li>
                </ul>
            </div>

            {{-- Kolom 4: Social Media (SVG Manual) --}}
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold mb-4 text-warning">Ikuti Kami</h6>
                <p class="text-white-50">Dapatkan update terbaru dari kami.</p>
                <div class="d-flex gap-2">
                    @foreach ($socials as $social)
                    <a href="{{ $social['url'] }}" class="text-white text-decoration-none me-3" target="_blank"
                        title="{{ ucfirst($social['platform']) }}">

                        {{-- FACEBOOK SVG --}}
                        @if ($social['platform'] == 'facebook')
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                        </svg>

                            {{-- LINKEDIN SVG --}}
                            @elseif($social['platform'] == 'linkedin')
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                                </svg>
                            
                            {{-- FALLBACK LINK SVG --}}
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                                    <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z"/>
                                </svg>
                            @endif
                        </a>
                    @endforeach

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