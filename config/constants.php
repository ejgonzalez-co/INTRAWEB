<?php

return [
    'support_message' => 'Lo sentimos, hubo un problema al procesar su solicitud. Por favor, verifique que la información sea correcta. En caso de persistir el inconveniente, no dude en contactarnos a través de ' . (env("MAIL_SUPPORT") ?: 'soporte@seven.com.co') . '.<br>',
    // Modulos del sistema
    'modules' => [
        // // Modulo intranet
        // 'Intranet' => (object) [
        //     'title' => 'Intranet',
        //     'description' => 'Administre la estructura organizacional, variables, usuarios, dependencias, grupos de trabajo, cargos, sedes y ciudadanos de la entidad de manera eficiente.',
        //     'img' => 'assets/img/modules/Intranet.svg',
        //     // Componentes de intranet
        //     'components' => [
        //         // Componente de Regresar
        //         (object) [
        //             'name' => 'Regresar',
        //             'img' => '/assets/img/components/icono_regresar.png',
        //             'css_class' => '',
        //             'redirect' => '/dashboard',
        //             'roles' => ['Administrador intranet'],
        //         ],
        //         // Componente de Usuarios
        //         (object) [
        //             'name' => 'Gestión de usuarios y estructura organizacional',
        //             'img' => '/assets/img/components/intranet_users.png',
        //             'css_class' => '',
        //             'redirect' => '/intranet/users',
        //             'roles' => ['Administrador intranet'],
        //         ],
        //     ],
        // ],
        // // Modulo intranet
        'HerramientasColaborativas' => (object) [
            'title' => 'Herramientas colaborativas',
            // Componentes de intranet
            'components' => [
                // Componente de Regresar
                (object) [
                    'name' => 'Regresar',
                    'img' => '/assets/img/components/icono_regresar.png',
                    'css_class' => '',
                    'redirect' => '/dashboard',
                    'roles' => ['Administrador intranet'],
                ],
                // Componente de Calendario de eventos
                (object) [
                    'name' => 'Calendario de eventos',
                    'img' => '/assets/img/components/calendar_events.png',
                    'css_class' => '',
                    'redirect' => '/intranet/type-events',
                    'roles' => ['Administrador intranet de eventos'],
                ],
                // Componente de Descargas
                (object) [
                    'name' => 'Descargas',
                    'img' => '/assets/img/components/intranet_download.png',
                    'css_class' => '',
                    'redirect' => '/intranet/downloads',
                    'roles' => ['Administrador intranet de descargas'],
                ],
                // Componente de Difusión de noticias
                (object) [
                    'name' => 'Difusión de noticias',
                    'img' => '/assets/img/components/news.png',
                    'css_class' => '',
                    'redirect' => '/intranet/notices',
                    'roles' => ['Administrador intranet'],
                ],
                // Componente de Encuestas de satisfacción
                (object) [
                    'name' => 'Encuestas de la entidad',
                    'img' => '/assets/img/components/intranet_poll.png',
                    'css_class' => '',
                    'redirect' => '/intranet/polls',
                    'roles' => ['Administrador intranet de encuestas'],
                ],
            ],
        ],
        // // Módulo de correspondencia interna, enviada y recibida
        // 'Correspondence' => (object) [
        //     'title' => 'Correspondencia',
        //     'description' => 'Administre correspondencias internas y externas, cree y visualice documentos digitales, y aplique procesos electrónicos para una gestión eficiente en formato digital.',
        //     'img' => 'assets/img/modules/Correspondencia.svg',

        //     // Componentes de Autizacion sanitaria
        //     'components' => [
        //         // Componente de Regresar
        //         (object) [
        //             'name' => 'Regresar',
        //             'img' => '/assets/img/components/icono_regresar.png',
        //             'css_class' => '',
        //             'redirect' =>'/dashboard',
        //             'roles' => ['Registered']
        //         ],
        //         // Componente de correspondencia interna
        //         (object) [
        //             'name' => 'Correspondencia Interna',
        //             'img' => '/assets/img/components/solicitudes.png',
        //             'css_class' => '',
        //             'redirect' => '/correspondence/internals',
        //             'roles' => ['Registered'],
        //         ],
        //         // Componente de correspondencia recibida
        //         (object) [
        //             'name' => 'Correspondencia Recibida',
        //             'img' => '/assets/img/components/solicitudes.png',
        //             'css_class' => '',
        //             'redirect' => '/correspondence/external-receiveds',
        //             'roles' => ['Registered'],
        //         ],
        //         // Componente de correspondencia enviada
        //         (object) [
        //             'name' => 'Correspondencia Enviada',
        //             'img' => '/assets/img/components/solicitudes.png',
        //             'css_class' => '',
        //             'redirect' => '/correspondence/externals',
        //             'roles' => ['Registered'],
        //         ],
        //         // Componente de correo integrado
        //         // (object) [
        //         //     'name' => 'Comunicaciones por correo',
        //         //     'img' => '/assets/img/components/intranet_download.png',
        //         //     'css_class' => '',
        //         //     'redirect' => '/correspondence/correo-integrados',
        //         //     'roles' => ['Administrador Correspondencia'],
        //         // ],
        //         // // Componente de correo integrado
        //         // (object) [
        //         //     'name' => 'Rutas y planillas',
        //         //     'img' => '/assets/img/components/intranet_poll.png',
        //         //     'css_class' => '',
        //         //     'redirect' => '/correspondence/planillas',
        //         //     'roles' => ['Administrador Correspondencia'],
        //         // ],
        //     ]
        // ],
        // Módulo de PQRS
        'PQRS' => (object) [
            'title' => 'PQRS',
            'description' => 'Optimice el registro, seguimiento, y control en línea de solicitudes ciudadanas para garantizar respuestas oportunas y el manejo adecuado de información, cumpliendo con los plazos legales.',
            'img' => 'assets/img/modules/PQRS.svg',
            // Componentes de Autizacion sanitaria
            'components' => [
                // Componente de Regresar
                (object) [
                    'name' => 'Regresar',
                    'img' => '/assets/img/components/icono_regresar.png',
                    'css_class' => '',
                    'redirect' => '/dashboard',
                    'roles' => ['Registered', 'Administrador de requerimientos', 'Ciudadano']
                ],
                // Componente de PQRS
                (object) [
                    'name' => 'PQRS',
                    'img' => '/assets/img/components/convocatoria.png',
                    'css_class' => '',
                    'redirect' => '/pqrs/p-q-r-s',
                    'roles' => ['Registered', 'Administrador de requerimientos', 'Consulta de requerimientos'],
                ],
                // Componente de PQRS para los ciudadanos
                (object) [
                    'name' => 'PQRS',
                    'img' => '/assets/img/components/convocatoria.png',
                    'css_class' => '',
                    'redirect' => '/pqrs/p-q-r-s-ciudadano',
                    'roles' => ['Ciudadano'],
                ],
                // Componente de ejes temáticos
                (object) [
                    'name' => 'Ejes temáticos',
                    'img' => '/assets/img/components/calendar_events.png',
                    'css_class' => '',
                    'redirect' => '/pqrs/p-q-r-eje-tematicos',
                    'roles' => ['Administrador de requerimientos'],
                ],
                // Componente tipos de solicitudes
                (object) [
                    'name' => 'Tipos de solicitudes',
                    'img' => '/assets/img/components/solicitudes.png',
                    'css_class' => '',
                    'redirect' => '/pqrs/p-q-r-tipo-solicituds',
                    'roles' => ['Administrador de requerimientos'],
                ],
            ]
        ],
        // //Modulo de clasificacion documental
        // 'DocumentaryClassification' => (object) [
        //     'title' => 'Gestión Documental',
        //     'description' => 'Organice su documentación mediante series y subseries, tablas de retención y digitalización de inventarios para facilitar su consulta futura.',
        //     'img' => 'assets/img/modules/GestionDocumental.svg',
        //     //componentes de clasificacion documental
        //     'components' => [
        //         //componente regresar
        //         (object) [
        //             'name' => 'Regresar',
        //             'img' => '/assets/img/components/icono_regresar.png',
        //             'css_class' => '',
        //             'redirect' => '/dashboard',
        //             'roles' => ['Gestión Documental Admin','Gestión Documental Consulta']
        //         ],
        //         //componente tipos documentales
        //         (object) [
        //             'name' => 'Tipos documentales',
        //             'img' => '/assets/img/components/bandeja.png',
        //             'css_class' => '',
        //             'redirect' => '/documentary-classification/type-documentaries',
        //             'roles' => ['Gestión Documental Admin']
        //         ],
        //         //componente Gestión de series y subseries documentales
        //         (object) [
        //             'name' => 'Gestión de series y subseries documentales',
        //             'img' => '/assets/img/components/carpeta.png',
        //             'css_class' => '',
        //             'redirect' => '/documentary-classification/series-subseries',
        //             'roles' => ['Gestión Documental Admin']
        //         ],
        //         //componente Tablas de retención documental
        //         (object) [
        //             'name' => 'Tablas de retención documental',
        //             'img' => '/assets/img/components/archivos.png',
        //             'css_class' => '',
        //             'redirect' => '/documentary-classification/dependencias',
        //             'roles' => ['Gestión Documental Admin']
        //         ],
        //         //componente Inventario documental
        //         (object) [
        //             'name' => 'Inventario documental',
        //             'img' => '/assets/img/components/inventario_documents.png',
        //             'css_class' => '',
        //             'redirect' => '/documentary-classification/inventory-documentals',
        //             'roles' => ['Gestión Documental Admin']
        //         ],
        //         //componente Consultar documentos del inventario
        //         (object)[
        //             'name' => 'Consultar documentos del inventario',
        //             'img' => '/assets/img/classification_logo/consultas.png',
        //             'css_class' => '',
        //             'redirect' => '',
        //             'roles' => ['Gestión Documental Consulta']
        //         ]
        //     ]
        // ],
        // // Modulo mesa de ayuda
        // 'HelpTable' => (object) [
        //     'title' => 'Mesa de ayuda',
        //     'description' => 'Mejore su servicio al cliente gestionando tickets de soporte de forma eficiente, aumentando la satisfacción del cliente, administrando inventario y creando base de conocimiento.',
        //     'img' => 'assets/img/modules/pqrs.png',
        //     // Componentes de mesa de ayuda
        //     'components' => [
        //         // Componente de Regresar
        //         (object) [
        //             'name' => 'Regresar',
        //             'img' => '/assets/img/components/icono_regresar.png',
        //             'css_class' => '',
        //             'redirect' => '/dashboard',
        //             'roles' => ['Administrador TIC', 'Soporte TIC', 'Usuario TIC', 'Proveedor TIC'],
        //         ],
        //         // Componente de tecnicos
        //         (object) [
        //             'name' => 'Gestión de técnicos y proveedores',
        //             'img' => '/assets/img/components/estructura_organizacional.png',
        //             'css_class' => '',
        //             'redirect' => '/help-table/users',
        //             'roles' => ['Administrador TIC'],
        //         ],
        //         // Componente de Inventario
        //         (object) [
        //             'name' => 'Inventario',
        //             'img' => '/assets/img/components/inventario.png',
        //             'css_class' => '',
        //             'redirect' => '/help-table/tic-assets',
        //             'roles' => ['Administrador TIC', 'Soporte TIC'],
        //         ],
        //         // Componente de Solicitud
        //         (object) [
        //             'name' => 'Solicitudes',
        //             'img' => '/assets/img/components/solicitudes.png',
        //             'css_class' => '',
        //             'redirect' => '/help-table/tic-requests',
        //             'roles' => ['Administrador TIC', 'Soporte TIC', 'Usuario TIC', 'Proveedor TIC'],
        //         ],
        //         // Componente de Estadistica
        //         (object) [
        //             'name' => 'Estadísticas',
        //             'img' => '/assets/img/components/estadistica.png',
        //             'css_class' => '',
        //             'redirect' => '/help-table/statistics',
        //             'roles' => [],
        //         ],
        //         // Componente de Base de Conocimiento
        //         (object) [
        //             'name' => 'Base de conocimiento',
        //             'img' => '/assets/img/components/bases_conocimiento.png',
        //             'css_class' => '',
        //             'redirect' => '/help-table/tic-knowledge-bases',
        //             'roles' => ['Administrador TIC', 'Soporte TIC'],
        //         ],
        //     ],
        // ],
        // // Modulo de historias laborales
        'Workhistories' => (object) [
            'title' => 'Historias Laborales',
            'description' => 'Gestione de manera eficiente y organizada las historias laborales de la entidad.',
            'img' => 'assets/img/modules/historias_laborales.png',
            // Componentes del módulo
            'components' => [
                // Componente de Regresar
                (object) [
                    'name' => 'Regresar',
                    'img' => '/assets/img/components/regresar.png',
                    'css_class' => '',
                    'redirect' => config('app.url').'/index.php?option=com_intranet&view=perfil',
                    'roles' => ['Administrador historias laborales','Gestor hojas de vida'],
                ],
                // Componente de intranet
                (object) [
                    'name' => 'Funcionarios activos y retirados',
                    'img' => '/assets/img/components/doc_funcionario.png',
                    'css_class' => '',
                    'redirect' => '/work-histories/work-histories-actives',
                    'roles' => ['Administrador historias laborales'],
                ],
                  // Componente de intranet
                (object) [
                    'name' => 'Consulta funcionarios activos y retirados',
                    'img' => '/assets/img/components/doc_funcionario.png',
                    'css_class' => '',
                    'redirect' => '/work-histories/work-histories-actives',
                    'roles' => ['Gestor hojas de vida'],
                ],
                // Componente de intranet
                (object) [
                    'name' => 'Pensionados, sustitutos y cuotas partes',
                    'img' => '/assets/img/components/doc_pensionado.png',
                    'css_class' => '',
                    'redirect' => '/work-histories/work-hist-pensioners',
                    'roles' => ['Administrador historias laborales'],
                ],
                // Componente de intranet
                (object) [
                    'name' => 'Consulta funcionarios pensionados, sustitutos y cuotas partes',
                    'img' => '/assets/img/components/doc_pensionado.png',
                    'css_class' => '',
                    'redirect' => '/work-histories/work-hist-pensioners',
                    'roles' => ['Gestor hojas de vida'],
                ],
                // Componente de intranet
                (object) [
                    'name' => 'Autorizaciones',
                    'img' => '/assets/img/components/doc_funcionario.png',
                    'css_class' => '',
                    'redirect' => '/work-histories/work-request',
                    'roles' => ['Administrador historias laborales'],
                ],
                (object) [
                    'name' => 'Solicitudes de autorización',
                    'img' => '/assets/img/components/doc_funcionario.png',
                    'css_class' => '',
                    'redirect' => '/work-histories/work-request',
                    'roles' => ['Gestor hojas de vida'],
                ]
            ]
        ],
        // // Modulo configuracion
        // 'Configuracion' => (object) [
        //     'title' => 'Configuración',
        //     'description' => 'Administre documentos de ayuda para su entidad, mejorando la claridad y el uso diario de las herramientas por parte de los funcionarios',
        //     'img' => 'assets/img/modules/Configuracion.svg',
        //     // Componentes de configuracion
        //     'components' => [
        //         // Componente de Regresar
        //         (object) [
        //             'name' => 'Regresar',
        //             'img' => '/assets/img/components/icono_regresar.png',
        //             'css_class' => '',
        //             'redirect' => '/dashboard',
        //             'roles' => ['Administrador intranet'],
        //         ],
        //         // Componente de Usuarios
        //         (object) [
        //             'name' => 'Documentos de ayuda',
        //             'img' => '/assets/img/components/doc_consultar.png',
        //             'css_class' => '',
        //             'redirect' => '/configuracion/documentos-ayudas',
        //             'roles' => ['Administrador intranet'],
        //         ],
        //     ],
        // ],
    ],
];
