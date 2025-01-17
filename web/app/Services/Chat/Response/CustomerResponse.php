<?php

namespace App\Services\Chat\Response;

use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Enums\Provider;
use App\Services\Chat\Messaging\MessagePrompts;
use EchoLabs\Prism\ValueObjects\Messages\{AssistantMessage, UserMessage};

final class CustomerResponse{

    public function __construct(protected string $message) {}

    public function response($customer): string {

        $messages = [
            new AssistantMessage(MessagePrompts::customerPrompt(json_encode($customer))),
            new UserMessage($this->message)
        ];

        $response = Prism::text()
            ->using(Provider::OpenAI, 'gpt-3.5-turbo')
            ->withMessages($messages)
            ->generate();

        return $response->text;
    }
}
