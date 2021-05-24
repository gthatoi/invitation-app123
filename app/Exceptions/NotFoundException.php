<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class NotFoundException extends \Exception
{
    /**
     * @var int
     */
    private $httpStatusCode;

    public function __construct(
        $message = 'Not found',
        $code = 5,
        \Throwable $previous = null,
        $httpStatusCode = Response::HTTP_NOT_FOUND
    ) {
        $this->httpStatusCode = $httpStatusCode;

        parent::__construct($message, $code, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
