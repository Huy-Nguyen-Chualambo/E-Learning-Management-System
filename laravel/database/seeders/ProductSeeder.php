<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Lấy instructor (admin users)
        $instructors = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'super-admin', 'manager']);
        })->get();

        if ($instructors->isEmpty()) {
            $instructors = User::take(1)->get();
        }

        // Lấy categories
        $categories = Category::all();

        $courses = [
            [
                'name' => 'Laravel Fundamentals',
                'description' => 'Learn the basics of Laravel PHP framework from scratch. Perfect for beginners who want to start web development with Laravel.',
                'content' => 'This comprehensive course covers Laravel installation, routing, controllers, views, database operations, and more.',
                'price' => 0.00,
                'duration_hours' => 20,
                'level' => 'beginner',
                'status' => 'active',
            ],
            [
                'name' => 'Advanced JavaScript Concepts',
                'description' => 'Master advanced JavaScript concepts including closures, prototypes, async/await, and modern ES6+ features.',
                'content' => 'Deep dive into JavaScript with practical examples and real-world projects.',
                'price' => 49.99,
                'duration_hours' => 35,
                'level' => 'advanced',
                'status' => 'active',
            ],
            [
                'name' => 'React for Beginners',
                'description' => 'Learn React.js from the ground up. Build modern, interactive user interfaces with React components.',
                'content' => 'Step-by-step guide to React development with hands-on projects.',
                'price' => 29.99,
                'duration_hours' => 25,
                'level' => 'beginner',
                'status' => 'active',
            ],
            [
                'name' => 'Database Design & MySQL',
                'description' => 'Master database design principles and MySQL administration. Learn to create efficient, scalable databases.',
                'content' => 'Complete guide to database design, normalization, indexing, and MySQL optimization.',
                'price' => 39.99,
                'duration_hours' => 30,
                'level' => 'intermediate',
                'status' => 'active',
            ],
            [
                'name' => 'Python Data Science',
                'description' => 'Learn data science with Python using pandas, numpy, matplotlib, and scikit-learn.',
                'content' => 'Comprehensive data science course with real datasets and practical projects.',
                'price' => 59.99,
                'duration_hours' => 40,
                'level' => 'intermediate',
                'status' => 'active',
            ],
            [
                'name' => 'Web Design with CSS Grid & Flexbox',
                'description' => 'Master modern CSS layout techniques with Grid and Flexbox. Create responsive, beautiful websites.',
                'content' => 'Modern CSS layout course with practical exercises and real-world examples.',
                'price' => 19.99,
                'duration_hours' => 15,
                'level' => 'beginner',
                'status' => 'active',
            ],
        ];

        foreach ($courses as $courseData) {
            $course = Product::create([
                ...$courseData,
                'instructor_id' => $instructors->random()->id,
            ]);

            // Attach random categories
            if ($categories->isNotEmpty()) {
                $course->categories()->attach(
                    $categories->random(rand(1, min(3, $categories->count())))->pluck('id')
                );
            }
        }

        // Enroll some users in courses
        $this->enrollUsersInCourses();
    }

    private function enrollUsersInCourses()
    {
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->get();

        $courses = Product::where('status', 'active')->get();

        foreach ($users as $user) {
            // Enroll each user in 1-3 random courses
            $randomCourses = $courses->random(rand(1, min(3, $courses->count())));
            
            foreach ($randomCourses as $course) {
                $status = collect(['enrolled', 'in_progress', 'completed'])->random();
                $progress = match($status) {
                    'enrolled' => 0,
                    'in_progress' => rand(10, 80),
                    'completed' => 100,
                };

                $user->courses()->attach($course->id, [
                    'status' => $status,
                    'progress_percentage' => $progress,
                    'enrolled_at' => now()->subDays(rand(1, 30)),
                    'completed_at' => $status === 'completed' ? now()->subDays(rand(1, 10)) : null,
                ]);
            }
        }
    }
}