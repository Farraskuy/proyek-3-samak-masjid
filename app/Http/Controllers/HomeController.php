<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postingan;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Postingan::query()
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('client.home', ['posts' => $posts]);
    }
}
