<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = fake()->words(2, true);
        
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'image' => null,
            'parent_id' => null,
            'sort_order' => fake()->numberBetween(0, 100),
            'is_active' => fake()->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Create a root category (no parent)
     *
     * @return static
     */
    public function root()
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => null,
        ]);
    }

    /**
     * Create a child category
     *
     * @return static
     */
    public function child()
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => \App\Models\Category::factory(),
        ]);
    }
}
