<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'status'
    ];

    protected $casts = [
        'sku' => 'string',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
} 