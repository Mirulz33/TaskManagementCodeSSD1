<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminSetupController extends Controller
{
    /**
     * Show the admin setup form.
     */
    public function create()
    {
        if (User::where('role', 'admin')->exists()) {
            abort(403, 'Admin already exists');
        }

        return view('auth.setup-admin');
    }

    /**
     * Handle admin registration.
     */
    public function store(Request $request)
    {
        if (User::where('role', 'admin')->exists()) {
            abort(403, 'Admin already exists');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->mixedCase(),
            ],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect('/login')->with('success', 'Admin account created successfully.');
    }
}
