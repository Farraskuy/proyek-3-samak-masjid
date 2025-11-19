<?php

namespace App\Http\Controllers\Donasi\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankController extends Controller
{
    public function index()
    {
        $banks = BankAccount::all();
        return view('admin.banks.index', compact('banks'));
    }

    public function create()
    {
        return view('admin.banks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required',
            'account_number' => 'required',
            'account_holder_name' => 'required',
            'category' => 'required',
            'logo' => 'required|image|max:2048'
        ]);

        // Upload Logo
        $path = $request->file('logo')->store('bank_logos', 'public');

        BankAccount::create([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder_name' => $request->account_holder_name,
            'category' => $request->category,
            'logo_url' => '/storage/' . $path,
            'is_active' => true
        ]);

        return redirect()->route('admin.banks.index')->with('success', 'Rekening berhasil ditambahkan');
    }

    public function edit($id)
    {
        $bank = BankAccount::findOrFail($id);
        return view('admin.banks.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $bank = BankAccount::findOrFail($id);
        
        $data = $request->all();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('bank_logos', 'public');
            $data['logo_url'] = '/storage/' . $path;
        }

        // Handle checkbox is_active
        $data['is_active'] = $request->has('is_active');

        $bank->update($data);

        return redirect()->route('admin.banks.index')->with('success', 'Rekening diperbarui');
    }

    public function destroy($id)
    {
        BankAccount::destroy($id);
        return redirect()->back()->with('success', 'Rekening dihapus');
    }
}