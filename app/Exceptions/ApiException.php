<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class ApiException extends \Exception
{
    /**
     * @var int
     */
    private $httpStatusCode;

    public function __construct(
        $message = 'Api Exception',
        $code = 5,
        \Throwable $previous = null,
        $httpStatusCode = Response::HTTP_BAD_REQUEST
    ) {
        $this->httpStatusCode = $httpStatusCode;

        parent::__construct($message, $code, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
