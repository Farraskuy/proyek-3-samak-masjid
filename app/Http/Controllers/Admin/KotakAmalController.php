<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KotakAmal;
use Illuminate\Http\Request;

class KotakAmalController extends Controller
{
    public function index()
    {
        $collections = KotakAmal::latest()->paginate(10);
        return view('admin.kotak_amal.index', compact('collections'));
    }

    public function create()
    {
        return view('admin.kotak_amal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'box_name' => 'required|string|max:255',
            'collection_date' => 'required|date',
            'officers' => 'required|array|min:1',
            'officers.*.name' => 'required|string',
            'officers.*.phone' => 'nullable|string',
            'officers.*.signature' => 'required|string', // Base64
            'details' => 'required|array',
            'details.*.nominal' => 'required|integer',
            'details.*.quantity' => 'required|integer|min:0',
            'details.*.is_locked' => 'required|boolean|accepted', // Must be true/locked
        ]);

        $totalAmount = 0;
        $details = [];
        foreach ($request->details as $detail) {
            $subtotal = $detail['nominal'] * $detail['quantity'];
            $totalAmount += $subtotal;
            $details[] = [
                'nominal' => $detail['nominal'],
                'quantity' => $detail['quantity'],
                'subtotal' => $subtotal,
                'is_locked' => true,
            ];
        }

        KotakAmal::create([
            'box_name' => $request->box_name,
            'collection_date' => $request->collection_date,
            'total_amount' => $totalAmount,
            'status' => 'finalized',
            'officers' => $request->officers,
            'details' => $details,
        ]);

        return redirect()->route('admin.kotak-amal.index')->with('success', 'Pendataan kotak amal berhasil disimpan.');
    }

    public function show($id)
    {
        $collection = KotakAmal::findOrFail($id);
        return view('admin.kotak_amal.show', compact('collection'));
    }

    public function destroy($id)
    {
        $collection = KotakAmal::findOrFail($id);
        $collection->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
