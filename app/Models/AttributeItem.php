<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeItem extends Model
{
    use HasFactory;

    protected $fillable = ['attribute_id', 'parent_id', 'value'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function parent()
    {
        return $this->belongsTo(AttributeItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AttributeItem::class, 'parent_id');
    }
}
