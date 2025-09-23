<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = fake()->words(3, true);
        $price = fake()->randomFloat(2, 50, 500);
        $salePrice = fake()->boolean(30) ? fake()->randomFloat(2, 30, $price * 0.8) : null;
        
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'image' => null,
            'price' => $price,
            'sale_price' => $salePrice,
            'duration' => fake()->numberBetween(300, 1800), // 5-30 hours in minutes
            'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'is_active' => fake()->boolean(85), // 85% chance of being active
            'is_featured' => fake()->boolean(20), // 20% chance of being featured
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * Create a featured product
     *
     * @return static
     */
    public function featured()
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_active' => true,
        ]);
    }

    /**
     * Create a beginner level product
     *
     * @return static
     */
    public function beginner()
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'beginner',
        ]);
    }

    /**
     * Create an advanced level product
     *
     * @return static
     */
    public function advanced()
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'advanced',
        ]);
    }
}
