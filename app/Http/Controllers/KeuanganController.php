<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('showing', 50);
        if ($request->query('showing') === 'all') {
            $perPage = 1000;
        }

        $keyword = $request->query('keyword', '');
        $sortField = $request->query('sorted_by', 'bank_name');
        $sortOrder = $request->query('ordered_by', 'asc');

        $query = BankAccount::query();
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('bank_name', 'like', "%$keyword%")
                    ->orWhere('account_number', 'like', "%$keyword%")
                    ->orWhere('account_holder_name', 'like', "%$keyword%");
            });
        }

        $data = $query->orderBy($sortField, $sortOrder)->paginate($perPage)->withQueryString();
        return view('admin.keuangan.index', ['data' => $data]);
    }
}
