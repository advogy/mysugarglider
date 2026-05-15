<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShelterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('shelters')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nama' => 'Paws & Gliders Surabaya', 'kode' => 'SH-001', 'alamat' => 'Surabaya, Jawa Timur', 'status' => '1', 'user_id' => 2, 'gambar' => 'shelter_1.png'
            ],
            [
                'nama' => 'Malang SG Sanctuary', 'kode' => 'SH-002', 'alamat' => 'Malang, Jawa Timur', 'status' => '1', 'user_id' => 2, 'gambar' => 'shelter_2.png'
            ],
            [
                'nama' => 'Madiun Breeders Hub', 'kode' => 'SH-003', 'alamat' => 'Madiun, Jawa Timur', 'status' => '1', 'user_id' => 3, 'gambar' => 'shelter_1.png'
            ],
            [
                'nama' => 'Bali Tropical Gliders', 'kode' => 'SH-004', 'alamat' => 'Denpasar, Bali', 'status' => '1', 'user_id' => 3, 'gambar' => 'shelter_2.png'
            ],
            [
                'nama' => 'Jakarta Exotic Pets', 'kode' => 'SH-005', 'alamat' => 'Jakarta Selatan, DKI Jakarta', 'status' => '1', 'user_id' => 2, 'gambar' => 'shelter_1.png'
            ],
            [
                'nama' => 'Bandung SG Farm', 'kode' => 'SH-006', 'alamat' => 'Bandung, Jawa Barat', 'status' => '1', 'user_id' => 3, 'gambar' => 'shelter_2.png'
            ],
            [
                'nama' => 'Jogja Sugar Glider Care', 'kode' => 'SH-007', 'alamat' => 'Yogyakarta, DIY', 'status' => '1', 'user_id' => 2, 'gambar' => 'shelter_1.png'
            ],
            [
                'nama' => 'Semarang Pocket Pets', 'kode' => 'SH-008', 'alamat' => 'Semarang, Jawa Tengah', 'status' => '1', 'user_id' => 3, 'gambar' => 'shelter_2.png'
            ]
        ];

        DB::table('shelters')->insert($data);
    }
}
