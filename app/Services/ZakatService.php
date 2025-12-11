<?php

namespace App\Services;

use InvalidArgumentException;

class ZakatService
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('zakat-config');
    }

    /**
     * Get all zakat types with their configuration
     */
    public function getTypes(): array
    {
        return $this->config['types'] ?? [];
    }

    /**
     * Get specific zakat type configuration
     */
    public function getType(string $type): ?array
    {
        return $this->config['types'][$type] ?? null;
    }

    /**
     * Get current gold price per gram
     */
    public function getHargaEmas(): int
    {
        return $this->config['harga_emas_per_gram'] ?? 1300000;
    }

    /**
     * Get current rice price per kg
     */
    public function getHargaBeras(): int
    {
        return $this->config['harga_beras_per_kg'] ?? 13500;
    }

    /**
     * Get nisab value for maal (85 gram emas)
     */
    public function getNisabMaal(): float
    {
        $nisabGram = $this->config['nisab_emas_gram'] ?? 85;
        return $nisabGram * $this->getHargaEmas();
    }

    /**
     * Get monthly nisab for profesi
     */
    public function getNisabProfesiBulanan(): float
    {
        return $this->getNisabMaal() / 12;
    }

    /**
     * Calculate zakat based on type and inputs
     * This is the SERVER-SIDE calculation to prevent manipulation
     */
    public function calculate(string $type, array $inputs): array
    {
        $typeConfig = $this->getType($type);

        if (!$typeConfig) {
            throw new InvalidArgumentException("Jenis zakat '$type' tidak ditemukan");
        }

        // Validate required inputs
        $this->validateInputs($type, $inputs);

        $result = match ($type) {
            'fitrah' => $this->calculateFitrah($inputs),
            'maal' => $this->calculateMaal($inputs),
            'profesi' => $this->calculateProfesi($inputs),
            'emas' => $this->calculateEmas($inputs),
            'tabungan' => $this->calculateTabungan($inputs),
            'pertanian' => $this->calculatePertanian($inputs),
            'rikaz' => $this->calculateRikaz($inputs),
            default => throw new InvalidArgumentException("Perhitungan untuk '$type' belum tersedia"),
        };

        return [
            'type' => $type,
            'type_name' => $typeConfig['name'],
            'amount' => $result['amount'],
            'meets_nisab' => $result['meets_nisab'],
            'nisab_value' => $result['nisab_value'] ?? null,
            'calculation_details' => $result['details'] ?? [],
        ];
    }

    /**
     * Validate inputs for a zakat type
     */
    public function validateInputs(string $type, array $inputs): bool
    {
        $typeConfig = $this->getType($type);

        if (!$typeConfig) {
            throw new InvalidArgumentException("Jenis zakat tidak valid");
        }

        $requiredInputs = collect($typeConfig['inputs'])
            ->filter(fn($cfg) => $cfg['required'] ?? false)
            ->keys()
            ->toArray();

        foreach ($requiredInputs as $required) {
            if (!isset($inputs[$required]) || $inputs[$required] === null || $inputs[$required] === '') {
                throw new InvalidArgumentException("Input '$required' wajib diisi");
            }
        }

        return true;
    }

    /**
     * Calculate Zakat Fitrah
     */
    protected function calculateFitrah(array $inputs): array
    {
        $jumlahJiwa = (int) ($inputs['jumlah_jiwa'] ?? 1);
        $hargaBeras = $this->getHargaBeras();
        $amount = $jumlahJiwa * 2.5 * $hargaBeras;

        return [
            'amount' => $amount,
            'meets_nisab' => true, // No nisab for fitrah
            'details' => [
                'jumlah_jiwa' => $jumlahJiwa,
                'kg_per_jiwa' => 2.5,
                'harga_beras' => $hargaBeras,
            ]
        ];
    }

    /**
     * Calculate Zakat Maal
     */
    protected function calculateMaal(array $inputs): array
    {
        $totalHarta = $this->parseNumber($inputs['total_harta'] ?? 0);
        $hutang = $this->parseNumber($inputs['hutang'] ?? 0);
        $nisab = $this->getNisabMaal();

        $hartaBersih = $totalHarta - $hutang;
        $meetsNisab = $hartaBersih >= $nisab;
        $amount = $meetsNisab ? $hartaBersih * 0.025 : 0;

        return [
            'amount' => $amount,
            'meets_nisab' => $meetsNisab,
            'nisab_value' => $nisab,
            'details' => [
                'total_harta' => $totalHarta,
                'hutang' => $hutang,
                'harta_bersih' => $hartaBersih,
                'rate' => '2.5%',
            ]
        ];
    }

    /**
     * Calculate Zakat Profesi (monthly)
     */
    protected function calculateProfesi(array $inputs): array
    {
        $gaji = $this->parseNumber($inputs['gaji_bulanan'] ?? 0);
        $bonus = $this->parseNumber($inputs['bonus'] ?? 0);
        $pengeluaran = $this->parseNumber($inputs['pengeluaran_pokok'] ?? 0);

        $nisabBulanan = $this->getNisabProfesiBulanan();
        $totalBulanan = $gaji + $bonus - $pengeluaran;
        $totalSetahun = $totalBulanan * 12;

        $meetsNisab = $totalSetahun >= $this->getNisabMaal();
        $amount = $meetsNisab ? $totalBulanan * 0.025 : 0;

        return [
            'amount' => $amount,
            'meets_nisab' => $meetsNisab,
            'nisab_value' => $nisabBulanan,
            'details' => [
                'gaji_bulanan' => $gaji,
                'bonus' => $bonus,
                'pengeluaran_pokok' => $pengeluaran,
                'total_bulanan' => $totalBulanan,
                'total_setahun' => $totalSetahun,
                'rate' => '2.5%',
            ]
        ];
    }

    /**
     * Calculate Zakat Emas
     */
    protected function calculateEmas(array $inputs): array
    {
        $beratEmas = (float) ($inputs['berat_emas'] ?? 0);
        $hargaEmas = $this->getHargaEmas();
        $nisabGram = $this->config['nisab_emas_gram'] ?? 85;

        $nilaiEmas = $beratEmas * $hargaEmas;
        $meetsNisab = $beratEmas >= $nisabGram;
        $amount = $meetsNisab ? $nilaiEmas * 0.025 : 0;

        return [
            'amount' => $amount,
            'meets_nisab' => $meetsNisab,
            'nisab_value' => $nisabGram * $hargaEmas,
            'details' => [
                'berat_emas' => $beratEmas,
                'harga_emas' => $hargaEmas,
                'nilai_emas' => $nilaiEmas,
                'nisab_gram' => $nisabGram,
                'rate' => '2.5%',
            ]
        ];
    }

    /**
     * Calculate Zakat Tabungan
     */
    protected function calculateTabungan(array $inputs): array
    {
        $saldo = $this->parseNumber($inputs['saldo_tabungan'] ?? 0);
        $bunga = $this->parseNumber($inputs['bunga'] ?? 0);
        $nisab = $this->getNisabMaal();

        $total = $saldo + $bunga;
        $meetsNisab = $total >= $nisab;
        $amount = $meetsNisab ? $total * 0.025 : 0;

        return [
            'amount' => $amount,
            'meets_nisab' => $meetsNisab,
            'nisab_value' => $nisab,
            'details' => [
                'saldo_tabungan' => $saldo,
                'bunga' => $bunga,
                'total' => $total,
                'rate' => '2.5%',
            ]
        ];
    }

    /**
     * Calculate Zakat Pertanian
     */
    protected function calculatePertanian(array $inputs): array
    {
        $hasilPanen = $this->parseNumber($inputs['hasil_panen'] ?? 0);
        $isIrigasi = (bool) ($inputs['is_irigasi'] ?? false);
        $nisabKg = $this->config['nisab_pertanian_kg'] ?? 520;
        $nisabValue = $nisabKg * $this->getHargaBeras();

        $rate = $isIrigasi ? 0.05 : 0.10;
        $meetsNisab = $hasilPanen >= $nisabValue;
        $amount = $meetsNisab ? $hasilPanen * $rate : 0;

        return [
            'amount' => $amount,
            'meets_nisab' => $meetsNisab,
            'nisab_value' => $nisabValue,
            'details' => [
                'hasil_panen' => $hasilPanen,
                'is_irigasi' => $isIrigasi,
                'rate' => ($rate * 100) . '%',
            ]
        ];
    }

    /**
     * Calculate Zakat Rikaz
     */
    protected function calculateRikaz(array $inputs): array
    {
        $nilaiTemuan = $this->parseNumber($inputs['nilai_temuan'] ?? 0);
        $amount = $nilaiTemuan * 0.20;

        return [
            'amount' => $amount,
            'meets_nisab' => true, // No nisab for rikaz
            'details' => [
                'nilai_temuan' => $nilaiTemuan,
                'rate' => '20%',
            ]
        ];
    }

    /**
     * Parse number from formatted string (e.g., "1.000.000" -> 1000000)
     */
    protected function parseNumber($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Remove currency symbols and formatting
        $cleaned = preg_replace('/[^\d,.-]/', '', (string) $value);
        $cleaned = str_replace('.', '', $cleaned); // Remove thousand separators
        $cleaned = str_replace(',', '.', $cleaned); // Convert decimal comma to dot

        return (float) $cleaned;
    }

    /**
     * Format amount to Rupiah
     */
    public function formatRupiah(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
