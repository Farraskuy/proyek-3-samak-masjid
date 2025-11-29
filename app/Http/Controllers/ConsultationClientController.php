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

class ConsultationClientController extends Controller
{
    /**
     * Public landing page for consultation
     */
    public function index()
    {
        return view('client.consultations.landing');
    }

    /**
     * Store new consultation (Must be authenticated)
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!Auth::user()->email_verified_at) {
            return response()->json(['error' => 'Email belum diverifikasi'], 403);
        }

        // Check if user has pending consultation
        $hasPending = Consultation::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($hasPending) {
            return response()->json(['error' => 'Anda masih memiliki konsultasi yang sedang berlangsung'], 422);
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
                'question_from' => Auth::user()->full_name,
                'is_anonymous' => $validated['is_anonymous'] ?? false,
                'status' => 'pending',
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            // Notify Ustadz
            $ustadzUsers = User::where('role', 'ustadz')->get();
            foreach ($ustadzUsers as $ustadz) {
                Notification::createNotification(
                    $ustadz->id,
                    'consultation_new',
                    'Pertanyaan Baru',
                    'Ada pertanyaan baru: ' . $validated['question_subject'],
                    route('admin.consultations.show', $consultation->id),
                    $consultation->id,
                    Auth::id()
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Konsultasi berhasil dikirim',
                'redirect' => route('client.consultations.show', $consultation->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show user's consultation history
     */
    /**
     * Show user's consultation history
     */
    public function history()
    {
        $consultations = Consultation::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.profile.consultation-history', compact('consultations'));
    }

    /**
     * Show create consultation form
     */
    public function create()
    {
        return view('client.profile.consultation-create');
    }

    /**
     * Show consultation chat/detail
     */
    public function show($id)
    {
        $consultation = Consultation::findOrFail($id);

        if ($consultation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $messages = $consultation->messages()->with('user')->orderBy('created_at', 'asc')->get();

        // Mark messages as read
        $consultation->messages()
            ->where('user_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Get history for sidebar
        if (request()->ajax()) {
            return view('components.chat-area', compact('consultation', 'messages'));
        }

        $conversations = Consultation::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.consultations.show', compact('consultation', 'messages', 'conversations'));
    }

    /**
     * Send message in chat
     */
    public function sendMessage(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        if ($consultation->user_id !== Auth::id()) {
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

            return response()->json(['success' => true, 'message' => $message->message,]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
