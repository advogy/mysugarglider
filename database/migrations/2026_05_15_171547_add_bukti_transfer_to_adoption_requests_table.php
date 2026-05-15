<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->string('bukti_transfer')->nullable()->after('keterangan');
            $table->timestamp('paid_at')->nullable()->after('bukti_transfer');
            $table->timestamp('confirmed_at')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn(['bukti_transfer', 'paid_at', 'confirmed_at']);
        });
    }
};
