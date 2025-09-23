<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = fake()->unique()->word();
        
        return [
            'name' => $name,
            'display_name' => ucwords(str_replace('-', ' ', $name)),
            'description' => fake()->sentence(),
        ];
    }

    /**
     * Create a view permission
     *
     * @return static
     */
    public function view()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'view-' . fake()->word(),
            'display_name' => 'View ' . ucwords(fake()->word()),
            'description' => 'Can view ' . fake()->word(),
        ]);
    }

    /**
     * Create a create permission
     *
     * @return static
     */
    public function create()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'create-' . fake()->word(),
            'display_name' => 'Create ' . ucwords(fake()->word()),
            'description' => 'Can create ' . fake()->word(),
        ]);
    }
} 