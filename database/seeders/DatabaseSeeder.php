<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'name' => 'Manufacturing ERP System',
            'description' => 'Inventory, purchasing and sales for a packaging factory.',
        ])->tasks()->createMany([
            ['title' => 'Design database', 'is_completed' => true],
            ['title' => 'Build login module', 'is_completed' => true],
            ['title' => 'Create inventory API', 'is_completed' => false],
            ['title' => 'Build inventory screens', 'is_completed' => false],
        ]);

        Project::create([
            'name' => 'Insurance Management Portal',
            'description' => 'Policies and claims portal for an insurance company.',
        ])->tasks()->createMany([
            ['title' => 'Policy module', 'is_completed' => true],
            ['title' => 'Claims dashboard', 'is_completed' => true],
        ]);

        Project::create([
            'name' => 'Company Portfolio Website',
            'description' => 'Marketing website. Tasks not planned yet.',
        ]);
    }
}
