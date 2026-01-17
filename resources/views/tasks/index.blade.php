<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Task Management</h2>
    </x-slot>

    <div class="p-6">

        @if(auth()->user()->canManageTasks())
            <a href="{{ route('tasks.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
                Create New Task
            </a>
        @endif

        <hr class="my-4">

        @forelse ($tasks as $task)
            <div class="border p-3 mb-2 flex justify-between items-center">
                <div>
                    <strong>{{ $task->title }}</strong>
                    <p>{{ $task->description }}</p>
                    <small class="text-gray-600">
                        Assigned to: {{ $task->user?->name ?? 'N/A' }} |
                        Status: {{ $task->status }}
                    </small>
                </div>

                <div class="flex gap-2">
                    @if(auth()->user()->canManageTasks())
                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-600">Edit</a>

                        <form method="POST" action="{{ route('tasks.destroy', $task->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500">No tasks found.</p>
        @endforelse

    </div>
</x-app-layout>
