<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">Project Management</h1>
        <p class="mt-1 text-sm text-slate-500">Create, monitor, and manage your projects and tasks.</p>
    </div>

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

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="border-b border-slate-100 pb-3 mb-4">
            <h2 class="text-lg font-semibold text-slate-900">
                {{ $editingId ? 'Edit Project' : 'Create a New Project' }}
            </h2>
            <p class="text-xs text-slate-500">
                {{ $editingId ? 'Modify the project details below and save your changes.' : 'Fill in the details below to add a new project to your list.' }}
            </p>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Project Name *</label>
                <input id="name" type="text" wire:model="name"
                       placeholder="e.g. Mobile Banking API"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="mb-1 block text-sm font-medium text-slate-700">
                    Description
                </label>
                <textarea id="description" wire:model="description" rows="3"
                    placeholder="Brief description of project goals and scope..."
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                    {{ $editingId ? 'Update Project' : 'Create Project' }}
                </button>

                @if ($editingId)
                    <button type="button" wire:click="resetForm"
                            class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>

    <div class="pt-4 border-t border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Existing Projects</h2>
                <p class="text-sm text-slate-500">View progress, manage tasks, or update existing projects</p>
            </div>
            <span class="rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-semibold text-indigo-700">
                {{ $projects->count() }} {{ $projects->count() === 1 ? 'Project' : 'Projects' }}
            </span>
        </div>

        <div class="space-y-4">
            @forelse ($projects as $project)
                @php
                    $percent = $project->tasks_count > 0
                        ? round($project->completed_tasks_count / $project->tasks_count * 100)
                        : 0;
                @endphp

                <div wire:key="project-{{ $project->id }}"
                     class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300 transition">

                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">
                                <a href="{{ route('projects.show', $project) }}"
                                   class="hover:text-indigo-600 transition">{{ $project->name }}</a>
                            </h3>
                            <p class="mt-1 text-sm text-slate-600">{{ $project->description }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium text-slate-600">Progress</span>
                            <span class="text-slate-500 font-medium">
                                {{ $project->completed_tasks_count }}/{{ $project->tasks_count }} Tasks Completed ({{ $percent }}%)
                            </span>
                        </div>

                        <div class="mt-2 h-2.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-indigo-600 transition-all duration-300"
                                 style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-2 text-sm">
                        <a href="{{ route('projects.show', $project) }}"
                           class="rounded-lg bg-indigo-600 px-3.5 py-1.5 text-white font-medium hover:bg-indigo-700 transition">
                            View tasks &rarr;
                        </a>

                        <button type="button" wire:click="editProject({{ $project->id }})"
                                class="rounded-lg border border-slate-300 px-3 py-1.5 text-slate-700 hover:bg-slate-50 transition">
                            Edit
                        </button>

                        <button type="button" wire:click="deleteProject({{ $project->id }})"
                                wire:confirm="Are you sure you want to delete this project?"
                                class="rounded-lg border border-red-200 px-3 py-1.5 text-red-600 hover:bg-red-50 transition">
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 p-8 text-center bg-white">
                    <p class="text-slate-500 font-medium">No projects created yet.</p>
                    <p class="text-xs text-slate-400 mt-1">Use the form above to create your first project.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
