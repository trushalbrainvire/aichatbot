<?php

namespace App\Services\Chat\Traits;

use App\Models\User;
use App\Services\Chat\DTOs\Customer;
use App\Services\Chat\Messaging\Rules;
use Illuminate\Support\Facades\Session;

trait ChatTrait {

    /**
     * get the details related to message
     *
     * @param  string $message
     * @return array
     */
    public function classifyIntentAndTone(string $message): array {
        return [
            self::fetchIntent($message),
            self::getType($message)
        ];
    }

    /**
     * get the intent of the message based on specified rules
     *
     * @param  string $message
     * @return string
     */
    public static function fetchIntent(string $message): string{
        return (new Rules())->messageRules($message);
    }

    /**
     * get the type of message
     *
     * @param  string $message
     * @return mixed
     */
    public static function getType(string $message): mixed {
        return gettype($message);
    }
}
