<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use App\Models\User;

class EmailVerificationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_move_to_email_verification_site()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/email/verify')
                ->click('.verify__link')
                ->pause(1000);
            
            $handles = $browser->driver->getWindowHandles(); //タブ一覧取得

            $this->assertCount(2, $handles); //新しいタブが開いたことを確認

            $browser->driver->switchTo()->window($handles[1]); //新しいタブへ切り替え

            $browser->pause(1000)
                    ->assertUrlIs('http://localhost:8025/'); //メール認証サイトURLへ遷移したことを確認
        });
    }
}
