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
        $user = User::factory()->create();
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


        $response = $this->get('/item/' . $item->id);

        $response->assertSee('storage/' . $item->image);
        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->likes->count());
        $response->assertSee('data-testid="comment-count-top">' . $item->comments->count() . '<', false); // ボタン下のコメント数確認 HTMLとして比較 
        $response->assertSee($category->content);
        $response->assertSee($item->description);
        $response->assertSee($item->condition_text);
        $response->assertSee('コメント (' . $item->comments()->count() .')'); // コメント欄のコメント数確認
        $response->assertSee($comment->user->name);
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

        $response = $this->get('/item/' . $item->id);

        $response->assertSee($category1->content);
        $response->assertSee($category2->content);
    }
}
