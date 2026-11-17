<?php

namespace Tests\Feature;

use App\Livewire\Projects\Show;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_task_can_be_added(): void
    {
        $project = Project::factory()->create();

        Livewire::test(Show::class, ['project' => $project])
            ->set('newTitle', 'Write documentation')
            ->call('addTask')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'title' => 'Write documentation',
            'is_completed' => false,
        ]);
    }

    public function test_task_title_cannot_be_empty(): void
    {
        $project = Project::factory()->create();

        Livewire::test(Show::class, ['project' => $project])
            ->set('newTitle', '')
            ->call('addTask')
            ->assertHasErrors(['newTitle' => 'required']);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_task_can_be_toggled_both_ways(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->for($project)->create();

        $component = Livewire::test(Show::class, ['project' => $project]);

        $component->call('toggleTask', $task->id);
        $this->assertTrue($task->fresh()->is_completed);

        $component->call('toggleTask', $task->id);
        $this->assertFalse($task->fresh()->is_completed);
    }

    public function test_task_title_can_be_edited(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->for($project)->create(['title' => 'Old title']);

        Livewire::test(Show::class, ['project' => $project])
            ->call('startEditing', $task->id)
            ->set('editingTitle', 'New title')
            ->call('saveTitle')
            ->assertHasNoErrors();

        $this->assertSame('New title', $task->fresh()->title);
    }

    public function test_a_task_can_be_deleted(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->for($project)->create();

        Livewire::test(Show::class, ['project' => $project])
            ->call('deleteTask', $task->id);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
