<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\Notification;

class NotificationService
{
    public function __construct(
        protected Messaging $messaging
    ) {
    }

    /**
     * Enregistre ou met à jour le token FCM d'un utilisateur.
     */
    public function saveFcmToken(
        User $user,
        string $token
    ): User {
        $user->update([
            'fcm_token' => $token,
        ]);

        return $user->fresh();
    }

    public function sendToUsers(
        Collection $users,
        string $title,
        string $body
    ): void {

        foreach ($users as $user) {

            if (empty($user->fcm_token)) {
                continue;
            }

            try {

                $message = CloudMessage::new()
                    ->withToken($user->fcm_token)
                    ->withNotification(
                        Notification::create(
                            $title,
                            $body
                        )
                    )
                    ->withData([
                        'type' => 'annonce',
                    ]);

                $this->messaging->send($message);

            } catch (\Throwable $e) {

                Log::warning(
                    'Échec de l\'envoi de la notification Firebase.',
                    [
                        'user_id' => $user->id,
                        'message' => $e->getMessage(),
                    ]
                );
            }
        }
    }
}