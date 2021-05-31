<?php

namespace App\Http\Controllers\Api\Resources;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class to define the Default Response Package of the API.
 *
 * Save in data all the important content for the response
 * Error will have the error message if something bad happens
 * Status code is an extra field and must match with the response in the http
 * request.
 *
 * @package App\Http\Controllers\Api\Resources
 */
class ResponsePackage
{
    public $message = '';

    public $error = '';

    public $status = BaseApi::HTTP_OK;

    public $data = [];

    public function __construct($data = [], $error = '', $status = BaseApi::HTTP_OK)
    {
        $this->data = $data;
        $this->error = $error;
        $this->status = $status;
        $this->headers = [];
    }

    /**
     * Use it to set the user data in the response.
     *
     * @param $label
     * @param $data
     *
     * @return $this
     */
    public function setData($label, $data)
    {
        if ($data instanceof LengthAwarePaginator) {
            $paginator = $data->toArray();
            $this->data[$label] = $paginator['data'];
            unset($paginator['data']);
            $this->pager = $paginator;

            return $this;
        }

        $this->data[$label] = $data;
        return $this;
    }

    /**
     * Use it to append data to the current data payload.
     *
     * @param $key
     *
     * @param $newData
     */
    public function appendData($key, $newData)
    {
        $this->data[$key] = $newData;
    }

    /**
     * Use it to set the error messages to the client
     *
     * @param $error
     * @param null $status
     *
     * @return $this
     */
    public function setError($error, $status = null)
    {
        if (!empty($status)) {
            $this->setStatus($status);
        } elseif ($this->status == BaseApi::HTTP_OK) {
            $this->setStatus(BaseApi::HTTP_INVALID_REQUEST);
        }
        $this->error = $error;
        return $this;
    }

    /**
     * Use it to set the error status of the response
     *
     * @param $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Use it to set messages for the client.
     *
     * @param $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setHeaders($headers = []) {
        $this->headers = $headers;
    }

    /**
     * Generates the Json Response
     */
    public function toResponse()
    {
        return response()->json($this, $this->status, $this->headers);
    }


    /**
     * Sets the response as a validator error for custom validations
     *
     * @param array $errors
     *
     *   Format of errors
     *   [field => [message, message, ...]
     *
     *   For example
     *   for example ["dob" => ["Age must be grather than 13"]]
     */
    public function setValidationError(array $errors)
    {
        $this->setError(
            'Los datos proveídos no ha pasado la validación',
            BaseApi::HTTP_INVALID_REQUEST
        );
        $this->setData("errors", $errors);
    }
}
