@extends('admin.layout')

@section('title', 'Pengaturan Nisab Zakat')

@section('content')
    <section class="p-3 container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Pengaturan Nisab Zakat</h4>
                <p class="text-muted mb-0">Atur harga emas dan beras untuk perhitungan zakat</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <form action="{{ route('admin.settings.zakat.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card bg-white border-0 rounded-4 shadow-sm p-4 mb-4">
                        <h5 class="fw-semibold mb-4">
                            <i class="fas fa-coins text-warning me-2"></i>Harga Referensi
                        </h5>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-ring text-warning me-1"></i>
                                        Harga Emas per Gram
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" 
                                            name="harga_emas_per_gram" 
                                            id="harga_emas" 
                                            class="form-control form-control-lg money-input @error('harga_emas_per_gram') is-invalid @enderror"
                                            value="{{ old('harga_emas_per_gram', number_format($settings['harga_emas_per_gram'] ?? 1300000, 0, ',', '.')) }}"
                                            required>
                                    </div>
                                    @error('harga_emas_per_gram')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        Digunakan untuk menghitung nisab zakat maal (85 gram emas)
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-seedling text-success me-1"></i>
                                        Harga Beras per Kg
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" 
                                            name="harga_beras_per_kg" 
                                            id="harga_beras"
                                            class="form-control form-control-lg money-input @error('harga_beras_per_kg') is-invalid @enderror"
                                            value="{{ old('harga_beras_per_kg', number_format($settings['harga_beras_per_kg'] ?? 13500, 0, ',', '.')) }}"
                                            required>
                                    </div>
                                    @error('harga_beras_per_kg')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        Digunakan untuk menghitung zakat fitrah (2.5 kg/jiwa)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="card bg-white border-0 rounded-4 shadow-sm p-4">
                    <h5 class="fw-semibold mb-3">
                        <i class="fas fa-calculator text-primary me-2"></i>Kalkulasi Otomatis
                    </h5>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-start px-0">
                            <div>
                                <div class="fw-semibold">Nisab Zakat Maal</div>
                                <small class="text-muted">85 gram × harga emas</small>
                            </div>
                            <span class="badge bg-primary rounded-pill fs-6" id="nisab-maal">
                                Rp {{ number_format(($settings['harga_emas_per_gram'] ?? 1300000) * 85, 0, ',', '.') }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start px-0">
                            <div>
                                <div class="fw-semibold">Nisab Profesi/Bulan</div>
                                <small class="text-muted">Nisab maal ÷ 12</small>
                            </div>
                            <span class="badge bg-info rounded-pill fs-6" id="nisab-profesi">
                                Rp {{ number_format((($settings['harga_emas_per_gram'] ?? 1300000) * 85) / 12, 0, ',', '.') }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start px-0">
                            <div>
                                <div class="fw-semibold">Zakat Fitrah/Jiwa</div>
                                <small class="text-muted">2.5 kg × harga beras</small>
                            </div>
                            <span class="badge bg-success rounded-pill fs-6" id="zakat-fitrah">
                                Rp {{ number_format(($settings['harga_beras_per_kg'] ?? 13500) * 2.5, 0, ',', '.') }}
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="card bg-light border-0 rounded-4 p-4 mt-4">
                    <h6 class="fw-semibold mb-2">
                        <i class="fas fa-info-circle text-info me-1"></i>Informasi
                    </h6>
                    <p class="small text-muted mb-0">
                        Perubahan harga akan mempengaruhi perhitungan zakat di seluruh sistem, termasuk halaman donasi publik dan input donasi offline.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format money input
            document.querySelectorAll('.money-input').forEach(input => {
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^,\d]/g, '').toString();
                    let split = value.split(',');
                    let sisa = split[0].length % 3;
                    let rupiah = split[0].substr(0, sisa);
                    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }
                    e.target.value = rupiah;
                    updateCalculations();
                });
            });

            function parseInputValue(input) {
                return parseInt(input.value.replace(/\./g, '')) || 0;
            }

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(number);
            }

            function updateCalculations() {
                const hargaEmas = parseInputValue(document.getElementById('harga_emas'));
                const hargaBeras = parseInputValue(document.getElementById('harga_beras'));

                const nisabMaal = hargaEmas * 85;
                const nisabProfesi = nisabMaal / 12;
                const zakatFitrah = hargaBeras * 2.5;

                document.getElementById('nisab-maal').textContent = formatRupiah(nisabMaal);
                document.getElementById('nisab-profesi').textContent = formatRupiah(nisabProfesi);
                document.getElementById('zakat-fitrah').textContent = formatRupiah(zakatFitrah);
            }

            // Parse money input before submit
            document.querySelector('form').addEventListener('submit', function(e) {
                document.querySelectorAll('.money-input').forEach(input => {
                    input.value = input.value.replace(/\./g, '');
                });
            });
        });
    </script>
@endpush
