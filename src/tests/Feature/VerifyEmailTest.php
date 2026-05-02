<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use App\Models\User;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_email_is_sent_after_registration()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]); 

        $user = User::where('email', 'test@example.com')->first();

        Notification::assertSentTo( //登録ユーザーへ認証メールが送信されたかことを確認
            $user,
            VerifyEmail::class
        );
    }

    public function test_verified_user_redirects_to_profile_page()
    {
        $user = User::factory()->unverified()->create(); //未認証ユーザー作成

        $verificationUrl = URL::temporarySignedRoute( //認証メールリンク作成
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl); //認証メールリンクアクセス

        $response->assertRedirect('/mypage/profile'); //リダイレクト確認

        $this->assertTrue($user->fresh()->hasVerifiedEmail()); //認証確認
    } 
}
