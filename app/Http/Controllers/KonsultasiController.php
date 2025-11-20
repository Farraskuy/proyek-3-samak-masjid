<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
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

    /**
     * Admin: Display consultation index with double sidebar
     */
    public function index(Request $request)
    {
        $perPage = $this->perPage($request);
        $keyword = $request->query('keyword', '');
        $status = $request->query('status', 'all'); // all, pending, answered, rejected

        $query = Consultation::query();

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search filter
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('question_subject', 'like', "%{$keyword}%")
                  ->orWhere('question_text', 'like', "%{$keyword}%")
                  ->orWhere('question_from', 'like', "%{$keyword}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        if ($perPage === 1000) {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage)->withQueryString();
        }

        // Get consultation list for sidebar
        $consultations = Consultation::orderBy('created_at', 'desc')->get();

        return view('admin.konsultasi.index', [
            'data' => $data,
            'consultations' => $consultations,
            'currentStatus' => $status,
            'keyword' => $keyword
        ]);
    }

    /**
     * Admin: Show detail consultation form
     */
    public function show($id)
    {
        $consultation = Consultation::findOrFail($id);
        return view('admin.konsultasi.show', compact('consultation'));
    }

    /**
     * Admin: Answer consultation
     */
    public function answer(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

            // Pembatasan ustadz hanya bisa menanggapi maksimal 5 konsultasi aktif
            $ustadzId = Auth::id();
            $activeCount = Consultation::whereIn('status', ['in_progress', 'answered'])
                ->where('answered_by_ustadz_id', $ustadzId)
                ->count();
            if ($activeCount >= 5) {
                // Komentar: Ustadz sudah menanggapi 5 konsultasi aktif, tidak bisa menjawab lagi
                return redirect()->back()->with('error', 'Anda hanya dapat menanggapi maksimal 5 konsultasi aktif sekaligus. Selesaikan konsultasi yang sedang berjalan sebelum menjawab yang baru.');
            }

        $validated = $request->validate([
            'answer_text' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $consultation->update([
                'answer_text' => $validated['answer_text'],
                'status' => 'answered',
                'answered_by_ustadz_id' => Auth::id(),
                'answered_at' => now(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Konsultasi berhasil dijawab!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Reject consultation with reason
     */
    public function reject(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $consultation->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Konsultasi berhasil ditolak!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Complete/Close consultation with conclusion
     */
    public function close(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        $validated = $request->validate([
            'conclusion' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $consultation->update([
                'status' => 'closed',
                'conclusion' => $validated['conclusion'],
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Konsultasi berhasil ditutup!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Change consultation status
     */
    public function updateStatus(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,answered,closed,rejected',
        ]);

        DB::beginTransaction();
        try {
            $consultation->update(['status' => $validated['status']]);
            DB::commit();

            return redirect()->back()->with('success', 'Status konsultasi berhasil diubah!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Delete consultation
     */
    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);

        DB::beginTransaction();
        try {
            $consultation->delete();
            DB::commit();

            return redirect()->route('konsultasi')->with('success', 'Konsultasi berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get consultation stats for dashboard
     */
    public static function getStats()
    {
        return [
            'total' => Consultation::count(),
            'pending' => Consultation::where('status', 'pending')->count(),
            'in_progress' => Consultation::where('status', 'in_progress')->count(),
            'answered' => Consultation::where('status', 'answered')->count(),
            'closed' => Consultation::where('status', 'closed')->count(),
            'rejected' => Consultation::where('status', 'rejected')->count(),
        ];
    }
}
