<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('collections')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'shelter_id' => '1', 'sugarglider_id' => '1', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '1', 'sugarglider_id' => '2', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '6', 'sugarglider_id' => '3', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '2', 'sugarglider_id' => '4', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '2', 'sugarglider_id' => '5', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '7', 'sugarglider_id' => '6', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '3', 'sugarglider_id' => '7', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '5', 'sugarglider_id' => '8', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '8', 'sugarglider_id' => '9', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '8', 'sugarglider_id' => '10', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '1', 'sugarglider_id' => '11', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '5', 'sugarglider_id' => '12', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '6', 'sugarglider_id' => '13', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '5', 'sugarglider_id' => '14', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '8', 'sugarglider_id' => '15', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '6', 'sugarglider_id' => '16', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '1', 'sugarglider_id' => '17', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '4', 'sugarglider_id' => '18', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '4', 'sugarglider_id' => '19', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '3', 'sugarglider_id' => '20', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '7', 'sugarglider_id' => '21', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '6', 'sugarglider_id' => '22', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '2', 'sugarglider_id' => '23', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '2', 'sugarglider_id' => '24', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '7', 'sugarglider_id' => '25', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '7', 'sugarglider_id' => '26', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '2', 'sugarglider_id' => '27', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '7', 'sugarglider_id' => '28', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '2', 'sugarglider_id' => '29', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '3', 'sugarglider_id' => '30', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '6', 'sugarglider_id' => '31', 'user_id' => 2, 'status' => '2'
            ],
            [
                'shelter_id' => '5', 'sugarglider_id' => '32', 'user_id' => 2, 'status' => '2'
            ],
        ];

        DB::table('collections')->insert($data);
    }
}
