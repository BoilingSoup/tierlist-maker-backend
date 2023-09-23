<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class ImageDataRules implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value)) {
          $fail("Invalid {$attribute}.");

          return;
        }

        $validator = Validator::make($value, [
            'id' => ['required', 'string'],
            'src' => ['required', 'string'],
        ]);

        $validator->validate();
    }
}
