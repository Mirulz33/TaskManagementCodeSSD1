<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Show all tasks
    public function index(): View
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if ($authUser->role === 'admin') {
            // Admin sees all tasks
            $tasks = Task::with('user')->latest()->get();
        } else {
            // Standard user sees only assigned tasks
            $tasks = Task::with('user')
                ->where('user_id', $authUser->id)
                ->latest()
                ->get();
        }

        return view('tasks.index', compact('tasks'));
    }

    // Show form to create task (Admin only)
    public function create(): View
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if ($authUser->role !== 'admin') {
            abort(403);
        }

        $users = User::where('role', 'user')->get();
        return view('tasks.create', compact('users'));
    }

    // Store new task (Admin only)
    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if ($authUser->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => $authUser->id,
            'action' => 'Task Created',
            'description' => 'Title: '.$task->title
                             .' | Description: '.($task->description ?? 'N/A')
                             .' | Assigned To: '.($task->user->name ?? 'N/A')
                             .' | Status: '.$task->status,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    // Show edit form (Admin only)
    public function edit(Task $task): View
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if ($authUser->role !== 'admin') {
            abort(403);
        }

        $users = User::where('role','user')->get();
        return view('tasks.edit', compact('task', 'users'));
    }

    // Update task (Admin only)
    public function update(Request $request, Task $task): RedirectResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if ($authUser->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'status' => $request->status,
        ]);

        AuditLog::create([
            'user_id' => $authUser->id,
            'action' => 'Task Updated',
            'description' => 'Title: '.$task->title
                             .' | Description: '.($task->description ?? 'N/A')
                             .' | Assigned To: '.($task->user->name ?? 'N/A')
                             .' | Status: '.$task->status,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    // Standard user: update status only
    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if ($task->user_id !== $authUser->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string',
        ]);

        $task->update(['status' => $request->status]);

        AuditLog::create([
            'user_id' => $authUser->id,
            'action' => 'Task Status Updated',
            'description' => 'Title: '.$task->title
                             .' | Description: '.($task->description ?? 'N/A')
                             .' | Status: '.$task->status,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task status updated.');
    }

    // Delete task (Admin only)
    public function destroy(Task $task): RedirectResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if ($authUser->role !== 'admin') {
            abort(403);
        }

        $title = $task->title;
        $description = $task->description;
        $assignedUser = $task->user->name ?? 'N/A';
        $status = $task->status;

        $task->delete();

        AuditLog::create([
            'user_id' => $authUser->id,
            'action' => 'Task Deleted',
            'description' => 'Title: '.$title
                             .' | Description: '.($description ?? 'N/A')
                             .' | Assigned To: '.$assignedUser
                             .' | Status: '.$status,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
