<?php

namespace Larrock\Core\Helpers;

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
    }
}
