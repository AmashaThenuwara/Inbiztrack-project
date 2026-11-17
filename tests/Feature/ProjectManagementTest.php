<?php

namespace Tests\Feature;

use App\Livewire\Projects\Index;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_project_can_be_created(): void
    {
        Livewire::test(Index::class)
            ->set('name', 'Manufacturing ERP')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', ['name' => 'Manufacturing ERP']);
    }

    public function test_project_name_is_required(): void
    {
        Livewire::test(Index::class)
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_a_project_can_be_updated(): void
    {
        $project = Project::factory()->create(['name' => 'Old name']);

        Livewire::test(Index::class)
            ->call('editProject', $project->id)
            ->set('name', 'New name')
            ->call('save');

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'New name']);
    }

    public function test_list_shows_completed_and_total_tasks(): void
    {
        $project = Project::factory()->create();
        Task::factory()->completed()->count(3)->for($project)->create();
        Task::factory()->count(2)->for($project)->create();

        Livewire::test(Index::class)->assertSee('3/5 Tasks Completed');
    }

    public function test_project_with_incomplete_tasks_cannot_be_deleted(): void
    {
        $project = Project::factory()->create();
        Task::factory()->count(2)->for($project)->create();
        Task::factory()->completed()->for($project)->create();

        Livewire::test(Index::class)
            ->call('deleteProject', $project->id)
            ->assertSee('Cannot delete project: There are 2 uncompleted tasks.');

        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function test_project_with_only_completed_tasks_is_deleted_with_its_tasks(): void
    {
        $project = Project::factory()->create();
        Task::factory()->completed()->count(2)->for($project)->create();

        Livewire::test(Index::class)->call('deleteProject', $project->id);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_project_without_tasks_can_be_deleted(): void
    {
        $project = Project::factory()->create();

        Livewire::test(Index::class)->call('deleteProject', $project->id);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
