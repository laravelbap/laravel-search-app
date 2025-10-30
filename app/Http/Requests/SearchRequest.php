<?php

namespace App\Http\Requests;

use App\Enum\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'searchTerm' => ['nullable', 'string', 'max:255'],
            'filterProductType' => ['nullable', 'string', Rule::in(array_column(ProductType::cases(), 'value'))],
            'filterManufacturer' => ['nullable', 'string', 'max:255'],
            'filterConnectorType' => ['nullable', 'string', 'max:255'],
            'filterPrice.min' => ['nullable', 'numeric', 'min:0'],
            'filterPrice.max' => ['nullable', 'numeric', 'min:0', 'gte:filterPrice.min'],
            'filterPowerOutput.min' => ['nullable', 'numeric', 'min:0'],
            'filterPowerOutput.max' => ['nullable', 'numeric', 'min:0', 'gte:filterPowerOutput.min'],
            'filterCapacity.min' => ['nullable', 'numeric', 'min:0'],
            'filterCapacity.max' => ['nullable', 'numeric', 'min:0', 'gte:filterCapacity.min'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert empty strings to null for numeric fields
        $this->merge([
            'filterPrice.min' => $this->filterPrice['min'] === '' ? null : $this->filterPrice['min'],
            'filterPrice.max' => $this->filterPrice['max'] === '' ? null : $this->filterPrice['max'],
            'filterPowerOutput.min' => $this->filterPowerOutput['min'] === '' ? null : $this->filterPowerOutput['min'],
            'filterPowerOutput.max' => $this->filterPowerOutput['max'] === '' ? null : $this->filterPowerOutput['max'],
            'filterCapacity.min' => $this->filterCapacity['min'] === '' ? null : $this->filterCapacity['min'],
            'filterCapacity.max' => $this->filterCapacity['max'] === '' ? null : $this->filterCapacity['max'],
        ]);
    }
}
