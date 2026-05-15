<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('profiles')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'alamat' => 'Surabaya', 'telepon' => '+62 123456789', 'user_id' => 2
            ],
            [
                'alamat' => 'Malang', 'telepon' => '+62 123456789', 'user_id' => 3
            ],
        ];

        DB::table('profiles')->insert($data);
    }
}
