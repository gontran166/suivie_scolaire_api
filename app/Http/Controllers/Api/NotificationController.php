<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\SaveFcmTokenRequest;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function saveToken(
        SaveFcmTokenRequest $request
    ): JsonResponse {

        $user = $request->user();

        $user->update([
            'fcm_token' => $request->validated('fcm_token'),
        ]);

        return response()->json([
            'message' => 'Token FCM enregistré avec succès.',
        ]);
    }
}