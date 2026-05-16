<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_items', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('kategori', 30);
            $table->unsignedInteger('poin_required');
            $table->unsignedTinyInteger('diskon_persen')->nullable();
            $table->integer('stok')->nullable();
            $table->boolean('aktif')->default(true);
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_items');
    }
};
