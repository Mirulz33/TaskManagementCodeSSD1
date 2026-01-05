<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Create Task
        </h2>
    </x-slot>

    <div class="p-6">

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 p-2 mb-4 rounded">
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

            {{-- Task Title --}}
            <div class="mb-3">
                <label class="block font-medium text-gray-700 dark:text-gray-300">
                    Task Title
                </label>
                <input type="text" name="title"
                    class="border p-2 w-full rounded
                           text-gray-900 dark:text-gray-100
                           bg-white dark:bg-gray-800
                           border-gray-300 dark:border-gray-600"
                    required>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label class="block font-medium text-gray-700 dark:text-gray-300">
                    Description
                </label>
                <textarea name="description"
                    class="border p-2 w-full rounded
                           text-gray-900 dark:text-gray-100
                           bg-white dark:bg-gray-800
                           border-gray-300 dark:border-gray-600">
                </textarea>
            </div>

            {{-- Assign User --}}
            <div class="mb-3">
                <label class="block font-medium text-gray-700 dark:text-gray-300">
                    Assign User
                </label>
                <select name="user_id"
                    class="border p-2 w-full rounded
                           text-gray-900 dark:text-gray-100
                           bg-white dark:bg-gray-800
                           border-gray-300 dark:border-gray-600"
                    required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Submit Button --}}
            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Create Task
            </button>
        </form>
        @else
            <p class="text-red-600 dark:text-red-400 font-semibold">
                You are not authorized to create tasks.
            </p>
        @endif

    </div>
</x-app-layout>
