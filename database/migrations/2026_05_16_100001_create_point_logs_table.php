<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50);
            $table->integer('points');
            $table->nullableMorphs('subject');
            $table->string('note')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'expired_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_logs');
    }
};
