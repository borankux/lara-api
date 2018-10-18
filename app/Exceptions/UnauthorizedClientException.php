<?php

namespace App\Exceptions;

class UnauthorizedClientException extends ApiException
{
    public function __construct(string $message = null, \Exception $previous = null, array $headers = array())
    {
        parent::__construct(ErrorCodes::UNAUTHORIZED_CLIENT, $message, $previous, $headers);
    }
}
