    const HARGA_EMAS = 1300000;
    const NISHAB_EMAS_GRAM = 85;
    const HARGA_BERAS_KILOGRAM = 13500;
    const NISHAB_MAAL = NISHAB_EMAS_GRAM * HARGA_EMAS;
    const NISHAB_PERTANIAN = 520 * HARGA_BERAS_KILOGRAM;

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', { style : 'currency', currency:'IDR', maximumFractionDigits: 0}).format(number);
    }

    const parseInput = (val) => {
        if(!val) return 0;
        let cleanString = val.toString().replace(/[^0-9]/g, '');
        
        return parseFloat(cleanString) || 0;
    }

    // Fungsi format angka saat mengetik
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


    // 1. Fungsi Satu Pintu untuk Update Link Tombol
    function refreshKonfirmasiLink() {
        const btn = document.getElementById('btn-konfirmasi');
        const zakatSectionVisible = document.getElementById('section-zakat').style.display !== 'none';
        
        let amount = 0;
        let type = '';

        if (zakatSectionVisible) {
            const resultText = document.getElementById('zakat-result').innerText;
            amount = parseInput(resultText); 
            type = document.getElementById('zakat-type').value;
        } else {
            amount = parseInput(document.getElementById('infaq-amount').value);
            type = document.getElementById('infaq-type').value;
        }

        const selectedBankEl = document.querySelector('input[name="selected_bank"]:checked');
        const bankId = selectedBankEl ? selectedBankEl.value : '';

        const cleanAmount = Math.round(amount);
        let url = `/donasi/konfirmasi?amount=${cleanAmount}&type=${type}`;
        
        if (bankId) {
            url += `&bank_id=${bankId}`;
        }

        btn.setAttribute('href', url);
    }

    // 2. Logic Navigasi
    function switchMode(mode) {
        const zakatSec = document.getElementById('section-zakat');
        const infaqSec = document.getElementById('section-infaq');
        const title = document.getElementById('calculator-title');
        const btnZakat = document.getElementById('btn-menu-zakat');
        const btnInfaq = document.getElementById('btn-menu-infaq');
        const rekContainer = document.getElementById('rekening-container');

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
            refreshKonfirmasiLink(); 
        }
    }

    // 3. Render Form Zakat
    function renderZakatForm() {
        const type = document.getElementById('zakat-type').value;
        const container = document.getElementById('zakat-form-container');
        let html = '';

        if (type === 'maal') {
            html = `<label class="form-label">Total Harta (Tahunan)</label>
                    <div class="input-group mb-3"><span class="input-group-text">Rp</span><input type="text" class="form-control money-input" id="maal-harta" onkeyup="calculateMaal()" placeholder="0"></div>
                    <label class="form-label">Hutang Jatuh Tempo</label>
                    <div class="input-group mb-3"><span class="input-group-text">Rp</span><input type="text" class="form-control money-input" id="maal-hutang" onkeyup="calculateMaal()" placeholder="0"></div>`;
        } else if (type === 'profesi') {
            html = `<label class="form-label">Penghasilan Bulanan</label>
                    <div class="input-group mb-3"><span class="input-group-text">Rp</span><input type="text" class="form-control money-input" id="profesi-gaji" onkeyup="calculateProfesi()" placeholder="0"></div>
                    <label class="form-label">Pendapatan Lain (Bonus/THR)</label>
                    <div class="input-group mb-3"><span class="input-group-text">Rp</span><input type="text" class="form-control money-input" id="profesi-bonus" onkeyup="calculateProfesi()" placeholder="0"></div>
                    <label class="form-label">Pengeluaran Kebutuhan Pokok / Hutang</label>
                    <div class="input-group mb-3"><span class="input-group-text">Rp</span><input type="text" class="form-control money-input" id="profesi-hutang" onkeyup="calculateProfesi()" placeholder="0"></div>`;
        } else if (type === 'emas') {
            html = `<label class="form-label">Jumlah Emas (Gram)</label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" id="emas-berat" 
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '')" 
                            onkeyup="calculateEmas()" placeholder="0">
                        <span class="input-group-text">Gram</span>
                    </div>
                    <small class="text-muted">Asumsi harga emas: ${formatRupiah(HARGA_EMAS)} / gram</small>`;
        } else if (type === 'tabungan') {
            html = `<label class="form-label">Saldo Tabungan</label>
                    <div class="input-group mb-3"><span class="input-group-text">Rp</span><input type="text" class="form-control money-input" id="tabungan-saldo" onkeyup="calculateTabungan()" placeholder="0"></div>
                    <label class="form-label">Bunga (Jika ada)</label>
                    <div class="input-group mb-3"><span class="input-group-text">Rp</span><input type="text" class="form-control money-input" id="tabungan-bunga" onkeyup="calculateTabungan()" placeholder="0"></div>`;
        } else if (type === 'pertanian') {
            html = `<label class="form-label">Hasil Panen</label>
                    <div class="input-group mb-3"><span class="input-group-text">Rp</span><input type="text" class="form-control money-input" id="hasil-panen" onkeyup="calculatePertanian()" placeholder="0"></div>
                    <div class="p-3 border rounded bg-white d-flex align-items-start"><div class="me-3"><input type="checkbox" id="irigasi" onclick="calculatePertanian()" style="width: 24px; height: 24px; cursor: pointer; margin-top: 2px;"></div><div><label for="irigasi" class="fw-bold mb-1 text-dark">Menggunakan Irigasi / Berbiaya</label><div class="text-muted small">Centang jika pakai pompa (Zakat 5%).<br>Jika tidak = Tadah hujan (Zakat 10%).</div></div></div>`;
        }

        container.innerHTML = html;
        updateResult(0, false);
    }

    // 4. Logic Perhitungan Zakat
    function calculateMaal() {
        let harta = parseInput(document.getElementById('maal-harta').value);
        let hutang = parseInput(document.getElementById('maal-hutang').value);
        let total = harta - hutang;
        updateResult(total * 0.025, total < NISHAB_MAAL, 'zakat');
    }

    function calculateProfesi() {
        let gaji = parseInput(document.getElementById('profesi-gaji').value);
        let bonus = parseInput(document.getElementById('profesi-bonus').value);
        let hutang = parseInput(document.getElementById('profesi-hutang').value);
        let totalBulanan = gaji + bonus - hutang;
        let totalSetahun = totalBulanan * 12;
        updateResult(totalBulanan * 0.025, totalSetahun < NISHAB_MAAL, 'zakat');
    }

    function calculateEmas() {
        let berat = parseFloat(document.getElementById('emas-berat').value) || 0;
        let nilaiEmas = berat * HARGA_EMAS;
        updateResult(nilaiEmas * 0.025, berat < NISHAB_EMAS_GRAM, 'zakat');
    }

    function calculateTabungan() {
        let saldo = parseInput(document.getElementById('tabungan-saldo').value);
        let bunga = parseInput(document.getElementById('tabungan-bunga').value);
        let total = saldo + bunga;
        updateResult(total * 0.025, total < NISHAB_MAAL, 'zakat');
    }

    function calculatePertanian() {
        let hasil = parseInput(document.getElementById('hasil-panen').value);
        let isIrigasi = document.getElementById('irigasi').checked;
        let tarif = isIrigasi ? 0.05 : 0.1;
        updateResult(hasil * tarif, hasil < NISHAB_PERTANIAN, 'zakat');
    }

    // Update Result UI (Untuk Zakat)
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
            if (amount > 0) {
                showRekening(category); 
            } else {
                rekDiv.style.display = 'none';
            }
        }
        
        refreshKonfirmasiLink();
    }

    // 5. Logic Infak
    function calculateInfaq() {
        let val = parseInput(document.getElementById('infaq-amount').value);
        document.getElementById('infaq-result').innerText = formatRupiah(val);

        if (val > 0) {
            showRekening('infaq');
        } else {
            document.getElementById('rekening-container').style.display = 'none';
        }
        
        refreshKonfirmasiLink();
    }

    // 6. Tampilkan Rekening 
    function showRekening(categoryType) {
        const container = document.getElementById('rekening-list');
        const wrapper = document.getElementById('rekening-container');

        const filtered = window.BankAccounts.filter(acc => 
            acc.category === 'all' || acc.category === categoryType
        );

        let html = '';
        if(filtered.length > 0) {
            filtered.forEach ((acc, index) => {
                const isChecked = index === 0; 
                html += `
                    <label class="d-block mb-3" style="cursor:pointer">
                        <input type="radio" name="selected_bank" 
                               value="${acc.account_id}" 
                               class="hidden-checkbox" 
                               id="bank-${acc.account_id}"
                               onchange="refreshKonfirmasiLink()" ${isChecked ? 'checked' : ''}>

                        <div class="bank-card custom-checkbox-label"> 
                        <img src="${acc.logo_url}" alt="${acc.bank_name}" class="bank-logo">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold">${acc.bank_name}</h6>
                            <div class="d-flex align-items-center">
                                <span class="me-2 fs-5" id="rek-${acc.account_id}">${acc.account_number}</span>
                                <i class="bi bi-clipboard copy-btn" onclick="event.preventDefault(); copyText('rek-${acc.account_id}')" title="Salin"></i>
                            </div>
                            <small class="text-muted">a.n ${acc.account_holder_name}</small>
                        </div>
                        </div>
                    </label>
                `;
            });
            container.innerHTML = html;
            wrapper.style.display = 'block';
            
            refreshKonfirmasiLink();
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

    renderZakatForm();