<?php

namespace App\Http\Controllers\API;

use App\Helpers\DateTimeSetupUsingRequest;
use Illuminate\Http\Request;
use App\Services\Chat\Facades\Chat;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Chat\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function invoke(Request $request): JsonResponse
    {
        $time = [
            "date"=>"2025-01-20",
            "current_time"=> "16:40:04",
            "timezone"=> "Asia/Calcutta"
        ];

        try {
            $fetchRequestTime = DateTimeSetupUsingRequest::setupDateTime($time);
            $response = Chat::storeClassifier(Auth::user(),  $request->get('logged_in_customer_id'));
            return $this->successResponse($response);
        } catch (\Throwable $th) {
            return $this->errorResponse('Something went wrong !', 200, $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function message(Request $request):JsonResponse
    {
        try {
            $chats = $request->get('message');
            $userLatestMessage = collect($chats['contents'])->filter(fn($chat) =>  $chat['role'] == 'user')->last()['parts'][0]['text'];
            $response = Chat::generateResponse(User::first(), $userLatestMessage, $chats, $request->get('logged_in_customer_id'));
            return $this->successResponse($response);
        }
        catch (\Throwable $th) {
            return $this->errorResponse('Something went wrong !', 500, $th->getMessage());
        }
    }
}
