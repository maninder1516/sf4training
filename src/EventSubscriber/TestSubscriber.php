<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use App\Event\RestrictedWordEvent;

class TestSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        // echo 'I am echo from kernel.request event';
        if ($event->getRequest()->server->get('REMOTE_ADDR') != '127.0.0.1') {
            $event->setResponse(new Response('You are not allowed to enter!'));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
            RestrictedWordEvent::NAME => 'onRestrictedWord'
        ];
    }
}
