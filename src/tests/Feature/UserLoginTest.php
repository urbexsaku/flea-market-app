<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_message_is_displayed_when_email_is_empty()
    {
        // 1. ログインページにアクセスできることを確認
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 2. メールアドレス未入力でログインボタンを押す
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        // バリデーションメッセージ表示確認
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    public function test_validation_message_is_displayed_when_password_is_empty()
    {
        // 1. ログインページにアクセスできることを確認
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 2. パスワード未入力でログインボタンを押す
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        // バリデーションメッセージ表示確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);
    }

    public function test_validation_message_is_displayed_when_credentials_are_invalid()
    {
        // 1. ログインページにアクセスできることを確認
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 2. 未登録情報でログインボタンを押す
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    
        $response = $this->post('/login', [
            'email' => 'unknown@example.com',
            'password' => '12345678',
        ]);

        // バリデーションメッセージ表示確認
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません'
        ]);
    }

    public function test_user_can_login()
    {
        // 1. ログインページにアクセスできることを確認
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 2. 正しい情報を入力してログイン実行
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // ログイン状態を確認
        $this->assertAuthenticated();

        $response->assertRedirect('/');
    }
}