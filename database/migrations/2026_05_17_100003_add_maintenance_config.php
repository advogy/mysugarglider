<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('app_configs')->insertOrIgnore([
            [
                'key'         => 'maintenance_mode',
                'label'       => 'Mode Maintenance',
                'value'       => '0',
                'keterangan'  => 'Aktifkan untuk memblokir login pengguna biasa. Hanya admin yang bisa masuk.',
                'group'       => 'maintenance',
                'type'        => 'toggle',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'maintenance_message',
                'label'       => 'Pesan Maintenance',
                'value'       => 'Sistem sedang dalam pemeliharaan. Silakan coba beberapa saat lagi.',
                'keterangan'  => 'Pesan yang ditampilkan di halaman login saat maintenance aktif.',
                'group'       => 'maintenance',
                'type'        => 'textarea',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('app_configs')->whereIn('key', ['maintenance_mode', 'maintenance_message'])->delete();
    }
};
