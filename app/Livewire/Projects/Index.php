<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;

class Index extends Component
{
    public string $name = '';
    public string $description = '';
    public ?int $editingId = null;

    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }

    protected function messages(): array
    {
        return ['name.required' => 'Please enter a project name.'];
    }

    public function save(): void
    {
        $this->reset('successMessage', 'errorMessage');

        $data = $this->validate();
        $data['description'] = $data['description'] ?: null;

        if ($this->editingId) {
            Project::findOrFail($this->editingId)->update($data);
            $this->successMessage = 'Project updated.';
        } else {
            Project::create($data);
            $this->successMessage = 'Project created.';
        }

        $this->resetForm();
    }

    public function editProject(int $id): void
    {
        $project = Project::findOrFail($id);

        $this->editingId = $project->id;
        $this->name = $project->name;
        $this->description = $project->description ?? '';

        $this->reset('successMessage', 'errorMessage');
        $this->resetValidation();
    }

    public function deleteProject(int $id): void
    {
        $this->reset('successMessage', 'errorMessage');

        $project = Project::findOrFail($id);
        $pending = $project->incompleteTasksCount();

        if ($pending > 0) {
            $this->errorMessage = 'Cannot delete project: '
                . ($pending === 1
                    ? 'There is 1 uncompleted task.'
                    : "There are {$pending} uncompleted tasks.");

            return;
        }

        $project->delete();

        if ($this->editingId === $id) {
            $this->resetForm();
        }

        $this->successMessage = 'Project deleted.';
    }

    public function resetForm(): void
    {
        $this->reset('name', 'description', 'editingId');
        $this->resetValidation();
    }

    public function render()
    {
        $projects = Project::query()
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn ($q) => $q->where('is_completed', true),
            ])
            ->latest('id')
            ->get();

        return view('livewire.projects.index', ['projects' => $projects]);
    }
}
