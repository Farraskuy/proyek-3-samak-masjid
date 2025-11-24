<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConsultationUstadzController extends Controller
{
    /**
     * Ustadz Dashboard / Index
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = Consultation::query();

        if (Auth::user()->role === 'ustadz') {
            // Ustadz sees pending (available to take) and their own active/closed
            $query->where(function($q) {
                $q->where('status', 'pending')
                  ->orWhere('answered_by_ustadz_id', Auth::id());
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $consultations = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats
        $stats = [
            'pending' => Consultation::where('status', 'pending')->count(),
            'active' => Consultation::where('status', 'active')->where('answered_by_ustadz_id', Auth::id())->count(),
            'closed' => Consultation::where('status', 'closed')->where('answered_by_ustadz_id', Auth::id())->count(),
        ];

        return view('admin.consultations.index', compact('consultations', 'stats', 'status'));
    }

    /**
     * Show consultation detail/chat
     */
    public function show($id)
    {
        $consultation = Consultation::findOrFail($id);
        
        // Authorization check
        if (Auth::user()->role === 'ustadz' && 
            $consultation->status !== 'pending' && 
            $consultation->answered_by_ustadz_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $messages = $consultation->messages()->with('user')->orderBy('created_at', 'asc')->get();

        return view('admin.consultations.show', compact('consultation', 'messages'));
    }

    /**
     * Accept Consultation
     */
    public function accept($id)
    {
        $consultation = Consultation::findOrFail($id);

        if ($consultation->status !== 'pending') {
            return back()->with('error', 'Konsultasi tidak tersedia');
        }

        // Check limit (max 5 active)
        $activeCount = Consultation::where('answered_by_ustadz_id', Auth::id())
            ->where('status', 'active')
            ->count();

        if ($activeCount >= 5) {
            return back()->with('error', 'Anda sudah menangani 5 konsultasi aktif. Selesaikan terlebih dahulu.');
        }

        $consultation->update([
            'status' => 'active',
            'answered_by_ustadz_id' => Auth::id(),
            'answered_at' => now(),
        ]);

        return back()->with('success', 'Konsultasi diterima. Silakan mulai chat.');
    }

    /**
     * Reject Consultation
     */
    public function reject(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);
        
        $request->validate(['reason' => 'required|string']);

        $consultation->update([
            'status' => 'rejected',
            'answered_by_ustadz_id' => Auth::id(),
            'rejection_reason' => $request->reason,
            'closed_at' => now(),
        ]);

        return back()->with('success', 'Konsultasi ditolak.');
    }

    /**
     * Close Consultation
     */
    public function close(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        if ($consultation->answered_by_ustadz_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['conclusion' => 'required|string']);

        $consultation->update([
            'status' => 'closed',
            'conclusion' => $request->conclusion,
            'closed_at' => now(),
        ]);

        return back()->with('success', 'Konsultasi ditutup.');
    }
}
