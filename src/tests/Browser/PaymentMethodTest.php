<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Item;

class PaymentMethodTest extends DuskTestCase
{
    use DatabaseMigrations;
    
    public function test_selected_payment_method_is_updated_in_summary()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $item){
            $browser->loginAs($user)
                ->visit('/purchase/' . $item->id)
                ->waitFor('#payment', 5) //id=paymentの要素が表示されるまで最大5秒待つ
                
                ->select('#payment', '1')
                ->pause(500)
                ->assertSeeIn('#paymentText', 'コンビニ支払い');
        });
     }
}
