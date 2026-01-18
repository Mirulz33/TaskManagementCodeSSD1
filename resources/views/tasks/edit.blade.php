<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
            Edit Task
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200 p-2 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 p-2 mb-4 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('tasks.update', $task->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1 text-gray-700 dark:text-gray-200">
                            Title
                        </label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $task->title) }}"
                            class="border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700
                                   text-gray-900 dark:text-gray-100
                                   p-2 w-full rounded focus:outline-none focus:ring focus:ring-blue-400 dark:focus:ring-blue-600"
                            required
                        >
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1 text-gray-700 dark:text-gray-200">
                            Description
                        </label>
                        <textarea
                            name="description"
                            class="border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700
                                   text-gray-900 dark:text-gray-100
                                   p-2 w-full rounded focus:outline-none focus:ring focus:ring-blue-400 dark:focus:ring-blue-600"
                        >{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Assign User --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1 text-gray-700 dark:text-gray-200">
                            Assign User
                        </label>
                        <select
                            name="user_id"
                            class="border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700
                                   text-gray-900 dark:text-gray-100
                                   p-2 w-full rounded focus:outline-none focus:ring focus:ring-blue-400 dark:focus:ring-blue-600"
                        >
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-6">
                        <label class="block font-medium mb-1 text-gray-700 dark:text-gray-200">
                            Status
                        </label>
                        <select
                            name="status"
                            class="border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700
                                   text-gray-900 dark:text-gray-100
                                   p-2 w-full rounded focus:outline-none focus:ring focus:ring-blue-400 dark:focus:ring-blue-600"
                        >
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in-progress" {{ $task->status == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Update Button --}}
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700
                               text-white dark:text-white
                               dark:bg-blue-500 dark:hover:bg-blue-600
                               px-4 py-2 rounded font-semibold shadow-md
                               transition-colors duration-200"
                    >
                        Update Task
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
