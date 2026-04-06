<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'storage/app/public/images/watch.jpg',
                'condition' => 1,
                'user_id' => rand(1, 3),
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'storage/app/public/images/hdd.jpg',
                'condition' => 2,
                'user_id' => rand(1, 3),
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => null,
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'storage/app/public/images/onion.jpg',
                'condition' => 3,
                'user_id' => rand(1, 3),
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand' => null,
                'description' => 'クラシックなデザインの革靴',
                'image' => 'storage/app/public/images/leather_shoes.jpg',
                'condition' => 4,
                'user_id' => rand(1, 3),
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => null,
                'description' => '高性能なノートパソコン',
                'image' => 'storage/app/public/images/laptop.jpg',
                'condition' => 1,
                'user_id' => rand(1, 3),
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand' => null,
                'description' => '高音質のレコーディング用マイク',
                'image' => 'storage/app/public/images/mic.jpg',
                'condition' => 2,
                'user_id' => rand(1, 3),
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'storage/app/public/images/purse.jpg',
                'condition' => 3,
                'user_id' => rand(1, 3),
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => null,
                'description' => '使いやすいタンブラー',
                'image' => 'storage/app/public/images/tumbler.jpg',
                'condition' => 4,
                'user_id' => rand(1, 3),
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image' => 'storage/app/public/images/coffee.jpg',
                'condition' => 1,
                'user_id' => rand(1, 3),
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => null,
                'description' => '便利なメイクアップセット',
                'image' => 'storage/app/public/images/makeup.jpg',
                'condition' => 2,
                'user_id' => rand(1, 3),
            ],
        ];

        DB::table('items')->insert($items);
    }
}
