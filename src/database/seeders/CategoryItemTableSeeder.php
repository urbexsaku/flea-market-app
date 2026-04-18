<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryItemTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            1 => [1,5],
            2 => [2],
            3 => [2, 10],
            4 => [1, 5],
            5 => [2],
            6 => [13],
            7 => [1, 4],
            8 => [10],
            9 => [10],
            10 => [6],
        ];

        $insertData = [];

        foreach ($data as $itemId => $categoryIds) {
            foreach ($categoryIds as $categoryId) {
                $insertData[] = [
                    'item_id' => $itemId,
                    'category_id' => $categoryId
                ];
            }
        }

        DB::table('category_item')->insert($insertData);
    }
}
