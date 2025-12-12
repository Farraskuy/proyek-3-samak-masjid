@extends('admin.layout')

@section('title', 'Input Donasi Offline')

@section('content')
    <div class="container p-3">
        {{-- Header --}}
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('admin.donasi.index') }}" class="btn btn-light btn-sm rounded-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="fw-semibold mb-0">Input Donasi Offline</h4>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4">

                    <form action="{{ route('admin.donasi.store_offline') }}" method="POST" id="offline-form">
                        @csrf
                        <input type="hidden" name="donation_category" id="donation_category" value="zakat">

                        {{-- Donor Info --}}
                        <h5 class="fw-bold mb-3">Informasi Donatur</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Donatur</label>
                                <input type="text" name="donor_name" class="form-control" required
                                    placeholder="Nama Lengkap / Hamba Allah">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">No. HP (Opsional)</label>
                                <input type="text" name="phone" class="form-control" placeholder="08...">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Catatan (Opsional)</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Doa atau catatan khusus..."></textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Tabs --}}
                        <ul class="nav nav-pills nav-fill mb-4 gap-2" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-bold" id="pills-zakat-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-zakat" type="button" role="tab"
                                    onclick="setCategory('zakat')">
                                    <i class="fas fa-calculator me-2"></i> Zakat
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-bold" id="pills-infaq-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-infaq" type="button" role="tab"
                                    onclick="setCategory('infaq')">
                                    <i class="fas fa-heart me-2"></i> Infaq
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">

                            {{-- ZAKAT SECTION --}}
                            <div class="tab-pane fade show active" id="pills-zakat" role="tabpanel">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Jenis Zakat</label>
                                    <select class="form-select" id="zakat-type" name="donation_type"
                                        onchange="renderZakatForm()">
                                        @foreach ($zakatTypes as $key => $type)
                                            <option value="{{ $key }}">{{ $type['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="alert alert-light border-start border-4 border-primary">
                                    <h6 class="fw-bold text-primary" id="zakat-type-name">Zakat Fitrah</h6>
                                    <p class="small mb-0 text-muted" id="zakat-type-desc">Deskripsi...</p>
                                </div>

                                <div id="zakat-form-container"></div>

                                <div class="mt-4 p-3 bg-light rounded-3 text-center">
                                    <label class="small text-muted fw-bold">ESTIMASI ZAKAT</label>
                                    <h2 class="fw-bold text-primary mb-0" id="zakat-result">Rp 0</h2>
                                    <small class="text-danger fst-italic" id="nishab-warning" style="display:none;">
                                        * Belum mencapai nisab.
                                    </small>
                                </div>
                            </div>

                            {{-- INFAQ SECTION --}}
                            <div class="tab-pane fade" id="pills-infaq" role="tabpanel">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Program Infaq</label>
                                    <select class="form-select" id="infaq-type" name="donation_type_infaq"
                                        onchange="updateInfaqBank()">
                                        <option value="umum">Infaq Umum</option>
                                        @foreach ($infaqPrograms as $program)
                                            <option value="{{ $program->id }}"
                                                data-bank-id="{{ $program->bank_account_id }}">
                                                {{ $program->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Nominal Infaq</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control money-input" id="infaq-amount"
                                            name="infaq_amount" placeholder="0" onkeyup="calculateInfaq()">
                                    </div>
                                </div>

                                <div class="mt-4 p-3 bg-light rounded-3 text-center">
                                    <label class="small text-muted fw-bold">NOMINAL INFAQ</label>
                                    <h2 class="fw-bold text-success mb-0" id="infaq-result">Rp 0</h2>
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        {{-- Bank Selection --}}
                        <h5 class="fw-bold mb-3">Rekening Tujuan / Kas</h5>
                        <div id="bank-selection-container">
                            {{-- Banks will be rendered here via JS --}}
                        </div>
                        <input type="hidden" name="bank_id" id="selected_bank_id" required>

                        <button type="submit" class="btn btn-primary w-100 btn-lg mt-4" id="btn-submit" disabled>
                            <i class="fas fa-save me-2"></i> Simpan Donasi
                        </button>

                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3">Informasi Nisab</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Harga Emas (per gram)
                            <span
                                class="fw-bold">{{ 'Rp ' . number_format($zakatConfig['harga_emas'], 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Harga Beras (per kg)
                            <span
                                class="fw-bold">{{ 'Rp ' . number_format($zakatConfig['harga_beras'], 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Nisab Maal (85g Emas)
                            <span
                                class="fw-bold">{{ 'Rp ' . number_format($zakatConfig['nisab_maal'], 0, ',', '.') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Configuration from server
        const ZAKAT_CONFIG = @json($zakatConfig);
        const ZAKAT_TYPES = @json($zakatTypes);
        const ZAKAT_BANKS = @json($zakatBanks);
        const INFAQ_BANKS = @json($infaqBanks);

        const HARGA_EMAS = ZAKAT_CONFIG.harga_emas;
        const HARGA_BERAS = ZAKAT_CONFIG.harga_beras;
        const NISHAB_MAAL = ZAKAT_CONFIG.nisab_maal;
        const NISHAB_PERTANIAN = 520 * HARGA_BERAS;

        let currentCategory = 'zakat';

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(number);
        }

        function parseInput(val) {
            if (!val) return 0;
            let cleanString = val.toString().replace(/[^0-9]/g, '');
            return parseFloat(cleanString) || 0;
        }

        function setCategory(cat) {
            currentCategory = cat;
            document.getElementById('donation_category').value = cat;

            // Reset inputs
            if (cat === 'zakat') {
                document.getElementById('infaq-type').name = 'donation_type_infaq'; // disable infaq type
                document.getElementById('zakat-type').name = 'donation_type'; // enable zakat type
                renderZakatForm();
            } else {
                document.getElementById('zakat-type').name = 'donation_type_zakat'; // disable zakat type
                document.getElementById('infaq-type').name = 'donation_type'; // enable infaq type
                calculateInfaq();
            }
            renderBanks();
        }

        // Format money input
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('money-input')) {
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
            }
        });

        function renderZakatForm() {
            const type = document.getElementById('zakat-type').value;
            const container = document.getElementById('zakat-form-container');
            const typeConfig = ZAKAT_TYPES[type];

            document.getElementById('zakat-type-name').innerText = typeConfig.name;
            document.getElementById('zakat-type-desc').innerText = typeConfig.description;

            let html = '';
            for (const [key, input] of Object.entries(typeConfig.inputs)) {
                if (input.type === 'money') {
                    html += `
                    <label class="form-label mt-3">${input.label}</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control money-input" 
                               id="input-${key}" name="${key}" 
                               placeholder="0" oninput="calculateZakat()">
                    </div>`;
                } else if (input.type === 'number') {
                    html += `
                    <label class="form-label mt-3">${input.label}</label>
                    <div class="input-group">
                        <input type="number" class="form-control" 
                               id="input-${key}" name="${key}" 
                               placeholder="0" step="${input.step || '1'}"
                               oninput="calculateZakat()">
                        ${key.includes('emas') ? '<span class="input-group-text">Gram</span>' : ''}
                    </div>`;
                }
            }
            container.innerHTML = html;
            calculateZakat();
        }

        function calculateZakat() {
            const type = document.getElementById('zakat-type').value;
            let amount = 0;
            let belowNisab = false;

            // Simple calculation logic mirroring server side
            // (Simplified for brevity, ensure matches ZakatService)
            switch (type) {
                case 'fitrah':
                    const jiwa = parseInput(document.getElementById('input-jumlah_jiwa')?.value) || 1;
                    amount = jiwa * 2.5 * HARGA_BERAS;
                    break;
                case 'maal':
                    const harta = parseInput(document.getElementById('input-total_harta')?.value);
                    const hutang = parseInput(document.getElementById('input-hutang')?.value);
                    amount = (harta - hutang) * 0.025;
                    belowNisab = (harta - hutang) < NISHAB_MAAL;
                    break;
                case 'profesi':
                    const gaji = parseInput(document.getElementById('input-gaji_bulanan')?.value);
                    const bonus = parseInput(document.getElementById('input-bonus')?.value);
                    const pengeluaran = parseInput(document.getElementById('input-pengeluaran_pokok')?.value);
                    const total = (gaji + bonus - pengeluaran) * 12;
                    amount = (gaji + bonus - pengeluaran) * 0.025;
                    belowNisab = total < NISHAB_MAAL;
                    break;
                    // Add other cases as needed
                case 'emas':
                    const berat = parseFloat(document.getElementById('input-berat_emas')?.value) || 0;
                    amount = berat * HARGA_EMAS * 0.025;
                    belowNisab = berat < 85;
                    break;
                case 'tabungan':
                    const saldo = parseInput(document.getElementById('input-saldo_tabungan')?.value);
                    const bunga = parseInput(document.getElementById('input-bunga')?.value);
                    amount = (saldo + bunga) * 0.025;
                    belowNisab = (saldo + bunga) < NISHAB_MAAL;
                    break;
            }

            if (belowNisab) amount = 0;
            if (amount < 0) amount = 0;

            document.getElementById('zakat-result').innerText = formatRupiah(amount);
            document.getElementById('nishab-warning').style.display = belowNisab ? 'block' : 'none';

            checkSubmitValidity(amount);
        }

        function calculateInfaq() {
            const val = parseInput(document.getElementById('infaq-amount').value);
            document.getElementById('infaq-result').innerText = formatRupiah(val);
            checkSubmitValidity(val);
        }

        function renderBanks() {
            const container = document.getElementById('bank-selection-container');
            const banks = currentCategory === 'zakat' ? ZAKAT_BANKS : INFAQ_BANKS;

            let html = '<div class="row g-3">';
            banks.forEach(bank => {
                html += `
                <div class="col-md-6">
                    <label class="card h-100 border-0 shadow-sm p-3 cursor-pointer">
                        <div class="d-flex align-items-center gap-3">
                            <input type="radio" name="bank_selection" value="${bank.account_id}" 
                                   class="form-check-input" onchange="selectBank(${bank.account_id})">
                            <div>
                                <div class="fw-bold">${bank.bank_name}</div>
                                <div class="small text-muted">${bank.account_number}</div>
                            </div>
                        </div>
                    </label>
                </div>`;
            });
            html += '</div>';
            container.innerHTML = html;
        }

        function selectBank(id) {
            document.getElementById('selected_bank_id').value = id;
            // Re-check validity
            if (currentCategory === 'zakat') calculateZakat();
            else calculateInfaq();
        }

        function updateInfaqBank() {
            const select = document.getElementById('infaq-type');
            const option = select.options[select.selectedIndex];
            const bankId = option.dataset.bankId;
            if (bankId) {
                // Auto select bank if program has specific bank
                // But we need to find the radio button
                const radio = document.querySelector(`input[name="bank_selection"][value="${bankId}"]`);
                if (radio) {
                    radio.checked = true;
                    selectBank(bankId);
                }
            }
        }

        function checkSubmitValidity(amount) {
            const bankId = document.getElementById('selected_bank_id').value;
            const btn = document.getElementById('btn-submit');

            if (amount > 0 && bankId) {
                btn.disabled = false;
            } else {
                btn.disabled = true;
            }
        }

        // Init
        renderZakatForm();
        renderBanks();
    </script>
@endpush
