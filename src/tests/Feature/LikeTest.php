<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // 2. 商品詳細ページを開く
        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        // 初期いいね数0を確認
        $response->assertSee('id="likeCount">0<', false);

        // いいねアイコンを押下
        $response = $this->post('/like/' . $item->id);
        $response->assertStatus(200);

        // いいねした商品として登録を確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // いいね合計値の増加を確認
        $response = $this->get('/item/' . $item->id);
        $response->assertSee('id="likeCount">1<', false);
    }

    public function test_like_icon_changes_when_an_item_is_liked()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // 2. 商品詳細ページを開く
        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        // いいねアイコンデフォルト（白）表示を確認
        $response->assertSee('images/heart-logo-default.png');

        // いいねアイコンを押下
        $response = $this->post('/like/' . $item->id);
        $response->assertStatus(200);

        // いいねアイコンピンク色への変化を確認
        $response = $this->get('/item/' . $item->id);
        $response->assertSee('images/heart-logo-pink.png');
    }

    public function test_user_can_unlike_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // 2. 商品詳細ページを開く
        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        // いいねアイコンを押下
        $response = $this->post('/like/' . $item->id);
        $response->assertStatus(200);

        // いいね数1を確認
        $response = $this->get('/item/' . $item->id);
        $response->assertSee('id="likeCount">1<', false);

        // 再度いいねアイコンを押下
        $response = $this->post('/like/' . $item->id);
        $response->assertStatus(200);

        // いいねした商品の登録解除を確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // いいね合計値の減少を確認
        $response = $this->get('/item/' . $item->id);
        $response->assertSee('id="likeCount">0<', false);
    }
}
