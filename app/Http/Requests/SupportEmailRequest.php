<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SupportEmailRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        $this->merge(
            [
                'attachment-count' => (int)$this->input('attachment-count'),
            ]
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'Content-Type'        => 'sometimes|string',
            'Date'                => 'sometimes|string',
            'From'                => 'sometimes|string',
            'In-Reply-To'         => 'sometimes|string',
            'Message-Id'          => 'sometimes|string',
            'Mime-Version'        => 'sometimes|string',
            'Received'            => 'sometimes|string',
            'References'          => 'sometimes|string',
            'Subject'             => 'sometimes|string',
            'To'                  => 'sometimes|string',
            'User-Agent'          => 'sometimes|string',
            'X-Mailgun-Variables' => 'sometimes|json',

            'attachment-count' => 'sometimes|integer',
            'body-html'        => 'sometimes|string',
            'body-plain'       => 'sometimes|string',
            'from'             => 'required|string',
            'recipient'        => 'sometimes|string',
            'sender'           => 'sometimes|string',
            'signature'        => 'sometimes|string',
            'stripped-html'    => 'sometimes|string',
            'stripped-text'    => 'required|string',
            'subject'          => 'required|string',
            'timestamp'        => 'sometimes|numeric',
            'token'            => 'sometimes',

            'attachment-1' => 'sometimes|file',
        ];
    }

    /**
     * @param  Validator  $validator
     */
    protected function failedValidation(Validator $validator)
    {
        \Log::error($validator->errors()->toJson());
    }
}
