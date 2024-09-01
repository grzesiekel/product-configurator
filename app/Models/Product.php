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
        'slug',
        'quantity',
        'view',
        'price',
        'route',
        'multiplier'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
