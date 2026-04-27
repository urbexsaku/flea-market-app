<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_items_are_displayed()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get('/');

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_all_sold_items_displays_sold()
    {
        $item = Item::factory()->create([
            'is_sold' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee($item->name);
        $response->assertSee('Sold');
    }

    public function test_own_items_are_not_displayed()
    {
        $user = User::factory()->create();
    
        $ownItem = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherItem = Item::factory()->create();

        $response = $this->get('/');

        $response->assertDontSee($ownItem->name);
        $response->assertSee($otherItem->name);
    }

    public function test_mylist_displays_only_liked_items()
    {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create();
        $notLikedItem = Item::factory()->create();

        Like::factory()->create([
            'user_id'  => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee($likedItem->name);
        $response->assertDontSee($notLikedItem->name);
    }

    public function test_all_sold_items_in_mylist_displays_sold()
    {
        $user = User::factory()->create();

        $soldLikedItem = Item::factory()->create([
            'is_sold' => true,
        ]);

        Like::factory()->create([
            'user_id'  => $user->id,
            'item_id' => $soldLikedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee($soldLikedItem->name);
        $response->assertSee('Sold');
    }
}
