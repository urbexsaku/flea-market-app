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

        $this->actingAs($user);

        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee('id="likeCount">0<', false); //初期いいね数0を確認

        $this->post('/like/' . $item->id); //いいね実行

        $this->assertDatabaseHas('likes', [ //DB保存確認
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/item/' . $item->id);
        $response->assertSee('id="likeCount">1<', false); //いいね数1へ増加を確認
    }

    public function test_like_icon_changes_when_an_item_is_liked()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/item/' . $item->id);
        $response->assertSee('images/heart-logo-default.png'); //初期（白）を確認
        
        $this->post('/like/' . $item->id); //いいね実行

        $response = $this->get('/item/' . $item->id);
        $response->assertSee('images/heart-logo-pink.png'); //ピンクを確認
    }

    public function test_user_can_unlike_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);
        $this->post('/like/' . $item->id); //いいね実行

        $response = $this->get('/item/' . $item->id);
        $response->assertSee('id="likeCount">1<', false); //いいね数1を確認

        $this->post('/like/' . $item->id); //いいね解除

        $this->assertDatabaseMissing('likes', [ //DBにないことを確認
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/item/' . $item->id);
        $response->assertSee('id="likeCount">0<', false); //いいね数0を確認
    }

}
