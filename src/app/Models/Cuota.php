<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuota extends Model
{
    protected $fillable = [
        'festero_id','ejercicio','cuota_asociacion','cuota_fester',
        'pagada_asociacion','pagada_fester','pagada_asociacion_at','pagada_fester_at'
    ];

    public function festero(): BelongsTo {
        return $this->belongsTo(Festero::class);
    }

    public function getImporteTotalAttribute(): float {
        return (float)$this->cuota_asociacion + (float)$this->cuota_fester;
    }

    public function getImportePagadoAttribute(): float {
        return ($this->pagada_asociacion ? (float)$this->cuota_asociacion : 0)
             + ($this->pagada_fester ? (float)$this->cuota_fester : 0);
    }
}
