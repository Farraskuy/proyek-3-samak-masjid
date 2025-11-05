<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\DonationConfirmation;
use Illuminate\Http\Request;

class ZISController extends Controller
{
    public function index()
    {
        // Ambil data bank yang active
        $rekening = BankAccount::where('is_active',true)->get();

        return view('client.keuangan.informasi.index', [
            'daftarRekening' => $rekening
        ]);
    }
}


