<?php

namespace App\Services\Chat\Response;

use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\ValueObjects\Messages\SystemMessage;

final class GreetingsResponse{

    public function response($message) : string {
        $response = Prism::text()
                    ->using(Provider::OpenAI, 'gpt-3.5-turbo')
                    ->withMessages([
                        new SystemMessage($message)
                    ])
                    ->generate();

        return $response->text;
    }
}
