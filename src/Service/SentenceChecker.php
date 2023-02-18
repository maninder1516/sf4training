<?php

namespace App\Service;

use App\Event\RestrictedWordEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SentenceChecker {

    public const NOT_ALLOWED_WORDS = [
        '/uncensored word 1/',
        '/uncensored word 2/',
        '/uncensored word 3/'
    ];

    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher) {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function parse(string $str) {
        $count = 0;
        $str = preg_replace(self::NOT_ALLOWED_WORDS, '***', $str, -1, $count);
        
        if ($count) {
            $event = new RestrictedWordEvent($str);
            $this->eventDispatcher->dispatch($event, RestrictedWordEvent::NAME);
        }
        return $str;
    }

}