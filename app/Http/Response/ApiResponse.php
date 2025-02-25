<?php

namespace App\Http\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiResponse
{
    // Http Error Codes
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;

    const HTTP_BAD_REQUEST = 400;
    const HTTP_UN_AUTHORIZED = 401;
    const HTTP_FORBIDDEN  = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_VALIDATION_ERROR = 422;
    const HTTP_METHOD_NOT_ALLOWED = 404;
    const HTTP_UNPROCESSABLE_ENTITY = 405;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    // Validation Messages
    const VALIDATION_FAILED = "Bad Request";
    const UNEXPECTED_ERROR = "Unexpected error occurred";
    const UNAUTHORIZED_ERROR = "Unauthorized";
    const ERROR_STATUS = 'Error';
    const SUCCESS_STATUS = 'Success';
    const URL_NOT_FOUND = 'Request Url not found';
    const NOT_FOUND = 'Not Found';
    const HTTP_EXCEPTION = 'HTTP Exception';

    public static function success(
        $data = null,
        $message = 'Success',
        $statusCode = self::HTTP_OK
    ): JsonResponse {
        return response()->json(
            [
                'status' => self::SUCCESS_STATUS,
                'message' => $message,
                'data' => $data
            ],
            $statusCode
        );
    }

    public function handleExceptionErrors($e): JsonResponse
    {
        switch (get_class($e)) {
            case ValidationException::class:
                return $this->handleValidationException($e);
            case ModelNotFoundException::class:
                return $this->handleModelNotFoundException($e);
            case HttpException::class:
                return $this->handleHttpException($e);
            case NotFoundHttpException::class:
                return $this->handleNotFoundException($e);
            default:
                return self::error($e->getMessage());
        }
    }

    /**
     * @param string $message
     * @param $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function error(string $message = 'Error', $errors = null, int $statusCode = self::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json(
            [
                'status' => self::ERROR_STATUS,
                'message' => $message,
                'errors' => $errors,
                'error_code' => $statusCode
            ],
            $statusCode
        );
    }

    /**
     * @param ModelNotFoundException $exception
     * @return JsonResponse
     */
    protected function handleModelNotFoundException(ModelNotFoundException $exception): JsonResponse
    {
        return self::error(self::NOT_FOUND, $exception->getMessage(), self::HTTP_NOT_FOUND);
    }

    protected function handleValidationException(ValidationException $exception): JsonResponse
    {
        return self::error(
            self::VALIDATION_FAILED,
            $exception->errors(),
            self::HTTP_VALIDATION_ERROR
        );
    }

    protected function handleHttpException(HttpException $exception): JsonResponse
    {
        // Handle HTTP exception
        return self::error(
            self::VALIDATION_FAILED,
            $exception->getMessage(),
            $exception->getStatusCode()
        );
    }

    protected function handleNotFoundException(NotFoundHttpException $exception): JsonResponse
    {
        return self::error(
            self::URL_NOT_FOUND,
            $exception->getMessage(),
            self::HTTP_NOT_FOUND
        );
    }
}
