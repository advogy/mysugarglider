<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('group')->default('site');
            $table->string('type')->default('text');
            $table->timestamps();
        });

        DB::table('app_configs')->insert([
            // Site Settings
            ['key' => 'site_name',        'value' => 'MySugarGlider.id', 'keterangan' => 'Nama situs',               'group' => 'site', 'type' => 'text'],
            ['key' => 'site_tagline',     'value' => 'Komunitas Pecinta Sugar Glider Indonesia', 'keterangan' => 'Slogan situs',  'group' => 'site', 'type' => 'text'],
            ['key' => 'contact_email',    'value' => '',                  'keterangan' => 'Email kontak',              'group' => 'site', 'type' => 'text'],
            ['key' => 'contact_whatsapp', 'value' => '',                  'keterangan' => 'Nomor WhatsApp (format 628xxx)', 'group' => 'site', 'type' => 'text'],
            ['key' => 'contact_address',  'value' => '',                  'keterangan' => 'Alamat atau kota',          'group' => 'site', 'type' => 'text'],
            // Halaman Publik
            ['key' => 'about_heading',    'value' => 'Tentang MySugarGlider.id', 'keterangan' => 'Judul halaman Tentang',   'group' => 'halaman', 'type' => 'text'],
            ['key' => 'about_intro',      'value' => 'MySugarGlider.id adalah platform komunitas untuk pecinta dan peternak sugar glider di Indonesia.', 'keterangan' => 'Paragraf pembuka halaman Tentang', 'group' => 'halaman', 'type' => 'textarea'],
            ['key' => 'about_content',    'value' => '',                  'keterangan' => 'Isi utama halaman Tentang (mendukung HTML dasar)', 'group' => 'halaman', 'type' => 'textarea'],
            ['key' => 'home_intro',       'value' => '',                  'keterangan' => 'Teks intro halaman Beranda', 'group' => 'halaman', 'type' => 'textarea'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('app_configs');
    }
};
