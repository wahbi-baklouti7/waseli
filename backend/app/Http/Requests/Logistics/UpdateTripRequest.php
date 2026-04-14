<?php

namespace App\Http\Requests\Logistics;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTripRequest extends FormRequest
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
            'category_id' => ['sometimes', 'exists:categories,id'],
            'departed_country_id' => ['sometimes', 'exists:countries,id'],
            'arrival_city_id' => ['sometimes', 'exists:regions,id'],
            'arrival_date' => ['sometimes', 'date', 'after:now'],
            'status' => ['sometimes', 'string', 'in:open,in_progress,completed'],
        ];
    }
}
