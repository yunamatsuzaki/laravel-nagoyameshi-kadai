<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    //indexアクション（店舗一覧ページ）
    public function test_guest_cannot_access_admin_restaurants_index()
    {
        $response = $this->get(route('admin.restaurants.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_cannot_access_admin_restaurants_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.restaurants.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_admin_restaurants_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.index'));
        $response->assertStatus(200);
    }

    //showアクション（店舗詳細ページ）
    public function test_guest_cannot_access_admin_restaurants_show()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('admin.restaurants.show', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_cannot_access_admin_restaurants_show()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.restaurants.show', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_admin_restaurants_show()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.show', $restaurant));
        $response->assertStatus(200);
    }

    //createアクション（店舗登録ページ）
    public function test_guest_cannot_access_admin_restaurants_create()
    {
        $response = $this->get(route('admin.restaurants.create'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_cannot_access_admin_restaurants_create()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.restaurants.create'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_admin_restaurants_create()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.create'));
        $response->assertStatus(200);
    }

    //storeアクション（店舗登録機能）
    public function test_guest_cannot_access_admin_restaurants_store()
    {
        $categories = Category::factory()->count(3)->create();
        $category_ids = $categories->pluck('id')->toArray();

        $restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'seating_capacity' => 50,
            'category_ids' => $category_ids,
        ];

        $response = $this->post(route('admin.restaurants.store'), $restaurant_data);
        unset($restaurant_data['category_ids']);
        $this->assertDatabaseMissing('restaurants', $restaurant_data);
        foreach ($category_ids as $category_id) {
            $this->assertDatabaseMissing('category_restaurant', ['category_id' => $category_id]);
        }
        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_cannot_access_admin_restaurants_store()
    {
        $user = User::factory()->create();
        $categories = Category::factory()->count(3)->create();
        $category_ids = $categories->pluck('id')->toArray();

        $restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'seating_capacity' => 50,
            'category_ids' => $category_ids,
        ];

        $response = $this->actingAs($user)->post(route('admin.restaurants.store'), $restaurant_data);
        unset($restaurant_data['category_ids']);
        $this->assertDatabaseMissing('restaurants', $restaurant_data);
        foreach ($category_ids as $category_id) {
            $this->assertDatabaseMissing('category_restaurant', ['category_id' => $category_id]);
        }
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_admin_restaurants_store()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $categories = Category::factory()->count(3)->create();
        $category_ids = $categories->pluck('id')->toArray();

        $restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'seating_capacity' => 50,
            'category_ids' => $category_ids,
        ];

        $response = $this->actingAs($admin, 'admin')->post(route('admin.restaurants.store'), $restaurant_data);
        unset($restaurant_data['category_ids']);
        $this->assertDatabaseHas('restaurants', $restaurant_data);
        $restaurant = \App\Models\Restaurant::latest()->first();
        foreach ($category_ids as $category_id) {
            $this->assertDatabaseHas('category_restaurant', ['restaurant_id' => $restaurant->id, 'category_id' => $category_id]);
        }
        $response->assertRedirect(route('admin.restaurants.index'));
    }

    //editアクション（店舗編集ページ）
    public function test_guest_cannot_access_admin_restaurants_edit()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('admin.restaurants.edit', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_cannot_access_admin_restaurants_edit()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.restaurants.edit', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_admin_restaurants_edit()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.edit', $restaurant));
        $response->assertStatus(200);
    }

    //updateアクション（店舗更新機能）
    public function test_guest_cannot_access_admin_restaurants_update()
{
    $old_restaurant = Restaurant::factory()->create();
    $categories = Category::factory()->count(3)->create();
    $category_ids = $categories->pluck('id')->toArray();

    $new_restaurant_data = [
        'name' => 'テスト更新',
        'description' => 'テスト更新',
        'lowest_price' => 5000,
        'highest_price' => 10000,
        'postal_code' => '1234567',
        'address' => 'テスト更新',
        'opening_time' => '13:00:00',
        'closing_time' => '23:00:00',
        'seating_capacity' => 100,
        'category_ids' => $category_ids,
    ];

    $response = $this->patch(route('admin.restaurants.update', $old_restaurant), $new_restaurant_data);
    unset($new_restaurant_data['category_ids']);
    
    $this->assertDatabaseMissing('restaurants', $new_restaurant_data);
    // old_restaurantのデータでタイムスタンプを無視してアサート
    $this->assertDatabaseHas('restaurants', [
        'id' => $old_restaurant->id,
        'name' => $old_restaurant->name,
        'description' => $old_restaurant->description,
        'lowest_price' => $old_restaurant->lowest_price,
        'highest_price' => $old_restaurant->highest_price,
        'postal_code' => $old_restaurant->postal_code,
        'address' => $old_restaurant->address,
        'opening_time' => $old_restaurant->opening_time,
        'closing_time' => $old_restaurant->closing_time,
        'seating_capacity' => $old_restaurant->seating_capacity,
    ]);
    
    $response->assertRedirect(route('admin.login'));
}

public function test_user_cannot_access_admin_restaurants_update()
{
    $user = User::factory()->create();
    $old_restaurant = Restaurant::factory()->create();
    $categories = Category::factory()->count(3)->create();
    $category_ids = $categories->pluck('id')->toArray();

    $new_restaurant_data = [
        'name' => 'テスト更新',
        'description' => 'テスト更新',
        'lowest_price' => 5000,
        'highest_price' => 10000,
        'postal_code' => '1234567',
        'address' => 'テスト更新',
        'opening_time' => '13:00:00',
        'closing_time' => '23:00:00',
        'seating_capacity' => 100,
        'category_ids' => $category_ids,
    ];

    $response = $this->actingAs($user)->patch(route('admin.restaurants.update', $old_restaurant), $new_restaurant_data);
    unset($new_restaurant_data['category_ids']);
    
    $this->assertDatabaseMissing('restaurants', $new_restaurant_data);
    // old_restaurantのデータでタイムスタンプを無視してアサート
    $this->assertDatabaseHas('restaurants', [
        'id' => $old_restaurant->id,
        'name' => $old_restaurant->name,
        'description' => $old_restaurant->description,
        'lowest_price' => $old_restaurant->lowest_price,
        'highest_price' => $old_restaurant->highest_price,
        'postal_code' => $old_restaurant->postal_code,
        'address' => $old_restaurant->address,
        'opening_time' => $old_restaurant->opening_time,
        'closing_time' => $old_restaurant->closing_time,
        'seating_capacity' => $old_restaurant->seating_capacity,
    ]);
    
    $response->assertRedirect(route('admin.login'));
}
    //destroyアクション（店舗削除機能）
    public function test_guest_cannot_access_admin_restaurants_destroy()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->delete(route('admin.restaurants.destroy', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_cannot_access_admin_restaurants_destroy()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($user)->delete(route('admin.restaurants.destroy', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_admin_restaurants_destroy()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->delete(route('admin.restaurants.destroy', $restaurant));
        $this->assertDatabaseMissing('restaurants', $restaurant->toArray());
        $response->assertRedirect(route('admin.restaurants.index'));
    }
}