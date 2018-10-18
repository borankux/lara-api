<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * Override: A list of the internal exception types that should not be reported.
     *
     * @var array
     */
    protected $internalDontReport = [

    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof HttpException) {
            Log::warning(get_class($exception) . " Msg: " . $exception->getMessage());
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     * Only HttpException will be rendered as expected 4xx response.
     * Other type of Exception will be rednered as 500 internal error.
     * You should handle other types of Exceptions at proper layers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        // Render HttpException to response
        if ($exception instanceof HttpException) {

            // Wrap Normal HttpException into ApiException to keep same response meta format.
            if (! $exception instanceof ApiException) {
                $exception = $this->prepareApiException($exception);
            }

            return $this->failed($exception->getStatusCode(), $exception->getCode(), $exception->getMessage());
        }

        // Render debug exception html view for unexpected exceptions
        if (config('app.debug', false)) {
            return $this->prepareResponse($request, $exception);
        }

        // Render internal error response for unexpected exceptions
        return $this->failed(
            FoundationResponse::HTTP_INTERNAL_SERVER_ERROR,
            ErrorCodes::INTERNAL_SERVER_ERROR,
            ErrorCodes::getMessage(ErrorCodes::INTERNAL_SERVER_ERROR)
        );
    }

    /**
     * Wrap HttpException into ApiException
     *
     * @param HttpException $exception
     * @return ApiException
     */
    public function prepareApiException(HttpException $exception)
    {
        $errorCode = ErrorCodes::getErrorCode($exception->getStatusCode());
        return new ApiException($errorCode, $exception->getMessage(), $exception);
    }
}
