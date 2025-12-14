<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKegiatan;
use App\Models\FormResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PjDashboardController extends Controller
{
    /**
     * Display PJ dashboard with assigned events
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get events where this user is the PJ with related forms
        $events = JadwalKegiatan::where('pj_user_id', $user->id)
            ->with(['registrationForm.responses', 'closingForm'])
            ->orderBy('start_time', 'desc')
            ->get();
        
        return view('admin.pj-dashboard.index', compact('events'));
    }


    /**
     * View registrants for a specific event
     */
    public function registrants($eventId)
    {
        $user = Auth::user();
        
        $event = JadwalKegiatan::where('event_id', $eventId)
            ->where('pj_user_id', $user->id)
            ->with(['registrationForm.fields', 'registrationForm.responses.items'])
            ->firstOrFail();
        
        $registrants = collect([]);
        
        if ($event->registrationForm) {
            $registrants = FormResponse::where('form_id', $event->registrationForm->id)
                ->with('items')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('admin.pj-dashboard.registrants', compact('event', 'registrants'));
    }


    /**
     * Verify a registrant (update status)
     */
    public function verifyRegistrant(Request $request, $eventId, $responseId)
    {
        $user = Auth::user();
        
        // Verify PJ owns this event
        $event = JadwalKegiatan::where('event_id', $eventId)
            ->where('pj_user_id', $user->id)
            ->firstOrFail();
        
        $response = FormResponse::findOrFail($responseId);
        
        // Update verification status
        $response->update([
            'is_verified' => $request->boolean('is_verified'),
            'verified_at' => $request->boolean('is_verified') ? now() : null,
            'verified_by' => $request->boolean('is_verified') ? $user->id : null,
        ]);
        
        return back()->with('success', 'Status verifikasi berhasil diperbarui.');
    }

    /**
     * Toggle questionnaire availability
     */
    public function toggleQuestionnaire(Request $request, $eventId)
    {
        $user = Auth::user();
        
        $event = JadwalKegiatan::where('event_id', $eventId)
            ->where('pj_user_id', $user->id)
            ->firstOrFail();
        
        $event->update([
            'questionnaire_enabled' => $request->boolean('questionnaire_enabled')
        ]);
        
        $status = $request->boolean('questionnaire_enabled') ? 'dibuka' : 'ditutup';
        
        return back()->with('success', "Kuesioner berhasil {$status}.");
    }
    
    /**
     * Show detailed event control panel
     */
    public function show($eventId)
    {
        $user = Auth::user();
        
        $event = JadwalKegiatan::where('event_id', $eventId)
            ->where('pj_user_id', $user->id)
            ->with(['registrationForm.responses', 'closingForm.responses'])
            ->firstOrFail();
        
        $registrantCount = 0;
        if ($event->registrationForm) {
            $registrantCount = $event->registrationForm->responses->count();
        }
        
        return view('admin.pj-dashboard.show', compact('event', 'registrantCount'));
    }
    
    /**
     * Toggle registration form availability
     */
    public function toggleRegistration(Request $request, $eventId)
    {
        $user = Auth::user();
        
        $event = JadwalKegiatan::where('event_id', $eventId)
            ->where('pj_user_id', $user->id)
            ->firstOrFail();
        
        $event->update([
            'registration_enabled' => $request->boolean('registration_enabled')
        ]);
        
        $status = $request->boolean('registration_enabled') ? 'dibuka' : 'ditutup';
        
        return back()->with('success', "Pendaftaran berhasil {$status}.");
    }
    
    /**
     * Start event now (manual override)
     */
    public function startEvent(Request $request, $eventId)
    {
        $user = Auth::user();
        
        $event = JadwalKegiatan::where('event_id', $eventId)
            ->where('pj_user_id', $user->id)
            ->firstOrFail();
        
        $event->update([
            'event_started' => true,
            'start_time' => now() // Update start time to now
        ]);
        
        return back()->with('success', 'Kegiatan telah dimulai sekarang!');
    }
    
    /**
     * End event now (manual override)
     */
    public function endEvent(Request $request, $eventId)
    {
        $user = Auth::user();
        
        $event = JadwalKegiatan::where('event_id', $eventId)
            ->where('pj_user_id', $user->id)
            ->firstOrFail();
        
        $event->update([
            'end_time' => now()
        ]);
        
        return back()->with('success', 'Kegiatan telah diakhiri.');
    }

    /**
     * View closing form (questionnaire) responses for a specific event
     */
    public function closingResponses($eventId)
    {
        $user = Auth::user();
        
        $event = JadwalKegiatan::where('event_id', $eventId)
            ->where('pj_user_id', $user->id)
            ->with(['closingForm.fields', 'closingForm.responses.items'])
            ->firstOrFail();
        
        $responses = collect([]);
        
        if ($event->closingForm) {
            $responses = FormResponse::where('form_id', $event->closingForm->id)
                ->with('items')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('admin.pj-dashboard.closing-responses', compact('event', 'responses'));
    }
}
