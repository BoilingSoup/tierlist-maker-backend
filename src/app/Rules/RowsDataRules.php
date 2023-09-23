<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class RowsDataRules implements ValidationRule
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

        foreach ($value as $row) {
          $rowInfo = Validator::make($row, [
              'id' => ['required', 'string'],
              'label' => ['required', 'string', 'nullable'],
              'color' => ['required', 'string'],
              'items' => ['array'],
              'items.*' => [new ImageDataRules()],
          ]);
          $rowInfo->validate();
        }
    }
}
