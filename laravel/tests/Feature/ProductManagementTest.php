<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'admin']);
        $this->user->roles()->attach($role);
    }

    public function test_can_create_product()
    {
        $category = Category::factory()->create();
        
        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'level' => 'beginner',
            'categories' => [$category->id]
        ];

        $response = $this->actingAs($this->user)->postJson('/api/products', $productData);

        $response->assertStatus(201)
                ->assertJson(['success' => true])
                ->assertJsonPath('data.name', 'Test Product');

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 99.99
        ]);
    }

    public function test_can_search_products()
    {
        Product::factory()->create(['name' => 'Laravel Course']);
        Product::factory()->create(['name' => 'React Course']);

        $response = $this->actingAs($this->user)->getJson('/api/products/search?keyword=Laravel');

        $response->assertStatus(200)
                ->assertJson(['success' => true])
                ->assertJsonCount(1, 'data');
    }

    public function test_product_final_price_accessor()
    {
        $product = Product::factory()->create(['price' => 100, 'sale_price' => 80]);
        
        $this->assertEquals(80, $product->final_price);

        $product2 = Product::factory()->create(['price' => 100, 'sale_price' => null]);
        
        $this->assertEquals(100, $product2->final_price);
    }

    public function test_can_filter_products_by_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create();
        $product->categories()->attach($category);

        $response = $this->actingAs($this->user)->getJson(`/api/products/category/${category->id}`);

        $response->assertStatus(200)
                ->assertJson(['success' => true])
                ->assertJsonCount(1, 'data');
    }
} 