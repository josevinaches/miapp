<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comparsa extends Model
{
    protected $fillable = ['bando_id', 'nombre', 'user_id'];

    public function bando(): BelongsTo
    {
        return $this->belongsTo(Bando::class);
    }

    public function representante(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function festeros(): HasMany
    {
        return $this->hasMany(\App\Models\Festero::class);
    }
}
