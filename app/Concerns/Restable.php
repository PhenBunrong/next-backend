<?php

namespace App\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait Restable
{
    /**
     * The default status code.
     *
     * @var int
     */
    protected int $statusCode = IlluminateResponse::HTTP_OK;

    /**
     * @param $filePath
     * @param $fileName
     * @param  array  $headers
     * @return BinaryFileResponse
     */
    public function respondWithFile($filePath, $fileName, array $headers = []): BinaryFileResponse
    {
        return Response::download($filePath, $fileName, $headers);
    }

    /**
     * Will return a response.
     *
     * @param  array  $data The given data
     * @param  array|string  $message
     * @param  array  $headers The given headers
     * @return JsonResponse The JSON-response
     */
    public function respondWithMessage($data, array|string $message = 'Item has been created.', array $headers = []): JsonResponse
    {
        $title = null;
        $type = 'success';
        if (is_array($message)) {
            $type = Arr::get($message, 'type', 'success');
            $title = Arr::get($message, 'title');
            $message = Arr::get($message, 'message');
        }
        $data = array_merge(
            $data,
            [
                'type' => $type,
                'title' => $title,
                'statusText' => $message,
                'statusCode' => $this->getStatusCode(),
            ]
        );

        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Will return a response.
     *
     * @param  array  $data The given data
     * @param  array  $headers The given headers
     * @return JsonResponse The JSON-response
     */
    public function respond(array $data, array $headers = []): JsonResponse
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Getter for the status code.
     *
     * @return int The status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Setter for the status code.
     *
     * @param  int  $statusCode The given status code
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Will result in a 201 code.
     *
     * @param  string  $message The given message
     * @param  array  $headers The headers that should be send with the JSON-response
     * @return JsonResponse The JSON-response with the message
     */
    protected function respondCreated(string $message = 'Item created', array $headers = []): JsonResponse
    {
        $this->setStatusCode(IlluminateResponse::HTTP_CREATED);

        return $this->respondWithSuccess($message, $headers);
    }

    /**
     * Will result in an success message.
     *
     * @param  array|string  $message The given message
     * @param  array  $headers The headers that should be send with the JSON-response
     * @return JsonResponse The JSON-response with the message
     */
    public function respondWithSuccess(array|string $message, array $headers = []): JsonResponse
    {
        $title = null;
        $type = 'success';
        $status = null;
        if (is_array($message)) {
            $type = Arr::get($message, 'type', 'success');
            $title = Arr::get($message, 'title');
            $status = Arr::get($message, 'status');
            $message = Arr::get($message, 'message');
        }

        return $this->respond([
            'type' => $type,
            'title' => $title,
            'statusText' => $message,
            'statusCode' => $this->getStatusCode(),
            'status' => $status,
            'message' => $message,
            'status_code' => $this->getStatusCode(),
        ], $headers);
    }

    /**
     * Will result in a 400 error code.
     *
     * @param  string  $message The given message
     * @param  array  $headers The headers that should be send with the JSON-response
     * @return JsonResponse The JSON-response with the error code
     */
    protected function respondBadRequest(string $message = 'Bad request', array $headers = []): JsonResponse
    {
        $this->setStatusCode(IlluminateResponse::HTTP_BAD_REQUEST);

        return $this->respondWithError($message, $headers);
    }

    /**
     * Will result in an error.
     *
     * @param  array|string  $message The given message
     * @param  array  $headers The headers that should be send with the JSON-response
     * @return JsonResponse The JSON-response with the error message
     */
    public function respondWithError(array|string $message, array $headers = []): JsonResponse
    {
        $title = null;
        if (is_array($message)) {
            $message = Arr::get($message, 'message');
            $title = Arr::get($message, 'title');
        }

        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode(),
            ],
            'statusCode' => $this->getStatusCode(),
            'title' => $title,
            'statusText' => $message ?: __('messages.not_found', ['name' => 'Model']),
        ], $headers);
    }

    /**
     * Will result in a 401 error code.
     *
     * @param  string|null  $message The given message
     * @param  array  $headers The headers that should be send with the JSON-response
     * @return JsonResponse The JSON-response with the error code
     */
    protected function respondUnauthorized(string $message = null, array $headers = []): JsonResponse
    {
        $this->setStatusCode(IlluminateResponse::HTTP_UNAUTHORIZED);

        return $this->respond([
            'statusCode' => $this->getStatusCode(),
            'title' => $message ?: __('auth.token.expired.title'),
            'statusText' => __('auth.token.expired.message'),
        ], $headers);
    }

    /**
     * Will result in a 403 error code.
     *
     * @param  string  $message The given message
     * @param  array  $headers The headers that should be send with the JSON-response
     * @return JsonResponse The JSON-response with the error message
     */
    protected function respondForbidden(string $message = 'Forbidden', array $headers = []): JsonResponse
    {
        $this->setStatusCode(IlluminateResponse::HTTP_FORBIDDEN);

        return $this->respondWithError($message, $headers);
    }

    /**
     * Will result in a 404 error code.
     *
     * @param  string  $message The given message
     * @return JsonResponse The JSON-response with the error message
     */
    protected function respondNotFound(string $message = 'Not found'): JsonResponse
    {
        $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND);

        return $this->respondWithError($message);
    }

    /**
     * Will result in a 405 error code.
     *
     * @param  string  $message The given message
     * @param  array  $headers The headers that should be send with the JSON-response
     * @return JsonResponse The JSON-response with the error message
     */
    protected function respondNotAllowed(string $message = 'Method not allowed', array $headers = []): JsonResponse
    {
        $this->setStatusCode(IlluminateResponse::HTTP_METHOD_NOT_ALLOWED);

        return $this->respondWithError($message, $headers);
    }

    /**
     * Will result in a 422 error code.
     *
     * @param  string  $message The given message
     * @param  array  $headers The headers that should be send with the JSON-response
     * @return JsonResponse The JSON-response with the error code
     */
    protected function respondUnprocessableEntity(string $message = 'Unprocessable', array $headers = []): JsonResponse
    {
        $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);

        return $this->respondWithError($message, $headers);
    }

    /**
     * Will result in a 429 error code.
     *
     * @param  string  $message The given message
     * @param  array  $headers The headers that should be send with the JSON-response
     * @return JsonResponse The JSON-response with the error message
     */
    protected function respondTooManyRequests(string $message = 'Too many requests', array $headers = []): JsonResponse
    {
        $this->setStatusCode(IlluminateResponse::HTTP_TOO_MANY_REQUESTS);

        return $this->respondWithError($message, $headers);
    }

    /**
     * Will result in a 500 error code.
     *
     * @param  string  $message The given message
     * @param  array  $headers The headers that should be send with the JSON-response
     * @return JsonResponse The JSON-response with the error message
     */
    protected function respondInternalError(string $message = 'Internal Server Error', array $headers = []): JsonResponse
    {
        $this->setStatusCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR);

        return $this->respondWithError($message, $headers);
    }
}
