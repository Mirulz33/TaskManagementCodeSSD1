<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $tasks = $user->canManageTasks()
            ? Task::with('user')->latest()->get()
            : Task::with('user')->where('user_id', $user->id)->latest()->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->canManageTasks(), 403);

        $users = $user->isAdmin()
            ? User::all()
            : User::where('role', 'standard')->get();

        return view('tasks.create', compact('users'));
    }

    // ✅ Use TaskRequest here
    public function store(TaskRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->canManageTasks(), 403);

        $targetUser = User::findOrFail($request->user_id);
        abort_unless($user->canAssignTo($targetUser), 403);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $targetUser->id,
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'Task Created',
            'description' => "Task: {$task->title} → {$targetUser->name}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->canManageTasks(), 403);

        $users = $user->isAdmin()
            ? User::all()
            : User::where('role', 'standard')->get();

        return view('tasks.edit', compact('task', 'users'));
    }

    // ✅ Use TaskRequest here
    public function update(TaskRequest $request, Task $task)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->canManageTasks(), 403);

        $targetUser = User::findOrFail($request->user_id);
        abort_unless($user->canAssignTo($targetUser), 403);

        $task->update($request->only('title', 'description', 'user_id', 'status'));

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        abort_unless($task->user_id === Auth::id(), 403);

        $request->validate([
            'status' => 'required|in:pending,in-progress,completed',
        ]);

        $task->update(['status' => $request->status]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(Task $task)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->canManageTasks(), 403);

        $task->delete();

        return back()->with('success', 'Task deleted.');
    }
}
