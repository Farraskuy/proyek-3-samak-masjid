<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LostItem;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LostItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->query('keyword', '');
        $query = LostItem::with('category', 'reportedBy')
            ->where('status', 'aktif')
            ->latest();

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('item_name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('location_lost', 'like', "%{$keyword}%");
            });
        }

        $items = $query->paginate(10);
        return view('admin.lost-found.lost.index', compact('items'));
    }

    public function create()
    {
        $categories = ItemCategory::all();
        return view('admin.lost-found.lost.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:100',
            'description' => 'required|string',
            'location_lost' => 'required|string|max:100',
            'lost_at' => 'required|date',
            'category_id' => 'required|exists:item_categories,id',
        ]);

        $expiryDate = \Carbon\Carbon::parse($request->lost_at)->addDays(30);

        LostItem::create([
            'reported_by_admin_id' => Auth::id(),
            'category_id' => $request->category_id,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'location_lost' => $request->location_lost,
            'lost_at' => $request->lost_at,
            'expiry_date' => $expiryDate,
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.lost-items.index')
            ->with('success', 'Laporan barang hilang berhasil ditambahkan!');
    }

    public function edit(LostItem $lostItem)
    {
        $categories = ItemCategory::all();
        return view('admin.lost-found.lost.edit', compact('lostItem', 'categories'));
    }

    public function update(Request $request, LostItem $lostItem)
    {
        $request->validate([
            'item_name' => 'required|string|max:100',
            'description' => 'required|string',
            'location_lost' => 'required|string|max:100',
            'lost_at' => 'required|date',
            'category_id' => 'required|exists:item_categories,id',
        ]);

        $expiryDate = \Carbon\Carbon::parse($request->lost_at)->addDays(30);

        $lostItem->update([
            'category_id' => $request->category_id,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'location_lost' => $request->location_lost,
            'lost_at' => $request->lost_at,
            'expiry_date' => $expiryDate,
        ]);

        return redirect()->route('admin.lost-items.index')
            ->with('success', 'Laporan barang hilang berhasil diperbarui!');
    }

    public function destroy(LostItem $lostItem)
    {
        $lostItem->delete();
        return redirect()->route('admin.lost-items.index')
            ->with('success', 'Laporan barang hilang berhasil dihapus!');
    }
}
