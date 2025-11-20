<?php

namespace App\Http\Controllers\Donasi\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonationConfirmation;
use Illuminate\Support\Facades\Auth;

class DonationConfirmationController extends Controller
{
    public function index(Request $request)
    {
        $query = DonationConfirmation::with(['user', 'destinationAccount']);

        // Filter
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('source_bank', 'like', "%$keyword%")
                  ->orWhere('amount', 'like', "%$keyword%")
                  ->orWhereHas('user', function($u) use ($keyword) {
                      $u->where('name', 'like', "%$keyword%"); 
                  });
            });
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

        return view('admin.donasi.index', compact('data'));
    }

    public function approve($id)
    {
        $donasi = DonationConfirmation::findOrFail($id);

        $donasi->update([
            'status' => 'Verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Donasi berhasil diverifikasi/diterima.');
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