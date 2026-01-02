<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(ItemProperty::class, 'item_property_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
