<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_detail_is_displayed()
    {
        $user = User::factory()->create([
            'profile_image' => 'images/test.jpg',
        ]);

        $sellingItem = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $purchasedItem = Item::factory()->create([
            'is_sold' => true,
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
        ]);

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // 2. プロフィールページを開く
        $response = $this->get('/mypage');
        $response->assertStatus(200);

        // プロフィール画像表示確認
        $response->assertSee('/storage/' . $user->profile_image);

        // ユーザー名表示確認
        $response->assertSee($user->name);

        // 出品した商品一覧表示確認
        $response = $this->get('/mypage?page=sell');
        $response->assertSee($sellingItem->name);

        // 購入した商品一覧表示確認
        $response = $this->get('/mypage?page=buy');
        $response->assertSee($purchasedItem->name);
    }

    public function test_profile_edit_form_is_pre_filled_with_user_information()
    {
        $user = User::factory()->create([
            'profile_image' => 'images/test.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // 2. プロフィール編集ページを開く
        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        // プロフィール画像（初期値）表示確認
        $response->assertSee('/storage/' . $user->profile_image);

        // ユーザー名（初期値）表示確認
        $response->assertSee($user->name);

        // 郵便番号（初期値）表示確認
        $response->assertSee($user->postal_code);

        // 住所（初期値）表示確認
        $response->assertSee($user->address);
    }
}
