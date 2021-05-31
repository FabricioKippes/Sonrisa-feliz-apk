<?php

namespace App\Exceptions;

use App\Http\Controllers\Api\Resources\BaseApi;
use App\Http\Controllers\Api\Resources\ResponsePackage;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            $package = new ResponsePackage();
            switch (get_class($exception)) {
                case 'Illuminate\Validation\ValidationException':
                    $validationErrors = $exception->validator->errors();
                    $errors = $validationErrors->isEmpty() ? [] : $validationErrors;
                    $package->setError(BaseApi::DEFAULT_VALIDATION_ERROR, BaseApi::HTTP_INVALID_REQUEST);
                    $package->setData('errors', $errors);
                    break;
                case 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException':
                    $package->setError('Service Not found', BaseApi::HTTP_NOT_FOUND);
                    $package->setData('errors', BaseApi::NOT_FOUND);
                    break;
                case 'Illuminate\Auth\Access\AuthorizationException':
                    $package->setError(BaseApi::DEFAULT_AUTHORIZATION_ERROR, BaseApi::HTTP_FORBIDDEN_ERROR);
                    $package->setData('errors', $exception->getMessage());
                    break;
                case 'Illuminate\Database\Eloquent\ModelNotFoundException':
                    $package->setError(BaseApi::DEFAULT_MODEL_QUERY_RESULT_ERROR, BaseApi::HTTP_NOT_FOUND);
                    $package->setData('errors', BaseApi::DEFAULT_MODEL_QUERY_RESULT_ERROR);
                    break;
                case 'Illuminate\Http\Exceptions\ThrottleRequestsException':
                    $package->setError(BaseApi::TOO_MANY_ATTEMPTS, BaseApi::HTTP_TOO_MANY_ATTEMPTS);
                    $package->setData('errors', BaseApi::TOO_MANY_ATTEMPTS);
                    break;
                default:
                    $status = method_exists($exception, 'getStatusCode')
                        ? $exception->getStatusCode()
                        : BaseApi::HTTP_INVALID_REQUEST;
                    $message = $exception->getMessage() ? $exception->getMessage() : BaseApi::API_GENERAL_ERROR;
                    $package->setError($message, $status);
                    $package->setStatus($status);
                    $package->setData('errors', $message);
            }
            return $package->toResponse();
        }
        return parent::render($request, $exception);
    }
}
