<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class TierListDataRules implements ValidationRule
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
            'sidebar' => ['array'],
            'sidebar.*' => [new ImageDataRules()],
            'rows' => ['required', new RowsDataRules()],
        ]);

        $validator->validate();
    }
}
