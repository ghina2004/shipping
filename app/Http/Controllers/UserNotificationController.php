<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFcmTokenRequest;
use App\Models\Notification;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    use ResponseTrait;

    public function __construct(protected NotificationService $service) {}

    public function storeToken(StoreFcmTokenRequest $request)
    {
        $user = $request->user();
        $user->update(['fcm_token' => $request->string('token')]);

        return self::Success([], 'FCM token saved successfully.');
    }


    public function clearToken()
    {
        $user = auth()->user();
        $user->update(['fcm_token' => null]);

        return self::Success([], 'FCM token cleared.');
    }


    public function index()
    {
        $notifications = $this->service->allForUser(Auth::id());
        return self::Success($notifications, 'All notifications.');
    }

    public function unreadCount()
    {
        $count = $this->service->unreadCount(Auth::id());
        return self::Success(['unread_count' => $count], 'Unread notifications count.');
    }

    public function markAsRead($id)
    {
        $notifications = $this->service->markAsRead($id);
        return self::Success(['mark_as_read' => $notifications], 'Notifications marked as read.');
    }

}
