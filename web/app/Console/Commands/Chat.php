<?php

namespace App\Console\Commands;

use App\Tools\SearchTool;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Text\Generator;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;

use function Laravel\Prompts\textarea;

class Chat extends Command
{
    use Colors;
    use DrawsBoxes;

    public Collection $messages;

    public function __construct() {
        parent::__construct();
        $this->messages = collect();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AI Based Chat Window';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $prism = $this->prismFactory();

        while(true){
            $this->chat($prism);
        }
    }

    protected function chat($prism) {
        $message = textarea('Message');
        $this->messages->push(new UserMessage($message));

        $answer = $prism
            ->withMessages($this->messages->toArray())
            ->generate();

        $this->messages->merge($answer->responseMessages);

        $this->box('Response', wordwrap($answer->text, 100), color:'magenta');
    }


    protected function prismFactory():Generator
    {
        return Prism::text()
            ->withTools([
                new SearchTool()
            ])
            ->withSystemPrompt(view('prompts.leila'))
            ->withMaxSteps(5)
            ->withClientRetry(2, 50)
            ->using(Provider::OpenAI, 'gpt-4o');
    }
}
