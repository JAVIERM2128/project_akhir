<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreSettingController extends Controller
{
    /**
     * Show the form for editing the store settings.
     */
    public function edit()
    {
        $setting = StoreSetting::first(); // Ambil pengaturan pertama (kita hanya punya satu toko)

        // Jika belum ada pengaturan, kita buat satu
        if (!$setting) {
            $setting = StoreSetting::create([]);
        }

        return view('admin.store-settings.edit', compact('setting'));
    }

    /**
     * Update the store settings in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // maks 2MB
        ]);

        $setting = StoreSetting::firstOrFail();

        // Proses upload logo jika ada
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($setting->logo_path) {
                Storage::delete('public/logos/' . $setting->logo_path);
            }

            // Simpan logo baru
            $logo = $request->file('logo');
            $fileName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->storeAs('public/logos', $fileName);

            $setting->logo_path = $fileName;
        }

        // Update data lain
        $setting->update([
            'store_name' => $request->store_name,
            'contact_phone' => $request->contact_phone,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.store-setting.edit')->with('success', 'Pengaturan toko berhasil diperbarui.');
    }
}
