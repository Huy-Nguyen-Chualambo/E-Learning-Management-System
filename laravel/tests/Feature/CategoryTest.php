<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_have_parent()
    {
        $parent = Category::factory()->create(['name' => 'Parent Category']);
        $child = Category::factory()->create(['name' => 'Child Category', 'parent_id' => $parent->id]);

        $this->assertEquals($parent->id, $child->parent_id);
        $this->assertTrue($parent->children->contains($child));
    }

    public function test_category_can_have_products()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create();

        $category->products()->attach($product);

        $this->assertTrue($category->products->contains($product));
        $this->assertTrue($product->categories->contains($category));
    }

    public function test_category_scope_active()
    {
        Category::factory()->create(['is_active' => true]);
        Category::factory()->create(['is_active' => false]);

        $activeCategories = Category::active()->get();

        $this->assertEquals(1, $activeCategories->count());
        $this->assertTrue($activeCategories->first()->is_active);
    }

    public function test_category_scope_root_categories()
    {
        $root = Category::factory()->create(['parent_id' => null]);
        $child = Category::factory()->create(['parent_id' => $root->id]);

        $rootCategories = Category::rootCategories()->get();

        $this->assertEquals(1, $rootCategories->count());
        $this->assertEquals($root->id, $rootCategories->first()->id);
    }
} 