<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->unsignedInteger('platform_fee')->default(0)->after('harga_final');
            $table->timestamp('disbursed_at')->nullable()->after('confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn(['platform_fee', 'disbursed_at']);
        });
    }
};
