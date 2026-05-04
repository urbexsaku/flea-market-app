<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $this->actingAs($user);

        $response = $this->post('/purchase/' . $item->id, [
            'payment_method' => '1',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response->assertStatus(302); // リダイレクト確認

        $this->assertDatabaseHas('purchases', [ // DB保存確認
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_purchased_item_is_marked_sold_in_item_list()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->post('/purchase/' . $item->id, [
            'payment_method' => '1',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response = $this->get('/');

        $response->assertSee('Sold');
    }

    public function test_purchased_item_is_displayed_in_mypage_purchase_history()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->post('/purchase/' . $item->id, [
            'payment_method' => '1',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response = $this->get('/mypage?page=buy');

        $response->assertSee($item->name);
    }

    public function test_shipping_address_is_displayed_in_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/purchase/address/' . $item->id, [
            'payment_method' => '1',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response->assertStatus(302);

        $response = $this->get('/purchase/' . $item->id);
        $response->assertSee('東京都渋谷区1-1-1');
    }
}
