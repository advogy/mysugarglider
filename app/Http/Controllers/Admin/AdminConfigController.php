<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use Illuminate\Http\Request;

class AdminConfigController extends Controller
{
    public function site()
    {
        $configs = AppConfig::byGroup('site');
        return view('admin.configs.v_site', compact('configs'));
    }

    public function updateSite(Request $request)
    {
        foreach ($request->input('configs', []) as $key => $value) {
            AppConfig::where('key', $key)->update(['value' => $value]);
        }

        return back()->with('pesan', 'Konfigurasi sistem berhasil disimpan.');
    }

    public function halaman()
    {
        $configs = AppConfig::byGroup('halaman');
        return view('admin.configs.v_halaman', compact('configs'));
    }

    public function updateHalaman(Request $request)
    {
        foreach ($request->input('configs', []) as $key => $value) {
            AppConfig::where('key', $key)->update(['value' => $value]);
        }

        return back()->with('pesan', 'Konten halaman publik berhasil disimpan.');
    }
}
