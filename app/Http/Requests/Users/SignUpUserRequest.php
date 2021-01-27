<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseFormRequest;

class SignUpUserRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|min:1|email|unique:users',
            'username' => 'required|min:1|unique:users',
            'password' => 'required|min:8',
        ];
    }
}
