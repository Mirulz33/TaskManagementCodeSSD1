<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Audit Logs
        </h2>
    </x-slot>

    <div class="p-6 text-gray-900 dark:text-gray-100">

        {{-- Table of Audit Logs --}}
        <table class="w-full border border-gray-300 dark:border-gray-700">
            <thead class="bg-gray-200 dark:bg-gray-700">
                <tr>
                    <th class="p-2 border">User</th>
                    <th class="p-2 border">Action</th>
                    <th class="p-2 border">Description</th>
                    <th class="p-2 border">Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($auditLogs as $log)
                    <tr class="bg-white dark:bg-gray-800">
                        <td class="p-2 border">
                            {{ $log->user->name ?? 'System' }}
                        </td>
                        <td class="p-2 border">{{ $log->action }}</td>
                        <td class="p-2 border">{{ $log->description }}</td>
                        <td class="p-2 border text-sm text-gray-600 dark:text-gray-400">
                            {{ $log->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">
                            No audit logs found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</x-app-layout>
