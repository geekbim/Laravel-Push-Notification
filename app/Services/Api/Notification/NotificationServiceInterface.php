<?php

namespace App\Services\Api\Notification;

interface NotificationServiceInterface 
{
    public function sendNotification($data);
    public function pushFCM($data);
    public function listNotification($userId);
}