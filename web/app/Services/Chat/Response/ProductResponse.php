<?php

namespace App\Services\Chat\Response;

use App\Models\Product;
use App\Services\Chat\Messaging\MessagePrompts;
use EchoLabs\Prism\Prism;
use Pgvector\Laravel\Vector;
use Pgvector\Laravel\Distance;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;
use EchoLabs\Prism\ValueObjects\Messages\SystemMessage;

class ProductResponse {

    public function __construct(protected string $message) {}

    public function response(): string{

        $messageEmbeddingAPI = Prism::embeddings()
                ->using(Provider::OpenAI, 'text-embedding-ada-002')
                ->fromInput( $this->message)
                ->generate();

        // Get your embeddings vector
        $messageEmbedding = new Vector($messageEmbeddingAPI->embeddings);

        $products = Product::query()
                    ->nearestNeighbors('embeddings', $messageEmbedding, Distance::L2)
                    ->take(5)
                    ->select(['title','price','vendor','body', 'productType', 'tags'])
                    ->get()
                    ->toArray();

        $chatQuery = 'Context: '.json_encode($products)."\n\n----\n\nQuestion: ".$this->message;

        $systemMessage = new SystemMessage(MessagePrompts::productPrompt());
        $userMessage = new UserMessage($chatQuery);

        $messages = [
            $systemMessage,
            $userMessage
        ];

        $response = Prism::text()
            ->using(Provider::OpenAI, 'gpt-3.5-turbo')
            ->withMessages($messages)
            ->generate();

        $response = $response->text;

        return $response;
    }
}
