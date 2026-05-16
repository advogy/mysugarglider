<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->string('value', 100);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        DB::table('point_configs')->insert([
            ['key' => 'min_redeem_poin',    'value' => '500',  'keterangan' => 'Minimum poin untuk melakukan penukaran'],
            ['key' => 'diskon_max_persen',  'value' => '30',   'keterangan' => 'Maksimum diskon adopsi via poin (%)'],
            ['key' => 'kode_klaim_expired', 'value' => '30',   'keterangan' => 'Masa berlaku kode klaim diskon (hari)'],
            ['key' => 'shelter_max_bonus',  'value' => '5',    'keterangan' => 'Maks kandang yang dapat poin bonus'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('point_configs');
    }
};
