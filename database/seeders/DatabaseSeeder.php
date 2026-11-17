<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'name' => 'Fintech Payment Gateway Integration',
            'description' => 'Secure payment processing API with merchant dashboard and webhooks.',
        ])->tasks()->createMany([
            ['title' => 'Setup OAuth2 authentication and API tokens', 'is_completed' => true],
            ['title' => 'Implement payment webhook listener', 'is_completed' => true],
            ['title' => 'Build transaction reconciliation report', 'is_completed' => false],
            ['title' => 'Integrate refund processing workflow', 'is_completed' => false],
        ]);

        Project::create([
            'name' => 'Healthcare Patient Booking Portal',
            'description' => 'Online appointment scheduling and doctor availability calendar.',
        ])->tasks()->createMany([
            ['title' => 'Configure automated SMS appointment reminders', 'is_completed' => true],
            ['title' => 'Design doctor availability calendar view', 'is_completed' => true],
        ]);

        Project::create([
            'name' => 'Cloud Infrastructure Migration',
            'description' => 'Containerizing services and migrating staging cluster to Kubernetes.',
        ]);
    }
}
