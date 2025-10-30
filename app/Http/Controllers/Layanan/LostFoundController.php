<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use App\Models\LostAndFoundItem;
use Illuminate\Http\Request;


class LostFoundController extends Controller
{
    public function index()
    {
        $items = LostAndFoundItem::where('status', 'Tersedia')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.layanan.barang-hilang.index', compact('items'));
    }
}
