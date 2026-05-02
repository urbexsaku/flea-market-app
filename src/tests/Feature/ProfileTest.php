<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_detail_is_displayed()
    {
        $user = User::factory()->create([
            'profile_image' => 'images/test.jpg',
        ]);

        $sellingItem = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $purchasedItem = Item::factory()->create();

        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage');
        $response->assertSee('/storage/' . $user->profile_image);
        $response->assertSee($user->name);

        $response = $this->get('/mypage?page=sell');
        $response->assertSee($sellingItem->name);

        $response = $this->get('/mypage?page=buy');
        $response->assertSee($purchasedItem->name);
    }

    public function test_profile_edit_form_is_pre_filled_with_user_information()
    {
        $user = User::factory()->create([
            'profile_image' => 'images/test.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertSee('/storage/' . $user->profile_image);
        $response->assertSee($user->name);
        $response->assertSee($user->postal_code);
        $response->assertSee($user->address);
    }
}
