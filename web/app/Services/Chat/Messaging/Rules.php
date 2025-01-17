<?php

namespace App\Services\Chat\Messaging;

use App\Services\Chat\Enums\IntentEnum;

class Rules {

    public function messageRules(string $message): string{

        // Retrieve keywords from the IntentEnum
        $intentKeywords = IntentEnum::keywords();

        // Default intent
        $intent = IntentEnum::GeneralInquiry->value;

        // Match the message with the keywords
        foreach ($intentKeywords as $key => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($message, $keyword) !== false) {
                    $intent = $key; // Use the matched intent
                    break 2; // Exit both loops
                }
            }
        }

        return $intent;
    }
}
