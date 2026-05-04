<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_can_post_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->assertEquals(0, $item->comments()->count());

        $this->post('/comment/' . $item->id, [
            'content' => 'テストコメント'
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);

        $item = $item->fresh();

        $response = $this->get('/item/' . $item->id);
        $response->assertSee('data-testid="comment-count-top">' . $item->comments()->count() . '<', false);
        $response->assertSee('コメント (' . $item->comments()->count() . ')');
    }

    public function test_guest_cannot_post_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post('/comment/' . $item->id, [
            'content' => 'テストコメント'
        ]);

        $response->assertRedirect('/login'); // ログイン画面へリダイレクトを確認

        $this->assertDatabaseMissing('comments', [ // DB未登録を確認
            'content' => 'テストコメント',
        ]);
    }

    public function test_comment_is_required()
    {
        $user = User::factory()->create();        
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/comment/' . $item->id, [
            'content' => ''
        ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください'
        ]);
    }

    public function test_comment_needs_to_be_less_than_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/comment/' . $item->id, [
            'content' => 'テストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメントテストコメント'
        ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントは255文字以下で入力してください'
        ]);
    }
}
