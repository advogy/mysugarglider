<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('app_configs')->insertOrIgnore([
            [
                'key'        => 'admin_bank_name',
                'label'      => 'Nama Bank (Escrow)',
                'value'      => '',
                'keterangan' => 'Nama bank rekening penampung dana adopsi (contoh: BRI, BCA, Mandiri)',
                'group'      => 'site',
                'type'       => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key'        => 'admin_bank_number',
                'label'      => 'Nomor Rekening (Escrow)',
                'value'      => '',
                'keterangan' => 'Nomor rekening admin untuk penampungan dana adopsi',
                'group'      => 'site',
                'type'       => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key'        => 'admin_bank_holder',
                'label'      => 'Nama Pemilik Rekening (Escrow)',
                'value'      => '',
                'keterangan' => 'Nama pemilik rekening sesuai buku tabungan',
                'group'      => 'site',
                'type'       => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key'        => 'admin_platform_fee',
                'label'      => 'Biaya Platform (Flat)',
                'value'      => '0',
                'keterangan' => 'Biaya administrasi platform per transaksi adopsi berbayar (Rupiah)',
                'group'      => 'site',
                'type'       => 'number',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('app_configs')->whereIn('key', [
            'admin_bank_name', 'admin_bank_number', 'admin_bank_holder', 'admin_platform_fee',
        ])->delete();
    }
};
