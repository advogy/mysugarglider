<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->string('kode_diskon', 20)->nullable()->after('bukti_transfer');
            $table->unsignedInteger('diskon_amount')->nullable()->after('kode_diskon');
            $table->unsignedInteger('harga_final')->nullable()->after('diskon_amount');
        });
    }

    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn(['kode_diskon', 'diskon_amount', 'harga_final']);
        });
    }
};
