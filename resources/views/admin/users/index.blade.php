<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Role</th>
                            <th class="border px-4 py-2">Blocked</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="border px-4 py-2">{{ $user->id }}</td>
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($user->role) }}</td>
                                <td class="border px-4 py-2">
                                    @if($user->is_blocked)
                                        <span class="text-red-600 font-semibold">Yes</span>
                                    @else
                                        <span class="text-green-600 font-semibold">No</span>
                                    @endif
                                </td>
                                <td class="border px-4 py-2 space-x-2">

                                    @if(!$user->is_blocked)
                                        <form method="POST" action="{{ route('admin.users.block', $user->id) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="bg-red-600 text-white px-2 py-1 rounded">Block</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.unblock', $user->id) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="bg-green-600 text-white px-2 py-1 rounded">Unblock</button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-gray-600 text-white px-2 py-1 rounded">Delete</button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
