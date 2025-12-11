<?php

namespace App\Http\Controllers\Donasi\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonationConfirmation;
use Illuminate\Support\Facades\Auth;
use App\Models\FinancialTransaction;
use App\Models\BankAccount;
use Illuminate\Support\Facades\DB;

class DonationConfirmationController extends Controller
{
    public function index(Request $request)
    {
        $query = DonationConfirmation::with(['user', 'destinationAccount']);

        // Filter by keyword
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('source_bank', 'like', "%$keyword%")
                    ->orWhere('amount', 'like', "%$keyword%")
                    ->orWhere('guest_name', 'like', "%$keyword%")
                    ->orWhereHas('user', function ($u) use ($keyword) {
                        $u->where('name', 'like', "%$keyword%");
                    });
            });
        }

        // Filter by tab/status
        $status = $request->query('status', 'Pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by donation type
        if ($request->has('donation_type') && $request->donation_type != 'all') {
            $query->where('donation_type', 'like', $request->donation_type . '%');
        }

        // Sorting
        $sortDirection = $request->query('ordered_by', 'desc');
        $query->orderBy('created_at', $sortDirection);

        $showing = $request->query('showing', 10);
        if ($showing == 'all') {
            $data = $query->get();
        } else {
            $data = $query->paginate($showing)->withQueryString();
        }

        // Count for tabs
        $counts = [
            'all' => DonationConfirmation::count(),
            'Pending' => DonationConfirmation::where('status', 'Pending')->count(),
            'Verified' => DonationConfirmation::where('status', 'Verified')->count(),
            'Rejected' => DonationConfirmation::where('status', 'Rejected')->count(),
        ];

        $banks = BankAccount::all();

        return view('admin.donasi.index', compact('data', 'banks', 'counts', 'status'));
    }

    public function approve($id)
    {
        DB::transaction(function () use ($id) {
            $donation = DonationConfirmation::with('destinationAccount')->findOrFail($id);

            $donation->update([
                'status' => 'Verified',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // Add balance to bank account
            $bankAccount = $donation->destinationAccount;
            if ($bankAccount) {
                $bankAccount->addBalance($donation->amount);
            }

            // Get bank name from relation
            $bankName = $donation->destinationAccount->bank_name ?? 'Bank Umum';

            FinancialTransaction::create([
                'type' => 'pemasukan',
                'bank_name' => $bankName,
                'amount' => $donation->amount,
                'category' => 'Donasi Online',
                'description' => 'Donasi dari ' . ($donation->user->name ?? $donation->guest_name) . ' (ID: ' . $donation->confirmation_id . ')',
                'transaction_date' => now(),
                'proof_image_url' => $donation->proof_image_url,
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->back()->with('success', 'Donasi disetujui & saldo otomatis masuk ke rekening.');
    }

    public function reject($id)
    {
        $donasi = DonationConfirmation::findOrFail($id);

        $donasi->update([
            'status' => 'Rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('warning', 'Donasi telah ditolak.');
    }
}