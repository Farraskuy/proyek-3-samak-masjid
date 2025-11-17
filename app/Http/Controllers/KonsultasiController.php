<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class KonsultasiController extends Controller
{
    protected function perPage(Request $request)
    {
        $showing = $request->query('showing', 50);
        if ($showing === 'all') {
            return 1000;
        }
        $n = (int) $showing;
        return $n > 0 ? $n : 50;
    }

    public function index(Request $request)
    {
        $perPage = $this->perPage($request);
        $data = Consultation::orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        return view('admin.konsultasi.index', ['data' => $data]);
    }
}
