<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Festero;

/**
 * @extends Factory<Festero>
 */
class FesteroFactory extends Factory
{
    protected $model = Festero::class;

    public function definition(): array
    {
        // Fecha entre 10 y 60 años
        $fecha = $this->faker->dateTimeBetween('-60 years', '-10 years');
        $edad = (int) now()->diff($fecha)->y;

        // DNI español: 8 dígitos + letra válida
        $num = $this->faker->numberBetween(10000000, 99999999);
        $letters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $dni = $num . $letters[$num % 23];

        $trabuco = $edad < 18 ? false : $this->faker->boolean(30);
        $embarque = $edad < 18 ? false : $this->faker->boolean(30);

         return [
            'nombre'            => $this->faker->firstName(),
            'primer_apellido'   => $this->faker->lastName(),
            'segundo_apellido'  => $this->faker->lastName(),
            'dni'               => $dni,
            'email'             => $this->faker->unique()->safeEmail(),
            'telefono'          => $this->faker->numerify('6########'),
            'fecha_nacimiento'  => $fecha->format('Y-m-d'),
            'trabuco'           => $trabuco,
            'embarque'          => $embarque,
        ];
    }
}
