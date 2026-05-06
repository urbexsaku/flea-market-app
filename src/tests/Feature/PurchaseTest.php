<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_purchase_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // 2. 商品購入画面を開く
        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);

        // 3. 「購入する」ボタンを押下
        $response = $this->post('/purchase/' . $item->id, [
            'payment_method' => '1',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        // リダイレクト確認（購入完了確認）
        $response->assertRedirect('/');

        // データベース登録確認（購入完了確認）
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_purchased_item_is_marked_sold_in_item_list()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // 2. 商品購入画面を開く
        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);

        // 3. 「購入する」ボタンを押下
        $this->post('/purchase/' . $item->id, [
            'payment_method' => '1',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        // 4. 商品一覧画面を表示する
        $response = $this->get('/');

        // 5. 購入した商品が「Sold」として表示される
        $response->assertSee($item->name);
        $response->assertSee('Sold');
    }

    public function test_purchased_item_is_displayed_in_mypage_purchase_history()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // 2. 商品購入画面を開く
        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);

        // 3. 「購入する」ボタンを押下
        $this->post('/purchase/' . $item->id, [
            'payment_method' => '1',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        // 4. プロフィール画面（購入した商品一覧タブ）を表示する
        $response = $this->get('/mypage?page=buy');

        // 5. 商品一覧に追加されていることを確認
        $response->assertSee($item->name);
    }

    public function test_shipping_address_is_displayed_in_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // 2. 送付先住所変更画面で住所を登録する
        $response = $this->post('/purchase/address/' . $item->id, [
            'payment_method' => '1',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response->assertRedirect('/purchase/' . $item->id);

        // 3. 商品購入画面を再度開く
        $response = $this->get('/purchase/' . $item->id);

        // 登録した住所が正しく反映されることを確認
        $response->assertSee('東京都渋谷区1-1-1');
    }
}
