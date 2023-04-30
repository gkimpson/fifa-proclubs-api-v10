<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events as LaravelEvents;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class LogActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
    }

    public function login(LaravelEvents\Login $event): void
    {
        $ip = Request::getClientIp();
        $this->info($event,
            "User {$event->user->email} logged in from $ip",
            $event->user->only('id', 'email')
        );
    }

    public function logout(LaravelEvents\Logout $event): void
    {
        $ip = Request::getClientIp();
        Cache::flush();
        $this->info($event,
            "User {$event->user->email} logged out from $ip",
            $event->user->only('id', 'email')
        );
    }

    public function registered(LaravelEvents\Registered $event): void
    {
        $ip = Request::getClientIp();
        $this->info($event, "User registered: {$event->user->email} from $ip");
    }

    public function failed(LaravelEvents\Failed $event): void
    {
        $ip = Request::getClientIp();
        $this->info($event,
            "User {$event->credentials['email']} login failed from $ip}",
            ['email' => $event->credentials['email']]
        );
    }

    public function passwordReset(LaravelEvents\PasswordReset $event): void
    {
        $ip = Request::getClientIp();
        $this->info($event,
            "User {$event->user->email} password reset from $ip",
            $event->user->only('id', 'email')
        );
    }

    protected function info(object $event, string $message, array $context = []): void
    {
        $class = get_class($event);
        Log::info("[$class] $message", $context);
    }
}
