<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bando extends Model {
    protected $fillable = ['nombre','slug'];
    public function comparsas(): HasMany { return $this->hasMany(Comparsa::class); }
}
