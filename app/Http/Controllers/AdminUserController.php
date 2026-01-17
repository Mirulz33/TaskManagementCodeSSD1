<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        // Only show users who are not soft deleted
        $users = User::where('id', '!=', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function promote(User $user)
    {
        if ($user->role !== 'standard') {
            return back()->with('error', 'Only standard users can be promoted.');
        }

        $user->update([
            'role' => 'super_user',
        ]);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'PROMOTE_USER',
            'description' => 'Promoted user to Super User: '.$user->name.' (ID: '.$user->id.')',
            'ip_address'  => request()->ip(),
        ]);

        return back()->with('success', $user->name.' is now a Super User.');
    }

    public function demote(User $user)
    {
        if ($user->role !== 'super_user') {
            return back()->with('error', 'Only Super Users can be demoted.');
        }

        $user->update([
            'role' => 'standard',
        ]);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'DEMOTE_USER',
            'description' => 'Demoted Super User to Standard: '.$user->name.' (ID: '.$user->id.')',
            'ip_address'  => request()->ip(),
        ]);

        return back()->with('success', $user->name.' has been demoted to Standard User.');
    }

    public function block(User $user)
    {
        if ($user->is_blocked) {
            return back()->with('error', 'User is already blocked.');
        }

        $user->update([
            'is_blocked' => true,
        ]);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'BLOCK_USER',
            'description' => 'Blocked user: '.$user->name.' (ID: '.$user->id.')',
            'ip_address'  => request()->ip(),
        ]);

        return back()->with('success', $user->name.' has been blocked.');
    }

    public function unblock(User $user)
    {
        if (!$user->is_blocked) {
            return back()->with('error', 'User is not blocked.');
        }

        $user->update([
            'is_blocked' => false,
        ]);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'UNBLOCK_USER',
            'description' => 'Unblocked user: '.$user->name.' (ID: '.$user->id.')',
            'ip_address'  => request()->ip(),
        ]);

        return back()->with('success', $user->name.' has been unblocked.');
    }

    public function destroy(User $user)
    {
        $name = $user->name;
        $id   = $user->id;

        // Soft delete instead of hard delete
        $user->delete();

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'DELETE_USER',
            'description' => 'Soft-deleted (archived) user: '.$name.' (ID: '.$id.')',
            'ip_address'  => request()->ip(),
        ]);

        return back()->with('success', $name.' has been archived.');
    }
}
