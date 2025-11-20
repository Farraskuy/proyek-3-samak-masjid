@extends('client.layout')

@section('title', 'Konfirmasi Donasi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-white text-center py-4 border-0">
                    <h3 class="fw-bold text-primary mb-0">Form Konfirmasi Donasi</h3>
                    <p class="text-muted small mt-2">Silakan upload bukti transfer zakat/infak Anda</p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    
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
                            <label class="form-label fw-bold">Nama Pengirim</label>
                            <input type="text" name="nama_pengirim" class="form-control form-control-lg" 
                                placeholder="Nama Lengkap Anda" 
                                value="{{ Auth::check() ? Auth::user()->name : old('nama_pengirim') }}" 
                                required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nominal Transfer (Rp)</label>
                                <input type="number" name="amount" class="form-control form-control-lg" placeholder="Contoh: 100000" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Transfer</label>
                                <input type="date" name="transfer_date" class="form-control form-control-lg" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bank Tujuan (Rekening Kami)</label>
                            <select name="destination_account_id" class="form-select form-select-lg" required>
                                <option value="" selected disabled>-- Pilih Bank Tujuan --</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->account_id }}">
                                        {{ $bank->bank_name }} - {{ $bank->account_number }} (a.n {{ $bank->account_holder_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bank Pengirim (Bank Kamu)</label>
                            <input type="text" name="source_bank" class="form-control" placeholder="Contoh: BCA, Mandiri, Gopay" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan / Doa (Opsional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Tuliskan niat atau doa..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Upload Bukti Transfer</label>
                            <input type="file" name="proof_file" class="form-control" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG. Maksimal 2MB.</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-5">
                            <i class="bi bi-send-fill me-2"></i> Kirim Konfirmasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection