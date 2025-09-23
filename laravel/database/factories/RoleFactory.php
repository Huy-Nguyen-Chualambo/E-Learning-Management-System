<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
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
     * Create a super admin role
     *
     * @return static
     */
    public function superAdmin()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'super-admin',
            'display_name' => 'Super Administrator',
            'description' => 'Full access to all system features',
        ]);
    }

    /**
     * Create an admin role
     *
     * @return static
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Admin access with some restrictions',
        ]);
    }
}
