<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class KernelRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Environment $twig,
        private bool $isMaintenance
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->isMaintenance) {
            $event->setResponse(new Response($this->twig->render('maintenance.html.twig')));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 9999],
        ];
    }
}
