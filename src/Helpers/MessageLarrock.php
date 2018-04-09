<?php

namespace Larrock\Core\Helpers;

use Larrock\Core\Events\MessageLarrockEvent;

/**
 * Вывод уведомлений в интерфейс, запись событий в лог
 * Class MessageLarrock.
 */
class MessageLarrock
{
    /**
     * @param string $message
     * @param null|bool $logWrite
     */
    public static function success(string $message, $logWrite = null)
    {
        \Session::push('message.success', $message);
        if ($logWrite) {
            \Log::info($message);
        }
        event(new MessageLarrockEvent('success', $message));
    }

    /**
     * @param string $message
     * @param null|bool $logWrite
     * @param null $exception
     * @throws \Exception
     */
    public static function danger(string $message, $logWrite = null, $exception = null)
    {
        \Session::push('message.danger', $message);
        if ($logWrite) {
            \Log::error($message);
        }
        event(new MessageLarrockEvent('danger', $message));
        if ($exception) {
            throw new \Exception($message, 500);
        }
    }

    /**
     * @param string $message
     * @param null|bool $logWrite
     */
    public static function warning(string $message, $logWrite = null)
    {
        \Session::push('message.warning', $message);
        if ($logWrite) {
            \Log::info($message);
        }
        event(new MessageLarrockEvent('warning', $message));
    }

    /**
     * @param string $message
     * @param null|bool $logWrite
     */
    public static function notice(string $message, $logWrite = null)
    {
        \Session::push('message.notice', $message);
        if ($logWrite) {
            \Log::notice($message);
        }
        event(new MessageLarrockEvent('notice', $message));
    }

    /**
     * @param string $type - тип ошибки
     * @param string $message
     * @param null|bool $logWrite
     * @param null $exception
     * @throws \Exception
     */
    public static function manual(string $type, string $message, $logWrite = null, $exception = null)
    {
        \Session::push('message.'. $type, $message);
        if ($logWrite) {
            \Log::notice($message);
        }
        event(new MessageLarrockEvent($type, $message));
        if ($exception) {
            throw new \Exception($message, 500);
        }
    }
}
