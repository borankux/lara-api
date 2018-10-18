<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Errors need no more handling should be wrapped into this.
 *
 *
 */
class ApiException extends HttpException
{
    /**
     * @param int $errorCode
     * @param \Exception $previous
     * @param array $headers
     *
     */
    public function __construct(int $errorCode, string $message = null, \Exception $previous = null, array $headers = array())
    {
        parent::__construct(
            ErrorCodes::getStatusCode($errorCode),
            $message ? $message : ErrorCodes::getMessage($errorCode),
            $previous,
            $headers,
            $errorCode
        );
    }
}
