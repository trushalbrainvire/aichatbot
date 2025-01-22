<?php

namespace App\Helpers;

use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Text\Generator as TextGenerator;
use EchoLabs\Prism\Embeddings\Generator as EmbeddingsGenerator;

trait PrismProvider {

    public function prismTextGeneratorFactory(): TextGenerator {
        return Prism::text()
        ->using(Provider::OpenAI, 'gpt-4o')
        ->withSystemPrompt(view('prompts.leila'))
        ->withMaxSteps(5)
        ->withClientRetry(2, 50);
    }

    public function prismEmbeddingsFactory(): EmbeddingsGenerator{
        return Prism::embeddings()
        ->using(Provider::OpenAI, 'text-embedding-ada-002');
    }
}
