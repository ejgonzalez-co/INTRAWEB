<?php

return [
    'name' => 'PQRS',
    // Tipo de documento
    'type_document' => [
        (object) [
        'id' => 1,
        'name' => "Cédula de ciudadanía",
        'state' => 'Activa'
        ],
        (object) [
        'id' => 2,
        'name' => "Cédula de Extranjería",
        'state' => 'Activa'
        ],
        (object) [
        'id' => 3,
        'name' => "Tarjeta Identidad",
        'state' => 'Activa'
        ],
        (object) [
        'id' => 4,
        'name' => "Pasaporte",
        'state' => 'Activa'
        ],
        (object) [
        'id' => 5,
        'name' => "NIT",
        'state' => 'Activa'
        ],
        (object) [
        'id' => 6,
        'name' => "Otro",
        'state' => 'Activa'
        ],
        (object) [
        'id' => 7,
        'name' => "NIUP- Número Unico de identificación personal",
        'state' => 'Activa'
        ],
        (object) [
        'id' => 8,
        'name' => "PPT - Permiso por Protección Temporal",
        'state' => 'Activa'
        ],
    ],
    // Tipo de persona
    "type_person" => [
        (object) [
        'id' => 1,
        'name' => "Persona natural",
        ],
        (object) [
        'id' => 2,
        'name' => "Persona jurídica",
        ],
    ],
];
