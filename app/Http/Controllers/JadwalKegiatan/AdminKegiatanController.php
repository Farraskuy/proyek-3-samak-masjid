<?php

namespace App\Http\Controllers\JadwalKegiatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalKegiatan;

class AdminKegiatanController extends Controller
{
    //Tampilkan daftar kegiatan (khusus admin)
    public function index()
    {
        $events = JadwalKegiatan::orderBy('start_time', 'asc')->get();

        return view('admin.kegiatan.index', compact('events'));
    }

    public function create()
    {
        // Ambil daftar ustadz dari tabel users
        $ustadz = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'ustadz');
        })->get();
        // Ambil daftar form dengan jumlah pertanyaan
        $forms = \App\Models\Form::withCount('fields')->get();

        return view('admin.kegiatan.create', compact('ustadz', 'forms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:200',
            'theme' => 'nullable|string|max:255',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'location' => 'required|string|max:100',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'daftar_tamu.*' => 'nullable|string|max:255',
            'has_registration_form' => 'nullable|boolean',
            'registration_form_id' => 'nullable|exists:forms,id',
            'has_closing_form' => 'nullable|boolean',
            'closing_form_id' => 'nullable|exists:forms,id',
            'has_pj' => 'nullable|boolean',
        ]);

        $posterPath = null;

        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        // Handle PJ Creation
        $pjUserId = null;
        $pjCredentials = null;

        if ($request->has('has_pj') && $request->has_pj == '1') {
            $password = \Illuminate\Support\Str::random(8); // Generate random password
            $email = 'pj.' . \Illuminate\Support\Str::slug($request->event_name) . '.' . rand(100, 999) . '@samak.com';

            $pjUser = \App\Models\User::create([
                'name' => 'PJ - ' . $request->event_name,
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'role' => 'penanggung_jawab',
                'email_verified_at' => now(),
            ]);

            $pjUserId = $pjUser->id;
            $pjCredentials = [
                'email' => $email,
                'password' => $password
            ];
        }

        // Simpan event
        $event = JadwalKegiatan::create([
            'event_name' => $request->event_name,
            'theme' => $request->theme,
            'poster' => $posterPath,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_have_tamu_undangan' => !empty(array_filter($request->daftar_tamu ?? [])),
            'created_by' => auth()->id(),
            'has_registration_form' => $request->has('has_registration_form'),
            'registration_form_id' => $request->registration_form_id,
            'has_closing_form' => $request->has('has_closing_form'),
            'closing_form_id' => $request->closing_form_id,
            'has_pj' => $request->has('has_pj'),
            'pj_user_id' => $pjUserId,
        ]);

        // DEBUG: tampilkan apa yang diterima request
        \Log::info('DAFTAR TAMU INPUT = ', $request->daftar_tamu ?? []);
        \Log::info('EVENT ID = ' . $event->event_id);

        // Simpan tamu undangan
        if (!empty($request->daftar_tamu)) {
            foreach ($request->daftar_tamu as $tamu) {
                if (!empty(trim($tamu))) {
                    \App\Models\EventTamu::create([
                        'event_id' => $event->event_id,
                        'nama_tamu' => $tamu,
                    ]);
                }
            }
        }

        $redirect = redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan!');

        if ($pjCredentials) {
            $redirect->with('pj_credentials', $pjCredentials);
        }

        return $redirect;
    }

    public function edit($id)
    {
        $event = JadwalKegiatan::with(['tamuUndangan', 'pjUser'])->findOrFail($id);
        $ustadz = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'ustadz');
        })->get();
        $forms = \App\Models\Form::withCount('fields')->get();

        return view('admin.kegiatan.edit', compact('event', 'ustadz', 'forms'));
    }

    public function update(Request $request, $id)
    {
        $event = JadwalKegiatan::findOrFail($id);

        $request->validate([
            'event_name' => 'required|string|max:200',
            'theme' => 'nullable|string|max:255',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'location' => 'required|string|max:100',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'daftar_tamu.*' => 'nullable|string|max:255',
            'has_registration_form' => 'nullable|boolean',
            'registration_form_id' => 'nullable|exists:forms,id',
            'has_closing_form' => 'nullable|boolean',
            'closing_form_id' => 'nullable|exists:forms,id',
            'has_pj' => 'nullable|boolean',
        ]);

        // Handle poster
        if ($request->has('hapus_poster') && $event->poster) {
            if (\Storage::disk('public')->exists($event->poster)) {
                \Storage::disk('public')->delete($event->poster);
            }
            $event->poster = null;
        }

        if ($request->hasFile('poster')) {
            // Hapus poster lama kalau ada
            if ($event->poster) {
                \Storage::disk('public')->delete($event->poster);
            }
            $posterPath = $request->file('poster')->store('posters', 'public');
            $event->poster = $posterPath;
        }

        // Handle PJ Creation (Only if not already exists and toggled on)
        $pjUserId = $event->pj_user_id;
        $pjCredentials = null;

        if ($request->has('has_pj') && $request->has_pj == '1' && !$event->has_pj) {
            $password = \Illuminate\Support\Str::random(8);
            $email = 'pj.' . \Illuminate\Support\Str::slug($request->event_name) . '.' . rand(100, 999) . '@samak.com';

            $pjUser = \App\Models\User::create([
                'name' => 'PJ - ' . $request->event_name,
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'role' => 'penanggung_jawab',
                'email_verified_at' => now(),
            ]);

            $pjUserId = $pjUser->id;
            $pjCredentials = [
                'email' => $email,
                'password' => $password
            ];
        }

        // Update data utama
        $event->update([
            'event_name' => $request->event_name,
            'theme' => $request->theme,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_have_tamu_undangan' => !empty(array_filter($request->daftar_tamu ?? [])),
            'has_registration_form' => $request->has('has_registration_form'),
            'registration_form_id' => $request->registration_form_id,
            'has_closing_form' => $request->has('has_closing_form'),
            'closing_form_id' => $request->closing_form_id,
            'has_pj' => $request->has('has_pj'),
            'pj_user_id' => $pjUserId,
        ]);

        // Hapus tamu lama, lalu tambah yang baru
        $event->tamuUndangan()->delete();

        if (!empty($request->daftar_tamu)) {
            foreach (array_filter($request->daftar_tamu) as $tamu) {
                $event->tamuUndangan()->create(['nama_tamu' => trim($tamu)]);
            }
        }

        $redirect = redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui!');

        if ($pjCredentials) {
            $redirect->with('pj_credentials', $pjCredentials);
        }

        return $redirect;
    }

    public function destroy($id)
    {
        $event = JadwalKegiatan::findOrFail($id);

        if ($event->poster && \Storage::disk('public')->exists($event->poster)) {
            \Storage::disk('public')->delete($event->poster);
        }

        // Foreign key set null on delete handles PJ user unlinking

        $event->tamuUndangan()->delete();
        $event->delete();

        return redirect()
            ->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus!');
    }
}
