<?php

return [
    'name' => 'Intranet',
    // Estados de los tipos de eventos
    'type_event_status' => [
        (object) [
        'id' => 1,
        'name' => "Activo",
        ],
        (object) [
        'id' => 2,
        'name' => "Inactivo",
        ],
    ],
    // Tipo de categorias del calendario de eventos
    'event_category_type' => [
        (object) [
        'id' => 1,
        'name' => "Público",
        ],
        (object) [
        'id' => 2,
        'name' => "Personal",
        ],
    ],
    // Duracion de los eventos del calendario
    'duration_event' => [
        (object) [
        'id' => 1,
        'name' => "15 minutos",
        ],
        (object) [
        'id' => 2,
        'name' => "30 minutos",
        ],
        (object) [
        'id' => 3,
        'name' => "45 minutos",
        ],
        (object) [
        'id' => 4,
        'name' => "1 hora",
        ],
        (object) [
        'id' => 5,
        'name' => "1,5 horas",
        ],
        (object) [
        'id' => 6,
        'name' => "2 horas",
        ],
        (object) [
        'id' => 7,
        'name' => "2,5 horas",
        ],
    ],
    // Estados de los eventos
    'event_status' => [
        (object) [
        'id' => 1,
        'name' => "Pendiente",
        ],
        (object) [
        'id' => 2,
        'name' => "Terminado",
        ],
        (object) [
        'id' => 3,
        'name' => "Cancelado",
        ],
    ],
    // Estados de poll
    'state' => [
        (object) [
        'id' => 1,
        'name' => "En elaboración",
        'colour' => "#1971B6"

        ],
        (object) [
        'id' => 2,
        'name' => "Publicada",
        'colour' => "#8BC34A"

        ],
        (object) [
        'id' => 3,
        'name' => "Cerrada",
        'colour' => "#9D9D9D"
        ]
    ],
    'type_question_poll' => [
        (object) [
        'id' => 1,
        'name' => "Pregunta de selección única",
        'colour' => "#1971B6"

        ],
        (object) [
        'id' => 2,
        'name' => "Pregunta de selección multiple",
        'colour' => "#8BC34A"

        ],
        (object) [
        'id' => 3,
        'name' => "Pregunta de comentario u opinión",
        'colour' => "#9D9D9D"
        ],
        (object) [
        'id' => 4,
        'name' => "Escala de valoración del 1 a 5 con estrellas",
        'colour' => "#9D9D9D"
        ]
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