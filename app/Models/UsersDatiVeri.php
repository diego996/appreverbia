<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsersDatiVeri extends Model
{
    use HasFactory;

    protected $table = 'users_dati_veri';

    protected $guarded = [];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
