<?php

namespace App\Services\Chat\Enums;

enum IntentEnum: string
{
    case ProductInquiry = 'Product Inquiry';
    case OrderStatus = 'Order Status';
    case AccountHelp = 'Account Help';
    case UserInfo = 'User Information';
    case GeneralInquiry = 'General Inquiry';
    case Greetings = "Greetings";
    case ExitGreetings = "Exit Greeting";

    /**
     * Map each intent to its keywords.
     */
    public static function keywords(): array
    {
        return [
            self::ProductInquiry->value => ['product', 'price', 'availability', 'features', 'item', 'bundle', 'gift-card'],
            self::OrderStatus->value => ['order', 'track', 'package', 'delivery', 'shipping'],
            self::AccountHelp->value => ['account', 'password', 'reset', 'help'],
            self::UserInfo->value => ['i','my','mine','me','myself', 'name', 'email', 'mail','phone', 'contact', 'address'],
            self::Greetings->value => ['hello', 'hi', 'good morning', 'good afternoon', 'hey'],
            self::ExitGreetings->value => ['bye', 'exit', 'see you later', 'see you soon'],

        ];
    }
}
