<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_items_are_displayed()
    {
        // 1. 商品ページを開く    
        $items = Item::factory()->count(3)->create();
        $response = $this->get('/');
        $response->assertStatus(200);

        // 全商品の表示を確認
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_all_sold_items_displays_sold()
    {
        $item = Item::factory()->create([
            'is_sold' => true,
        ]);

        // 1. 商品ページを開く
        $response = $this->get('/');
        $response->assertStatus(200);

        // 購入済み商品に「Sold」表示確認
        $response->assertSee($item->name);
        $response->assertSee('Sold');
    }

    public function test_own_items_are_not_displayed()
    {
        $user = User::factory()->create();

        $ownItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
        ]);

        $otherItem = Item::factory()->create([
            'name' => '他人の商品',
        ]);

        // 1. ユーザーにログインする
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);

        // 自分が出品した商品が一覧に表示されないことを確認
        $response->assertDontSee($ownItem->name);
        $response->assertSee($otherItem->name);
    }

    public function test_mylist_displays_only_liked_items()
    {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create([
            'name' => 'いいね商品',
        ]);
        $notLikedItem = Item::factory()->create([
            'name' => 'その他の商品',
        ]);

        Like::factory()->create([
            'user_id'  => $user->id,
            'item_id' => $likedItem->id,
        ]);

        // 1-2. ユーザーにログインして、マイリストページを開く
        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);

        // いいねした商品の表示確認
        $response->assertSee($likedItem->name);
        $response->assertDontSee($notLikedItem->name);
    }

    public function test_all_sold_items_in_mylist_displays_sold()
    {
        $user = User::factory()->create();

        $soldLikedItem = Item::factory()->create([
            'is_sold' => true,
        ]);

        Like::factory()->create([
            'user_id'  => $user->id,
            'item_id' => $soldLikedItem->id,
        ]);

        // 1. ユーザーにログイン
        // 2. マイリストページを開く
        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);

        // マイリストの購入済み商品に「Sold」表示確認
        $response->assertSee($soldLikedItem->name);
        $response->assertSee('Sold');
    }

    public function test_guest_cannot_see_items_in_mylist()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Like::factory()->create([
            'user_id'  => $user->id,
            'item_id' => $item->id,
        ]);

        // 1. ログインせずにマイリストページを開く
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        // 商品が表示されないことを確認
        $response->assertDontSee($item->name);
    }

    public function test_items_can_be_searched_with_keyword()
    {
        $matchedItem = Item::factory()->create([
            'name' => 'テストサンプル商品',
        ]);

        $unmatchedItem = Item::factory()->create([
            'name' => '表示されない',
        ]);

        // 1. 検索欄にキーワードを入力
        $response = $this->get('/?keyword=サンプル');
        $response->assertStatus(200);

        // 部分一致する商品が表示されることを確認
        $response->assertSee($matchedItem->name);
        $response->assertDontSee($unmatchedItem->name);
    }

    public function test_search_keyword_is_preserved_in_mylist()
    {
        $user = User::factory()->create();

        $matchedItem = Item::factory()->create([
            'name' => 'テストサンプル商品',
        ]);

        $unmatchedItem = Item::factory()->create([
            'name' => '表示されない',
        ]);

        Like::factory()->create([
            'user_id'  => $user->id,
            'item_id' => $matchedItem->id,
        ]);

        Like::factory()->create([
            'user_id'  => $user->id,
            'item_id' => $unmatchedItem->id,
        ]);

        // 1. 検索欄にキーワードを入力
        $response = $this->get('/?keyword=サンプル');
        $response->assertStatus(200);

        // 部分一致する商品が表示されることを確認
        $response->assertSee($matchedItem->name);
        $response->assertDontSee($unmatchedItem->name);

        // 2. マイリストページに遷移
        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=サンプル');
        $response->assertStatus(200);

        // 検索状態がマイリストでも保持されていることを確認
        $response->assertSee($matchedItem->name);
        $response->assertDontSee($unmatchedItem->name);
    }
}
