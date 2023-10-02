<?php

namespace App\Http\Requests;

use App\Rules\TierListDataRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTierListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'data' => ['required', new TierListDataRules()],
            // 'thumbnail' => ['required', 'url:https'],
        ];
    }
}
