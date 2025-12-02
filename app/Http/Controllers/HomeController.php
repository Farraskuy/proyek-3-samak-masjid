<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postingan;

use App\Models\JadwalKegiatan;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Postingan::query()
        ->where('status', 'published')
        ->orderBy('created_at', 'desc')
        ->limit(4)
        ->get();


        $events = JadwalKegiatan::with('tamuUndangan')
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->limit(3)
            ->get();

        return view('client.home', [
            'posts' => $posts,
            'events' => $events
        ]);
    }
}
