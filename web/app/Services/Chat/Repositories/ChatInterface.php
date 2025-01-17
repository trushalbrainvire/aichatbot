<?php

namespace App\Services\Chat\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Osiset\ShopifyApp\Contracts\ShopModel;

interface ChatInterface
{
    /*
    |=================================================
    | This would return a basic prompt of merchant to
    | clarify the chat conversation
    |=================================================
    */

    /**
     * @method mixed storeClassifier()
     *
     * @param User $user
     * @return string
     */
    public function storeClassifier(User $user, $customerId): string;

    /*
    |=======================================================
    | This would return a response of user inputted message
    | as AI based.
    |=======================================================
    */

    /**
     * @method mixed generateResponse()
     *
     * @param User $user
     * @param string | null $message
     * @param array $chats
     * @return string
     */
    public function generateResponse(User $user, string $message, array $chats, $customerId): string;
}
