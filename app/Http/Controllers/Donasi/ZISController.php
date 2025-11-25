<?php

namespace App\Http\Controllers\Donasi;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BankAccount;
use App\Models\DonationConfirmation;

class ZISController extends Controller
{
    public function index()
    {
        return view('client.donasi.informasi.index');
    }

    public function donasi()
    {
        // Ambil data bank yang active 
        $rekening = BankAccount::where('is_active',true)->get();

        return view('client.donasi.index', [
            'daftarRekening' => $rekening
        ]);
    }

    public function konfirmasi()
    {
        $banks = BankAccount::where('is_active', true)->get();

        $riwayat = [];

        if (Auth::check()) {
            $riwayat = DonationConfirmation::where('user_id', Auth::id())
                        ->with('destinationAccount')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
        }

        return view('client.donasi.konfirmasi', compact('banks', 'riwayat'));
    }

    public function storeKonfirmasi(Request $request)
    {
        $request->validate([
            'nama_pengirim' => 'required|string|max:100',
            'amount' => 'required|numeric|min:10000',
            'transfer_date' => 'required|date',
            'destination_account_id' => 'required|exists:bank_accounts,account_id',
            'source_bank' => 'required|string|max:50',
            'proof_file' => 'required|image|mimes:jpeg,png,jpg|max:5000',
            'notes' => 'nullable|string|max:255',
        ]);

        // Upload gambar
        $path = null;
        if($request->hasFile('proof_file')) {
            $file = $request->file('proof_file');
            $path = $file->store('bukti_transfer', 'public');
        }

        $userId = Auth::check() ? Auth::id() : null;
        $guestName = Auth::check() ? Auth::user()->name : $request->nama_pengirim; 

        // Simpan ke DB
        DonationConfirmation::create([
            'user_id' => $userId,
            'guest_name' => $guestName,
            'amount' => $request->amount,
            'transfer_date' => $request->transfer_date,
            'destination_account_id' => $request->destination_account_id,
            'source_bank' => $request->source_bank,
            'proof_image_url' => $path ? '/storage/' . $path : null, 
            'notes' => $request->notes ?? '-', 
            'status' => 'Pending'
        ]);

        return redirect()->route('donasi.konfirmasi')->with('success', 'Konfirmasi berhasil dikirim! Mohon tunggu verifikasi admin.');
    }

}


