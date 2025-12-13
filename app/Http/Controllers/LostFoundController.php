<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FoundItem;
use App\Models\FoundItemPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\LostItem;
use App\Models\ItemCategory;

class LostFoundController extends Controller
{
    public function index()
    {
        $query = FoundItem::with('photos')
            ->where('status', 'Tersedia');

        if (request('category')) {
            $query->where('category', request('category'));
        }

        if (request('search')) {
            $query->where(function ($q) {
                $q->where('item_name', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%')
                    ->orWhere('location_found', 'like', '%' . request('search') . '%');
            });
        }

        $items = $query->latest()->paginate(9);
        return view('client.layanan.barang-hilang.index', compact('items'));
    }

    public function adminIndex(Request $request)
    {
        $keyword = $request->query('keyword', '');
        $query = FoundItem::with('user', 'photos')->latest();

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('item_name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('location_found', 'like', "%{$keyword}%")
                    ->orWhere('category', 'like', "%{$keyword}%");
            });
        }

        $items = $query->get();
        return view('admin.lost-found.found.index', compact('items'));
    }

    public function publicIndex(Request $request)
    {
        $tab = $request->query('tab', 'lost');
        $lostItems = collect();
        $foundItems = collect();
        $categories = ItemCategory::all();

        if ($tab === 'lost') {
            $query = LostItem::with('category')
                ->where('status', 'aktif')
                ->where('expiry_date', '>=', now());

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('item_name', 'like', "%{$request->search}%")
                        ->orWhere('description', 'like', "%{$request->search}%")
                        ->orWhere('location_lost', 'like', "%{$request->search}%");
                });
            }

            if ($request->filled('category')) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }

            $lostItems = $query->paginate(9);
        }

        if ($tab === 'found') {
            $query = FoundItem::with('photos')
                ->where('status', 'Tersedia');

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('item_name', 'like', "%{$request->search}%")
                        ->orWhere('description', 'like', "%{$request->search}%")
                        ->orWhere('location_found', 'like', "%{$request->search}%");
                });
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            $foundItems = $query->paginate(9);
        }

        return view('client.layanan.barang-hilang-ditemukan.index', compact('lostItems', 'foundItems', 'categories', 'tab'));
    }

    public function create()
    {
        return view('admin.lost-found.found.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:100',
            'description' => 'required|string',
            'location_found' => 'required|string|max:100',
            'status' => 'required|in:Tersedia,Diambil',
            'featured_images' => 'required|array|min:1',
            'featured_images.*' => 'image|mimes:jpeg,png,jpg|max:1240',
            'category' => 'required|in:kendaraan,elektronik,aksesoris,dokumen,lain-lain',
        ]);

        $item = FoundItem::create([
            'inputted_by_user_id' => auth::id(),
            'item_name' => $request->item_name,
            'description' => $request->description,
            'location_found' => $request->location_found,
            'status' => $request->status,
            'category' => $request->category,
        ]);

        foreach ($request->file('featured_images') as $image) {
            $path = $image->store('lost-found', 'public');
            FoundItemPhoto::create([
                'found_item_id' => $item->item_id,
                'image_url' => $path,
                'uploaded_by_admin_id' => auth::id(),
            ]);
        }

        return redirect()->route('admin.barang-hilang')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($item_id)
    {
        $item = FoundItem::with('photos')->findOrFail($item_id);
        return view('admin.lost-found.found.edit', compact('item'));
    }

    public function update(Request $request, $item_id)
    {
        $request->validate([
            'item_name' => 'required|string|max:100',
            'description' => 'required|string',
            'location_found' => 'required|string|max:100',
            'status' => 'required|in:Tersedia,Diambil',
            'new_featured_images' => 'nullable|array',
            'new_featured_images.*' => 'image|mimes:jpeg,png,jpg|max:1240',
        ]);

        $item = FoundItem::findOrFail($item_id);
        $item->update([
            'item_name' => $request->item_name,
            'description' => $request->description,
            'location_found' => $request->location_found,
            'status' => $request->status,
            'category' => $request->category,
        ]);

        if ($request->has('remove_photos')) {
            foreach ($request->remove_photos as $photo_id => $should_remove) {
                if ($should_remove == 1) {
                    $photo = $item->photos->firstWhere('photo_id', $photo_id);
                    if ($photo) {
                        Storage::disk('public')->delete($photo->image_url);
                        $photo->delete();
                    }
                }
            }
        }

        if ($request->hasFile('new_featured_images')) {
            foreach ($request->file('new_featured_images') as $image) {
                $path = $image->store('lost-found', 'public');
                FoundItemPhoto::create([
                    'found_item_id' => $item->item_id,
                    'image_url' => $path,
                    'uploaded_by_admin_id' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('admin.barang-hilang')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($item_id)
    {
        $item = FoundItem::findOrFail($item_id);

        foreach ($item->photos as $photo) {
            Storage::disk('public')->delete($photo->image_url);
            $photo->delete();
        }

        $item->delete();

        return redirect()->route('admin.barang-hilang')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
