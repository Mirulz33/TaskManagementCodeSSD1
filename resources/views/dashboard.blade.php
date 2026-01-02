<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- General Dashboard Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            {{-- Admin Links --}}
            @if(auth()->user()->role === 'admin')
                <div class="mt-6 space-x-4">
                    <a href="{{ route('tasks.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Manage Tasks
                    </a>
                    <a href="{{ route('tasks.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">
                        Create New Task
                    </a>
                    <a href="{{ route('audit.logs') }}" class="bg-gray-600 text-white px-4 py-2 rounded">
                        Audit Logs
                    </a>
                </div>
            @endif

            {{-- Standard User Tasks --}}
            @if(auth()->user()->role !== 'admin')
                @php
                    $tasks = \App\Models\Task::with('user')
                                ->where('user_id', auth()->id())
                                ->latest()
                                ->get();
                @endphp

                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                        Your Tasks
                    </h3>

                    @if($tasks->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No tasks assigned to you.</p>
                    @else
                        <ul>
                            @foreach($tasks as $task)
                                <li class="border p-4 mb-2 bg-gray-50 dark:bg-gray-700 rounded flex justify-between items-center">
                                    <div>
                                        <strong>{{ $task->title }}</strong>
                                        @if($task->description)
                                            - {{ $task->description }}
                                        @endif
                                        <br>
                                        <span class="text-gray-600 dark:text-gray-300 text-sm">
                                            Status: {{ ucfirst($task->status) }}
                                        </span>
                                    </div>

                                    {{-- Standard user can update status --}}
                                    <form method="POST" action="{{ route('tasks.update.status', $task->id) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="border p-1 rounded">
                                            <option value="pending" @if($task->status=='pending') selected @endif>Pending</option>
                                            <option value="in-progress" @if($task->status=='in-progress') selected @endif>In Progress</option>
                                            <option value="completed" @if($task->status=='completed') selected @endif>Completed</option>
                                        </select>
                                        <button class="bg-green-600 text-white px-2 py-1 rounded">Update</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
