<?php

namespace App\Notifier;

use App\Notifier\Factory\ChainNotificationFactory;
use Symfony\Component\Notifier\NotifierInterface;

class MovieNotifier
{
    public function __construct(
        private NotifierInterface $notifier,
        private ChainNotificationFactory $factory
    ) {}

    public function sendNotification()
    {
        $user = new class {
            public function getPreferredChannel()
            {
                return 'slack';
            }
        };
        $notification = $this->factory->createNotification($user->getPreferredChannel());

        $this->notifier->send($notification);
    }
}