<?php

namespace App\Http\Controllers\Chat;

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Order;
use App\Services\Chat\MessageService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    use ResponseTrait;

    public function __construct(protected MessageService $messageService) {}

    public function sendMessage(SendMessageRequest $request, Order $order): JsonResponse
    {
        $message = $this->messageService->sendMessage($request->validated(), $order);

        return self::Success([
            'message' => new MessageResource($message),
        ],'message sent successfully');

    }

    public function getMessages(Order $order): JsonResponse
    {
        $messages = $this->messageService->getOrderMessages($order);

        return self::Success([
            'messages' => MessageResource::collection($messages),
        ],'messages shown successfully');
    }
}
