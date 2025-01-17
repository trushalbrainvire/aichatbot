<?php

namespace App\Services\Chat\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\Chat\Repositories\ChatInterface;

class Chat extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ChatInterface::class;
    }
}
