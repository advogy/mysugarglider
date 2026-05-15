<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sugargliders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->boolean('kelamin')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('warna')->nullable();
            $table->string('jenis')->nullable();
            $table->string('genetika')->nullable();
            $table->text('fenotype')->nullable();
            // Self-referencing FK: indukan (parent) → sugargliders.id
            $table->unsignedBigInteger('indukan_betina')->nullable();
            $table->unsignedBigInteger('indukan_jantan')->nullable();
            $table->foreign('indukan_betina')->references('id')->on('sugargliders')->nullOnDelete();
            $table->foreign('indukan_jantan')->references('id')->on('sugargliders')->nullOnDelete();
            $table->string('gambar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sugargliders');
    }
};
