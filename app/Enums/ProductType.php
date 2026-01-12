<?php

namespace App\Enums;

enum ProductType: string
{
    case BATTERY = 'battery';
    case CONNECTOR = 'connector';
    case SOLAR_PANEL = 'solar_panel';

    public function label(): string
    {
        return match ($this) {
            self::BATTERY => 'Battery',
            self::CONNECTOR => 'Connector',
            self::SOLAR_PANEL => 'Solar Panel',
        };
    }

    public function csvFile(): string
    {
        return match ($this) {
            self::BATTERY => 'batteries.csv',
            self::CONNECTOR => 'connectors.csv',
            self::SOLAR_PANEL => 'solar_panels.csv',
        };
    }

    /**
     * @return FilterableAttribute[]
     */
    public function filterableAttributes(): array
    {
        return match ($this) {
            self::BATTERY => [FilterableAttribute::CAPACITY],
            self::CONNECTOR => [FilterableAttribute::CONNECTOR_TYPE],
            self::SOLAR_PANEL => [FilterableAttribute::POWER_OUTPUT],
        };
    }
}
