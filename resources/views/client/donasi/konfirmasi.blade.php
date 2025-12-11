@extends('client.layout')

@section('title', 'Konfirmasi Donasi')

@section('content')
    <style>
        .page-title {
            font-weight: 700;
            color: #1a1e21;
        }

        .form-card,
        .history-card {
            border: 1px solid #eef0f2;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.02);
            border-radius: 12px;
            background: #fff;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            background-color: #fff;
            border: 1px solid #dfe1e5;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #212529;
            box-shadow: none;
            background-color: #fff;
        }

        .form-control[readonly] {
            background-color: #e9ecef;
            opacity: 1;
            cursor: not-allowed;
        }

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

        .badge-verified {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .badge-pending {
            background-color: #fff3cd;
            color: #664d03;
        }

        .donation-summary {
            background: linear-gradient(135deg, #0099ff 0%, #0066cc 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .donation-summary .amount {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .donation-summary .type-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .history-card {
            z-index: 1;
            position: relative;
        }
    </style>

    <div class="container py-5">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-4">
            <h2 class="page-title mb-1">Konfirmasi Donasi</h2>
            <p class="text-muted">Upload bukti transfer untuk pencatatan yang akurat</p>
        </div>

        @isset($donationData)
            <div class="donation-summary">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="mb-2">
                            <span class="type-badge">
                                <i
                                    class="fas fa-{{ $donationData['category'] === 'zakat' ? 'calculator' : 'heart-fill' }} me-1"></i>
                                {{ $donationData['type_name'] ?? ucfirst($donationData['type']) }}
                            </span>
                        </div>
                        <div class="amount">Rp {{ number_format($donationData['calculated_amount'], 0, ',', '.') }}</div>
                        <small class="opacity-75">Silakan transfer sesuai nominal di atas</small>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-white text-dark p-3 rounded-3 shadow-sm">
                            <small class="text-muted d-block mb-1">Rekening Tujuan Transfer:</small>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="fw-bold mb-0 text-primary">{{ $donationData['bank_name'] ?? '-' }}</h5>
                                    <div class="d-flex align-items-center mt-1">
                                        <span class="fs-4 fw-bold me-2"
                                            id="rek-number">{{ $donationData['bank_account_number'] ?? '' }}</span>
                                        <button class="btn btn-sm btn-light border text-primary" onclick="copyRekening()"
                                            title="Salin Nomor Rekening">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">a.n
                                        {{ $donationData['bank_account_holder'] ?? 'Masjid' }}</small>
                                </div>
                                @if (isset($donationData['bank_logo']))
                                    <img src="{{ $donationData['bank_logo'] }}" alt="Bank Logo" style="height: 40px;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function copyRekening() {
                    const rek = document.getElementById('rek-number').innerText;
                    navigator.clipboard.writeText(rek).then(() => {
                        Toast.fire({
                            icon: 'success',
                            title: 'Nomor rekening berhasil disalin'
                        });
                    });
                }
            </script>

    @endisset

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card form-card h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Formulir Konfirmasi</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('donasi.store') }}" method="POST" enctype="multipart/form-data"
                        id="konfirmasi-form">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_pengirim" class="form-label">Nama Pengirim</label>
                            <input type="text" name="nama_pengirim" id="nama_pengirim" class="form-control"
                                placeholder="Nama Anda"
                                value="{{ Auth::check() ? Auth::user()->name : old('nama_pengirim') }}" required>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" class="form-check-input"
                                    value="1">
                                <label for="is_anonymous" class="form-check-label">
                                    Sembunyikan nama saya (Donasi sebagai "Hamba Allah")
                                </label>
                            </div>
                        </div>

                        @isset($donationData)
                            <div class="mb-3">
                                <label class="form-label">Jumlah Donasi</label>
                                <input type="text" class="form-control"
                                    value="Rp {{ number_format($donationData['calculated_amount'], 0, ',', '.') }}" readonly>
                                <small class="text-muted">Nominal dihitung dari kalkulator</small>
                            </div>
                        @endisset

                        <div class="mb-3">
                            <label for="transfer_date" class="form-label">Tanggal Transfer</label>
                            <input type="date" name="transfer_date" id="transfer_date" class="form-control"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="source_bank" class="form-label">Dari Bank</label>
                            <input type="text" name="source_bank" id="source_bank" class="form-control"
                                placeholder="Contoh: BCA, BRI, Mandiri, dll" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Transfer</label>
                            <div class="upload-zone" id="upload-zone">
                                <input type="file" name="proof_file" id="proof_file" accept="image/png, image/jpeg"
                                    required onchange="previewFile(this)">
                                <div class="upload-content" id="upload-content">
                                    <i class="fas fa-upload upload-icon" id="upload-icon"></i>
                                    <p class="mb-0 text-muted small" id="upload-text">Klik untuk upload JPG atau PNG
                                    </p>
                                </div>
                                <div class="preview-container" id="preview-container" style="display: none;">
                                    <img src="" alt="Preview" id="preview-image" class="img-fluid rounded"
                                        style="max-height: 200px;">
                                    <p class="mb-0 mt-2 text-success small" id="file-name"></p>
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                                        onclick="clearPreview()">
                                        <i class="fas fa-x-circle me-1"></i>Ganti Gambar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"
                                placeholder="Tambahkan catatan jika diperlukan"></textarea>
                        </div>

                        <button type="submit" class="btn btn-submit w-100" id="btn-submit">
                            <i class="fas fa-send me-2"></i>Kirim Konfirmasi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card history-card" style="position: sticky; top: 100px;">
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
                                        $badgeClass = match (strtolower($item->status)) {
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
                                    <i class="fas fa-calendar-event me-1"></i>
                                    {{ \Carbon\Carbon::parse($item->transfer_date)->format('d M Y') }}
                                </div>
                                <div class="small text-muted">
                                    <i class="fas fa-bank me-1"></i>
                                    {{ optional($item->destinationAccount)->bank_name ?? 'Bank Tujuan' }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted border border-dashed rounded bg-light">
                                <i class="fas fa-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <small>Belum ada riwayat konfirmasi.</small>
                            </div>
                        @endforelse
                    @else
                        <div class="text-center py-5 bg-light rounded-3 border border-dashed">
                            <div class="mb-3">
                                <i class="fas fa-lock fs-1 text-secondary"></i>
                            </div>
                            <h6 class="fw-bold text-dark">Riwayat Terkunci</h6>
                            <p class="small text-muted px-3 mb-3">
                                Login untuk menyimpan dan memantau status konfirmasi donasi Anda.
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
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Hide upload content, show preview
                    document.getElementById('upload-content').style.display = 'none';
                    document.getElementById('preview-container').style.display = 'block';
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('file-name').innerText = file.name;

                    // Change upload zone style
                    document.getElementById('upload-zone').style.borderColor = '#198754';
                    document.getElementById('upload-zone').style.backgroundColor = '#f8fff8';
                };

                reader.readAsDataURL(file);
            }
        }

        function clearPreview() {
            document.getElementById('proof_file').value = '';
            document.getElementById('upload-content').style.display = 'block';
            document.getElementById('preview-container').style.display = 'none';
            document.getElementById('preview-image').src = '';
            document.getElementById('file-name').innerText = '';
            document.getElementById('upload-zone').style.borderColor = '#dee2e6';
            document.getElementById('upload-zone').style.backgroundColor = '#fff';
        }

        // Prevent double submit
        document.getElementById('konfirmasi-form').addEventListener('submit', function(e) {
            const btn = document.getElementById('btn-submit');
            if (btn.disabled) {
                e.preventDefault();
                return;
            }
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
        });
    </script>
@endsection
