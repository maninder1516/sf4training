<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;


class RestrictedWordEvent extends Event
{

    public const NAME = 'restricted.word';

    protected $str;

    public function __construct($str)
    {
        $this->str = $str;
    }

    public function getStr(): string {
        return $this->str;
    }

}