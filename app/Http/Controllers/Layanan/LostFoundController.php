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
        $query = LostAndFoundItem::where('status', 'Tersedia');

        if (request('search')) {
            $query->where(function ($q) {
                $q->where('item_name', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%')
                    ->orWhere('location_found', 'like', '%' . request('search') . '%');
            });
        }

        $items = $query->latest()->paginate(3);

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
            'status' => 'required|in:Tersedia,Diambil',
            'featured_images' => 'required|array|min:1',
            'featured_images.*' => 'image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $item = LostAndFoundItem::create([
            'inputted_by_admin_id' => 2,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'location_found' => $request->location_found,
            'featured_image_url' => '',
            'status' => $request->status,
        ]);

        $images = $request->file('featured_images');
        $imagePaths = [];

        foreach ($images as $image) {
            $path = $image->store('lost-found', 'public');
            $imagePaths[] = $path;

            \App\Models\LostItemPhoto::create([
                'item_id' => $item->item_id,
                'image_url' => $path,
                'caption' => '',
                'uploaded_by_admin_id' => 2,
            ]);
        }

        $item->update(['featured_image_url' => $imagePaths[0]]);

        return redirect()->route('admin.barang-hilang')
            ->with('success', 'Barang berhasil ditambahkan!');
    }
}
