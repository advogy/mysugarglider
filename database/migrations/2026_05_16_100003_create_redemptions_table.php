<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reward_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('poin_used');
            $table->string('kategori', 30);
            $table->string('status', 20)->default('pending');
            $table->string('kode_klaim', 20)->unique()->nullable();
            $table->unsignedTinyInteger('diskon_persen')->nullable();
            $table->text('alamat_pengiriman')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redemptions');
    }
};
