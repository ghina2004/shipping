<?php
namespace App\Services\Chat;

use App\Events\MessageSent;
use App\Exceptions\Types\CustomException;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class MessageService
{

    public function sendMessage(array $data, Order $order): Message
    {
        $userId = Auth::id();

        $order->loadMissing('conversation');

        $conversation = $this->getOrCreateConversation($order, $userId);

        $message = $this->storeMessage($conversation->id, $userId, $data['message']);

        broadcast(new MessageSent($message))->toOthers();

        // === إشعار المستلم ===
        $receiverId = $this->resolveReceiverId($order, $userId);
        $this->notifyReceiver($conversation->id, $receiverId, $message);

        return $message;
    }


    private function notifyReceiver(int $conversationId, int $receiverId, Message $message): void
    {
        // إذا المستلم فاتح نفس المحادثة → لا تبعث إشعار
        if (cache()->get("user:{$receiverId}:active_conversation") == $conversationId) {
            return;
        }

        $receiver = User::find($receiverId);
        if (!$receiver || !$receiver->fcm_token) {
            return;
        }

        app(\App\Services\Api\NotificationService::class)->send(
            $receiver->toArray(),
            'رسالة جديدة',
            $message->message,
            'chat'
        );
    }

    public function enterConversation(Conversation $conversation)
    {
        cache()->put("user:" . auth()->id() . ":active_conversation", $conversation->id, now()->addMinutes(30));
        return response()->json(['status' => 'entered']);
    }

    public function leaveConversation(Conversation $conversation)
    {
        cache()->forget("user:" . auth()->id() . ":active_conversation");
        return response()->json(['status' => 'left']);
    }

    public function getOrderMessages(Order $order): Collection
    {
        $conversation = $this->getOrCreateConversation($order, Auth::id());

        return $this->fetchMessages($conversation->id);
    }

    private function getOrCreateConversation(Order $order, int $userId): Conversation
    {
        if ($order->conversation) {
            if (!$this->userBelongsToOrder(Auth::user(), $order)) {
                throw new CustomException(__('chat.unauthorized'), 403);
            }
            return $order->conversation;
        }

        if ($order->employee_id) {
            return Conversation::create([
                'sender_id' => $userId,
                'receiver_id' => $this->resolveReceiverId($order, $userId),
                'order_id' => $order->id,
            ]);
        }

        throw new CustomException(__('chat.can_not_send_yet'), 403);
    }

    private function storeMessage(int $conversationId, int $senderId, string $messageText): Message
    {
        return Message::query()->create([
            'conversation_id' => $conversationId,
            'sender_id'       => $senderId,
            'message'         => $messageText,
        ]);
    }

    private function fetchMessages(int $conversationId): Collection
    {
        return Message::with('sender')
            ->where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    private function resolveReceiverId(Order $order, int $senderId): int
    {
        return $order->customer_id === $senderId
            ? $order->employee_id
            : $order->customer_id;
    }

    private function userBelongsToOrder(User $user, Order $order): bool
    {
        return in_array($user->id, [
            $order->customer_id,
            $order->employee_id,
        ]);
    }
}
