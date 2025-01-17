<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Chat\Repositories\{ChatInterface, ChatRepository};

class ChatProvider extends ServiceProvider
{
    /**
     * Register a chat services.
     */
    public function register(): void
    {
        match ($this->app->environment()) {
            default => $this->app->bind(ChatInterface::class, ChatRepository::class),
        };
    }
}
