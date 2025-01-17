<?php

namespace App\Services\Chat\Repositories;

use App\Models\User;
use App\Helpers\PurifyMessage;
use App\Services\Chat\Traits\ChatTrait;
use App\Services\Chat\Repositories\ChatInterface;
use App\Services\Chat\Actions\AIResponseGenerator;
use App\Services\Chat\DTOs\Customer;
use App\Services\Chat\Messaging\MessagePrompts;

class ChatRepository implements ChatInterface
{
    use ChatTrait;

    public function storeClassifier(User $user, $customerId): string
    {
        $customer = Customer::fetchCustomer($user,$customerId);
        $message = MessagePrompts::greetingsPrompt($user->merchant, !is_null($customer) ? $customer['firstName'] : null);
        return (new AIResponseGenerator($message,"Greetings", $customer))->response();
    }

    public function generateResponse(User $user, string $message, array $chats, $customerId): string
    {
        $message = (new PurifyMessage($message))->purify();
        [$intent] = $this->classifyIntentAndTone($message);
        $customer = Customer::fetchCustomer($user,$customerId);
        return (new AIResponseGenerator($message,$intent,$customer))->response();
    }
}
