<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->text('quote');
            $table->string('author');
            $table->string('durasi')->nullable();
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        DB::table('testimonials')->insert([
            [
                'quote'   => 'Berkat MySugarGlider, saya bisa menemukan silsilah lengkap dari peliharaan saya dan memastikan genetikanya sehat. Sangat merekomendasikan!',
                'author'  => 'Arjuna',
                'durasi'  => '2 Tahun bersama',
                'urutan'  => 1,
                'aktif'   => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quote'   => 'Platform ini luar biasa! Proses adopsi sangat mudah dan transparan. Sugar glider saya sekarang hidup bahagia di kandang baru.',
                'author'  => 'Sinta',
                'durasi'  => '1 Tahun bersama',
                'urutan'  => 2,
                'aktif'   => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quote'   => 'Saya awalnya ragu, tapi setelah mencoba MySugarGlider saya langsung jatuh cinta. Data pedigree-nya lengkap dan akurat.',
                'author'  => 'Budi',
                'durasi'  => '8 Bulan bersama',
                'urutan'  => 3,
                'aktif'   => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
