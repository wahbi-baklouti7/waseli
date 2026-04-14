<?php

namespace App\Http\Requests\Logistics;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isCarrier();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'departed_country_id' => ['required', 'exists:countries,id'],
            'arrival_city_id' => ['required', 'exists:regions,id'],
            'arrival_date' => ['required', 'date', 'after:now'],
            'status' => ['nullable', 'string', 'in:open,in_progress,completed'],
        ];
    }
}
