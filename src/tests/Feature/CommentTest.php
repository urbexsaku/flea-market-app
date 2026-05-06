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

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // 初期コメント数0を確認
        $this->assertEquals(0, $item->comments()->count());

        // 2. コメントを入力する
        // 3. コメントボタンを押す
        $response = $this->post('/comment/' . $item->id, [
            'content' => 'テストコメント'
        ]);
        $response->assertRedirect('/item/' . $item->id);

        // コメントが保存されたことを確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);

        $item = $item->fresh();

        $response = $this->get('/item/' . $item->id);

        // アイコン下のコメント数増加を確認
        $response->assertSee('data-testid="comment-count-top">' . $item->comments()->count() . '<', false);

        // コメント欄のコメント数増加を確認
        $response->assertSee('コメント (' . $item->comments()->count() . ')');
    }

    public function test_guest_cannot_post_comment()
    {
        $item = Item::factory()->create();

        // ゲストとしてコメントを入力
        $response = $this->post('/comment/' . $item->id, [
            'content' => 'テストコメント'
        ]);

         // ログイン画面へリダイレクトを確認
        $response->assertRedirect('/login');

        // 入力したコメントが未登録であることを確認
        $this->assertDatabaseMissing('comments', [
            'content' => 'テストコメント',
        ]);
    }

    public function test_validation_message_is_displayed_when_comment_is_empty()
    {
        $user = User::factory()->create();        
        $item = Item::factory()->create();

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // コメント入力せずにコメントボタンを押す
        $response = $this->post('/comment/' . $item->id, [
            'content' => ''
        ]);

        // バリデーションメッセージ表示確認
        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください'
        ]);
    }

    public function test_validation_message_is_displayed_when_comment_is_over_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 1. ユーザーにログインする
        $this->actingAs($user);

        // コメントを255文字以上入力してコメントボタンを押す
        $response = $this->post('/comment/' . $item->id, [
            'content' => str_repeat('あ', 256)
        ]);

        // バリデーションメッセージ表示確認
        $response->assertSessionHasErrors([
            'content' => 'コメントは255文字以下で入力してください'
        ]);
    }
}
