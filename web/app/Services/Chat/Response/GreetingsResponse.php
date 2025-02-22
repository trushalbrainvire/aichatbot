<?php

namespace App\Services\Chat\Response;

use App\Helpers\PrismProvider;

final class GreetingsResponse{

    use PrismProvider;

    public function response($message) : string {
        $response = $this->prismTextGeneratorFactory()
        ->withPrompt($message)
        ->generate();

        return $response->text;
    }
}
