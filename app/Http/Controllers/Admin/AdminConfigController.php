<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use Illuminate\Http\Request;

class AdminConfigController extends Controller
{
    public function site()
    {
        $configs     = AppConfig::byGroup('site');
        $maintenance = AppConfig::byGroup('maintenance');
        return view('admin.configs.v_site', compact('configs', 'maintenance'));
    }

    public function updateSite(Request $request)
    {
        $allowed = [
            'site_name', 'site_tagline',
            'contact_email', 'contact_whatsapp', 'contact_address',
            'admin_bank_name', 'admin_bank_number', 'admin_bank_holder',
            'admin_platform_fee',
        ];

        foreach ($request->input('configs', []) as $key => $value) {
            if (in_array($key, $allowed, true)) {
                AppConfig::where('key', $key)->update(['value' => $value]);
            }
        }

        return back()->with('pesan', 'Konfigurasi sistem berhasil disimpan.');
    }

    public function updateMaintenance(Request $request)
    {
        $defaults = [
            'maintenance_mode' => [
                'label' => 'Mode Maintenance', 'group' => 'maintenance', 'type' => 'toggle',
                'keterangan' => 'Aktifkan untuk memblokir login pengguna biasa. Hanya admin yang bisa masuk.',
            ],
            'maintenance_message' => [
                'label' => 'Pesan Maintenance', 'group' => 'maintenance', 'type' => 'textarea',
                'keterangan' => 'Pesan yang ditampilkan di halaman login saat maintenance aktif.',
            ],
        ];

        foreach ($defaults as $key => $meta) {
            $value = $request->input("configs.{$key}", $key === 'maintenance_mode' ? '0' : '');
            AppConfig::updateOrCreate(['key' => $key], array_merge(['value' => $value], $meta));
        }

        return back()->with('pesan', 'Konfigurasi maintenance berhasil disimpan.');
    }

    public function halaman()
    {
        $configs = AppConfig::byGroup('halaman');
        return view('admin.configs.v_halaman', compact('configs'));
    }

    public function updateHalaman(Request $request)
    {
        $allowed = ['home_intro', 'about_heading', 'about_intro', 'about_content'];

        foreach ($request->input('configs', []) as $key => $value) {
            if (in_array($key, $allowed, true)) {
                AppConfig::where('key', $key)->update(['value' => $value]);
            }
        }

        return back()->with('pesan', 'Konten halaman publik berhasil disimpan.');
    }
}
