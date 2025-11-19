//=============Konstanta Zakat================

    const HARGA_EMAS = 1300000;
    const NISHAB_EMAS_GRAM = 85;
    const NISHAB_MAAL = NISHAB_EMAS_GRAM * HARGA_EMAS;
    // const NISHAB_PROFESI = 6859394; // Contoh nishab bulanan (setara 524kg beras / emas) sesuaikan kebijakan lembaga

    
//===========Fungsi format rupiah==============

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', { style : 'currency', currency:'IDR', maximumFractionDigits: 0}).format(number);
    }

    const parseInput = (val) => {
        if(!val) return 0;
        return parseFloat(val.replace(/\./g, '').replace(/,/g, '')) || 0;
    }

    //Fungsi tambah titik otomatis
    document.addEventListener('input', function (e) {
        if(e.target.classList.contains('money-input')) {
            let value = e.target.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            e.target.value = rupiah;
        } 
    });

    //Logic Navigasi dan Tampilan
    function switchMode(mode) {
        const zakatSec = document.getElementById('section-zakat');
        const infaqSec = document.getElementById('section-infaq');
        const title = document.getElementById('calculator-title');
        const btnZakat = document.getElementById('btn-menu-zakat');
        const btnInfaq = document.getElementById('btn-menu-infaq');
        const rekContainer = document.getElementById('rekening-container');

        //Reset Tampilan
        rekContainer.style.display = 'none';
        document.getElementById('zakat-result').innerText = 'Rp 0';
        document.getElementById('infaq-result').innerText = 'Rp 0';
        document.getElementById('nishab-warning').style.display = 'none';

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
            title.innerText = "Ayo mulai infak!";
            btnZakat.classList.remove('active');
            btnInfaq.classList.add('active');
            document.getElementById('infaq-amount').value = '';
        }
    }


    //Logic Form Renderer (Zakat)
    function renderZakatForm() {
        const type = document.getElementById('zakat-type').value;
        const container = document.getElementById('zakat-form-container');
        let html = '';

        if (type === 'maal') {
            html = `
                <label class="form-label">Total Harta (Tahunan)</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control money-input" id="maal-harta" onkeyup="calculateMaal()" placeholder="0">
                </div>
                <label class="form-label">Hutang Jatuh Tempo</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control money-input" id="maal-hutang" onkeyup="calculateMaal()" placeholder="0">
                </div>
                `;
        } else if (type === 'profesi') {
            html = `
                <label class="form-label">Penghasilan Bulanan</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control money-input" id="profesi-gaji" onkeyup="calculateProfesi()" placeholder="0">
                </div>
                <label class="form-label">Pendapatan Lain (Bonus/THR)</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control money-input" id="profesi-bonus" onkeyup="calculateProfesi()" placeholder="0">
                </div>
                <label class="form-label">Pengeluaran Kebutuhan Pokok / Hutang</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control money-input" id="profesi-hutang" onkeyup="calculateProfesi()" placeholder="0">
                </div>
                `;
        } else if (type === 'emas') {
            html = `
                <label class="form-label">Jumlah Emas (Gram)</label>
                <div class="input-group mb-3">
                    <input type="number" class="form-control" id="emas-berat" onkeyup="calculateEmas()" placeholder="0">
                    <span class="input-group-text">Gram</span>
                </div>
                <small class="text-muted">Asumsi harga emas: ${formatRupiah(HARGA_EMAS)} / gram</small>
                `;
        } else if (type === 'tabungan') {
            html = 
                `<label class="form-label">Saldo Tabungan</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control money-input" id="tabungan-saldo" onkeyup="calculateTabungan()" placeholder="0">
                </div>
                <label class="form-label">Bagi Hasil / Bunga (Jika ada)</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control money-input" id="tabungan-bunga" onkeyup="calculateTabungan()" placeholder="0">
                </div>
                `;
        }

        container.innerHTML = html;
        //Reset setiap kali ganti tipe
        updateResult(0, false);
    }

    //Logic Kalkulasi

    //1. Zakat Maal
    function calculateMaal() {
        let harta = parseInput(document.getElementById('maal-harta').value);
        let hutang = parseInput(document.getElementById('maal-hutang').value);
        let total = harta -hutang;

        let wajibZakat = total >= NISHAB_MAAL;
        let zakat = total * 0.025;

        updateResult(zakat, !wajibZakat, 'zakat'); 
    }

    //2. Zakat Profesi
    function calculateProfesi() {
        let gaji = parseInput(document.getElementById('profesi-gaji').value);
        let bonus = parseInput(document.getElementById('profesi-bonus').value);
        let hutang = parseInput(document.getElementById('profesi-hutang').value);
        let total = gaji + bonus - hutang;

        let wajibZakat = total >= NISHAB_MAAL;
        let zakat = total * 0.025;

        updateResult(zakat, !wajibZakat, 'zakat');   
    }

    //3. Zakat Emas
    function calculateEmas() {
        let berat = parseFloat(document.getElementById('emas-berat').value) || 0;

        let wajibZakat = berat >= NISHAB_EMAS_GRAM;
        let nilaiEmas = berat * HARGA_EMAS;
        let zakat = nilaiEmas * 0.025;

        updateResult(zakat, !wajibZakat, 'zakat');
    }

    //4. Zakat Tabungan
    function calculateTabungan() {
        let saldo = parseInput(document.getElementById('tabungan-saldo').value);
        let bunga = parseInput(document.getElementById('tabungan-bunga').value);
        let total = saldo + bunga;

        let wajibZakat = total >= NISHAB_MAAL;
        let zakat = total * 0.025;

        updateResult(zakat, !wajibZakat, 'zakat');  
    }

    //5. Infaq
    function calculateInfaq() {
        let val = parseInput(document.getElementById('infaq-amount').value);
        document.getElementById('infaq-result').innerText = formatRupiah(val);

        if (val > 0) {
            showRekening('infaq');
        } else {
            document.getElementById('rekening-container').style.display = 'none';
        }
    }

    // Update UI Hasil dan Rekening
    function updateResult(amount, belowNishab, category = 'zakat') {
        const resDiv = document.getElementById('zakat-result');
        const warnDiv = document.getElementById('nishab-warning');
        const rekDiv = document.getElementById('rekening-container');

        if (amount < 0) amount = 0;
        resDiv.innerText = formatRupiah(amount);

        if (belowNishab) {
            warnDiv.style.display = 'block';
            rekDiv.style.display = 'none';
        } else {
            warnDiv.style.display = 'none';
            // Jika ada zakat yang harus dibayar, tampilkan rekening
            if (amount > 0) {
                showRekening(category);
            } else {
                rekDiv.style.display = 'none';
            }
        }
    }
    
    // Fungsi Tampil Rekening 
    function showRekening(categoryType) {
        const container = document.getElementById('rekening-list');
        const wrapper = document.getElementById('rekening-container');

        // Filter rekening berdasarkan category (zakat/infaq/all)
        const filtered = BankAccounts.filter(acc => 
            acc.category === 'all' || acc.category === categoryType
        );

        let html = '';
        if(filtered.length > 0) {
            filtered.forEach (acc => {
                html += `
                    <div class="bank-card">
                    <img src="${acc.logo_url}" alt="${acc.bank_name}" class="bank-logo">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">${acc.bank_name}</h6>
                        <div class="d-flex align-items-center">
                            <span class="me-2 fs-5" id="rek-${acc.account_id}">${acc.account_number}</span>
                            <i class="bi bi-clipboard copy-btn" onclick="copyText('rek-${acc.account_id}')" title="Salin"></i>
                        </div>
                        <small class="text-muted">a.n ${acc.account_holder_name}</small>
                    </div>
                </div>
                `;
            });
            container.innerHTML = html;
            wrapper.style.display = 'block';
        } else {
            container.innerHTML = '<p class="text-center text-muted">Belum ada rekening yang tersedia.</p>';
            wrapper.style.display = 'block';
        }
    }

    function copyText(elementId) {
        const text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(()=> {
            alert('Nomor rekening berhasil disalin!');
        }); 
    }

    // Inisialisasi awal
    renderZakatForm();