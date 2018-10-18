<?php

namespace App\Exceptions;

class UnauthorizedUserException extends ApiException
{
    public function __construct(string $message = null, \Exception $previous = null, array $headers = array())
    {
        parent::__construct(ErrorCodes::UNAUTHORIZED_USER, $message, $previous, $headers);
    }
}
