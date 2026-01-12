<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'type',
        'name',
        'manufacturer',
        'price',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function getCustomAttributes(): array
    {
        return $this->attributes()
            ->get()
            ->mapWithKeys(fn ($attr) => [$attr->attribute_key => $attr->value])
            ->toArray();
    }
}
