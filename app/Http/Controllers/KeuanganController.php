<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $banks = BankAccount::all()->map(function($bank) {
            
            $masuk = FinancialTransaction::where('bank_name', $bank->bank_name) 
                        ->where('type', 'pemasukan')
                        ->sum('amount');
            
            $keluar = FinancialTransaction::where('bank_name', $bank->bank_name)
                        ->where('type', 'pengeluaran')
                        ->sum('amount');
            
            $bank->saldo_saat_ini = $masuk - $keluar;
            
            return $bank;
        });
        $selectedBank = $request->input('bank', 'global');

        $query = FinancialTransaction::query();

        if ($selectedBank != 'global') {
            $query->where('bank_name', $selectedBank);
        }

        $totalPemasukan = (clone $query)->where('type', 'pemasukan')->sum('amount');
        $totalPengeluaran = (clone $query)->where('type', 'pengeluaran')->sum('amount');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;
        
        $currentYear = date('Y');
        
        $incomeData = (clone $query)->select(
                DB::raw('EXTRACT(MONTH FROM transaction_date) as month'), 
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('transaction_date', $currentYear)
            ->where('type', 'pemasukan')
            ->groupBy(DB::raw('EXTRACT(MONTH FROM transaction_date)'))
            ->pluck('total', 'month')->toArray();

        $expenseData = (clone $query)->select(
                DB::raw('EXTRACT(MONTH FROM transaction_date) as month'), 
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('transaction_date', $currentYear)
            ->where('type', 'pengeluaran')
            ->groupBy(DB::raw('EXTRACT(MONTH FROM transaction_date)'))
            ->pluck('total', 'month')->toArray();

        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartIncome = [];
        $chartExpense = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartIncome[] = $incomeData[$i] ?? 0;
            $chartExpense[] = $expenseData[$i] ?? 0;
        }

        $user = Auth::user();
        $allowedTypes = [];

        if ($user->hasPermission('manage_income')) $allowedTypes[] = 'pemasukan';
        if ($user->hasPermission('manage_expense')) $allowedTypes[] = 'pengeluaran';

        // Gunakan $query yang sudah difilter bank tadi, lalu tambahkan filter permission
        $transactions = (clone $query)
            ->whereIn('type', $allowedTypes)
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);

        // Kirim variable $selectedBank ke view agar dropdown tidak reset
        return view('admin.keuangan.index', compact(
            'transactions', 
            'totalPemasukan', 
            'totalPengeluaran', 
            'saldoAkhir',
            'chartLabels',
            'chartIncome',
            'chartExpense',
            'selectedBank',
            'banks' 
        ));
    }

    public function store(Request $request)
    {
        $querySaldo = FinancialTransaction::where('bank_name', $request->bank_name);
        $saldoBankIni = $querySaldo->where('type', 'pemasukan')->sum('amount') - 
                        $querySaldo->where('type', 'pengeluaran')->sum('amount');

        $rules = [
            'type' => 'required|in:pemasukan,pengeluaran',
            'bank_name' => 'required|string',
            'category' => 'required|string',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:1000',
            'proof_file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5000', 
        ];

        if ($request->type == 'pengeluaran') {
            $rules['amount'] .= '|lte:' . $saldoBankIni;
        }

        $request->validate($rules, [
            'amount.lte' => 'Saldo di ' . $request->bank_name . ' tidak cukup. Sisa: Rp ' . number_format($saldoBankIni, 2, ',', '.'),
        ]);

        $path = null;
        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('proofs', 'public');
        }

        FinancialTransaction::create([
            'type' => $request->type,
            'bank_name' => $request->bank_name, 
            'amount' => $request->amount,
            'category' => $request->category,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
            'proof_image_url' => $path ? $path : null,
            'user_id' => Auth::id(),
        ]);
        
        return redirect()->back()->with('success', 'Transaksi berhasil dicatat di ' . $request->bank_name);
    }

    public function destroy($id)
    {
        FinancialTransaction::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Transaksi dihapus.');
    }

    public function clientIndex(Request $request)
    {
        $banks = BankAccount::all()->map(function($bank) {
            
            $masuk = FinancialTransaction::where('bank_name', $bank->bank_name) 
                        ->where('type', 'pemasukan')
                        ->sum('amount');
            
            $keluar = FinancialTransaction::where('bank_name', $bank->bank_name)
                        ->where('type', 'pengeluaran')
                        ->sum('amount');
            
            $bank->saldo_saat_ini = $masuk - $keluar;
            
            return $bank;
        });
        $selectedBank = $request->input('bank', 'global');

        $query = FinancialTransaction::query();

        if ($selectedBank != 'global') {
            $query->where('bank_name', $selectedBank);
        }

        $totalPemasukan = (clone $query)->where('type', 'pemasukan')->sum('amount');
        $totalPengeluaran = (clone $query)->where('type', 'pengeluaran')->sum('amount');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;
        
        $currentYear = date('Y');
        
        $incomeData = (clone $query)->select(
                DB::raw('EXTRACT(MONTH FROM transaction_date) as month'), 
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('transaction_date', $currentYear)
            ->where('type', 'pemasukan')
            ->groupBy(DB::raw('EXTRACT(MONTH FROM transaction_date)'))
            ->pluck('total', 'month')->toArray();

        $expenseData = (clone $query)->select(
                DB::raw('EXTRACT(MONTH FROM transaction_date) as month'), 
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('transaction_date', $currentYear)
            ->where('type', 'pengeluaran')
            ->groupBy(DB::raw('EXTRACT(MONTH FROM transaction_date)'))
            ->pluck('total', 'month')->toArray();

        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartIncome = [];
        $chartExpense = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartIncome[] = $incomeData[$i] ?? 0;
            $chartExpense[] = $expenseData[$i] ?? 0;
        }

        $transactions = (clone $query)
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);

        // Kirim variable $selectedBank ke view agar dropdown tidak reset
        return view('client.keuangan.index', compact(
            'transactions', 
            'totalPemasukan', 
            'totalPengeluaran', 
            'saldoAkhir',
            'chartLabels',
            'chartIncome',
            'chartExpense',
            'selectedBank',
            'banks' 
        ));
    }
}