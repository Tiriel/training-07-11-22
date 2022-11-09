<?php

namespace App\Notifier\Factory;

use App\Notifier\Notification\FirebaseNotification;
use Symfony\Component\Notifier\Notification\Notification;

class FirebaseNotificationFactory implements NotificationFactoryInterface, IterableNotificationFactoryInterface
{

    public function createNotification(): Notification
    {
        return new FirebaseNotification();
    }

    public static function getDefaultIndexName(): string
    {
        return 'firebase';
    }
}