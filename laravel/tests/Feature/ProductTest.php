<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_have_categories()
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();

        $product->categories()->attach($category);

        $this->assertTrue($product->categories->contains($category));
        $this->assertTrue($category->products->contains($product));
    }

    public function test_product_scope_active()
    {
        Product::factory()->create(['is_active' => true]);
        Product::factory()->create(['is_active' => false]);

        $activeProducts = Product::active()->get();

        $this->assertEquals(1, $activeProducts->count());
        $this->assertTrue($activeProducts->first()->is_active);
    }

    public function test_product_scope_featured()
    {
        Product::factory()->create(['is_featured' => true]);
        Product::factory()->create(['is_featured' => false]);

        $featuredProducts = Product::featured()->get();

        $this->assertEquals(1, $featuredProducts->count());
        $this->assertTrue($featuredProducts->first()->is_featured);
    }

    public function test_product_final_price_accessor()
    {
        $product = Product::factory()->create(['price' => 100, 'sale_price' => 80]);
        
        $this->assertEquals(80, $product->final_price);

        $product2 = Product::factory()->create(['price' => 100, 'sale_price' => null]);
        
        $this->assertEquals(100, $product2->final_price);
    }
}