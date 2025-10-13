<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
       // 👇 importante
    protected $table = 'revisiones';
    protected $fillable = [
        'comparsa_id','representante_id','ejercicio','archivo_pdf',
        'estado','admin_id','admin_comentario','revisado_at',
    ];

    public function comparsa(){ return $this->belongsTo(Comparsa::class); }
    public function representante(){ return $this->belongsTo(User::class,'representante_id'); }
    public function admin(){ return $this->belongsTo(User::class,'admin_id'); }
}
