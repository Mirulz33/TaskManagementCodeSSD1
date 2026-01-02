<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Create Task</h2>
    </x-slot>

    <div class="p-6">

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-200 text-red-800 p-2 mb-4 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ADMIN ONLY --}}
        @if(auth()->user()->role === 'admin')
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf

            <div class="mb-3">
                <label class="block font-medium">Task Title</label>
                <input type="text" name="title" class="border p-2 w-full" required>
            </div>

            <div class="mb-3">
                <label class="block font-medium">Description</label>
                <textarea name="description" class="border p-2 w-full"></textarea>
            </div>

            <div class="mb-3">
                <label class="block font-medium">Assign User</label>
                <select name="user_id" class="border p-2 w-full" required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Create Task
            </button>
        </form>
        @else
            <p class="text-red-600 font-semibold">
                You are not authorized to create tasks.
            </p>
        @endif

    </div>
</x-app-layout>
