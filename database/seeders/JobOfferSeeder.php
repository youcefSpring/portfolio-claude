<?php

namespace Database\Seeders;

use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobOfferSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $jobOffers = [
            [
                'title' => 'Full-Stack Developer Needed',
                'description' => 'Looking for an experienced full-stack developer to build a modern web application. Must have experience with Laravel, Vue.js, and PostgreSQL.',
                'requirements' => "5+ years experience\nPortfolio of completed projects\nAvailable for at least 20 hours/week",
                'project_type' => 'freelance',
                'budget_min' => 5000,
                'budget_max' => 10000,
                'duration' => '3-6 months',
                'location_type' => 'remote',
                'location' => 'Worldwide',
                'status' => 'active',
                'featured' => true,
                'published_at' => now(),
                'user_id' => $user->id,
            ],
            [
                'title' => 'Laravel API Development',
                'description' => 'Need a Laravel developer to create a REST API for a mobile application. Authentication, database design, and documentation required.',
                'requirements' => "Laravel 10+ experience\nREST API best practices\nUnit testing experience",
                'project_type' => 'contract',
                'budget_min' => 3000,
                'budget_max' => 5000,
                'duration' => '1-2 months',
                'location_type' => 'remote',
                'location' => 'USA or Canada preferred',
                'status' => 'active',
                'featured' => false,
                'published_at' => now()->subDays(5),
                'user_id' => $user->id,
            ],
            [
                'title' => 'E-commerce Website Development',
                'description' => 'Looking for a developer to build a complete e-commerce platform with payment integration, inventory management, and admin dashboard.',
                'requirements' => "Previous e-commerce projects\nPayment gateway integration (Stripe/PayPal)\nResponsive design skills",
                'project_type' => 'freelance',
                'budget_min' => 8000,
                'budget_max' => 15000,
                'duration' => '4-6 months',
                'location_type' => 'hybrid',
                'location' => 'San Francisco, CA',
                'status' => 'filled',
                'featured' => true,
                'published_at' => now()->subDays(30),
                'user_id' => $user->id,
            ],
        ];

        foreach ($jobOffers as $offer) {
            JobOffer::create($offer);
        }

        $this->command->info('Created '.count($jobOffers).' job offers.');
    }
}
