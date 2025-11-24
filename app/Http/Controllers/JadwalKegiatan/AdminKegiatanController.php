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
        // Ambil daftar ustadz dari tabel users (misalnya role 'ustadz')
        $ustadz = \App\Models\User::where('role', 'ustadz')->get();

        return view('admin.kegiatan.create', compact('ustadz'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:200',
            'theme' => 'nullable|string|max:255',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'location' => 'required|string|max:100',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'daftar_tamu.*' => 'nullable|string|max:255',
        ]);

        $posterPath = null;

        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        // Simpan event
        $event = JadwalKegiatan::create([
            'event_name' => $request->event_name,
            'theme' => $request->theme,
            'poster' => $posterPath,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_have_tamu_undangan' => ($request->daftar_tamu != null),
            'created_by' => auth()->id(),
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

        return redirect()->route('admin.kegiatan')
            ->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $event  = JadwalKegiatan::with('tamuUndangan')->findOrFail($id);
        $ustadz = \App\Models\User::where('role', 'ustadz')->get();

        return view('admin.kegiatan.edit', compact('event', 'ustadz'));
    }

    public function update(Request $request, $id)
    {
        $event = JadwalKegiatan::findOrFail($id);

        $validated = $request->validate([
            'event_name' => 'required|string|max:200',
            'theme'      => 'nullable|string|max:255',
            'poster'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'location'   => 'required|string|max:100',
            'start_time' => 'required|date',
            'end_time'   => 'required|date',
            'daftar_tamu.*' => 'nullable|string|max:255',
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

        // Update data utama
        $event->update([
            'event_name' => $request->event_name,
            'theme'      => $request->theme,
            'location'   => $request->location,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'is_have_tamu_undangan' => !empty(array_filter($request->daftar_tamu ?? [])),
        ]);

        // Hapus tamu lama, lalu tambah yang baru
        $event->tamuUndangan()->delete();

        if (!empty($request->daftar_tamu)) {
            foreach (array_filter($request->daftar_tamu) as $tamu) {
                $event->tamuUndangan()->create(['nama_tamu' => trim($tamu)]);
            }
        }

        return redirect()->route('admin.kegiatan')
            ->with('success', 'Kegiatan berhasil diperbarui!');
    }

    public function destroy($id)
{
    $event = JadwalKegiatan::findOrFail($id);

    if ($event->poster && \Storage::disk('public')->exists($event->poster)) {
        \Storage::disk('public')->delete($event->poster);
    }

    $event->tamuUndangan()->delete();
    $event->delete();

    return redirect()
        ->route('admin.kegiatan')
        ->with('success', 'Kegiatan berhasil dihapus!');
}


}
