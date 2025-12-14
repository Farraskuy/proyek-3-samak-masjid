<?php

namespace App\Http\Controllers\JadwalKegiatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalKegiatan;
use App\Models\Form;
use App\Models\FormResponse;
use App\Models\FormResponseItem;
use Illuminate\Support\Facades\DB;

class JadwalKegiatanController extends Controller
{
    public function index()
    {
        $events = JadwalKegiatan::orderBy('start_time', 'desc')->paginate(10);

        $today = date('Y-m-d');

        $todayEvent = JadwalKegiatan::whereDate('start_time', $today)->first();

        return view('client.jadwalKegiatan.jadwal', compact('events', 'todayEvent'));
    }


    public function getData()
    {
        $data = JadwalKegiatan::all()->map(function ($event) {
            return [
                'title' => $event->event_name,
                'start' => $event->start_time,
                'end' => $event->end_time,
                'description' => $event->theme,
                'location' => $event->location,
            ];
        });

        return response()->json($data);
    }

    public function show($id)
    {
        $event = \App\Models\JadwalKegiatan::with(['creator', 'tamuUndangan', 'registrationForm.fields', 'closingForm.fields', 'registrationForm', 'closingForm'])->findOrFail($id);
        
        // Check if event has ended (form should be closed)
        $eventEnded = now()->gt($event->end_time);
        
        // Check if current IP has already registered
        $hasRegistered = false;
        if ($event->has_registration_form && $event->registration_form_id) {
            $hasRegistered = \App\Models\FormResponse::where('form_id', $event->registration_form_id)
                ->where('event_id', $event->event_id)
                ->where('ip_address', request()->ip())
                ->exists();
        }

        return view('client.jadwalKegiatan.detail', compact('event', 'eventEnded', 'hasRegistered'));
    }

    /**
     * Handle event registration form submission
     */
    public function register(Request $request, $eventId)
    {
        $event = JadwalKegiatan::with('registrationForm.fields')->findOrFail($eventId);

        // Check if event has ended
        if (now()->gt($event->end_time)) {
            return redirect()->back()->with('error', 'Pendaftaran sudah ditutup karena kegiatan telah selesai.');
        }

        // Check if form exists
        if (!$event->has_registration_form || !$event->registrationForm) {
            return redirect()->back()->with('error', 'Formulir pendaftaran tidak tersedia.');
        }

        // Check if already registered
        $alreadyRegistered = FormResponse::where('form_id', $event->registration_form_id)
            ->where('event_id', $event->event_id)
            ->where('ip_address', $request->ip())
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar untuk kegiatan ini.');
        }

        // Build validation rules from form fields
        $fields = $event->registrationForm->fields;
        $rules = [];
        
        foreach ($fields as $field) {
            $fieldRules = [];
            if ($field->is_required) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }
            
            // Add type-specific validation
            if ($field->type === 'email') {
                $fieldRules[] = 'email';
            } elseif ($field->type === 'number') {
                $fieldRules[] = 'numeric';
            }
            
            $rules[$field->name] = implode('|', $fieldRules);
        }

        $request->validate($rules);

        // Save response
        DB::beginTransaction();
        try {
            $response = FormResponse::create([
                'form_id' => $event->registration_form_id,
                'event_id' => $event->event_id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Save each field value
            foreach ($fields as $field) {
                $value = $request->input($field->name);
                FormResponseItem::create([
                    'response_id' => $response->id,
                    'field_name' => $field->name,
                    'field_label' => $field->label,
                    'value' => is_array($value) ? json_encode($value) : $value,
                ]);
            }

            DB::commit();
            
            return redirect()->back()->with('success', 'Terima kasih! Pendaftaran Anda berhasil.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Event registration failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        }
    }

    /**
     * Show registration history based on IP address
     */
    public function registrationHistory(Request $request)
    {
        $ipAddress = $request->ip();
        
        // Get all form responses from this IP that are linked to events
        $registrations = FormResponse::with(['form', 'items'])
            ->where('ip_address', $ipAddress)
            ->whereNotNull('event_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get event details for each registration
        $registrations = $registrations->map(function ($response) {
            $event = JadwalKegiatan::find($response->event_id);
            $response->event = $event;
            return $response;
        })->filter(function ($response) {
            return $response->event !== null;
        });

        return view('client.jadwalKegiatan.history', compact('registrations'));
    }


    
    public function getEventByDate(Request $request)
    {
        // Pastikan ambil parameter 'date' dari query string
        $date = $request->query('date');

        if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return response()->json([
                'success' => false,
                'message' => 'Format tanggal tidak valid'
            ], 400);
        }

        try {
            $event = \App\Models\JadwalKegiatan::with('tamuUndangan')
                        ->whereDate('start_time', $date)
                        ->first();

            $html = view('client.jadwalKegiatan.today', [
                'event' => $event,
                'selectedDate' => $date
            ])->render();

            return response()->json([
                'success' => true,
                'html'    => $html
            ]);

        } catch (\Throwable $e) {
            \Log::error('Error getEventByDate: '.$e->getMessage(), [
                'date' => $date,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }
}

