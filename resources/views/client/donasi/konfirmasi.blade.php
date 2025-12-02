@extends('client.layout')

@section('title', 'Konfirmasi Donasi')

@section('content')
<style>
    /* Styling khusus agar mirip desain gambar */
    .page-title {
        font-weight: 700;
        color: #1a1e21;
    }
    
    .form-card, .history-card {
        border: 1px solid #eef0f2;
        box-shadow: 0 4px 24px rgba(0,0,0,0.02);
        border-radius: 12px;
        background: #fff;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        background-color: #fff;
        border: 1px solid #dfe1e5;
        border-radius: 6px;
        padding: 10px 15px;
        font-size: 0.95rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #212529;
        box-shadow: none;
        background-color: #fff;
    }

    /* Style untuk input yang terkunci (Readonly) */
    .form-control[readonly], .form-select.locked {
        background-color: #e9ecef; /* Warna abu-abu */
        opacity: 1;
        cursor: not-allowed;
    }

    /* Area Upload Dashed */
    .upload-zone {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background-color: #fff;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .upload-zone:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
    }

    .upload-zone input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .upload-icon {
        font-size: 2rem;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .btn-submit {
        background-color: #1a1e21;
        color: white;
        border-radius: 6px;
        font-weight: 600;
        padding: 12px;
        border: none;
    }

    .btn-submit:hover {
        background-color: #000;
        color: white;
    }

    /* Styling Riwayat di Kanan */
    .history-item {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    .history-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .badge-status {
        font-size: 0.75rem;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: 500;
    }
    .badge-verified { background-color: #d1e7dd; color: #0f5132; }
    .badge-pending { background-color: #fff3cd; color: #664d03; }
</style>

<div class="container py-5">
    
    <div class="mb-4">
        <h2 class="page-title mb-1">Konfirmasi Donasi</h2>
        <p class="text-muted">Upload bukti transfer untuk pencatatan yang akurat</p>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card form-card h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Formulir Konfirmasi</h5>

                    {{-- Alert Error Validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('donasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Pengirim</label>
                            <input type="text" name="nama_pengirim" class="form-control" 
                                placeholder="Ahmad Fulan" 
                                value="{{ Auth::check() ? Auth::user()->name : old('nama_pengirim') }}" 
                                required>
                        </div>

                        {{-- Input Jumlah Donasi (Dikunci jika ada request amount) --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Jumlah Donasi (Rp)
                                @if(request('amount')) <i class="bi bi-lock-fill text-muted ms-1" title="Terkunci dari kalkulator"></i> @endif
                            </label>
                            <input type="number" 
                                   name="amount" 
                                   class="form-control {{ request('amount') ? 'bg-light' : '' }}" 
                                   placeholder="50000" 
                                   inputmode="numeric" 
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                   value="{{ request('amount') }}" 
                                   {{ request('amount') ? 'readonly' : '' }}
                                   required>
                            @if(request('amount'))
                                <small class="text-muted" style="font-size: 0.75rem;">*Nominal otomatis dari kalkulator</small>
                            @endif
                        </div>

                        {{-- Dropdown Jenis Donasi (Dikunci jika ada request type) --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Jenis Donasi
                                @if(request('type')) <i class="bi bi-lock-fill text-muted ms-1" title="Terkunci dari kalkulator"></i> @endif
                            </label>
                            <select name="donation_type" 
                                    class="form-select {{ request('type') ? 'locked' : '' }}" 
                                    {{ request('type') ? 'tabindex=-1' : '' }} 
                                    style="{{ request('type') ? 'pointer-events: none;' : '' }}"
                                    required>
                                <option value="" disabled {{ !request('type') ? 'selected' : '' }}>Pilih Jenis Donasi</option> 
                                <option value="maal" {{ request('type') == 'maal' ? 'selected' : '' }}>Zakat Maal (Harta)</option>
                                <option value="profesi" {{ request('type') == 'profesi' ? 'selected' : '' }}>Zakat Profesi (Penghasilan)</option>
                                <option value="emas" {{ request('type') == 'emas' ? 'selected' : '' }}>Zakat Emas & Perak</option>
                                <option value="tabungan" {{ request('type') == 'tabungan' ? 'selected' : '' }}>Zakat Tabungan</option>
                                <option value="pertanian" {{ request('type') == 'pertanian' ? 'selected' : '' }}>Zakat Pertanian</option>
                                <option value="umum" {{ request('type') == 'umum' ? 'selected' : '' }}>Infak Umum</option>
                                <option value="bencana" {{ request('type') == 'bencana' ? 'selected' : '' }}>Infak Bencana</option>
                                <option value="pendidikan" {{ request('type') == 'pendidikan' ? 'selected' : '' }}>Infak Pendidikan</option>
                                <option value="kesehatan" {{ request('type') == 'kesehatan' ? 'selected' : '' }}>Infak Kesehatan</option>
                            </select>
                            {{-- Trik agar data select tetap terkirim walaupun dikunci secara visual --}}
                            @if(request('type'))
                                <input type="hidden" name="donation_type_backup" value="{{ request('type') }}">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Transfer</label>
                            <input type="date" name="transfer_date" class="form-control" required>
                        </div>

                        {{-- Dropdown Bank Tujuan (Dikunci jika ada request bank_id) --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Transfer ke Bank Tujuan
                                @if(request('bank_id')) <i class="bi bi-lock-fill text-muted ms-1" title="Terkunci dari kalkulator"></i> @endif
                            </label>
                            <select name="destination_account_id" 
                                    class="form-select {{ request('bank_id') ? 'locked' : '' }}" 
                                    {{ request('bank_id') ? 'tabindex=-1' : '' }} 
                                    style="{{ request('bank_id') ? 'pointer-events: none;' : '' }}"
                                    required>
                                <option value="" selected disabled>Pilih bank tujuan</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->account_id }}"
                                        {{ request('bank_id') == $bank->account_id ? 'selected' : ''}}>
                                        {{ $bank->bank_name }} - {{ $bank->account_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dari Bank</label>
                            <input type="text" name="source_bank" class="form-control" placeholder="Contoh: BCA, BRI, dll" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Transfer</label>
                            <div class="upload-zone">
                                <input type="file" name="proof_file" accept="image/png, image/jpeg, application/pdf" required onchange="previewFile(this)">
                                <div class="upload-content">
                                    <i class="bi bi-upload upload-icon"></i>
                                    <p class="mb-0 text-muted small" id="upload-text">Klik untuk upload JPG, PNG, atau PDF</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                        </div>

                        <button type="submit" class="btn btn-submit w-100">
                            Kirim Konfirmasi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Bagian Riwayat (Tidak berubah) --}}
            <div class="card history-card sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Riwayat Konfirmasi</h5>
                    @auth
                        @forelse($riwayat as $item)
                            <div class="history-item">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div class="fw-bold text-dark">
                                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </div>
                                    @php
                                        $badgeClass = match($item->status) {
                                            'verified', 'approved' => 'badge-verified', 
                                            'rejected' => 'bg-danger text-white',
                                            default => 'badge-pending',
                                        };
                                    @endphp
                                    <span class="badge-status {{ $badgeClass }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                                <div class="small text-muted mb-1">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ \Carbon\Carbon::parse($item->transfer_date)->format('d M Y') }}
                                </div>
                                <div class="small text-muted">
                                    <i class="bi bi-bank me-1"></i>
                                    {{ optional($item->destinationAccount)->bank_name ?? 'Bank Tujuan' }}
                                    @if(optional($item->destinationAccount)->account_number)
                                        - {{ $item->destinationAccount->account_number }}
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted border border-dashed rounded bg-light">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <small>Belum ada riwayat konfirmasi.</small>
                            </div>
                        @endforelse
                    @else
                        <div class="text-center py-5 bg-light rounded-3 border border-dashed">
                            <div class="mb-3">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-secondary opacity-25"></i>
                                    <i class="fas fa-lock fa-stack-1x text-secondary"></i>
                                </span>
                            </div>
                            <h6 class="fw-bold text-dark">Riwayat Terkunci</h6>
                            <p class="small text-muted px-3 mb-3">
                                Login untuk menyimpan dan memantau status konfirmasi donasi Anda secara otomatis.
                            </p>
                            <a href="{{ route('login') }}" class="btn btn-sm btn-dark rounded-pill px-4 fw-bold">
                                Login Sekarang
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewFile(input) {
        if (input.files && input.files[0]) {
            document.getElementById('upload-text').innerText = input.files[0].name;
            document.querySelector('.upload-icon').classList.remove('bi-upload');
            document.querySelector('.upload-icon').classList.add('bi-file-earmark-check');
            document.querySelector('.upload-icon').style.color = '#198754';
        }
    }
</script>
@endsection