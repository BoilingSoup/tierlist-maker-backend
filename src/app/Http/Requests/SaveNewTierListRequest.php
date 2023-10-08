<?php

namespace App\Http\Requests;

use App\Rules\TierListDataRules;
use Database\Helpers\MaxLength;
use Illuminate\Foundation\Http\FormRequest;

class SaveNewTierListRequest extends FormRequest
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
            'title' => ['string', 'nullable', 'max:'.MaxLength::TIER_LISTS_TITLE],
            'data' => ['required', new TierListDataRules()],
            'thumbnail' => ['required', 'url:https'],
            'description' => ['string', 'nullable', 'max:'.MaxLength::TIER_LISTS_DESCRIPTION],
            'is_public' => ['required', 'boolean'],
        ];
    }
}
