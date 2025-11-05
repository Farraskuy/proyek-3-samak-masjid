@extends('client.layout')

@section('title', 'Informasi Rekening Bank - SAMAK-Kampus')

@section('content')
    <div class="container py-5">
        <!--Judul-->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-dark mb-4" data-testid="page-title">Salurkan ZIS Anda</h1>
            <p class="lead text-muted col-lg-8 mx-auto">
                Melalui SAMAK-Kampus, donasi Anda akan dikelola dengan transparan dan amanah untuk kemakmuran masjid dan kegiatan dakwah
            </p>
        </div>

        <!--Rekening Donasi-->
        <div class="mb-5">
            <h3 class="text-dark fw-bold text-center mb-4">Rekening Donasi</h3>

            <div class="row g-4 justify-content-center">
        
                @foreach ($daftarRekening as $rekening)
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow-sm rounded-4 h-100" data-testid="bank-account-{{ $rekening->account_id }}">
                            <div class="card-body p-4 text-center">
                                <div class="d-flex align-items-center justify-content-center mx-auto mb-3 bg-light rounded"
                                    style="width: 100px; height: 40px;">

                                    <img src="{{ $rekening->logo_url }}" alt="{{ $rekening->bank_name }}"
                                            style="max-height: 40px; max-width: 100px; object-fit: contain;">
                                </div>
                                <span class="small fw-bold text-muted">Bank</span>
                                <h5 class="fw-bold text-dark">{{ $rekening->bank_name }}</h5>
                            </div>
                            
                            <div class="card-body p-4 pt-0 text-center">
                                <div class="mb-3">
                                    <p class="small text-muted mb-1">Nomor Rekening</p>
                                    <p class="fs-4 fw-bold text-dark mb-0" data-testid="account-number-1">{{ $rekening->account_number }}</p>
                                </div>
                                <div class="mb-3">
                                    <p class="small text-muted mb-1">Atas Nama</p>
                                    <p class="fs-6 text-dark mb-0">{{ $rekening->account_holder_name }}</p>
                                </div>

                                <button class="btn btn-outline-secondary w-100" data-testid="copy-button-1">
                                    <i class="fas fa-copy me-2"></i>
                                    Salin Nomor Rekening
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($daftarRekening->isEmpty())
                    <div class="col-12 text-center">
                        <p class="text-muted">Saat ini belum ada rekening donasi yang tersedia.</p>
                    </div>
                @endif
            </div>
        </div>

        <!--Konfirmasi Donasi-->
        <div class="rounded-4 shadow text-white border-0" 
            style="background-image: linear-gradient(to bottom, #175C9E, #6686a3ff);">
            
            <div class="p-5 text-center">
                <h3 class="fs-3 fw-bold mb-4">Sudah Transfer?</h3>
                
                <p class="mb-5 text-white-55">
                    Ayo lakukan konfirmasi donasi untuk pencatatan yang akurat dan transparan
                </p>
                
                <div>
                    <a href="/donasi/konfirmasi">
                        <button class="btn btn-light fw-bold  shadow-sm rounded-3 px-4 py-2" style="color: #175C9E">
                            Konfirmasi Sekarang
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

<!--Tombol Salin-->
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded',function () {
            const buttons =  document.querySelectorAll('[data-testid^="copy-button-"]');
            buttons.forEach(button => {
                button.addEventListener('click' , () => {
                    const card = button.closest('.card');
                    const nomor = card.querySelector('[data-testid^="account-number-"]').innerText.trim();

                    navigator.clipboard.writeText(nomor).then(() => {
                        const original = button.innerHTML;
                        button.innerHTML = '<i class="fas fa-check me-2 text-success"></i> Disalin!';
                        button.classList.add('btn-success');
                        button.classList.remove('btn-outline-secondary');

                        setTimeout(() => {
                            button.innerHTML = original;
                            button.classList.remove('btn-success'); 
                            button.classList.add('btn-outline-secondary');
                        }, 2000);
                    });

                });
            });
        });
    </script>
@endpush

@endsection