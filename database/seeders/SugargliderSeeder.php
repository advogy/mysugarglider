<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SugargliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('sugargliders')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'kode' => 'ARSG-A001', 'nama' => 'Mochi', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'Classic Grey', 'jenis' => 'Classic Grey', 'genetika' => 'Standard', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_1.png'
            ],
            [
                'kode' => 'ARSG-A002', 'nama' => 'Kiki', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'White', 'jenis' => 'Leucistic', 'genetika' => '100% Leucistic', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_3.png'
            ],
            [
                'kode' => 'ARSG-A003', 'nama' => 'Nala', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'White/Grey', 'jenis' => 'Mosaic', 'genetika' => 'Ringtail Mosaic', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_3.png'
            ],
            [
                'kode' => 'ARSG-A004', 'nama' => 'Pip', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'Cream', 'jenis' => 'Creamino', 'genetika' => 'T-Albino', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_2.png'
            ],
            [
                'kode' => 'ARSG-A005', 'nama' => 'Gizmo', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'Silver', 'jenis' => 'Platinum', 'genetika' => 'het Leucistic', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_4.png'
            ],
            [
                'kode' => 'ARSG-A006', 'nama' => 'Luna', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'White Face', 'jenis' => 'White Face Blonde', 'genetika' => 'Standard WF', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_1.png'
            ],
            [
                'kode' => 'ARSG-A007', 'nama' => 'Milo', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'Dark Grey', 'jenis' => 'Black Beauty', 'genetika' => 'Charcoal Line', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_1.png'
            ],
            [
                'kode' => 'ARSG-A008', 'nama' => 'Bella', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'Reddish', 'jenis' => 'Caramino', 'genetika' => 'T+ Albino', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_5.png'
            ],
            [
                'kode' => 'ARSG-A009', 'nama' => 'Oreo', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'White/Grey', 'jenis' => 'Piebald', 'genetika' => 'Mosaic Piebald', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_2.png'
            ],
            [
                'kode' => 'ARSG-A010', 'nama' => 'Peanut', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'White', 'jenis' => 'Ruby Leucistic', 'genetika' => 'Ruby Eye', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_2.png'
            ],
            [
                'kode' => 'ARSG-A011', 'nama' => 'Simba', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'Classic Grey', 'jenis' => 'Classic Grey', 'genetika' => 'Standard', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_2.png'
            ],
            [
                'kode' => 'ARSG-A012', 'nama' => 'Cleo', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'White', 'jenis' => 'Leucistic', 'genetika' => '100% Leucistic', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_4.png'
            ],
            [
                'kode' => 'ARSG-A013', 'nama' => 'Toby', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'White/Grey', 'jenis' => 'Mosaic', 'genetika' => 'Ringtail Mosaic', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_2.png'
            ],
            [
                'kode' => 'ARSG-A014', 'nama' => 'Ruby', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'Cream', 'jenis' => 'Creamino', 'genetika' => 'T-Albino', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_5.png'
            ],
            [
                'kode' => 'ARSG-A015', 'nama' => 'Ziggy', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'Silver', 'jenis' => 'Platinum', 'genetika' => 'het Leucistic', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_3.png'
            ],
            [
                'kode' => 'ARSG-A016', 'nama' => 'Coco', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'White Face', 'jenis' => 'White Face Blonde', 'genetika' => 'Standard WF', 'fenotype' => "Warna grey", 'indukan_betina' => null, 'indukan_jantan' => null, 'user_id' => '2', 'gambar' => 'sg_2.png'
            ],
            [
                'kode' => 'ARSG-A017', 'nama' => 'Jasper', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'Dark Grey', 'jenis' => 'Black Beauty', 'genetika' => 'Charcoal Line', 'fenotype' => "Warna grey", 'indukan_betina' => '2', 'indukan_jantan' => '1', 'user_id' => '2', 'gambar' => 'sg_5.png'
            ],
            [
                'kode' => 'ARSG-A018', 'nama' => 'Willow', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'Reddish', 'jenis' => 'Caramino', 'genetika' => 'T+ Albino', 'fenotype' => "Warna grey", 'indukan_betina' => '4', 'indukan_jantan' => '3', 'user_id' => '2', 'gambar' => 'sg_2.png'
            ],
            [
                'kode' => 'ARSG-A019', 'nama' => 'Finn', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'White/Grey', 'jenis' => 'Piebald', 'genetika' => 'Mosaic Piebald', 'fenotype' => "Warna grey", 'indukan_betina' => '6', 'indukan_jantan' => '5', 'user_id' => '2', 'gambar' => 'sg_2.png'
            ],
            [
                'kode' => 'ARSG-A020', 'nama' => 'Daisy', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'White', 'jenis' => 'Ruby Leucistic', 'genetika' => 'Ruby Eye', 'fenotype' => "Warna grey", 'indukan_betina' => '8', 'indukan_jantan' => '7', 'user_id' => '2', 'gambar' => 'sg_5.png'
            ],
            [
                'kode' => 'ARSG-A021', 'nama' => 'Archie', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'Classic Grey', 'jenis' => 'Classic Grey', 'genetika' => 'Standard', 'fenotype' => "Warna grey", 'indukan_betina' => '10', 'indukan_jantan' => '9', 'user_id' => '2', 'gambar' => 'sg_5.png'
            ],
            [
                'kode' => 'ARSG-A022', 'nama' => 'Rosie', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'White', 'jenis' => 'Leucistic', 'genetika' => '100% Leucistic', 'fenotype' => "Warna grey", 'indukan_betina' => '12', 'indukan_jantan' => '11', 'user_id' => '2', 'gambar' => 'sg_4.png'
            ],
            [
                'kode' => 'ARSG-A023', 'nama' => 'Buster', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'White/Grey', 'jenis' => 'Mosaic', 'genetika' => 'Ringtail Mosaic', 'fenotype' => "Warna grey", 'indukan_betina' => '14', 'indukan_jantan' => '13', 'user_id' => '2', 'gambar' => 'sg_1.png'
            ],
            [
                'kode' => 'ARSG-A024', 'nama' => 'Olive', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'Cream', 'jenis' => 'Creamino', 'genetika' => 'T-Albino', 'fenotype' => "Warna grey", 'indukan_betina' => '16', 'indukan_jantan' => '15', 'user_id' => '2', 'gambar' => 'sg_3.png'
            ],
            [
                'kode' => 'ARSG-A025', 'nama' => 'Felix', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'Silver', 'jenis' => 'Platinum', 'genetika' => 'het Leucistic', 'fenotype' => "Warna grey", 'indukan_betina' => '18', 'indukan_jantan' => '17', 'user_id' => '2', 'gambar' => 'sg_3.png'
            ],
            [
                'kode' => 'ARSG-A026', 'nama' => 'Hazel', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'White Face', 'jenis' => 'White Face Blonde', 'genetika' => 'Standard WF', 'fenotype' => "Warna grey", 'indukan_betina' => '20', 'indukan_jantan' => '19', 'user_id' => '2', 'gambar' => 'sg_3.png'
            ],
            [
                'kode' => 'ARSG-A027', 'nama' => 'Shadow', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'Dark Grey', 'jenis' => 'Black Beauty', 'genetika' => 'Charcoal Line', 'fenotype' => "Warna grey", 'indukan_betina' => '22', 'indukan_jantan' => '21', 'user_id' => '2', 'gambar' => 'sg_1.png'
            ],
            [
                'kode' => 'ARSG-A028', 'nama' => 'Ginger', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'Reddish', 'jenis' => 'Caramino', 'genetika' => 'T+ Albino', 'fenotype' => "Warna grey", 'indukan_betina' => '24', 'indukan_jantan' => '23', 'user_id' => '2', 'gambar' => 'sg_2.png'
            ],
            [
                'kode' => 'ARSG-A029', 'nama' => 'Pippin', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'White/Grey', 'jenis' => 'Piebald', 'genetika' => 'Mosaic Piebald', 'fenotype' => "Warna grey", 'indukan_betina' => '26', 'indukan_jantan' => '25', 'user_id' => '2', 'gambar' => 'sg_3.png'
            ],
            [
                'kode' => 'ARSG-A030', 'nama' => 'Skye', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'White', 'jenis' => 'Ruby Leucistic', 'genetika' => 'Ruby Eye', 'fenotype' => "Warna grey", 'indukan_betina' => '28', 'indukan_jantan' => '27', 'user_id' => '2', 'gambar' => 'sg_5.png'
            ],
            [
                'kode' => 'ARSG-A031', 'nama' => 'Marley', 'kelamin' => '1', 'tgl_lahir' => '2022-01-01', 'warna' => 'Classic Grey', 'jenis' => 'Classic Grey', 'genetika' => 'Standard', 'fenotype' => "Warna grey", 'indukan_betina' => '30', 'indukan_jantan' => '29', 'user_id' => '2', 'gambar' => 'sg_3.png'
            ],
            [
                'kode' => 'ARSG-A032', 'nama' => 'Zoom', 'kelamin' => '0', 'tgl_lahir' => '2022-01-01', 'warna' => 'White', 'jenis' => 'Leucistic', 'genetika' => '100% Leucistic', 'fenotype' => "Warna grey", 'indukan_betina' => '30', 'indukan_jantan' => '29', 'user_id' => '2', 'gambar' => 'sg_2.png'
            ],
        ];

        DB::table('sugargliders')->insert($data);
    }
}
