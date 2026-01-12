<?php

namespace App\Pipelines\Filters\Strategies;

use App\Contracts\FilterStrategy;
use App\Enums\FilterStrategyType;
use InvalidArgumentException;

class FilterStrategyFactory
{
    private static array $strategies = [];

    public static function make(FilterStrategyType $type): FilterStrategy
    {
        if (!isset(self::$strategies[$type->value])) {
            self::$strategies[$type->value] = match ($type) {
                FilterStrategyType::RANGE => new RangeFilterStrategy(),
                FilterStrategyType::EXACT => new ExactFilterStrategy(),
            };
        }

        return self::$strategies[$type->value];
    }

    public static function makeFromString(string $type): FilterStrategy
    {
        $strategyType = FilterStrategyType::tryFrom($type);

        if (!$strategyType) {
            throw new InvalidArgumentException("Unknown filter strategy type: {$type}");
        }

        return self::make($strategyType);
    }
}
