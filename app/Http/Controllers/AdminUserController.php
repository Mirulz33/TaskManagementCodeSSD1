<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AdminUserController extends Controller
{
    /**
     * List all users
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Block user
     */
    public function block(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot block your own account.');
        }

        $user->update([
            'is_blocked' => true,
        ]);

        // ✅ AUDIT LOG
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'BLOCK_USER',
            'description'=> 'Blocked user: '.$user->name.' (ID: '.$user->id.')',
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', $user->name.' has been blocked.');
    }

    /**
     * Unblock user
     */
    public function unblock(User $user)
    {
        $user->update([
            'is_blocked' => false,
        ]);

        // ✅ AUDIT LOG
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'UNBLOCK_USER',
            'description'=> 'Unblocked user: '.$user->name.' (ID: '.$user->id.')',
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', $user->name.' has been unblocked.');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $id   = $user->id;

        $user->delete();

        // ✅ AUDIT LOG
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'DELETE_USER',
            'description'=> 'Deleted user: '.$name.' (ID: '.$id.')',
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', $name.' has been deleted.');
    }
}
