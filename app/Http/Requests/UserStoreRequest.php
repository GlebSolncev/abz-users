<?php

namespace App\Http\Requests;
;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'name'        => [
                'required',
                'string',
                'min:2',
                'max:60'
            ],
            'email'       => [
                'required',
                'email',
                'min:6',
                'max:100',
                'regex:/^(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/i',
            ],
            'phone'       => [
                'required',
                'string',
                'size:13',
//                'regex:/^\+380\d{9}$/'
                'regex:/^[\+]{0,1}380([0-9]{9})$/'
            ],
            'position_id' => [
                'required',
                'integer',
                'min:1',
                'exists:positions,id'
            ],
            'photo'       => [
                'required',
                'image',
                'mimes:jpeg,png',
                'max:5120',
                'dimensions:min_width=70,min_height=70',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.min'            => 'The name must be at least 2 characters.',
            'email.email'         => 'The email must be a valid email address.',
            'phone.required'      => 'The phone field is required.',
            'position_id.integer' => 'The position id must be an integer.',
            'photo.max'           => 'The photo may not be greater than 5 Mbytes.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'fails'   => $validator->errors()->messages(),
        ], 422));
    }
}
