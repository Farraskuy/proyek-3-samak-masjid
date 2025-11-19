<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\DonationConfirmation;

class ZISController extends Controller
{
    public function index()
    {
        return view('client.donasi.informasi.index');
    }

    //Ini tempat kalkulator dan nomor rekening berada
    public function donasi()
    {
        // Ambil data bank yang active 
        $rekening = BankAccount::where('is_active',true)->get();

        return view('client.donasi.index', [
            'daftarRekening' => $rekening
        ]);
    }

}


