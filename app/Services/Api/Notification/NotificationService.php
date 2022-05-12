<?php

namespace App\Services\Api\Notification;

use App\Repositories\Api\Notification\NotificationRepository;
use App\Services\Api\Notification\NotificationServiceInterface;

class NotificationService implements NotificationServiceInterface
{
    protected $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {        
        $this->notificationRepository = $notificationRepository;
    }

    public function sendNotification($data)
    {
        $this->pushFCM($data);

        return $this->notificationRepository->store($data);
    }

    public function pushFCM($data)
    {
        $url = env('FIREBASE_URL');
        $serverKey = env('FIREBASE_SERVER_KEY');

        $data = [
            "registration_ids" => $data['fcmToken'],
            "notification" => [
                "body"  => $data['content'],  
                "title" => $data['title'],  
                "image" => $data['image'],  
            ],
            "data" => [
                "title"     => $data['title'],
                "content"   => $data['content'],
                "image"     => $data['image'],
            ]
        ];

        $encodedData = json_encode($data);
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        // Init
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        

        // Close connection
        curl_close($ch);
    }

    public function listNotification($userId)
    {
        return $this->notificationRepository->getByUserId($userId);
    }
}