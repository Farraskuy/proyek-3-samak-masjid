<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DonationConfirmation;
use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ManagementController extends Controller
{
    protected function perPage(Request $request)
    {
        $showing = $request->query('showing', 50);
        if ($showing === 'all') {
            return 1000;
        }
        $n = (int) $showing;
        return $n > 0 ? $n : 50;
    }

    protected function emptyPaginator(Request $request, $perPage)
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        return new LengthAwarePaginator([], 0, $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }

    public function galeri(Request $request)
    {
        $perPage = $this->perPage($request);
        $data = $this->emptyPaginator($request, $perPage);
        return view('admin.galeri.index', ['data' => $data]);
    }

    public function kegiatan(Request $request)
    {
        $perPage = $this->perPage($request);
        $data = $this->emptyPaginator($request, $perPage);
        return view('admin.kegiatan.index', ['data' => $data]);
    }

    public function donasi(Request $request)
    {
        $perPage = $this->perPage($request);
        $keyword = $request->query('keyword', '');
        $sortField = $request->query('sorted_by', 'transfer_date');
        $sortOrder = $request->query('ordered_by', 'desc');

        $query = DonationConfirmation::with('user');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('source_bank', 'like', "%$keyword%")
                    ->orWhere('amount', 'like', "%$keyword%")
                    ->orWhere('notes', 'like', "%$keyword%")
                    ->orWhereHas('user', function ($uq) use ($keyword) {
                        $uq->where('full_name', 'like', "%$keyword%");
                    });
            });
        }

        $data = $query->orderBy($sortField, $sortOrder)->paginate($perPage)->withQueryString();
        return view('admin.donasi.index', ['data' => $data]);
    }

    public function keuangan(Request $request)
    {
        $perPage = $this->perPage($request);
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

    public function kajian(Request $request)
    {
        $perPage = $this->perPage($request);
        $data = $this->emptyPaginator($request, $perPage);
        return view('admin.kajian.index', ['data' => $data]);
    }

    public function pengguna(Request $request)
    {
        $perPage = $this->perPage($request);
        $keyword = $request->query('keyword', '');
        $sortField = $request->query('sorted_by', 'full_name');
        $sortOrder = $request->query('ordered_by', 'asc');

        $query = User::query();
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
                    ->orWhere('username', 'like', "%$keyword%");
            });
        }

        $data = $query->orderBy($sortField, $sortOrder)->paginate($perPage)->withQueryString();
        return view('admin.pengguna.index', ['data' => $data]);
    }

    public function konsultasi(Request $request)
    {
        $perPage = $this->perPage($request);
        $data = $this->emptyPaginator($request, $perPage);
        return view('admin.konsultasi.index', ['data' => $data]);
    }
}
