<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchPostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'clubName' => 'required|string|min:3|max:255',
            'platform' => ['required', 'in:pc,ps4,ps5,xboxone,xbox-series-xs'],   // todo - generate from Platforms enum
        ];
    }
}
