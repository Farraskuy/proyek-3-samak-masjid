<?php

namespace App\Http\Controllers\JadwalKegiatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalKegiatan;

class JadwalKegiatanController extends Controller
{
    public function index()
    {
        $events = JadwalKegiatan::orderBy('start_time', 'desc')->get();

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
        $event = \App\Models\JadwalKegiatan::with(['creator', 'tamuUndangan'])->findOrFail($id);

        return view('client.jadwalKegiatan.detail', compact('event'));
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
