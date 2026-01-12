<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_key',
        'string_value',
        'numeric_value',
    ];

    protected $casts = [
        'numeric_value' => 'float',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the attribute value (returns numeric if set, otherwise string)
     */
    public function getValueAttribute(): mixed
    {
        return $this->numeric_value ?? $this->string_value;
    }

    /**
     * Check if this attribute has a numeric value
     */
    public function isNumeric(): bool
    {
        return $this->numeric_value !== null;
    }

    /**
     * Create attribute with auto-detection of value type
     */
    public static function createWithValue(int $productId, string $key, mixed $value): self
    {
        $isNumeric = is_numeric($value);

        return self::create([
            'product_id' => $productId,
            'attribute_key' => $key,
            'string_value' => $isNumeric ? null : (string) $value,
            'numeric_value' => $isNumeric ? (float) $value : null,
        ]);
    }

    /**
     * Update or create attribute with auto-detection of value type
     */
    public static function setValueFor(int $productId, string $key, mixed $value): self
    {
        $isNumeric = is_numeric($value);

        return self::updateOrCreate(
            ['product_id' => $productId, 'attribute_key' => $key],
            [
                'string_value' => $isNumeric ? null : (string) $value,
                'numeric_value' => $isNumeric ? (float) $value : null,
            ]
        );
    }
}
