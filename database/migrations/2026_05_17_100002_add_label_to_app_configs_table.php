<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_configs', function (Blueprint $table) {
            $table->string('label')->nullable()->after('key');
        });

        $labels = [
            'site_name'        => 'Nama Situs',
            'site_tagline'     => 'Tagline / Slogan',
            'contact_email'    => 'Email Kontak',
            'contact_whatsapp' => 'Nomor WhatsApp',
            'contact_address'  => 'Alamat',
            'about_heading'    => 'Judul Halaman Tentang',
            'about_intro'      => 'Paragraf Pembuka',
            'about_content'    => 'Isi Halaman Tentang',
            'home_intro'       => 'Teks Intro Beranda',
        ];

        foreach ($labels as $key => $label) {
            DB::table('app_configs')->where('key', $key)->update(['label' => $label]);
        }
    }

    public function down(): void
    {
        Schema::table('app_configs', function (Blueprint $table) {
            $table->dropColumn('label');
        });
    }
};
