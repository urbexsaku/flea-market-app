<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use App\Models\User;

class EmailVerificationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_move_to_email_verification_site() {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->browse(function (Browser $browser) use ($user)
        {
            // 1. メール認証導線画面を表示
            // 2. 「認証はこちらから」ボタン押下
            $browser->loginAs($user)
                ->visit('/email/verify')
                ->click('.verify__link');
            
            // ウィンドウタブ一覧を取得
            $handles = $browser->driver->getWindowHandles();

            // 新しいウィンドウタブが開いたことを確認
            $this->assertCount(2, $handles);

            // 新しいウィンドウタブへ切り替え
            $browser->driver->switchTo()->window($handles[1]);

            // メール認証サイトURLへ遷移したことを確認
            $browser->pause(1000)
                ->assertUrlIs('http://localhost:8025/');
        });
    }
}
