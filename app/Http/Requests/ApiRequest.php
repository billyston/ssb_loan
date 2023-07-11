<?php

namespace App\Http\Requests;

use App\Traits\ResponseBuilder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ApiRequest extends FormRequest
{
    use ResponseBuilder;

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->unprocessableEntityResponseBuilder(
            status: true,
            code: Response::HTTP_UNPROCESSABLE_ENTITY,
            message: 'Unprocessable request.',
            description: 'The request is invalid. Check the request and try again.',
            error: $validator->errors()->all()
        ));
    }
}
