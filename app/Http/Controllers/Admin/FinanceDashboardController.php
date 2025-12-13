<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\FinancialTransaction;
use App\Models\DonationConfirmation;

class FinanceDashboardController extends Controller
{
    public function index()
    {
        // Total Saldo Bank (use 'balance' column)
        $totalSaldo = BankAccount::sum('balance');
        
        // Count active banks
        $totalBanks = BankAccount::where('is_active', true)->count();
        
        // Pemasukan bulan ini
        $pemasukanBulanIni = FinancialTransaction::where('type', 'income')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        
        // Pengeluaran bulan ini
        $pengeluaranBulanIni = FinancialTransaction::where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        
        // Donasi pending verification
        $donasiPending = DonationConfirmation::where('status', 'pending')->count();
        
        // Recent transactions (last 10)
        $recentTransactions = FinancialTransaction::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Bank accounts with balance
        $banks = BankAccount::where('is_active', true)->get();

        return view('admin.keuangan.dashboard', compact(
            'totalSaldo',
            'totalBanks',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'donasiPending',
            'recentTransactions',
            'banks'
        ));
    }
}
