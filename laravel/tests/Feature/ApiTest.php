<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
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

    public function test_can_get_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson('/api/products');

        $response->assertStatus(200)
                ->assertJson(['success' => true])
                ->assertJsonCount(3, 'data');
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

    public function test_can_get_categories()
    {
        Category::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->getJson('/api/categories');

        $response->assertStatus(200)
                ->assertJson(['success' => true])
                ->assertJsonCount(2, 'data');
    }

    public function test_can_create_category()
    {
        $categoryData = [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'is_active' => true
        ];

        $response = $this->actingAs($this->user)->postJson('/api/categories', $categoryData);

        $response->assertStatus(201)
                ->assertJson(['success' => true])
                ->assertJsonPath('data.name', 'Test Category');
    }
} 