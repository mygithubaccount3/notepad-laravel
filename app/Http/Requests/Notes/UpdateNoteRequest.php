<?php

namespace App\Http\Requests\Notes;

use App\Http\Requests\BaseFormRequest;
use App\Rules\TranslationRule;

class UpdateNoteRequest extends BaseFormRequest
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
            'translations' => [
                'required',
                new TranslationRule([
                    'title', 'text'
                ])
            ],
            'translations.*.title' => 'required|min:1|max:255',
            'translations.*.text' => 'required|min:1|max:500',
            'visibility' => 'required',
            'category' => 'required'
        ];
    }
}
