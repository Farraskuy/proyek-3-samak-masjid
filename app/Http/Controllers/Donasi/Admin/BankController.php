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
            'bank_name' => 'required|max:50',
            'account_number' => 'required|max:50',
            'account_holder_name' => 'required|max:100',
            'category' => 'required|in:zakat,infaq',
            'logo' => 'required|image|max:2048'
        ]);

        // Upload Logo
        $path = $request->file('logo')->store('bank_logos', 'public');

        // Determine type based on category
        $type = $request->category === 'zakat' ? 'bank_zakat' : 'bank_infaq';

        BankAccount::create([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder_name' => $request->account_holder_name,
            'category' => $request->category,
            'type' => $type,
            'logo_url' => '/storage/' . $path,
            'is_deletable' => true,
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

        $request->validate([
            'bank_name' => 'required|max:50',
            'account_number' => 'nullable|max:50',
            'account_holder_name' => 'required|max:100',
            'category' => 'required|in:zakat,infaq',
            'logo' => 'nullable|image|max:2048'
        ]);

        // Only allow editing certain fields (NOT balance)
        $data = [
            'bank_name' => $request->bank_name,
            'account_holder_name' => $request->account_holder_name,
            'is_active' => $request->has('is_active'),
        ];

        // Only update account_number if bank is not Kas type
        if ($bank->type !== 'kas') {
            $data['account_number'] = $request->account_number;
            $data['category'] = $request->category;
            $data['type'] = $request->category === 'zakat' ? 'bank_zakat' : 'bank_infaq';
        }

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($bank->logo_url && Storage::disk('public')->exists(str_replace('/storage/', '', $bank->logo_url))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $bank->logo_url));
            }
            $path = $request->file('logo')->store('bank_logos', 'public');
            $data['logo_url'] = '/storage/' . $path;
        }

        $bank->update($data);

        return redirect()->route('admin.banks.index')->with('success', 'Rekening diperbarui');
    }

    public function destroy($id)
    {
        $bank = BankAccount::findOrFail($id);

        // Check if bank can be deleted
        if (!$bank->canBeDeleted()) {
            $message = 'Rekening tidak dapat dihapus.';

            if (!$bank->is_deletable) {
                $message = 'Rekening Kas tidak dapat dihapus.';
            } elseif ($bank->balance > 0) {
                $message = 'Rekening dengan saldo tidak dapat dihapus. Saldo saat ini: ' . $bank->formatted_balance;
            }

            return redirect()->back()->with('error', $message);
        }

        // Soft delete
        $bank->delete();

        return redirect()->back()->with('success', 'Rekening berhasil dihapus');
    }
}