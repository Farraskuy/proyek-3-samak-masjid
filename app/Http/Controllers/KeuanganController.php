<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KeuanganController extends Controller
{
    public function index()
    {
        $transactions = FinancialTransaction::orderBy('transaction_date', 'desc')->paginate(10);

        $totalPemasukan = FinancialTransaction::where('type', 'pemasukan')->sum('amount');
        $totalPengeluaran = FinancialTransaction::where('type', 'pengeluaran')->sum('amount');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        $currentYear = date('Y');
        
        $incomeData = FinancialTransaction::select(
                DB::raw('EXTRACT(MONTH FROM transaction_date) as month'), 
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('transaction_date', $currentYear)
            ->where('type', 'pemasukan')
            ->groupBy(DB::raw('EXTRACT(MONTH FROM transaction_date)'))
            ->pluck('total', 'month')->toArray();

        $expenseData = FinancialTransaction::select(
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

        return view('admin.keuangan.index', compact(
            'transactions', 
            'totalPemasukan', 
            'totalPengeluaran', 
            'saldoAkhir',
            'chartLabels',
            'chartIncome',
            'chartExpense'
        ));
    }

    public function store(Request $request)
    {
        $totalPemasukan = FinancialTransaction::where('type', 'pemasukan')->sum('amount');
        $totalPengeluaran = FinancialTransaction::where('type', 'pengeluaran')->sum('amount');
        $saldoSaatIni = $totalPemasukan - $totalPengeluaran;

        $rules = [
            'type' => 'required|in:pemasukan,pengeluaran',
            'category' => 'required|string',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:1000', 
        ];

        if ($request->type == 'pengeluaran') {
            $rules['amount'] .= '|lte:' . $saldoSaatIni;
        }

        $request->validate($rules, [
            'amount.lte' => 'Saldo tidak mencukupi untuk pengeluaran ini. Sisa saldo: Rp ' . number_format($saldoSaatIni, '2', ',', '.'),
        ]);

        FinancialTransaction::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'category' => $request->category,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil dicatat.');
    }

    public function destroy($id)
    {
        FinancialTransaction::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Transaksi dihapus.');
    }

    public function clientIndex()
    {
        $transactions = FinancialTransaction::orderBy('transaction_date', 'desc')->paginate(15);

        $totalPemasukan = FinancialTransaction::where('type', 'pemasukan')->sum('amount');
        $totalPengeluaran = FinancialTransaction::where('type', 'pengeluaran')->sum('amount');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        $currentYear = date('Y');
        
        $incomeData = FinancialTransaction::select(
                DB::raw('EXTRACT(MONTH FROM transaction_date) as month'), 
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('transaction_date', $currentYear)
            ->where('type', 'pemasukan')
            ->groupBy(DB::raw('EXTRACT(MONTH FROM transaction_date)'))
            ->pluck('total', 'month')->toArray();

        $expenseData = FinancialTransaction::select(
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

        return view('client.keuangan.index', compact(
            'transactions', 
            'totalPemasukan', 
            'totalPengeluaran', 
            'saldoAkhir',
            'chartLabels',
            'chartIncome',
            'chartExpense'
        ));
    }
}