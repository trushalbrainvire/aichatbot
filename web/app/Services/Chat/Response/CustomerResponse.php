<?php

namespace App\Services\Chat\Response;

use App\Helpers\PrismProvider;
use App\Services\Chat\Messaging\MessagePrompts;
use EchoLabs\Prism\ValueObjects\Messages\{AssistantMessage, UserMessage};

final class CustomerResponse{
    use PrismProvider;

    public function __construct(protected string $message) {}

    public function response($customer): string {

        $messages = [
            new AssistantMessage(MessagePrompts::customerPrompt(json_encode($customer))),
            new UserMessage($this->message)
        ];

        $response = $this->prismTextGeneratorFactory()
            ->withMessages($messages)
            ->generate();

        return $response->text;
    }
}
