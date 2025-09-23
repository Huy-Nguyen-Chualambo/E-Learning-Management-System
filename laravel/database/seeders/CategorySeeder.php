<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Tạo categories cấp 1
        $programming = Category::create([
            'name' => 'Programming',
            'slug' => Str::slug('Programming'),
            'description' => 'Programming courses and tutorials',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $design = Category::create([
            'name' => 'Design',
            'slug' => Str::slug('Design'),
            'description' => 'Design courses and tutorials',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $business = Category::create([
            'name' => 'Business',
            'slug' => Str::slug('Business'),
            'description' => 'Business courses and tutorials',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Tạo categories cấp 2 cho Programming
        Category::create([
            'name' => 'Web Development',
            'slug' => Str::slug('Web Development'),
            'description' => 'Web development courses',
            'parent_id' => $programming->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Mobile Development',
            'slug' => Str::slug('Mobile Development'),
            'description' => 'Mobile development courses',
            'parent_id' => $programming->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Data Science',
            'slug' => Str::slug('Data Science'),
            'description' => 'Data science courses',
            'parent_id' => $programming->id,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Tạo categories cấp 2 cho Design
        Category::create([
            'name' => 'UI/UX Design',
            'slug' => Str::slug('UI/UX Design'),
            'description' => 'UI/UX design courses',
            'parent_id' => $design->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Graphic Design',
            'slug' => Str::slug('Graphic Design'),
            'description' => 'Graphic design courses',
            'parent_id' => $design->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Tạo categories cấp 2 cho Business
        Category::create([
            'name' => 'Marketing',
            'slug' => Str::slug('Marketing'),
            'description' => 'Marketing courses',
            'parent_id' => $business->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Finance',
            'slug' => Str::slug('Finance'),
            'description' => 'Finance courses',
            'parent_id' => $business->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);
    }
} 