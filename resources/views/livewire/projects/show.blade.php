<div class="space-y-6">
    <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600">
        &larr; Back to projects
    </a>

    <div>
        <h1 class="text-2xl font-bold">{{ $project->name }}</h1>
        <p class="mt-1 text-slate-600">{{ $project->description }}</p>
        <p class="mt-2 text-sm text-slate-500">
            {{ $completed }}/{{ $tasks->count() }} Tasks Completed
        </p>
    </div>

    <form wire:submit="addTask" class="rounded-xl border bg-white p-5">
        <div class="flex gap-3">
            <input type="text" wire:model="newTitle" placeholder="Add a new task..."
                   class="flex-1 rounded-lg border border-slate-300 px-3 py-2">
            <button type="submit"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                Add task
            </button>
        </div>
        @error('newTitle')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </form>

    <ul class="divide-y rounded-xl border bg-white">
        @forelse ($tasks as $task)
            <li wire:key="task-{{ $task->id }}"
                class="flex items-start justify-between gap-4 p-4">
                <div class="flex flex-1 items-start gap-3">
                    <input type="checkbox"
                           wire:click="toggleTask({{ $task->id }})"
                           @checked($task->is_completed)
                           class="mt-1 h-5 w-5 accent-indigo-600">
                    <div class="flex-1">
                        @if ($editingTaskId === $task->id)
                            <form wire:submit="saveTitle" class="flex gap-2">
                                <input type="text" wire:model="editingTitle"
                                       wire:keydown.escape="cancelEditing"
                                       class="flex-1 rounded-lg border px-3 py-1.5">
                                <button type="submit"
                                        class="rounded-lg bg-indigo-600 px-3 py-1.5 text-sm text-white">
                                    Save
                                </button>
                                <button type="button" wire:click="cancelEditing"
                                        class="rounded-lg border px-3 py-1.5 text-sm">
                                    Cancel
                                </button>
                            </form>
                            @error('editingTitle')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @else
                            <span @class([
                                'text-slate-400 line-through' => $task->is_completed,
                            ])>{{ $task->title }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2 text-sm">
                    @if ($editingTaskId !== $task->id)
                        <button type="button" wire:click="startEditing({{ $task->id }})"
                                class="text-indigo-600">Edit</button>
                    @endif
                    <button type="button" wire:click="deleteTask({{ $task->id }})"
                            wire:confirm="Delete this task?"
                            class="text-red-600">Delete</button>
                </div>
            </li>
        @empty
            <li class="p-6 text-center text-slate-500">
                No tasks yet. Add the first one above.
            </li>
        @endforelse
    </ul>
</div>
