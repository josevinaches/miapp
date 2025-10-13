<?php
return [
    'unique' => 'El :attribute ya ha sido registrado.',
    'attributes' => [
        'dni'   => 'DNI/NIE',
        'email' => 'email',
    ],
    'custom' => [
        'dni' => [
            'unique' => 'Este DNI/NIE ya está registrado.',
        ],
        'email' => [
            'unique' => 'Este email ya está registrado.',
        ],
    ],
];
