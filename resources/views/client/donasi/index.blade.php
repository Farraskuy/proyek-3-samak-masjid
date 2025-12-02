@extends('client.layout')

@section('title', 'Kalkulator Zakat & Infaq')

@section('content')
<style>
    /* Styling Custom Sesuai Screenshot */
    .zis-container {
        background-color: #f9fbfd;
        min-height: 100vh;
        padding: 50px 0;
    }
    .card-calculator {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        padding: 30px;
        border: none;
    }
    .nav-pills .nav-link {
        border-radius: 0;
        color: #6c757d;
        font-weight: 600;
        padding: 15px 20px;
        border-bottom: 3px solid transparent;
    }
    .nav-pills .nav-link.active {
        background: transparent;
        color: #0099ff; /* Warna Biru Brand */
        border-bottom: 3px solid #0099ff;
    }
    .form-label {
        font-weight: 500;
        color: #333;
    }
    .input-group-text {
        background: #f8f9fa;
        border-right: none;
    }
    .form-control {
        border-left: none;
        background: #f8f9fa;
    }
    .form-control:focus {
        background: white;
        box-shadow: none;
        border-color: #ced4da;
    }
    .result-text {
        font-size: 2rem;
        font-weight: bold;
        color: #00a3cc;
    }
    .bank-card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
        display: flex;
        align-items: center;
        background: #fff;
    }
    .bank-logo {
        max-width: 80px;
        margin-right: 15px;
    }
    .copy-btn {
        cursor: pointer;
        color: #0099ff;
        font-size: 0.9rem;
    }
    .hidden-checkbox {
        display: none;
    }
    .custom-checkbox-label {
        border: 2px solid #dee2e6;
        background-color: white;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        position: relative;
    }

    .hidden-checkbox:checked + .custom-checkbox-label {
        border-color: #198754;
        background-color: #e8f5e9;
        color: #198754;
        box-shadow: 0 4px 10px rgba(25, 135, 84, 0.15);
    }

    .hidden-checkbox:checked + .custom-checkbox-label::after {
        content: "✅";
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 1.2rem;
    }
</style>

<div class="zis-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3 mb-4">
                <div class="list-group shadow-sm rounded-3 overflow-hidden">
                    <a href="#" class="list-group-item list-group-item-action active p-3" id="btn-menu-zakat" onclick="switchMode('zakat')">
                        <h5 class="mb-0"><i class="bi bi-calculator me-2"></i> Zakat</h5>
                        <small>Hitung kewajiban zakatmu</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3" id="btn-menu-infaq" onclick="switchMode('infaq')">
                        <h5 class="mb-0"><i class="bi bi-heart-fill me-2"></i> Infak</h5>
                        <small>Berbagi keberkahan</small>
                    </a>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card-calculator">
                    
                    <div class="text-center mb-4">
                        <h3 id="calculator-title">Ayo hitung zakat kamu!</h3>
                        <p class="text-muted">Masukkan jumlah hartamu dan kalkulator kami akan menghitung jumlahnya.</p>
                    </div>

                    <div id="section-zakat">
                        <div class="mb-4">
                            <label class="form-label">Pilih Jenis Zakat</label>
                            <select class="form-select form-select-lg mb-3" id="zakat-type" onchange="renderZakatForm()">
                                <option value="maal">Zakat Maal (Harta)</option>
                                <option value="profesi">Zakat Profesi (Penghasilan)</option>
                                <option value="emas">Zakat Emas & Perak</option>
                                <option value="tabungan">Zakat Tabungan</option>
                                <option value="pertanian">Zakat Pertanian</option>
                            </select>
                        </div>

                        <div id="zakat-form-container"></div>
                        
                        <hr class="my-4">
                        
                        <div class="mb-3">
                            <label class="text-muted small fw-bold">JUMLAH ZAKAT YANG HARUS DIKELUARKAN</label>
                            <div class="result-text" id="zakat-result">Rp 0</div>
                            <small class="text-danger fst-italic" id="nishab-warning" style="display:none;">* Harta Anda belum mencapai nishab (batas minimal wajib zakat).</small>
                        </div>
                    </div>

                    <div id="section-infaq" style="display: none;">
                        <div class="mb-4">
                            <label class="form-label">Jenis Infak</label>
                            <select class="form-select form-select-lg mb-3" id="infaq-type" onchange="calculateInfaq()">
                                <option value="umum">Infak Umum</option>
                                <option value="bencana">Infak Bencana</option>
                                <option value="pendidikan">Infak Pendidikan</option>
                                <option value="kesehatan">Infak Kesehatan</option>
                            </select>
                            <label class="form-label">Nominal Infak</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control form-control-lg money-input" id="infaq-amount" placeholder="0" onkeyup="calculateInfaq()">
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="mb-3">
                            <label class="text-muted small fw-bold">NOMINAL INFAK KAMU</label>
                            <div class="result-text" id="infaq-result">Rp 0</div>
                        </div>
                    </div>

                    <div id="rekening-container" style="display: none;" class="mt-4 animate__animated animate__fadeInUp">
                        <div class="alert alert-info border-0 bg-light text-dark">
                            <i class="bi bi-info-circle me-2"></i> Silakan transfer donasi Anda ke salah satu rekening di bawah ini:
                        </div>
                        <div id="rekening-list">
                            </div>
                    </div>

                    <section class="py-5 bg-light">
                        <div class="container">
                            <div class="cta-box p-5 text-center mx-auto col-lg-10" 
                                data-aos="zoom-in" data-aos-duration="900">
                                
                                <div class="mb-4">
                                    <i class="fas fa-receipt fa-3x text-warning mb-3"></i>
                                    <h2 class="fw-bold text-dark">Sudah Melakukan Transfer?</h2>
                                    <p class="lead text-muted col-lg-9 mx-auto">
                                        Upload bukti donasi kamu agar tim kami bisa memverifikasi dengan cepat, transparan, dan amanah.
                                    </p>
                                </div>

                                <a href="/donasi/konfirmasi" id="btn-konfirmasi" class="btn btn-lg rounded-pill px-5 py-3 fw-bold shadow-sm" 
                                style="background-color: #FFC107; color: #212529; border: none;">
                                    <i class="fas fa-upload me-2"></i> Upload Bukti Donasi
                                </a>
                            </div>
                        </div>
                    </section>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.BankAccounts = @json($daftarRekening);
</script>

<script src="{{ asset('/assets/js/zakat-calculator-showRekening.js') }}"></script>

@endsection