<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_exhibit_an_item()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/sell', [
            'categories' => [$category->id],
            'condition' => 1,
            'name' => '商品名',
            'brand' => 'ブランド',
            'description' => '商品の説明',
            'price' => 1000,
            'image' => UploadedFile::fake()->create('test.jpg', 100),
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition' => 1,
            'name' => '商品名',
            'brand' => 'ブランド',
            'description' => '商品の説明',
            'price' => 1000,
            'is_sold' => false,
        ]);

        $item = Item::where('user_id', $user->id)
            ->where('name', '商品名')
            ->first();

        $this->assertDatabaseHas('category_item',[
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);
    }
}
