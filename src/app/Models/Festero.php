<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Festero extends Model
{
    use HasFactory;

    protected $table = 'festeros';

    protected $fillable = [
        'comparsa_id',
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'dni',
        'email',
        'telefono',
        'fecha_nacimiento',
        'trabuco',
        'embarque',
    ];

    // Casts útiles
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'trabuco' => 'boolean',
        'embarque' => 'boolean',
    ];



    /** Relaciones */
    public function comparsa(): BelongsTo
    {
        return $this->belongsTo(Comparsa::class);
    }

    public function cuotas(): HasMany
    {
        return $this->hasMany(\App\Models\Cuota::class);
    }

    public function isMenor(): bool
    {
        return optional($this->fecha_nacimiento)->age < 18;
    }

    public function cuotaDe(int $ejercicio)
    {
        return $this->cuotas()->where('ejercicio', $ejercicio)->first();
    }

    /**
     * IMPORTANTE: Alias en inglés para que funcione
     * with(['festeros.tutors']) aunque la tabla sea "tutores".
     */
    public function tutors(): HasMany
    {
        return $this->hasMany(\App\Models\Tutor::class, 'festero_id');
    }

    /**
     * Mantengo también el método en español por si lo usas en vistas/controladores.
     */
    public function tutores(): HasMany
    {
        return $this->tutors();
    }

    /** Normaliza DNI al guardar (mayúsculas, sin espacios ni guiones) */
    public function setDniAttribute($value): void
    {
        $this->attributes['dni'] = $value
            ? strtoupper(preg_replace('/\s|-/', '', $value))
            : null;
    }

    /**
     * Es menor a fecha de corte (24/07) del ejercicio dado.
     */
    public function esMenorPara(int $ejercicio): bool
    {
        if (empty($this->fecha_nacimiento)) return false;

        $cutoff = Carbon::createFromDate($ejercicio, 7, 24)->endOfDay();
        // Menor si su 18º cumpleaños es posterior al corte
        return Carbon::parse($this->fecha_nacimiento)->gt($cutoff->copy()->subYears(18));
    }
}
