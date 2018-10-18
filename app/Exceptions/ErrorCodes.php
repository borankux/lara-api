<?php

namespace App\Exceptions;

/**
 * All error codes returns to client.
 * Please add code and related message when necessary.
 *
 */
class ErrorCodes
{
    // ErrorCode Format:
    // [Http statuscode][your error code indicates a specific error under this statuscode]

    // --------------------------------------------------
    // Definitions of error codes for 4xx http statuscode
    // --------------------------------------------------
    const BAD_REQUEST = 40000;
    const UNAUTHORIZED = 40100;
    const UNAUTHORIZED_CLIENT = 40101;
    const UNAUTHORIZED_USER = 40102;
    const FORBIDDEN = 40300;
    const NOT_FOUND = 40400;
    const METHOD_NOT_ALLOWED = 40500;

    // --------------------------------------------------
    // Definitions of error codes for 5xx http statuscode
    // --------------------------------------------------
    const INTERNAL_SERVER_ERROR = 50000;
    const NOT_IMPLEMENTED = 50100;
    const SERVICE_UNAVAILABLE = 50300;


    // --------------------------------------------------
    // Messages of error codes
    // --------------------------------------------------
    private static $messages= array(
        40000 => 'Bad Request',
        40100 => 'Unauthorized',
        40101 => 'Unauthorized Client',
        40102 => 'Unauthorized User',
        40300 => 'Forbidden',
        40400 => 'Not Found',
        40500 => 'Method Not Allowed',
        50000 => 'Internal Server Error. Please contact dev@pengjisoft.com',
        50100 => 'Not Implemented',
        50300 => 'Service Unavailable',
    );


    /**
     * Convert error codes to message.
     *
     * @param int $code
     * @return string
     */
    public static function getMessage(int $code)
    {
        if (array_key_exists($code, self::$messages)) {
            return self::$messages[$code];
        } else {
            //todo should decide what do return when there is no corresponding message .
            return 'Unknown Error Code';
        }
    }

    /**
     * Convert error code to http statuscode
     *
     * @param int $code
     * @return int
     */
    public static function getStatusCode(int $code)
    {
        return intval(floor($code / 100));
    }

    public static function getErrorCode(int $statusCode)
    {
        return intval($statusCode * 100);
    }
}
