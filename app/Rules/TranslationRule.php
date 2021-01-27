<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TranslationRule implements Rule
{
    protected $keys;

    /**
     * Create a new rule instance.
     *
     * @param $keys
     */
    public function __construct($keys)
    {
        $this->keys = $keys;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        /**
         * @var array $validLocales
         */
        $validLocales = config('app.available_locales');
        $suppliedLocales = array_keys($value);

        foreach ($suppliedLocales as $locale) {
            if (!in_array($locale, $validLocales, true)) {
                return false;
            }

            foreach (array_keys($value[$locale]) as $key) {
                if (!in_array($key, $this->keys, true)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Not all translation keys are valid';
    }
}
