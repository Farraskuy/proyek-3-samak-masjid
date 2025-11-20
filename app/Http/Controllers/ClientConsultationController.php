<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClientConsultationController extends Controller
{
    /**
     * Display list of client consultations
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $status = $request->query('status', 'all');
        $keyword = $request->query('keyword', '');
        $perPage = $request->query('showing', 10);

        $query = Consultation::where('inputted_by_admin_id', $userId);

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('question_subject', 'like', "%{$keyword}%")
                  ->orWhere('question_text', 'like', "%{$keyword}%");
            });
        }

        $consultations = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $stats = [
            'total' => Consultation::where('inputted_by_admin_id', $userId)->count(),
            'pending' => Consultation::where('inputted_by_admin_id', $userId)->where('status', 'pending')->count(),
            'answered' => Consultation::where('inputted_by_admin_id', $userId)->where('status', 'answered')->count(),
            'closed' => Consultation::where('inputted_by_admin_id', $userId)->where('status', 'closed')->count(),
        ];

        return view('client.consultations.index', compact('consultations', 'stats', 'status', 'keyword'));
    }

    /**
     * Show create consultation form
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('client.consultations.create');
    }

    /**
     * Store new consultation
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        $validated = $request->validate([
            'question_subject' => 'required|string|max:255',
            'question_text' => 'required|string|min:10',
            'is_anonymous' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $consultation = Consultation::create([
                'question_subject' => $validated['question_subject'],
                'question_text' => $validated['question_text'],
                'question_from' => Auth::user()->full_name ?? 'Pengguna',
                'is_anonymous' => $validated['is_anonymous'] ?? false,
                'status' => 'pending',
                'inputted_by_admin_id' => Auth::id(),
            ]);

            DB::commit();

            // Create notification for ustadz
            $ustadzUsers = User::where('role', 'ustadz')->get();
            foreach ($ustadzUsers as $ustadz) {
                Notification::createNotification(
                    $ustadz->id,
                    'consultation_new',
                    'Pertanyaan Baru',
                    'Ada pertanyaan baru: ' . $validated['question_subject'],
                    route('konsultasi.show', $consultation->consultation_id),
                    $consultation->consultation_id,
                    Auth::id()
                );
            }

            return redirect()->route('client.consultations.index')
                ->with('success', 'Konsultasi Anda telah dikirim. Tunggu jawaban dari ustadz kami.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show consultation detail
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $consultation = Consultation::findOrFail($id);

        // Check if user is the owner or an admin/ustadz
        if ($consultation->inputted_by_admin_id !== Auth::id() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'ustadz') {
            abort(403, 'Unauthorized access');
        }

        $messages = $consultation->messages()->orderBy('created_at', 'asc')->get();

        // Mark messages as read
        $messages->where('user_id', '!=', Auth::id())->each(function ($message) {
            $message->markAsRead();
        });

        return view('client.consultations.show', compact('consultation', 'messages'));
    }

    /**
     * Send message in consultation
     */
    public function sendMessage(Request $request, $consultationId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $consultation = Consultation::findOrFail($consultationId);

        // Validate access
        if ($consultation->inputted_by_admin_id !== Auth::id() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'ustadz') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:5120', // 5MB max
        ]);

        DB::beginTransaction();
        try {
            $attachmentUrl = null;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = Storage::disk('public')->putFile('consultation-attachments', $file);
                $attachmentUrl = 'storage/' . $path;
            }

            $message = ConsultationMessage::create([
                'consultation_id' => $consultationId,
                'user_id' => Auth::id(),
                'message' => $validated['message'],
                'message_type' => $attachmentUrl ? 'file' : 'text',
                'attachment_url' => $attachmentUrl,
            ]);

            DB::commit();

            // Create notification
            $otherUser = $consultation->inputted_by_admin_id === Auth::id()
                ? $consultation->answerer
                : $consultation->inputter;

            if ($otherUser) {
                Notification::createNotification(
                    $otherUser->id,
                    'consultation_message',
                    'Pesan Baru',
                    'Ada pesan baru di konsultasi: ' . $consultation->question_subject,
                    route('client.consultations.show', $consultationId),
                    $consultationId,
                    Auth::id()
                );
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get messages for consultation (for chat view)
     */
    public function getMessages($consultationId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $consultation = Consultation::findOrFail($consultationId);

        if ($consultation->inputted_by_admin_id !== Auth::id() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'ustadz') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $consultation->messages()
            ->with('user:id,full_name,image_url')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->full_name,
                        'image' => $message->user->image_url,
                    ],
                    'message' => $message->message,
                    'attachment' => $message->attachment_url,
                    'type' => $message->message_type,
                    'is_own' => $message->user_id === Auth::id(),
                    'created_at' => $message->created_at->format('H:i'),
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    /**
     * Close consultation
     */
    public function close($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $consultation = Consultation::findOrFail($id);

        if ($consultation->inputted_by_admin_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (in_array($consultation->status, ['closed', 'rejected'])) {
            return back()->with('error', 'Konsultasi tidak dapat ditutup lagi');
        }

        DB::beginTransaction();
        try {
            $consultation->update(['status' => 'closed', 'closed_at' => now()]);
            DB::commit();

            return back()->with('success', 'Konsultasi berhasil ditutup');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete consultation (only if pending)
     */
    public function delete($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $consultation = Consultation::findOrFail($id);

        if ($consultation->inputted_by_admin_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($consultation->status !== 'pending') {
            return back()->with('error', 'Hanya konsultasi yang belum dijawab yang dapat dihapus');
        }

        DB::beginTransaction();
        try {
            $consultation->delete();
            DB::commit();

            return redirect()->route('client.consultations.index')
                ->with('success', 'Konsultasi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

