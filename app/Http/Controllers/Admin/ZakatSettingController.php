<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteInformation;
use Illuminate\Http\Request;

class ZakatSettingController extends Controller
{
    /**
     * Display zakat settings page
     */
    public function index()
    {
        $websiteInfo = WebsiteInformation::first();
        $settings = $websiteInfo?->zakat_settings ?? [
            'harga_emas_per_gram' => config('zakat-config.harga_emas_per_gram', 1300000),
            'harga_beras_per_kg' => config('zakat-config.harga_beras_per_kg', 13500),
        ];

        return view('admin.settings.zakat', compact('settings'));
    }

    /**
     * Update zakat settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'harga_emas_per_gram' => 'required|numeric|min:0',
            'harga_beras_per_kg' => 'required|numeric|min:0',
        ]);

        $websiteInfo = WebsiteInformation::first();

        if (!$websiteInfo) {
            return back()->with('error', 'Data informasi website belum tersedia. Silakan buat terlebih dahulu.');
        }

        // Get existing settings and merge
        $currentSettings = $websiteInfo->zakat_settings ?? [];
        $newSettings = array_merge($currentSettings, [
            'harga_emas_per_gram' => (int) $validated['harga_emas_per_gram'],
            'harga_beras_per_kg' => (int) $validated['harga_beras_per_kg'],
        ]);

        // Disable timestamps temporarily
        $websiteInfo->timestamps = false;
        $websiteInfo->zakat_settings = $newSettings;
        $websiteInfo->save();

        return back()->with('success', 'Pengaturan nisab zakat berhasil diperbarui.');
    }

    /**
     * Get zakat settings (for API/Service)
     */
    public static function getSettings(): array
    {
        $websiteInfo = WebsiteInformation::first();
        
        return $websiteInfo?->zakat_settings ?? [
            'harga_emas_per_gram' => config('zakat-config.harga_emas_per_gram', 1300000),
            'harga_beras_per_kg' => config('zakat-config.harga_beras_per_kg', 13500),
        ];
    }
}
