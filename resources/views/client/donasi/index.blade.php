@extends('client.layout')

@section('title', 'Kalkulator Zakat & Infaq')

@section('content')
    <style>
        .zis-container {
            background-color: #f9fbfd;
            min-height: 100vh;
            padding: 50px 0;
            font-size: 1.1rem;
        }

        .card-calculator {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 30px;
            border: none;
        }

        .card-calculator h3 {
            font-size: 1.5rem;
        }

        .card-calculator p {
            font-size: 1.05rem;
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
            color: #0099ff;
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
            padding: 12px 15px;
            font-size: 1.05rem;
        }

        .form-control:focus {
            background: white;
            box-shadow: none;
            border-color: #ced4da;
        }

        .form-select {
            padding: 12px 15px;
            font-size: 1.05rem;
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

        .hidden-checkbox:checked+.custom-checkbox-label {
            border-color: #198754;
            background-color: #e8f5e9;
            color: #198754;
            box-shadow: 0 4px 10px rgba(25, 135, 84, 0.15);
        }

        .hidden-checkbox:checked+.custom-checkbox-label::after {
            content: "✅";
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.2rem;
        }

        .zakat-description {
            background: #f8f9fa;
            border-left: 4px solid #0099ff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0 8px 8px 0;
        }

        .zakat-description h6 {
            color: #0099ff;
            margin-bottom: 5px;
        }

        .zakat-description p {
            margin-bottom: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .form-control.money-input {
            border-left: none !important;
        }

        .input-group .input-group-text {
            border-right: none;
        }

        .btn-lanjut {
            background: linear-gradient(135deg, #0099ff 0%, #0066cc 100%);
            border: none;
            padding: 15px 40px;
            font-weight: 600;
            border-radius: 10px;
        }

        .btn-lanjut:hover {
            background: linear-gradient(135deg, #0088ee 0%, #0055bb 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 153, 255, 0.3);
        }

        .btn-lanjut:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
    </style>

    <div class="zis-container">
        <div class="container">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-md-3 mb-4">
                    <div class="list-group shadow-sm rounded-3 overflow-hidden">
                        <a href="#" class="list-group-item list-group-item-action active p-3" id="btn-menu-zakat"
                            onclick="switchMode('zakat')">
                            <h5 class="mb-0"><i class="fas fa-calculator me-2"></i> Zakat</h5>
                            <small>Hitung kewajiban zakatmu</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action p-3" id="btn-menu-infaq"
                            onclick="switchMode('infaq')">
                            <h5 class="mb-0"><i class="fas fa-heart-fill me-2"></i> Infaq</h5>
                            <small>Berbagi keberkahan</small>
                        </a>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card-calculator">
                        <div class="text-center mb-4">
                            <h3 id="calculator-title">Ayo hitung zakat kamu!</h3>
                            <p class="text-muted">Masukkan data hartamu dan kalkulator kami akan menghitungnya.</p>
                        </div>

                        <!-- ZAKAT SECTION -->
                        <form id="zakat-form" action="{{ route('donasi.hitung') }}" method="POST">
                            @csrf
                            <input type="hidden" name="donation_category" value="zakat">

                            <div id="section-zakat">
                                <div class="mb-4">
                                    <label class="form-label">Pilih Jenis Zakat</label>
                                    <select class="form-select mb-3" id="zakat-type" name="donation_type"
                                        onchange="renderZakatForm()">
                                        @foreach ($zakatTypes as $key => $type)
                                            <option value="{{ $key }}">{{ $type['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="zakat-description" class="zakat-description">
                                    <h6 id="zakat-type-name">Zakat Fitrah</h6>
                                    <p id="zakat-type-desc">Deskripsi zakat akan muncul di sini</p>
                                </div>

                                <div id="zakat-form-container"></div>

                                <hr class="my-4">

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">JUMLAH ZAKAT YANG HARUS DIKELUARKAN</label>
                                    <div class="result-text" id="zakat-result">Rp 0</div>
                                    <small class="text-danger fst-italic" id="nishab-warning" style="display:none;">
                                        * Harta Anda belum mencapai nisab (batas minimal wajib zakat).
                                    </small>
                                </div>

                                <div id="rekening-container-zakat" style="display: none;" class="mt-4">
                                    <div class="alert alert-info border-0 bg-light text-dark">
                                        <i class="fas fa-info-circle me-2"></i> Pilih rekening tujuan zakat:
                                    </div>
                                    <div id="rekening-list-zakat"></div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg btn-lanjut" id="btn-lanjut-zakat"
                                        disabled>
                                        <i class="fas fa-arrow-right-circle me-2"></i> Lanjut ke Konfirmasi
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- INFAQ SECTION -->
                        <form id="infaq-form" action="{{ route('donasi.hitung') }}" method="POST">
                            @csrf
                            <input type="hidden" name="donation_category" value="infaq">

                            <div id="section-infaq" style="display: none;">
                                <div class="mb-4">
                                    <label class="form-label">Pilih Program Infaq</label>
                                    <select class="form-select mb-3" id="infaq-type" name="donation_type"
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
                                    <label class="form-label">Nominal Infaq</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control money-input" id="infaq-amount"
                                            name="infaq_amount" placeholder="0" onkeyup="calculateInfaq()">
                                    </div>
                                    <small class="text-muted">Minimal Rp 10.000</small>
                                </div>

                                <hr class="my-4">

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">NOMINAL INFAQ KAMU</label>
                                    <div class="result-text" id="infaq-result">Rp 0</div>
                                </div>

                                {{-- Bank auto-assigned from infaq program --}}
                                <div id="infaq-bank-info" style="display: none;" class="mt-4">
                                    <div class="alert alert-success border-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <span>Rekening tujuan akan otomatis sesuai program yang dipilih.</span>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg btn-lanjut"
                                        id="btn-lanjut-infaq" disabled>
                                        <i class="fas fa-arrow-right-circle me-2"></i> Lanjut ke Konfirmasi
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

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

        let currentMode = 'zakat';
        let selectedZakatBank = null;
        let selectedInfaqBank = null;

        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(number);
        };

        const parseInput = (val) => {
            if (!val) return 0;
            let cleanString = val.toString().replace(/[^0-9]/g, '');
            return parseFloat(cleanString) || 0;
        };

        // Format money input on typing
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

        // Switch between zakat and infaq
        function switchMode(mode) {
            currentMode = mode;
            const zakatSec = document.getElementById('section-zakat');
            const infaqSec = document.getElementById('section-infaq');
            const title = document.getElementById('calculator-title');
            const btnZakat = document.getElementById('btn-menu-zakat');
            const btnInfaq = document.getElementById('btn-menu-infaq');

            if (mode === 'zakat') {
                zakatSec.style.display = 'block';
                infaqSec.style.display = 'none';
                title.innerText = "Ayo hitung zakat kamu!";
                btnZakat.classList.add('active');
                btnInfaq.classList.remove('active');
                renderZakatForm();
            } else {
                zakatSec.style.display = 'none';
                infaqSec.style.display = 'block';
                title.innerText = "Ayo mulai infaq!";
                btnZakat.classList.remove('active');
                btnInfaq.classList.add('active');
                document.getElementById('infaq-amount').value = '';
                document.getElementById('infaq-result').innerText = 'Rp 0';
            }
        }

        // Render zakat form based on type
        function renderZakatForm() {
            const type = document.getElementById('zakat-type').value;
            const container = document.getElementById('zakat-form-container');
            const typeConfig = ZAKAT_TYPES[type];

            // Update description with gold price for maal
            document.getElementById('zakat-type-name').innerText = typeConfig.name;
            let desc = typeConfig.description;
            if (type === 'maal') {
                desc +=
                    ` Harga emas saat ini: ${formatRupiah(HARGA_EMAS)}/gram. Nisab = 85 gram x ${formatRupiah(HARGA_EMAS)} = ${formatRupiah(NISHAB_MAAL)}.`;
            }
            document.getElementById('zakat-type-desc').innerText = desc;

            let html = '';
            for (const [key, input] of Object.entries(typeConfig.inputs)) {
                if (input.type === 'money') {
                    html += `
                    <label class="form-label">${input.label}${input.required ? ' <span class="text-danger">*</span>' : ''}</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control money-input" 
                               id="input-${key}" name="${key}" 
                               placeholder="${input.placeholder || '0'}" 
                               oninput="calculateZakat()">
                    </div>`;
                } else if (input.type === 'number') {
                    html += `
                    <label class="form-label">${input.label}${input.required ? ' <span class="text-danger">*</span>' : ''}</label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" 
                               id="input-${key}" name="${key}" 
                               placeholder="${input.placeholder || '0'}" 
                               step="${input.step || '1'}"
                               min="${input.min || '0'}"
                               oninput="calculateZakat()">
                        ${key.includes('emas') ? '<span class="input-group-text">Gram</span>' : ''}
                    </div>`;
                } else if (input.type === 'checkbox') {
                    html += `
                    <div class="p-3 border rounded bg-white d-flex align-items-start mb-3">
                        <div class="me-3">
                            <input type="checkbox" id="input-${key}" name="${key}" value="1"
                                   style="width: 24px; height: 24px; cursor: pointer; margin-top: 2px;"
                                   onchange="calculateZakat()">
                        </div>
                        <div>
                            <label for="input-${key}" class="fw-bold mb-1 text-dark">${input.label}</label>
                            ${input.note ? `<div class="text-muted small">${input.note}</div>` : ''}
                        </div>
                    </div>`;
                }
            }

            if (type === 'emas') {
                html += `<small class="text-muted">Asumsi harga emas: ${formatRupiah(HARGA_EMAS)} / gram</small>`;
            }
            if (type === 'fitrah') {
                html += `<small class="text-muted">Asumsi harga beras: ${formatRupiah(HARGA_BERAS)} / kg</small>`;
            }

            container.innerHTML = html;
            updateZakatResult(0, false);
        }

        // Calculate zakat based on type
        function calculateZakat() {
            const type = document.getElementById('zakat-type').value;
            let amount = 0;
            let belowNisab = false;

            switch (type) {
                case 'fitrah':
                    const jiwa = parseInput(document.getElementById('input-jumlah_jiwa')?.value) || 1;
                    amount = jiwa * 2.5 * HARGA_BERAS;
                    break;

                case 'maal':
                    const harta = parseInput(document.getElementById('input-total_harta')?.value);
                    const hutang = parseInput(document.getElementById('input-hutang')?.value);
                    const hartaBersih = harta - hutang;
                    belowNisab = hartaBersih < NISHAB_MAAL;
                    amount = belowNisab ? 0 : hartaBersih * 0.025;
                    break;

                case 'profesi':
                    const gaji = parseInput(document.getElementById('input-gaji_bulanan')?.value);
                    const bonus = parseInput(document.getElementById('input-bonus')?.value);
                    const pengeluaran = parseInput(document.getElementById('input-pengeluaran_pokok')?.value);
                    const totalBulanan = gaji + bonus - pengeluaran;
                    const totalSetahun = totalBulanan * 12;
                    belowNisab = totalSetahun < NISHAB_MAAL;
                    amount = belowNisab ? 0 : totalBulanan * 0.025;
                    break;

                case 'emas':
                    const berat = parseFloat(document.getElementById('input-berat_emas')?.value) || 0;
                    const nilaiEmas = berat * HARGA_EMAS;
                    belowNisab = berat < 85;
                    amount = belowNisab ? 0 : nilaiEmas * 0.025;
                    break;

                case 'tabungan':
                    const saldo = parseInput(document.getElementById('input-saldo_tabungan')?.value);
                    const bunga = parseInput(document.getElementById('input-bunga')?.value);
                    const totalTab = saldo + bunga;
                    belowNisab = totalTab < NISHAB_MAAL;
                    amount = belowNisab ? 0 : totalTab * 0.025;
                    break;

                case 'pertanian':
                    const hasil = parseInput(document.getElementById('input-hasil_panen')?.value);
                    const isIrigasi = document.getElementById('input-is_irigasi')?.checked || false;
                    belowNisab = hasil < NISHAB_PERTANIAN;
                    const rate = isIrigasi ? 0.05 : 0.10;
                    amount = belowNisab ? 0 : hasil * rate;
                    break;

                case 'rikaz':
                    const temuan = parseInput(document.getElementById('input-nilai_temuan')?.value);
                    amount = temuan * 0.20;
                    break;
            }

            updateZakatResult(amount, belowNisab);
        }

        function updateZakatResult(amount, belowNisab) {
            const resDiv = document.getElementById('zakat-result');
            const warnDiv = document.getElementById('nishab-warning');
            const rekDiv = document.getElementById('rekening-container-zakat');
            const btnLanjut = document.getElementById('btn-lanjut-zakat');

            if (amount < 0) amount = 0;
            resDiv.innerText = formatRupiah(amount);

            if (belowNisab) {
                warnDiv.style.display = 'block';
                rekDiv.style.display = 'none';
                btnLanjut.disabled = true;
            } else {
                warnDiv.style.display = 'none';
                if (amount > 0) {
                    showZakatBanks();
                    btnLanjut.disabled = !selectedZakatBank;
                } else {
                    rekDiv.style.display = 'none';
                    btnLanjut.disabled = true;
                }
            }
        }

        function showZakatBanks() {
            const container = document.getElementById('rekening-list-zakat');
            const wrapper = document.getElementById('rekening-container-zakat');

            let html = '';
            ZAKAT_BANKS.forEach((acc, index) => {
                html += `
                <label class="d-block mb-3" style="cursor:pointer">
                    <input type="radio" name="bank_id" value="${acc.account_id}" 
                           class="hidden-checkbox" onchange="selectZakatBank(${acc.account_id})">
                    <div class="bank-card custom-checkbox-label">
                        ${acc.logo_url ? `<img src="${acc.logo_url}" alt="${acc.bank_name}" class="bank-logo">` : ''}
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold">${acc.bank_name}</h6>
                            <div class="d-flex align-items-center">
                                <span class="me-2 fs-5">${acc.account_number || '-'}</span>
                            </div>
                            <small class="text-muted">a.n ${acc.account_holder_name}</small>
                        </div>
                    </div>
                </label>`;
            });
            container.innerHTML = html;
            wrapper.style.display = 'block';
        }

        function selectZakatBank(bankId) {
            selectedZakatBank = bankId;
            document.getElementById('btn-lanjut-zakat').disabled = false;
        }

        // Infaq functions
        function calculateInfaq() {
            const val = parseInput(document.getElementById('infaq-amount').value);
            document.getElementById('infaq-result').innerText = formatRupiah(val);

            if (val >= 10000) {
                // Auto-assign bank from selected program
                updateInfaqBank();
                document.getElementById('infaq-bank-info').style.display = 'block';
                document.getElementById('btn-lanjut-infaq').disabled = false;
            } else {
                document.getElementById('infaq-bank-info').style.display = 'none';
                document.getElementById('btn-lanjut-infaq').disabled = true;
            }
        }

        function updateInfaqBank() {
            const select = document.getElementById('infaq-type');
            const option = select.options[select.selectedIndex];
            const bankId = option.dataset.bankId;
            if (bankId) {
                selectedInfaqBank = parseInt(bankId);
            }
        }

        // Prevent double submission and clean money inputs
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const btn = form.querySelector('button[type="submit"]');
                if (btn.disabled) {
                    e.preventDefault();
                    return;
                }

                // Clean money inputs - convert formatted strings to numeric
                form.querySelectorAll('.money-input').forEach(input => {
                    input.value = parseInput(input.value);
                });

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
            });
        });

        // Initialize
        renderZakatForm();
    </script>
@endsection
