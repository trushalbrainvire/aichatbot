<?php

namespace App\Services\Chat\Actions;

use App\Services\Chat\Response\{GreetingsResponse, ProductResponse, CustomerResponse, MessageResponse};

class AIResponseGenerator
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected string $message, protected string $intent, protected $customer)
    {
        //
    }

    public function response(){

        $responseMessage = '';

        switch ($this->intent) {
            case 'Greetings':
                $responseMessage = $this->getGreetResponse();
                break;
            case 'Product Inquiry':
                $responseMessage = $this->getProductResponse();
                break;
            case 'User Information':
                $responseMessage = $this->getUserResponse();
                break;
            default:
                $responseMessage = $this->messageResponse();
                break;
        }

        return $responseMessage;
    }

    protected function getGreetResponse(): string{
        return (new GreetingsResponse())->response($this->message);
    }

    protected function getProductResponse() {
        return (new ProductResponse($this->message))->response();
    }

    protected function getUserResponse(){
        return (new CustomerResponse($this->message))->response($this->customer);
    }

    protected function messageResponse(){
        return (new MessageResponse())->response($this->message);
    }
}
