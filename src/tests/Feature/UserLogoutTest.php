<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserLogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout()
    {
        
        // 1. ユーザーにログインする
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2. ログアウトボタンを押す
        $response = $this->post('/logout');

        // ログアウト処理確認
        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
