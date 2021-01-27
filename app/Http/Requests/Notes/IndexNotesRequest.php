<?php

namespace App\Http\Requests\Notes;

use App\Http\Requests\BaseFormRequest;

class IndexNotesRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return (bool) $this->authUser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|between:1,100',
            'per_page' => 'nullable|numeric|between:1,100',
            'category' => 'nullable|string'
        ];
    }
}
