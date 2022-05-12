<?php

namespace App\Repositories\Api\Notification;

use App\Models\Notification;
use App\Repositories\Api\Notification\NotificationRepositoryInterface;

class NotificationRepository implements NotificationRepositoryInterface
{
    protected $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function store($data)
    {
        $notification               = $this->notification;

        $notification->user_id      = $data['userId'];
        $notification->device_id    = $data['deviceId'];
        $notification->title        = $data['title'];
        $notification->content      = $data['content'];
        $notification->image        = $data['image'];

        $notification->save();

        return $notification->fresh();
    }

    public function getByUserId($userId)
    {
        $notifications = $this->notification
            ->where('user_id', $userId)
            ->paginate(10);

        return $notifications;
    }
}