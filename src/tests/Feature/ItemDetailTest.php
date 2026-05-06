<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Like;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_details_are_displayed()
    {
        $user = User::factory()->create([
            'profile_image' => 'images/test.jpg',
        ]);
        $item = Item::factory()->create();
        $category = Category::factory()->create();
        $item->categories()->attach($category->id);

        Like::factory()->create([
            'user_id'  => $user->id,
            'item_id' => $item->id,
        ]);

        $comment = Comment::factory()->create([
            'user_id'  => $user->id,
            'item_id' => $item->id,
            'content' => 'コメントテスト',
        ]);

        // 1. 商品詳細ページを開く
        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        // 商品画像表示確認
        $response->assertSee('storage/' . $item->image);

        // 商品名表示確認
        $response->assertSee($item->name);

        // ブランド名表示確認
        $response->assertSee($item->brand);

        // 価格表示確認
        $response->assertSee(number_format($item->price));

        // いいね数表示確認
        $response->assertSee($item->likes->count());

        // コメント数表示（ボタン下）確認
        $response->assertSee('data-testid="comment-count-top">' . $item->comments->count() . '<', false);

        // 商品説明表示確認
        $response->assertSee($item->description);

        // カテゴリ表示確認
        $response->assertSee($category->content);

        // 商品の状態表示確認
        $response->assertSee($item->condition_text);

        // コメント欄のコメント数表示確認
        $response->assertSee('コメント (' . $item->comments()->count() .')');

        // コメントしたユーザー情報（ユーザー名と画像）表示確認
        $response->assertSee($comment->user->name);
        $response->assertSee('storage/' . $comment->user->profile_image);

        // コメント内容表示確認
        $response->assertSee($comment->content);
    }

    public function test_multiple_categories_are_displayed()
    {
        $item = Item::factory()->create();

        $category1 = Category::factory()->create();

        $category2 = Category::factory()->create();

        $item->categories()->attach([
            $category1->id,
            $category2->id,
        ]);

        // 1. 商品詳細ページを開く
        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        // 複数選択されたカテゴリ表示を確認
        $response->assertSee($category1->content);
        $response->assertSee($category2->content);
    }
}
