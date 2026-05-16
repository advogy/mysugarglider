<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SELESAI: 9 → 5
        DB::table('collections')->where('status', 9)->update(['status' => 5]);
    }

    public function down(): void
    {
        DB::table('collections')->where('status', 5)->update(['status' => 9]);
    }
};
