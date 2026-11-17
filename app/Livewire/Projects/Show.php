<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    public Project $project;

    public string $newTitle = '';

    #[Locked]
    public ?int $editingTaskId = null;
    public string $editingTitle = '';

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function addTask(): void
    {
        $this->validate(
            ['newTitle' => ['required', 'string', 'max:255']],
            ['newTitle.required' => 'Task title cannot be empty.']
        );

        // Created through the relationship: project_id is set automatically
        $this->project->tasks()->create(['title' => trim($this->newTitle)]);

        $this->reset('newTitle');
    }

    public function toggleTask(int $taskId): void
    {
        // Only tasks of THIS project can be found
        $task = $this->project->tasks()->findOrFail($taskId);

        $task->update(['is_completed' => ! $task->is_completed]);
    }

    public function startEditing(int $taskId): void
    {
        $task = $this->project->tasks()->findOrFail($taskId);

        $this->editingTaskId = $task->id;
        $this->editingTitle = $task->title;
        $this->resetValidation();
    }

    public function saveTitle(): void
    {
        if ($this->editingTaskId === null) {
            return;
        }

        $this->validate(
            ['editingTitle' => ['required', 'string', 'max:255']],
            ['editingTitle.required' => 'Task title cannot be empty.']
        );

        $this->project->tasks()
            ->findOrFail($this->editingTaskId)
            ->update(['title' => trim($this->editingTitle)]);

        $this->cancelEditing();
    }

    public function cancelEditing(): void
    {
        $this->reset('editingTaskId', 'editingTitle');
        $this->resetValidation();
    }

    public function deleteTask(int $taskId): void
    {
        $this->project->tasks()->findOrFail($taskId)->delete();

        if ($this->editingTaskId === $taskId) {
            $this->cancelEditing();
        }
    }

    public function render()
    {
        $tasks = $this->project->tasks()->latest('id')->get();

        return view('livewire.projects.show', [
            'tasks' => $tasks,
            'completed' => $tasks->where('is_completed', true)->count(),
        ]);
    }
}
