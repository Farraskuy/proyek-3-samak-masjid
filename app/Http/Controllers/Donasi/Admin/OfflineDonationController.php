<?php

namespace App\Http\Controllers\Donasi\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\DonationConfirmation;
use App\Models\Infaq;
use App\Models\FinancialTransaction;
use App\Services\ZakatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfflineDonationController extends Controller
{
    protected ZakatService $zakatService;

    public function __construct(ZakatService $zakatService)
    {
        $this->zakatService = $zakatService;
    }

    public function create()
    {
        // Get all active bank accounts including 'kas' for offline
        $zakatBanks = BankAccount::where('is_active', true)
            ->where('category', 'zakat')
            ->get();

        $infaqBanks = BankAccount::where('is_active', true)
            ->where('category', 'infaq')
            ->get();

        // Get kas account for infaq transfer option
        $kasBanks = BankAccount::where('is_active', true)
            ->where('type', 'kas')
            ->get();

        // Get active infaq programs
        $infaqPrograms = Infaq::with('bankAccount')
            ->where('is_active', true)
            ->get();

        // Get zakat types from config
        $zakatTypes = $this->zakatService->getTypes();
        $zakatConfig = [
            'harga_emas' => $this->zakatService->getHargaEmas(),
            'harga_beras' => $this->zakatService->getHargaBeras(),
            'nisab_maal' => $this->zakatService->getNisabMaal(),
            'nisab_profesi_bulanan' => $this->zakatService->getNisabProfesiBulanan(),
        ];

        return view('admin.donasi.offline', [
            'zakatBanks' => $zakatBanks,
            'infaqBanks' => $infaqBanks,
            'kasBanks' => $kasBanks,
            'infaqPrograms' => $infaqPrograms,
            'zakatTypes' => $zakatTypes,
            'zakatConfig' => $zakatConfig,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'donation_category' => 'required|in:zakat,infaq',
            'donation_type' => 'required|string',
            'bank_id' => 'required|exists:bank_accounts,account_id',
            'donor_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:255',
        ]);

        $category = $request->donation_category;
        $type = $request->donation_type;
        $amount = 0;

        // Calculate Amount
        if ($category === 'zakat') {
            $inputs = $request->except(['_token', 'donation_category', 'donation_type', 'bank_id', 'donor_name', 'phone', 'notes']);
            try {
                $result = $this->zakatService->calculate($type, $inputs);
                $amount = $result['amount'];

                // Allow manual override if needed? For now trust calculator.
                // But wait, offline donation might be fixed amount regardless of calculator.
                // If user inputs manual amount, we should use it.
                // But the UI is based on calculator.
                // Let's assume admin uses calculator.

                if ($amount <= 0) {
                    return back()->withErrors(['error' => 'Jumlah zakat 0. Pastikan data benar atau capai nisab.']);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['error' => $e->getMessage()]);
            }
        } else {
            $request->validate([
                'infaq_amount' => 'required|numeric|min:1000',
            ]);
            $amount = (float) $request->infaq_amount;
        }

        DB::transaction(function () use ($request, $category, $type, $amount) {
            // Create Donation Record (Verified)
            $donation = DonationConfirmation::create([
                'user_id' => Auth::id(), // Admin as creator/user? Or null? Let's use Admin ID to track who input it.
                'guest_name' => $request->donor_name,
                'donation_type' => $category . '_' . $type,
                'amount' => $amount,
                'transfer_date' => now(),
                'destination_account_id' => $request->bank_id,
                'source_bank' => 'OFFLINE / TUNAI', // Marker for offline
                'proof_image_url' => 'offline_entry',
                'notes' => $request->notes . ' (No. HP: ' . $request->phone . ')',
                'status' => 'Verified',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // Update Bank Balance
            $bankAccount = BankAccount::find($request->bank_id);
            if ($bankAccount) {
                $bankAccount->addBalance($amount);
            }

            // Create Transaction Record
            FinancialTransaction::create([
                'type' => 'pemasukan',
                'bank_name' => $bankAccount->bank_name ?? 'Unknown',
                'amount' => $amount,
                'category' => 'Donasi Offline',
                'description' => 'Donasi Offline dari ' . $request->donor_name . ' (' . ucfirst($category) . ')',
                'transaction_date' => now(),
                'proof_image_url' => null,
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('admin.donasi.index')->with('success', 'Donasi offline berhasil dicatat.');
    }
}
