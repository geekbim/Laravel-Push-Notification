<?php

namespace App\Repositories\Api\Notification;

interface NotificationRepositoryInterface 
{
    public function store($data);
    public function getByUserId($userId);
}