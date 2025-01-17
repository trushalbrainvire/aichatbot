<?php

namespace App\Services\Chat\Messaging;

use Carbon\Carbon;
use App\Models\Merchant;

final class MessagePrompts {


    /**
     * greetings
     *
     * @param  Merchant $merchant
     * @param  array | null $customer
     * @return string
     */
    public static function greetingsPrompt(Merchant $merchant, string $customerName = null): string {
        $greeting_time = self::getTimeFrameForGreeting();

        return "
            You are a chat advisor and your your role is to generate the greeting message based on $greeting_time and sometime add emoji related to $greeting_time . if you have a customer details then use this customer details with greeting message and if the customer is not provided then generate greeting message on behalf of the store admin and your message response must not contains words like products, orders, brands, and any unappropriated words also represent your self as a assistance of $merchant->store and you are responsible to give an answer on behalf of store admin.

            the details are below

            Admin: $merchant->store
            Customer: $customerName
        ";
    }

    public static function productPrompt(){
        return '
            you are a support chat adviser and user ask you a query regarding the product. you need to generate the answer user friendly way which is asking by the the customer and provide details on provided data which are would be in array or object format but you always return answer in text format.
        ';
    }

    public static function customerPrompt($customer){
        return "
            You are a support chat adviser and user ask you a query regarding their details. as a friendly chat supporter you have to replay back to user's anser based on below customer data. if customer data is null then generate reply to give go to account page and login your self then we will give exact data regarding you. if details are provided then resolve customer query based on user details.

            below are the user details
            Customer: $customer
        ";
    }

    private static function getTimeFrameForGreeting(): string{
        $greeting = "";
        $currentTime = Carbon::now()->timezone('Asia/Kolkata');

        if ($currentTime->hour < 12) {
            $greeting = 'Good morning';
        } elseif ($currentTime->hour < 18) {
            $greeting = 'Good afternoon';
        } else {
            $greeting = 'Good evening';
        }
        return $greeting;
    }
}
