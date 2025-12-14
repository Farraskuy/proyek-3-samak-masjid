<?php

namespace App\Http\Controllers\JadwalKegiatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalKegiatan;

class AdminKegiatanController extends Controller
{
    //Tampilkan daftar kegiatan (khusus admin)
    public function index(Request $request)
    {
        $query = JadwalKegiatan::query();
        
        // Search functionality
        if ($request->filled('keyword')) {
            $keyword = '%' . $request->keyword . '%';
            $query->where(function($q) use ($keyword) {
                $q->where('event_name', 'ilike', $keyword)
                  ->orWhere('location', 'ilike', $keyword)
                  ->orWhere('theme', 'ilike', $keyword);
            });
        }
        
        // Sorting - ensure valid column
        $sortBy = $request->filled('sorted_by') ? $request->sorted_by : 'start_time';
        $orderBy = $request->get('ordered_by', 'asc');
        
        // Validate sort column to prevent SQL injection
        $allowedColumns = ['event_name', 'location', 'start_time', 'end_time'];
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'start_time';
        }
        
        $query->orderBy($sortBy, $orderBy);
        
        // Pagination
        $perPage = $request->get('showing', 50);
        $data = $perPage === 'all' ? $query->get() : $query->paginate($perPage)->appends($request->except('page'));

        return view('admin.kegiatan.index', compact('data'));
    }

    public function create()
    {
        // Ambil daftar ustadz dari tabel users
        $ustadz = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'ustadz');
        })->get();
        
        // Ambil ID form yang sudah digunakan oleh kegiatan lain
        $usedFormIds = JadwalKegiatan::whereNotNull('registration_form_id')
            ->pluck('registration_form_id')
            ->merge(
                JadwalKegiatan::whereNotNull('closing_form_id')->pluck('closing_form_id')
            )
            ->unique()
            ->toArray();
        
        // Ambil daftar form yang belum digunakan
        $forms = \App\Models\Form::withCount('fields')
            ->whereNotIn('id', $usedFormIds)
            ->get();

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
            $slug = \Illuminate\Support\Str::slug($request->event_name);
            $email = 'pj.' . $slug . '.' . rand(100, 999) . '@samak.com';
            $username = 'pj_' . $slug . '_' . rand(100, 999);

            // Get Penanggung Jawab role ID
            $pjRole = \App\Models\Role::where('name', 'Penanggung Jawab')->first();
            $roleId = $pjRole ? $pjRole->id : 6; // Fallback to Jamaah (id=6) if not found

            $pjUser = \App\Models\User::create([
                'username' => $username,
                'full_name' => 'PJ - ' . $request->event_name,
                'email' => $email,
                'phone_number' => '000000000000',
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'role_id' => $roleId,
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
            'theme' => $request->theme ?? '',
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
        
        // Check if event has ended (form cannot be changed)
        $eventEnded = now()->gt($event->end_time);
        
        // Ambil ID form yang sudah digunakan oleh kegiatan LAIN (exclude current event's forms)
        $usedFormIds = JadwalKegiatan::where('event_id', '!=', $id)
            ->whereNotNull('registration_form_id')
            ->pluck('registration_form_id')
            ->merge(
                JadwalKegiatan::where('event_id', '!=', $id)
                    ->whereNotNull('closing_form_id')
                    ->pluck('closing_form_id')
            )
            ->unique()
            ->toArray();
        
        // Ambil daftar form yang belum digunakan (plus forms already used by this event)
        $forms = \App\Models\Form::withCount('fields')
            ->where(function ($query) use ($usedFormIds, $event) {
                $query->whereNotIn('id', $usedFormIds)
                      ->orWhere('id', $event->registration_form_id)
                      ->orWhere('id', $event->closing_form_id);
            })
            ->get();

        return view('admin.kegiatan.edit', compact('event', 'ustadz', 'forms', 'eventEnded'));
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

        // Handle PJ User
        $pjUserId = $event->pj_user_id;
        $pjCredentials = null;

        // Case 1: PJ is being removed (was on, now off)
        if (!$request->has('has_pj') && $event->has_pj && $event->pj_user_id) {
            // Delete the PJ user account completely
            $pjUser = \App\Models\User::find($event->pj_user_id);
            if ($pjUser) {
                $pjUser->delete();
            }
            $pjUserId = null;
        }
        // Case 2: PJ is being added (was off, now on) OR was deleted and re-adding
        elseif ($request->has('has_pj') && $request->has_pj == '1' && (!$event->has_pj || !$event->pj_user_id)) {
            $password = \Illuminate\Support\Str::random(8);
            $slug = \Illuminate\Support\Str::slug($request->event_name);
            $email = 'pj.' . $slug . '.' . rand(100, 999) . '@samak.com';
            $username = 'pj_' . $slug . '_' . rand(100, 999);

            // Get Penanggung Jawab role ID
            $pjRole = \App\Models\Role::where('name', 'Penanggung Jawab')->first();
            $roleId = $pjRole ? $pjRole->id : 6; // Fallback to Jamaah (id=6) if not found

            $pjUser = \App\Models\User::create([
                'username' => $username,
                'full_name' => 'PJ - ' . $request->event_name,
                'email' => $email,
                'phone_number' => '000000000000',
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'role_id' => $roleId,
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
            'theme' => $request->theme ?? '',
            'location' => $request->location,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_have_tamu_undangan' => !empty(array_filter($request->daftar_tamu ?? [])),
            'has_registration_form' => $request->has('has_registration_form'),
            'registration_form_id' => $request->has('has_registration_form') ? $request->registration_form_id : null,
            'has_closing_form' => $request->has('has_closing_form'),
            'closing_form_id' => $request->has('has_closing_form') ? $request->closing_form_id : null,
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
