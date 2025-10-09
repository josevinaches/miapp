<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expediente extends Model
{
    protected $fillable = ['titulo','descripcion','estado','user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
