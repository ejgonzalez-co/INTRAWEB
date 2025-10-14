<?php

return [
    'name' => 'Correspondence',
    // Estados de la correspondencia externa recibida
    'external_received_states' => [
        (object) [
        'id' => 1,
        'name' => "Devuelto",
        'state' => "Act"
        ],
        (object) [
        'id' => 2,
        'name' => "Pendiente",
        ],
        (object) [
        'id' => 3,
        'name' => "Público",
        ],
        (object) [
        'id' => 4,
        'name' => "Rechazado",
        ],
    ],
    // Estados de la correspondencia externa recibida sin 2 estados
    'external_received_states_two' => [
        (object) [
        'id' => 1,
        'name' => "Devuelto",
        'state' => "Act"
        ],
        (object) [
        'id' => 3,
        'name' => "Público",
        ],
        
    ],
    // Canales para recibir
    'external_received_channels' => [
        (object) [
        'id' => 7,
        'name' => "Buzón de sugerencias",
        'state' => "Active",
        ],
        (object) [
        'id' => 1,
        'name' => "Correo certificado",
        'state' => "Active",
        ],
        (object) [
        'id' => 2,
        'name' => "Correo electrónico",
        'state' => "Active",
        ],
        (object) [
        'id' => 3,
        'name' => "Fax",
        'state' => "Active",
        ],
        (object) [
        'id' => 4,
        'name' => "Personal",
        'state' => "Active",
        ],
        (object) [
        'id' => 5,
        'name' => "Telefónico",
        'state' => "Active",
        ],
        (object) [
        'id' => 6,
        'name' => "Web",
        'state' => "Active",
        ],
        (object) [
            'id' => 8,
            'name' => "Verbal",
            'state' => "Active",
            ],
    ],
    // Estados de los tipos documentales
    "states_documentary_types" => [
        (object) [
        'id' => 1,
        'name' => "Activo",
        ],
        (object) [
        'id' => 2,
        'name' => "Inactivo",
        ],
    ],
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