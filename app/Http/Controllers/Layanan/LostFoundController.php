<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use App\Models\LostAndFoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LostFoundController extends Controller
{
    public function index()
    {
        $items = LostAndFoundItem::where('status', 'Tersedia')
            ->latest()
            ->get();
        return view('client.layanan.barang-hilang.index', compact('items'));
    }

    public function adminIndex()
    {
        $items = LostAndFoundItem::with('user')->latest()->get();
        return view('admin.lost-found.index', compact('items'));
    }

    public function create()
    {
        return view('admin.lost-found.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:100',
            'description' => 'required|string',
            'location_found' => 'required|string|max:100',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:Tersedia,Diambil',
        ]);

        $imagePath = '';
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('lost-found', 'public');
        }

        LostAndFoundItem::create([
            'inputted_by_admin_id' => 2,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'location_found' => $request->location_found,
            'featured_image_url' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.barang-hilang')
            ->with('success', 'Barang berhasil ditambahkan!');
    }
}