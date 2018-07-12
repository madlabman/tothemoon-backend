<?php

namespace App\Library;

use Illuminate\Support\Collection;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\MessageToRegistrationToken;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\ServiceAccount;

/**
 * Firebase admin SDK helper.
 *
 * Class Firebase
 * @package App\Library
 */
class Firebase
{
    /**
     * @var \Kreait\Firebase
     */
    private $firebase;

    private $messaging;

    public function __construct()
    {
        $service_account = ServiceAccount::fromJsonFile(__DIR__ . '/../../firebase.json');
        $this->firebase = (new Factory)->withServiceAccount($service_account)->create();
        $this->messaging = $this->firebase->getMessaging();
    }

    /**
     * Send notifications to all users from collection.
     *
     * @param Collection $users
     * @param string $title
     * @param string $body
     * @param array $data
     */
    public function send_notifications_to_users(Collection $users, string $title, string $body, array $data = []): void
    {
        try {
            $notification = Notification::create($title, $body);
            foreach ($users as $user) {
                foreach ($user->devices as $device) {
                    $message = MessageToRegistrationToken::create($device->token)
                        ->withNotification($notification)
                        ->withData($data);
                    $this->messaging->send($message);
                }
            }
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
    }
}