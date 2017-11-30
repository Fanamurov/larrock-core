<?php

namespace Larrock\Core\Exceptions;

use Exception;

class LarrockFormBuilderRowException extends Exception
{
    public $status = 422;

    /**
     * Create a new validation exception from a plain array of messages.
     *
     * @param  $message
     * @return static
     */
    public static function withMessage($message)
    {
        return new static($message);
    }
}
