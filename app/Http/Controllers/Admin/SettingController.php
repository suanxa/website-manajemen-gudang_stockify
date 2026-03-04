<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Artisan;
use \App\Models\Setting;
  
class SettingController extends Controller
{
    public function index()
    {
        // Ambil semua setting dan jadikan array key-value
        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.settings', compact('settings'));
    }
    public function update(Request $request)
    {
        $data = $request->validate([
            'app_name' => 'required|string|max:50',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => $data['app_name']]);
        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $path]);
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
    public function reset()
    {
        // 1. Hapus file logo fisik agar tidak memenuhi storage
        $logoSetting = Setting::where('key', 'app_logo')->first();
        if ($logoSetting && $logoSetting->value) {
            \Storage::disk('public')->delete($logoSetting->value);
        }

        // 2. Kosongkan tabel settings
        Setting::truncate();

        // 3. PAKSA hapus semua jenis cache agar perubahan INSTAN terlihat
        Artisan::call('cache:clear');
        Artisan::call('view:clear'); 
        Artisan::call('config:clear'); 

        return redirect()->route('admin.settings')->with('success', 'Pengaturan telah dikembalikan ke default!');
    }
}
