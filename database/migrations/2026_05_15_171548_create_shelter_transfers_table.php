<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shelter_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sugarglider_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('from_shelter_id')->nullable();
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->foreignId('to_shelter_id')->constrained('shelters')->cascadeOnDelete();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('adoption_request_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shelter_transfers');
    }
};
