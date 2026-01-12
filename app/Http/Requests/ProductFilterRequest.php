<?php

namespace App\Http\Requests;

use App\DTO\ProductFilterDTO;
use App\Enums\FilterableAttribute;
use App\Enums\ProductType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 422)
        );
    }

    public function rules(): array
    {
        $baseRules = [
            'type' => ['nullable', 'string', Rule::in(['all', ...array_column(ProductType::cases(), 'value')])],
            'name' => ['nullable', 'string', 'max:255'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];

        return array_merge($baseRules, FilterableAttribute::allValidationRules());
    }

    public function toDTO(): ProductFilterDTO
    {
        $data = $this->validated();

        $typeInput = $data['type'] ?? 'all';
        $type = $typeInput !== 'all' ? ProductType::tryFrom($typeInput) : null;

        $customAttributes = $type
            ? FilterableAttribute::extractForType($type, $data)
            : [];

        return new ProductFilterDTO(
            type: $type?->value,
            name: $data['name'] ?? null,
            manufacturer: $data['manufacturer'] ?? null,
            minPrice: isset($data['min_price']) ? (float) $data['min_price'] : null,
            maxPrice: isset($data['max_price']) ? (float) $data['max_price'] : null,
            description: $data['description'] ?? null,
            customAttributes: $customAttributes,
            page: (int) ($data['page'] ?? 1),
            perPage: (int) ($data['per_page'] ?? 20),
        );
    }
}
