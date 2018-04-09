<?php

namespace Larrock\Core\Events;

class MessageLarrockEvent
{
    /** @var string Тип ошибки */
    public $type;

    /** @var string Сообщение */
    public $message;

    /**
     * MessageLarrockEvent constructor.
     * @param string $type
     * @param string $message
     */
    public function __construct(string $type, string $message)
    {
        $this->type = $type;
        $this->message = $message;
    }
}