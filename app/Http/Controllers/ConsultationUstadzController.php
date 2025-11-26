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
            $query->where(function ($q) {
                $q->where('status', 'pending')
                    ->orWhere('answered_by_ustadz_id', Auth::id());
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $consultations = $query->orderBy('created_at', 'desc')->paginate(10);

        // Counts for Sidebar
        $counts = [
            'all' => Consultation::count(),
            'pending' => Consultation::where('status', 'pending')->count(),
            'active' => Consultation::where('status', 'active')->where('answered_by_ustadz_id', Auth::id())->count(),
            'closed' => Consultation::where('status', 'closed')->where('answered_by_ustadz_id', Auth::id())->count(),
        ];

        return view('admin.consultations.index', compact('consultations', 'status', 'counts'));
    }

    /**
     * Show consultation detail/chat
     */
    public function show($id)
    {
        $consultation = Consultation::findOrFail($id);

        // Authorization check
        if (Auth::user()->role === 'ustadz') {
            if ($consultation->status !== 'pending' && $consultation->answered_by_ustadz_id !== Auth::id()) {
                abort(403, 'Unauthorized');
            }
        }

        $messages = $consultation->messages()->with('user')->orderBy('created_at', 'asc')->get();

        if (request()->ajax()) {
            return view('admin.consultations.show_partial', compact('consultation', 'messages'));
        }

        // If not AJAX, we want to show the full page with this chat open.
        $request = request();
        $status = $request->query('status', 'all');
        $query = Consultation::query();

        if (Auth::user()->role === 'ustadz') {
            $query->where(function ($q) {
                $q->where('status', 'pending')
                    ->orWhere('answered_by_ustadz_id', Auth::id());
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $consultations = $query->orderBy('created_at', 'desc')->paginate(10);

        $counts = [
            'all' => Consultation::count(),
            'pending' => Consultation::where('status', 'pending')->count(),
            'active' => Consultation::where('status', 'active')->where('answered_by_ustadz_id', Auth::id())->count(),
            'closed' => Consultation::where('status', 'closed')->where('answered_by_ustadz_id', Auth::id())->count(),
        ];

        return view('admin.consultations.index', compact('consultations', 'status', 'counts', 'consultation', 'messages'));
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

    /**
     * Send message in chat (Ustadz)
     */
    public function sendMessage(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        if ($consultation->answered_by_ustadz_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($consultation->status !== 'active') {
            return response()->json(['error' => 'Konsultasi belum aktif atau sudah ditutup'], 422);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $attachmentUrl = null;
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('consultation-attachments', 'public');
                $attachmentUrl = 'storage/' . $path;
            }

            $message = ConsultationMessage::create([
                'consultation_id' => $id,
                'user_id' => Auth::id(),
                'message' => $validated['message'],
                'message_type' => $attachmentUrl ? 'file' : 'text',
                'attachment_url' => $attachmentUrl,
            ]);

            // Broadcast event (Reverb)
            event(new \App\Events\ConsultationMessageSent($message, Auth::user(), $id));

            DB::commit();

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
