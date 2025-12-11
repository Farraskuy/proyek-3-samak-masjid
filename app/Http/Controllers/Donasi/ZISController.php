<?php

namespace App\Http\Controllers\Donasi;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BankAccount;
use App\Models\DonationConfirmation;
use App\Models\Infaq;
use App\Services\ZakatService;

class ZISController extends Controller
{
    protected ZakatService $zakatService;

    public function __construct(ZakatService $zakatService)
    {
        $this->zakatService = $zakatService;
    }

    public function index()
    {
        return view('client.donasi.informasi.index');
    }

    public function donasi()
    {
        // Get active bank accounts grouped by category
        // Exclude 'kas' type - Kas is for offline recording only
        $zakatBanks = BankAccount::where('is_active', true)
            ->where('category', 'zakat')
            ->where('type', '!=', 'kas')
            ->get();

        $infaqBanks = BankAccount::where('is_active', true)
            ->where('category', 'infaq')
            ->where('type', '!=', 'kas')
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

        return view('client.donasi.index', [
            'zakatBanks' => $zakatBanks,
            'infaqBanks' => $infaqBanks,
            'infaqPrograms' => $infaqPrograms,
            'zakatTypes' => $zakatTypes,
            'zakatConfig' => $zakatConfig,
            'daftarRekening' => $zakatBanks->merge($infaqBanks), // For backward compatibility
        ]);
    }

    /**
     * Process donation calculation and store in session
     */
    public function submitDonation(Request $request)
    {
        $request->validate([
            'donation_category' => 'required|in:zakat,infaq',
            'donation_type' => 'required|string',
            'bank_id' => 'required|exists:bank_accounts,account_id',
        ]);

        $category = $request->donation_category;
        $type = $request->donation_type;
        $bankId = $request->bank_id;

        $calculatedAmount = 0;
        $calculationDetails = [];

        if ($category === 'zakat') {
            // Extract inputs based on zakat type
            $inputs = $request->except(['_token', 'donation_category', 'donation_type', 'bank_id']);

            try {
                $result = $this->zakatService->calculate($type, $inputs);
                $calculatedAmount = $result['amount'];
                $calculationDetails = $result;

                if (!$result['meets_nisab'] && $calculatedAmount <= 0) {
                    return back()->with('warning', 'Harta Anda belum mencapai nisab. Anda tidak wajib zakat, namun tetap dianjurkan bersedekah.');
                }
            } catch (\InvalidArgumentException $e) {
                return back()->withErrors(['error' => $e->getMessage()]);
            }
        } else {
            // Infaq - just get the amount from input
            $request->validate([
                'infaq_amount' => 'required|numeric|min:10000',
            ]);
            $calculatedAmount = (float) $request->infaq_amount;
            $calculationDetails = [
                'type' => $type,
                'type_name' => 'Infaq ' . ucfirst($type),
                'amount' => $calculatedAmount,
            ];
        }

        // Get bank details
        $bank = BankAccount::findOrFail($bankId);

        // Store in session
        session([
            'donation_data' => [
                'category' => $category,
                'type' => $type,
                'type_name' => $calculationDetails['type_name'] ?? ucfirst($type),
                'inputs' => $request->except(['_token', 'donation_category', 'donation_type', 'bank_id']),
                'calculated_amount' => $calculatedAmount,
                'bank_id' => $bankId,
                'bank_name' => $bank->bank_name,
                'bank_account_number' => $bank->account_number,
                'calculation_details' => $calculationDetails,
                'submitted_at' => now()->toISOString(),
            ]
        ]);

        return redirect()->route('donasi.konfirmasi');
    }

    public function konfirmasi()
    {
        // Check if coming from calculation form
        if (!session()->has('donation_data')) {
            return redirect()->route('donasi.sekarang')
                ->with('error', 'Silakan hitung donasi Anda terlebih dahulu.');
        }

        $donationData = session('donation_data');
        $banks = BankAccount::where('is_active', true)->get();

        $riwayat = [];
        if (Auth::check()) {
            $riwayat = DonationConfirmation::where('user_id', Auth::id())
                ->with('destinationAccount')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('client.donasi.konfirmasi', compact('banks', 'riwayat', 'donationData'));
    }

    public function storeKonfirmasi(Request $request)
    {
        // Verify session data exists
        if (!session()->has('donation_data')) {
            return redirect()->route('donasi.sekarang')
                ->with('error', 'Sesi donasi tidak valid. Silakan mulai dari awal.');
        }

        $donationData = session('donation_data');

        $request->validate([
            'nama_pengirim' => 'required|string|max:100',
            'is_anonymous' => 'nullable|boolean',
            'transfer_date' => 'required|date',
            'source_bank' => 'required|string|max:50',
            'proof_file' => 'nullable|image|mimes:jpeg,png,jpg|max:5000',
            'notes' => 'nullable|string|max:255',
        ]);

        // Upload image
        $path = null;
        if ($request->hasFile('proof_file')) {
            $file = $request->file('proof_file');
            $path = $file->store('bukti_transfer', 'public');
        }

        $userId = Auth::check() ? Auth::id() : null;
        $isAnonymous = $request->has('is_anonymous') && $request->is_anonymous;
        $guestName = $isAnonymous ? 'Hamba Allah' : $request->nama_pengirim;

        // Recalculate amount on server for security
        $finalAmount = $donationData['calculated_amount'];

        // Save to DB
        $confirmation = DonationConfirmation::create([
            'user_id' => $userId,
            'guest_name' => $guestName,
            'donation_type' => $donationData['category'] . '_' . $donationData['type'],
            'amount' => $finalAmount,
            'transfer_date' => $request->transfer_date,
            'destination_account_id' => $donationData['bank_id'],
            'source_bank' => $request->source_bank,
            'proof_image_url' => $path ? '/storage/' . $path : 'placeholder.jpg',
            'notes' => $request->notes ?? '-',
            'status' => 'Pending'
        ]);

        // Clear session
        session()->forget('donation_data');

        // Redirect to success page
        return redirect()->route('donasi.sukses')
            ->with('donation_success', [
                'amount' => $finalAmount,
                'type' => $donationData['type_name'],
                'bank' => $donationData['bank_name']
            ]);
    }

    public function sukses()
    {
        // Only accessible with success data
        if (!session()->has('donation_success')) {
            return redirect()->route('donasi.sekarang');
        }

        $successData = session('donation_success');
        return view('client.donasi.sukses', compact('successData'));
    }
}
