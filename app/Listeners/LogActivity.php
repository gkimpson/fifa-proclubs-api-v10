<?php

namespace App\Listeners;

use Illuminate\Auth\Events as LaravelEvents;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Request;

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
        //
    }

    public function login(LaravelEvents\Login $event)
    {
        $ip = Request::getClientIp(true);
    }

    public function logout(LaravelEvents\Logout $event)
    {
        $ip = Request::getClientIp(true);

        Cache::flush();

        $this->info($event, "User {$event->user->email} logged out from {$ip}", $event->user->only('id', 'email'));
    }

    public function registered(LaravelEvents\Registered $event)
    {
        $ip = Request::getClientIp(true);
        $this->info($event, "User registered: {$event->user->email} from {$ip}");
    }

    public function failed(LaravelEvents\Failed $event)
    {
        $ip = Request::getClientIp(true);
        $this->info($event, "User {$event->credentials['email']} login failed from {$ip}", ['email' => $event->credentials['email']]);
    }

    public function passwordReset(LaravelEvents\PasswordReset $event)
    {
        $ip = Request::getClientIp(true);
        $this->info($event, "User {$event->user->email} password reset from {$ip}", $event->user->only('id', 'email'));
    }

    protected function info(object $event, string $message, array $context = [])
    {
        $class = get_class($event);
        Log::info("[{$class}] {$message}", $context);
    }
}
