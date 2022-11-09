<?php

namespace App\Notifier\Factory;

interface IterableNotificationFactoryInterface
{
    public static function getDefaultIndexName(): string;
}