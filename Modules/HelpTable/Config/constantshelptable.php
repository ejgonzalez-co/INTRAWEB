<?php

return [
    // Numero de registro de solicitudes permitidas para el usuario por dia
    'requests_allowed' => 3,

    // Tipo de personas
    'type_person' => [
        (object) [
            'id' => 1,
            'name' => "Natural",
        ],
        (object) [
            'id' => 2,
            'name' => "Jurídica",
        ],
    ],

    // Tipos de numero de identificacion
    "identification_type" => [
        (object) [
            'id' => 1,
            'name' => "Cédula de Ciudadanía"
        ],
        (object) [
            'id' => 2,
            'name' => "Cédula de Extranjería"
        ],
        (object) [
            'id' => 3,
            'name' => "Tarjeta Identidad"
        ],
        (object) [
            'id' => 4,
            'name' => "Pasaporte"
        ],
        (object) [
            'id' => 5,
            'name' => "NIT"
        ]
    ],

    // Tipo de regimes
   'regime_type' => [
        (object) [
        'id' => 1,
        'name' => "Simplicado",
        ],
        (object) [
            'id' => 2,
            'name' => "Común",
        ],
    ],

    // Estado de los proveedores de mesa de ayuda
   'provider_state' => [
        (object) [
            'id' => 1,
            'name' => "Activo",
        ],
        (object) [
            'id' => 2,
            'name' => "Inactivo",
        ],
    ],

    'type_maintenance_tic' => [
        (object) [
            'id' => 1,
            'name' => "Preventivo",
        ],
        (object) [
            'id' => 2,
            'name' => "Correctivo",
        ],
    ],

    'maintenance_status_tic' => [
        (object) [
            'id' => 1,
            'name' => "Abierto",
        ],
        (object) [
            'id' => 2,
            'name' => "Asignado",
        ],
        (object) [
            'id' => 3,
            'name' => "En proceso",
        ],
        (object) [
            'id' => 4,
            'name' => "Pendiente",
        ],
        (object) [
            'id' => 5,
            'name' => "Cerrado",
        ],
    ],

    
    // Unidad de tiempo
   'unit_time' => [
        (object) [
            'id' => 1,
            'name' => "Horas",
        ],
        (object) [
            'id' => 2,
            'name' => "Días",
        ],
    ],

    // Tipo de plazo
   'type_term' => [
        (object) [
            'id' => 1,
            'name' => "Laboral",
        ],
        (object) [
            'id' => 2,
            'name' => "Calendario",
        ],
    ],





    // Tipo de prioridad de una solicitud
   'priority_request' => [
        (object) [
            'id' => 1,
            'name' => "Baja",
        ],
        (object) [
            'id' => 2,
            'name' => "Media",
        ],
        (object) [
            'id' => 3,
            'name' => "Alta",
        ],
    ],

    // Sistemas operativos
   'operating_systems' => [
        (object) [
            'id' => 1,
            'name' => "Windows",
        ],
        (object) [
            'id' => 2,
            'name' => "Linux",
        ],
        (object) [
            'id' => 3,
            'name' => "MacOs",
        ],
    ],

    // Vesiones de sistemas operativos
   'operating_systems_version' => [
        (object) [
            'id' => 1,
            'name' => "Windows XP",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 2,
            'name' => "Windows Vista",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 3,
            'name' => "Windows Home Server",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 4,
            'name' => "Windows 7",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 5,
            'name' => "Windows 7 Starter",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 6,
            'name' => "Windows 7 Home Basic",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 7,
            'name' => "Windows 7 Home Premium",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 8,
            'name' => "Windows 7 Professional",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 9,
            'name' => "Windows 7 Enterprise",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 10,
            'name' => "Windows 7 Ultimate",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 11,
            'name' => "Windows 8",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 12,
            'name' => "Windows 8 Pro",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 13,
            'name' => "Windows 8 Enterprise",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 14,
            'name' => "Windows 8.1",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 15,
            'name' => "Windows 10",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 16,
            'name' => "Windows 10 Home",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 17,
            'name' => "Windows 10 Pro",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 18,
            'name' => "Windows 10 Education",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 19,
            'name' => "Windows 10 Enterprise",
            'operating_systems_id' => 1,
        ],
        (object) [
            'id' => 20,
            'name' => "Debian",
            'operating_systems_id' => 2,
        ],
        (object) [
            'id' => 21,
            'name' => "Ubuntu",
            'operating_systems_id' => 2,
        ],
        (object) [
            'id' => 22,
            'name' => "Manjaro",
            'operating_systems_id' => 2,
        ],
        (object) [
            'id' => 23,
            'name' => "Fedora",
            'operating_systems_id' => 2,
        ],
        (object) [
            'id' => 24,
            'name' => "OpenSUSE",
            'operating_systems_id' => 2,
        ],
        (object) [
            'id' => 25,
            'name' => "Catalina",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 26,
            'name' => "Mojave",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 27,
            'name' => "High Sierra",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 28,
            'name' => "Sierra",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 29,
            'name' => "El Capitán",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 30,
            'name' => "Yosemite",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 31,
            'name' => "Mavericks",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 32,
            'name' => "Mountain Lion",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 33,
            'name' => "Lion",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 34,
            'name' => "Snow Leopard",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 35,
            'name' => "Leopard",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 36,
            'name' => "Tiger",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 37,
            'name' => "Panther",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 38,
            'name' => "Jaguar",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 39,
            'name' => "Puma",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 40,
            'name' => "Cheetah",
            'operating_systems_id' => 3,
        ],
        (object) [
            'id' => 41,
            'name' => "Windows 11PRO",
            'operating_systems_id' => 1,
        ],

    ],

    // Licencia de office
    'office_automation_versions' => [
        (object) [
            'id' => 1,
            'name' => "Microsoft Office 95",
        ],
        (object) [
            'id' => 2,
            'name' => "Microsoft Office 97",
        ],
        (object) [
            'id' => 3,
            'name' => "Microsoft Office 2000",
        ],
        (object) [
            'id' => 4,
            'name' => "Microsoft Office XP",
        ],
        (object) [
            'id' => 5,
            'name' => "Microsoft Office 2003",
        ],
        (object) [
            'id' => 6,
            'name' => "Microsoft Office 2007",
        ],
        (object) [
            'id' => 7,
            'name' => "Microsoft Office 2010",
        ],
        (object) [
            'id' => 8,
            'name' => "Microsoft Office 2013",
        ],
        (object) [
            'id' => 9,
            'name' => "Microsoft Office 2016",
        ],
        (object) [
            'id' => 10,
            'name' => "Microsoft Office 2017",
        ],
        (object) [
            'id' => 11,
            'name' => "Microsoft Office 2019",
        ],
        (object) [
            'id' => 12,
            'name' => "Microsoft Office 365",
        ],
        (object) [
            'id' => 13,
            'name' => "Libre Office",
        ],
        (object) [
            'id' => 14,
            'name' => "Open Office",
        ],
        (object) [
            'id' => 15,
            'name' => "Office 2021LTS",
        ],
    ],

    // Estado de los activos tic
    'state_assets_tic' => [
        (object) [
            'id' => 1,
            'name' => "Activo",
        ],
        (object) [
            'id' => 2,
            'name' => "Inactivo",
        ],
    ],

    // Estado de la base de conocimiento tic
    'state_knowledge_tic' => [
        (object) [
            'id' => 1,
            'name' => "Activo",
        ],
        (object) [
            'id' => 2,
            'name' => "Inactivo",
        ],
    ],
    // Tipo de soporte tic
    'support_type_tic' => [
        (object) [
            'id' => 1,
            'name' => "Interno",
        ],
        (object) [
            'id' => 2,
            'name' => "Externo",
        ],
    ],
    // Estados de la encuesta de satisfaccion
    'tic_poll_status' => [
        (object) [
            'id' => 1,
            'name' => "Encuesta pendiente",
        ],
        (object) [
            'id' => 2,
            'name' => "Encuesta realizada",
        ],
        (object) [
            'id' => 3,
            'name' => "Sin encuesta",
        ],
    ],
];

?>