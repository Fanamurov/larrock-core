<?php

namespace Larrock\Core\Helpers;

/**
 * Вывод уведомлений в интерфейс, запись событий в лог
 * Class MessageLarrock
 * @package Larrock\Core\Helpers
 */
class MessageLarrock
{
    /**
     * @param string $message
     * @param null|bool $logWrite
     */
    public static function success(string $message, $logWrite = NULL)
    {
        \Session::push('message.success', $message);
        if($logWrite){
            \Log::info($message);
        }
    }

    /**
     * @param string $message
     * @param null|bool $logWrite
     */
    public static function danger(string $message, $logWrite = NULL)
    {
        \Session::push('message.danger', $message);
        if($logWrite){
            \Log::error($message);
        }
    }

    /**
     * @param string $message
     * @param null|bool $logWrite
     */
    public static function warning(string $message, $logWrite = NULL)
    {
        \Session::push('message.warning', $message);
        if($logWrite){
            \Log::info($message);
        }
    }

    /**
     * @param string $message
     * @param null|bool $logWrite
     */
    public static function notice(string $message, $logWrite = NULL)
    {
        \Session::push('message.notice', $message);
        if($logWrite){
            \Log::notice($message);
        }
    }
}