<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\AuditLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Request;
use App\Models\User;

class LogLoginActivity
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @return void
     */
    public function handle($event): void
    {
        // Successful login
        if ($event instanceof Login) {
            /** @var User $user */
            $user = $event->user;

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'Login Successful',
                'description' => 'User logged in successfully',
                'ip_address' => Request::ip(),
            ]);
        }

        // Failed login
        if ($event instanceof Failed) {
            $email = $event->credentials['email'] ?? 'Unknown';

            AuditLog::create([
                'user_id' => null,
                'action' => 'Login Failed',
                'description' => 'Email: ' . $email,
                'ip_address' => Request::ip(),
            ]);
        }
    }
}
