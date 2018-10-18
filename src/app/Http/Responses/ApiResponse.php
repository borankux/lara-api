<?php
namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Illuminate\Support\Facades\Log;

/**
 * Formating response of api
 *
 */
trait ApiResponse
{
    /**
     * @param $statusCode
     * @param $body
     * @param array $header
     * @return \Illuminate\Http\JsonResponse
     */
    private function respond($statusCode, $body, $header = [])
    {
        $response = response()->json($body, $statusCode, $header);
        Log::debug($response);
        return $response;
    }

    /**
     * @param int $code
     * @param string $message
     * @param array $data
     * @return array
     */
    private function format(int $code, string $message, array $data = [], array $paginate = [])
    {
        $meta = array();
        $meta['code'] = $code;
        $meta['message'] = $message;

        $body = array();
        $body['meta'] = $meta;
        if (! empty($data)) {
            $body['data'] = $data;
        }

        if (! empty($paginate)) {
            $body['paginate'] = $paginate;
        }

        return $body;
    }

    /**
     * Render a http 4xx and 5xx response
     *
     * @param int $statusCode
     * @param int $code
     * @param string $message
     * @param $header
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function failed(int $statusCode, int $code, string $message, $header = [])
    {
        $body = $this->format($code, $message);
        return $this->respond($statusCode, $body, $header);
    }

    /**
     * Render a http 200 response
     *
     * @param array $data
     * @param $header
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ok(array $data = [], $paginate = [], $header = [])
    {
        $body = $this->format(20000, 'Success', $data, $paginate);
        return $this->respond(FoundationResponse::HTTP_OK, $body, $header);
    }

    /**
     * Render a http 202 response
     *
     * @param array $data
     * @param $header
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept(array $data = [], $header = [])
    {
        $body = $this->format(20200, 'Success', $data);
        return $this->respond(FoundationResponse::HTTP_ACCEPTED, $body, $header);
    }

    /**
     * Render a http 201 response
     *
     * @param array $data
     * @param $header
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function created(array $data = [], $header = [])
    {
        $body = $this->format(20100, 'Success', $data);
        return $this->respond(FoundationResponse::HTTP_CREATED, $body, $header);
    }

    /**
     * Render a http 204 response
     *
     * @param array $data
     * @param $header
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function noContent(array $data = [], $header = [])
    {
        $body = $this->format(20400, 'Success', $data);
        return $this->respond(FoundationResponse::HTTP_OK, $body, $header);
    }
}
