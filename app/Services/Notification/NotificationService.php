<?php

namespace App\Services\Notification;

use App\Models\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    private $messaging;

    public function __construct()
    {
        $serviceAccountPath = storage_path('app/firebase-service.json');
        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        $this->messaging = $factory->createMessaging();
    }

    public function send($user, string $title, string $message, string $type )
    {
        $type = 'basic';
        if (!$user->fcm_token) return false;

        $notification = [
            'title' => $title,
            'body'  => $message,
            'sound' => 'default',
        ];

        $data = [
            'type' => $type,
            'id'   => $user->id,
            'message' => $message,
        ];

        $cloudMessage = CloudMessage::withTarget('token', $user->fcm_token)
            ->withNotification($notification)
            ->withData($data);

        try {
            $this->messaging->send($cloudMessage);

            // save in DB
            Notification::create([
                'user_id' => $user->id,
                'title'   => $title,
                'body'    => $message,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function unreadCount(int $userId): int
    {
        return Notification::query()
            ->where('user_id', $userId)
            ->where('read', false)
            ->count();
    }

    public function markAsRead($id)
    {
        $n = Notification::findOrFail($id);
        return $n->update(['read' => 1]);
    }
    public function allForUser(int $userId)
    {
        return Notification::query()
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();
    }
}
