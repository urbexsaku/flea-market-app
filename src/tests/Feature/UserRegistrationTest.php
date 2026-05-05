<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_message_is_displayed_when_name_is_empty()
    {
        // 1. 会員登録ページにアクセスできることを確認
        $response = $this->get('/register');
        $response->assertStatus(200);

        // 2. 名前未入力で登録ボタンを押す
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // バリデーションメッセージ表示確認
        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);
    }

    public function test_validation_message_is_displayed_when_email_is_empty()
    {
        // 1. 会員登録ページにアクセスできることを確認
        $response = $this->get('/register');
        $response->assertStatus(200);

        // 2. メールアドレス未入力で登録ボタンを押す        
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // バリデーションメッセージ表示確認
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    public function test_validation_message_is_displayed_when_password_is_empty()
    {
        // 1. 会員登録ページにアクセスできることを確認
        $response = $this->get('/register');
        $response->assertStatus(200);

        // 2. パスワード未入力で登録ボタンを押す
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        // バリデーションメッセージ表示確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);
    }

    public function test_validation_message_is_displayed_when_password_is_less_than_8_characters()
    {
        // 1. 会員登録ページにアクセスできることを確認
        $response = $this->get('/register');
        $response->assertStatus(200);

        // 2. パスワード7文字以下で登録ボタンを押す
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        // バリデーションメッセージ表示確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください'
        ]);
    }

    public function test_validation_message_is_displayed_when_password_confirmation_does_not_match()
    {
        // 1. 会員登録ページにアクセスできることを確認
        $response = $this->get('/register');
        $response->assertStatus(200);

        // 2. 確認用パスワード不一致で登録ボタンを押す
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'different',
        ]);

        // バリデーションメッセージ表示確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードと一致しません'
        ]);
    }

    public function test_user_is_registered_and_redirected_to_profile_page()
    {
        // 1. 会員登録ページにアクセスできることを確認
        $response = $this->get('/register');
        $response->assertStatus(200);

        // 2. 必須項目を入力して登録ボタンを押す
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // 会員情報登録を確認
        $this->assertDatabaseHas('users', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        $this->assertAuthenticated();

        // プロフィール設定画面への遷移を確認
        $response->assertRedirect('/mypage/profile');
    }
}
