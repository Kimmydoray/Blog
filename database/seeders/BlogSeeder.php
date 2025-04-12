<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user ID
        $adminId = User::where('email', 'admin@example.com')->first()->id;
        
        // Create sample blogs
        Blog::create([
            'title' => 'Getting Started with Laravel',
            'content' => '<h2>Introduction to Laravel</h2><p>Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling.</p><p>Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:</p><ul><li>Simple, fast routing engine</li><li>Powerful dependency injection container</li><li>Multiple back-ends for session and cache storage</li><li>Expressive, intuitive database ORM</li><li>Database agnostic schema migrations</li><li>Robust background job processing</li><li>Real-time event broadcasting</li></ul>',
            'status' => 'published',
            'created_by' => $adminId,
        ]);

        Blog::create([
            'title' => 'Vue.js and Quasar Framework',
            'content' => '<h2>Modern Frontend Development</h2><p>Vue.js is a progressive JavaScript framework for building user interfaces. Unlike other monolithic frameworks, Vue is designed from the ground up to be incrementally adoptable.</p><p>Quasar Framework is a high-performance Vue.js based framework that helps developers quickly create websites, web apps, and mobile apps, all from the same codebase.</p><p>Key features of Quasar include:</p><ul><li>Built on Vue.js</li><li>Material design implementation</li><li>100+ Vue components</li><li>Single codebase for multiple platforms</li><li>Best-in-class build system</li></ul>',
            'status' => 'published',
            'created_by' => $adminId,
        ]);

        Blog::create([
            'title' => 'Working with APIs in Modern Applications',
            'content' => '<h2>API-First Development</h2><p>API-first development is an approach where your applications are built with APIs at the forefront of your development strategy.</p><p>Benefits include:</p><ul><li>Better integration between systems</li><li>Improved developer experience</li><li>More focused, modular codebase</li><li>Easier testing and maintenance</li><li>Future-proofing your application</li></ul><p>Laravel makes API development a breeze with features like API resources, rate limiting, and Passport for OAuth authentication.</p>',
            'status' => 'hidden',
            'created_by' => $adminId,
        ]);
    }
}