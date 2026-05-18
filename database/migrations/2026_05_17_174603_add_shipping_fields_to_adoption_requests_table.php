<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->string('resi_pengiriman')->nullable()->after('bukti_transfer');
            $table->string('bukti_pengiriman')->nullable()->after('resi_pengiriman');
        });
    }

    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn(['resi_pengiriman', 'bukti_pengiriman']);
        });
    }
};
