<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Services\Api\Notification\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Exception;

class NotificationController extends BaseController
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService  = $notificationService;
    }

    public function send(Request $request)
    {
        $data = $request->only([
            'fcmToken',
            'userId',
            'deviceId',
            'title',
            'content',
            'image',
        ]);

        $validator = Validator::make($data, [
            'fcmToken'  => 'required',
            'userId'    => 'required|string',
            'deviceId'  => 'required|string',
            'title'     => 'required|string',
            'content'   => 'required|string',
            'image'     => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $res = $this->notificationService->sendNotification($data);

            return $this->successResponse($res, 'Send notification successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function list($userId)
    {
        try {
            $data = $this->notificationService->listNotification($userId);

            return $this->successResponse($data, 'Get list notification successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
