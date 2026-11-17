<div class="space-y-6">

    <h1 class="text-2xl font-bold">Projects</h1>

    @if ($successMessage)
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
            {{ $successMessage }}
        </div>
    @endif

    @if ($errorMessage)
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
            {{ $errorMessage }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-4 rounded-xl border bg-white p-6">
        <h2 class="font-semibold">
            {{ $editingId ? 'Edit project' : 'Create a new project' }}
        </h2>

        <div>
            <label for="name" class="mb-1 block text-sm font-medium">Name *</label>
            <input id="name" type="text" wire:model="name"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="mb-1 block text-sm font-medium">
                Description
            </label>
            <textarea id="description" wire:model="description" rows="3"
                class="w-full rounded-lg border border-slate-300 px-3 py-2"></textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                {{ $editingId ? 'Update project' : 'Create project' }}
            </button>

            @if ($editingId)
                <button type="button" wire:click="resetForm"
                        class="rounded-lg border border-slate-300 px-4 py-2">
                    Cancel
                </button>
            @endif
        </div>
    </form>

    <div class="space-y-4">
        @forelse ($projects as $project)
            @php
                $percent = $project->tasks_count > 0
                    ? round($project->completed_tasks_count / $project->tasks_count * 100)
                    : 0;
            @endphp

            <div wire:key="project-{{ $project->id }}"
                 class="rounded-xl border bg-white p-5">

                <h3 class="text-lg font-semibold">
                    <a href="{{ route('projects.show', $project) }}"
                       class="hover:text-indigo-700">{{ $project->name }}</a>
                </h3>

                <p class="mt-1 text-sm text-slate-600">{{ $project->description }}</p>

                <p class="mt-3 text-sm text-slate-500">
                    {{ $project->completed_tasks_count }}/{{ $project->tasks_count }} Tasks Completed
                </p>

                <div class="mt-2 h-2 rounded-full bg-slate-100">
                    <div class="h-2 rounded-full bg-indigo-600"
                         style="width: {{ $percent }}%"></div>
                </div>

                <div class="mt-4 flex gap-2 text-sm">
                    <a href="{{ route('projects.show', $project) }}"
                       class="rounded-lg bg-indigo-600 px-3 py-1.5 text-white">
                        View tasks
                    </a>

                    <button type="button" wire:click="editProject({{ $project->id }})"
                            class="rounded-lg border border-slate-300 px-3 py-1.5">
                        Edit
                    </button>

                    <button type="button" wire:click="deleteProject({{ $project->id }})"
                            wire:confirm="Delete this project?"
                            class="rounded-lg border border-red-200 px-3 py-1.5 text-red-600">
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <p class="text-center text-slate-500">
                No projects yet. Create your first project above.
            </p>
        @endforelse
    </div>

</div>
