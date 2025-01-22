<?php

namespace App\Services\Chat\Response;

use App\Models\Product;
use Pgvector\Laravel\Vector;
use App\Helpers\PrismProvider;
use Pgvector\Laravel\Distance;
use App\Services\Chat\Messaging\MessagePrompts;
use EchoLabs\Prism\ValueObjects\Messages\AssistantMessage;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;

class ProductResponse {
    use PrismProvider;

    public function __construct(protected string $message) {}

    public function response(): string{
        $messageEmbeddingAPI = $this->prismEmbeddingsFactory()
        ->fromInput($this->message)
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

        $assistantMessage = new AssistantMessage(MessagePrompts::productPrompt());
        $userMessage = new UserMessage($chatQuery);

        $messages = [
            $assistantMessage,
            $userMessage
        ];

        $response = $this->prismTextGeneratorFactory()
        ->withMessages($messages)
        ->generate();

        $response = $response->text;

        return $response;
    }
}
