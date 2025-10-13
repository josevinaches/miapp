<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class StoreFesteroRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comparsa_id' => ['required', 'exists:comparsas,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'primer_apellido' => ['required', 'string', 'max:255'],
            'segundo_apellido' => ['nullable', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'trabuco' => ['boolean'],
            'embarque' => ['boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $fecha = $this->input('fecha_nacimiento');
            if ($fecha) {
                $edad = Carbon::parse($fecha)->age;
                if ($edad < 18 && ($this->boolean('trabuco') || $this->boolean('embarque'))) {
                    $validator->errors()->add('trabuco', 'Si es menor, no puede marcar Trabuco ni Embarque.');
                }
            }
        });
    }
}
