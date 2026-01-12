<?php

namespace App\Enums;

enum FilterableAttribute: string
{
    case CAPACITY = 'capacity';
    case CONNECTOR_TYPE = 'connector_type';
    case POWER_OUTPUT = 'power_output';

    public function strategyType(): FilterStrategyType
    {
        return match ($this) {
            self::CAPACITY => FilterStrategyType::RANGE,
            self::CONNECTOR_TYPE => FilterStrategyType::EXACT,
            self::POWER_OUTPUT => FilterStrategyType::RANGE,
        };
    }

    public function requestFields(): array
    {
        return match ($this) {
            self::CAPACITY => ['min' => 'min_capacity', 'max' => 'max_capacity'],
            self::CONNECTOR_TYPE => ['field' => 'connector_type'],
            self::POWER_OUTPUT => ['min' => 'min_power', 'max' => 'max_power'],
        };
    }

    public function validationRules(): array
    {
        return match ($this) {
            self::CAPACITY => [
                'min_capacity' => ['nullable', 'numeric', 'min:0'],
                'max_capacity' => ['nullable', 'numeric', 'min:0'],
            ],
            self::CONNECTOR_TYPE => [
                'connector_type' => ['nullable', 'string', 'max:255'],
            ],
            self::POWER_OUTPUT => [
                'min_power' => ['nullable', 'integer', 'min:0'],
                'max_power' => ['nullable', 'integer', 'min:0'],
            ],
        };
    }

    public function valueType(): string
    {
        return match ($this) {
            self::CAPACITY => 'float',
            self::CONNECTOR_TYPE => 'string',
            self::POWER_OUTPUT => 'int',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::CAPACITY => 'Capacity (Ah)',
            self::CONNECTOR_TYPE => 'Connector Type',
            self::POWER_OUTPUT => 'Power Output (W)',
        };
    }

    public static function allValidationRules(): array
    {
        $rules = [];
        foreach (self::cases() as $attribute) {
            $rules = array_merge($rules, $attribute->validationRules());
        }
        return $rules;
    }

    public function extractFromRequest(array $data): ?array
    {
        $strategyType = $this->strategyType();

        if ($strategyType === FilterStrategyType::RANGE) {
            return $this->extractRangeFromRequest($data);
        }

        if ($strategyType === FilterStrategyType::EXACT) {
            return $this->extractExactFromRequest($data);
        }

        return null;
    }

    public static function extractForType(ProductType $type, array $data): array
    {
        $attributes = [];

        foreach ($type->filterableAttributes() as $attribute) {
            $extracted = $attribute->extractFromRequest($data);
            if ($extracted !== null) {
                $attributes[$attribute->value] = $extracted;
            }
        }

        return $attributes;
    }

    private function extractRangeFromRequest(array $data): ?array
    {
        $fields = $this->requestFields();
        $minField = $fields['min'];
        $maxField = $fields['max'];

        if (!isset($data[$minField]) && !isset($data[$maxField])) {
            return null;
        }

        $valueType = $this->valueType();
        $castFn = fn($v) => $valueType === 'int' ? (int) $v : (float) $v;

        return [
            'min' => isset($data[$minField]) ? $castFn($data[$minField]) : null,
            'max' => isset($data[$maxField]) ? $castFn($data[$maxField]) : null,
            'type' => FilterStrategyType::RANGE->value,
        ];
    }

    private function extractExactFromRequest(array $data): ?array
    {
        $fields = $this->requestFields();
        $field = $fields['field'];

        if (!isset($data[$field]) || $data[$field] === '') {
            return null;
        }

        return [
            'value' => $data[$field],
            'type' => FilterStrategyType::EXACT->value,
        ];
    }
}
