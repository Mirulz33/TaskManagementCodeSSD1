<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Task</h2>
    </x-slot>

    <div class="p-6">

        {{-- ✅ SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-200 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- ❌ VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="bg-red-200 text-red-800 p-2 mb-4 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @can('admin')
        <form method="POST" action="{{ route('tasks.update', $task->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="block font-medium">Title</label>
                <input type="text" name="title" value="{{ $task->title }}" class="border p-2 w-full" required>
            </div>

            <div class="mb-3">
                <label class="block font-medium">Description</label>
                <textarea name="description" class="border p-2 w-full">{{ $task->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="block font-medium">Assign User</label>
                <select name="user_id" class="border p-2 w-full">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected($task->user_id == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="block font-medium">Status</label>
                <select name="status" class="border p-2 w-full">
                    <option value="pending" @selected($task->status=='pending')>Pending</option>
                    <option value="in-progress" @selected($task->status=='in-progress')>In Progress</option>
                    <option value="completed" @selected($task->status=='completed')>Completed</option>
                </select>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Update Task
            </button>
        </form>
        @endcan
    </div>
</x-app-layout>
