<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tutor extends Model
{
    protected $fillable = ['festero_id','nombre','dni','parentesco','telefono','email'];
      // 👇 nombre de tabla REAL en BD
    protected $table = 'tutores';
    
    public function festero(): BelongsTo {
        return $this->belongsTo(Festero::class);
    }
}
