<?php

namespace App\Http\Controllers\Donasi\Admin;

use App\Http\Controllers\Controller;
use App\Models\Infaq;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Infaq::with('bankAccount');

        // Search
        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // Filter Status
        if ($request->has('status') && $request->status != 'all') {
            $isActive = $request->status == 'active';
            $query->where('is_active', $isActive);
        }

        // Sort
        $sort = $request->query('sort', 'latest');
        if ($sort == 'oldest') {
            $query->oldest();
        } elseif ($sort == 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort == 'name_desc') {
            $query->orderBy('name', 'desc');
        } else {
            $query->latest();
        }

        $infaqs = $query->paginate(10)->withQueryString();

        return view('admin.infaqs.index', compact('infaqs'));
    }

    public function create()
    {
        // Only get infaq-category bank accounts
        $banks = BankAccount::where('category', 'infaq')
            ->where('is_active', true)
            ->get();
        return view('admin.infaqs.create', compact('banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable|string',
            'bank_account_id' => 'required|exists:bank_accounts,account_id',
            'poster' => 'nullable|image|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'bank_account_id' => $request->bank_account_id,
            'is_active' => true,
        ];

        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('infaq_posters', 'public');
            $data['poster_url'] = '/storage/' . $path;
        }

        Infaq::create($data);

        return redirect()->route('admin.infaqs.index')->with('success', 'Program infaq berhasil ditambahkan');
    }

    public function edit($id)
    {
        $infaq = Infaq::findOrFail($id);
        $banks = BankAccount::where('category', 'infaq')
            ->where('is_active', true)
            ->get();
        return view('admin.infaqs.edit', compact('infaq', 'banks'));
    }

    public function update(Request $request, $id)
    {
        $infaq = Infaq::findOrFail($id);

        $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable|string',
            'bank_account_id' => 'required|exists:bank_accounts,account_id',
            'poster' => 'nullable|image|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'bank_account_id' => $request->bank_account_id,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('poster')) {
            // Delete old poster if exists
            if ($infaq->poster_url && Storage::disk('public')->exists(str_replace('/storage/', '', $infaq->poster_url))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $infaq->poster_url));
            }
            $path = $request->file('poster')->store('infaq_posters', 'public');
            $data['poster_url'] = '/storage/' . $path;
        }

        $infaq->update($data);

        return redirect()->route('admin.infaqs.index')->with('success', 'Program infaq berhasil diperbarui');
    }

    public function destroy($id)
    {
        $infaq = Infaq::findOrFail($id);
        $infaq->delete();

        return redirect()->back()->with('success', 'Program infaq berhasil dihapus');
    }
}
