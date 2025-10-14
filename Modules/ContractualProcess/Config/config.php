<?php

return [
   // Estados de la convocatoria
   'pc_paa_calls_status' => [
      (object) [
         'id' => 1,
         'name' => "Abierta",
         'colour' => "#28a745",
      ],
      (object) [
         'id' => 2,
         'name' => "Cerrada",
         'colour' => "#6c757d",
      ],
   ],
   // Estados de las necesidades
   'pc_needs_status' => [
      (object) [
         'id' => 1,
         'name' => "Sin iniciar PAA",
         'colour' => "#007bff",
      ],
      (object) [
         'id' => 2,
         'name' => "PAA en elaboración",
         'colour' => "#6c757d",
      ],
      // (object) [
      //    'id' => 3,
      //    'name' => "PAA en revisión jefe presupuesto",
      //    'colour' => "#6c757d",
      // ],
      (object) [
         'id' => 3,
         'name' => "PAA en revisión",
         'colour' => "#6c757d",
      ],
      (object) [
         'id' => 4,
         'name' => "PAA devuelto",
         'colour' => "#6c757d",
      ],
      (object) [
         'id' => 5,
         'name' => "PAA finalizado",
         'colour' => "#6c757d",
      ],
      (object) [
         'id' => 6,
         'name' => "Solicitud de modificación de PAA",
         'colour' => "#6c757d",
      ],
      (object) [
         'id' => 7,
         'name' => "PAA habilitado para modificación",
         'colour' => "#6c757d",
      ],
      (object) [
         'id' => 8,
         'name' => "PAA validado por presupuesto",
         'colour' => "#6c757d",
      ],
      (object) [
         'id' => 9,
         'name' => "PAA validado por planeación",
         'colour' => "#6c757d",
      ],
   ],
   // Estado de las fichas tecnicas de inversion
   'investment_technical_state' => [
      (object) [
         'id' => 1,
         'name' => "En elaboración",
         'colour' => "#007bff",
         'state' => "Activa",
      ],
   ],
   // Plan de desarrollo municipal de las fichas tecnicas de inversion
   'investment_technical_municipal_development_plan' => [
      (object) [
         'id' => 1,
         'name' => "Armenia pa´TODOS",
         'state' => "Activa",
      ],
   ],
   // Periodos de las fichas tecnicas de inversion
   'investment_technical_period' => [
      (object) [
         'id' => 1,
         'name' => "2020-2023",
         'state' => "Activa",
      ],
   ],
   // Lineas estrategicas de las fichas tecnicas de inversion
   'investment_technical_strategic_line' => [
      (object) [
         'id' => 1,
         'name' => 'Infraestructura Construida: "Acciones Concretas"',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'Infraestructura Natural: "Armenia Capital Verde"',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'name' => 'Institucional y Gobierno: "Servir y hacer las cosas bien"',
         'state' => "Activa",
      ],
   ],
   // Sector de las fichas tecnicas de inversion
   'investment_technical_sector' => [
      (object) [
         'id' => 1,
         'name' => 'Vivienda',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'Ambiente y desarrollo sostenible',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'name' => 'Gobierno Territorial',
         'state' => "Activa",
      ],
   ],
   // Programa de las fichas tecnicas de inversion
   'investment_technical_program' => [
      (object) [
         'id' => 1,
         'name' => 'Infraestructura de servicios públicos pa´TODOS',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'Gestión Integral del recurso hídrico responsabilidad de TODOS',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'name' => 'EPA ESP la empresa de TODOS',
         'state' => "Activa",
      ],
   ],
   // Subprograma de las fichas tecnicas de inversion
   'investment_technical_subprogram' => [
      (object) [
         'id' => 1,
         'name' => 'Expansión de infraestructura  de servicios públicos',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'Reposición u optimización de infraestructura de servicios públicos',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'name' => 'Rehabilitación y/o mejoramiento de infraestructura  de servicios públicos',
         'state' => "Activa",
      ],
      (object) [
         'id' => 4,
         'name' => 'Contingencia y gestión del riesgo  de servicios públicos',
         'state' => "Activa",
      ],
      (object) [
         'id' => 5,
         'name' => 'Planeación técnica para el desarrollo de los servicios públicos',
         'state' => "Activa",
      ],
      (object) [
         'id' => 6,
         'name' => 'Fortalecimiento técnico y operativo para el desarrollo de los servicios ',
         'state' => "Activa",
      ],
      (object) [
         'id' => 7,
         'name' => 'Fomento a la Gestión Integral de Residuos Solidos',
         'state' => "Activa",
      ],
      (object) [
         'id' => 8,
         'name' => 'Uso Eficiente y Ahorro del Agua',
         'state' => "Activa",
      ],
      (object) [
         'id' => 9,
         'name' => 'Monitoreo y Control de la Calidad del Agua',
         'state' => "Activa",
      ],
      (object) [
         'id' => 10,
         'name' => 'Planes y Programas Institucionales',
         'state' => "Activa",
      ],
      (object) [
         'id' => 11,
         'name' => 'Modelo Integrado de Planeación y Gestión ',
         'state' => "Activa",
      ],
      (object) [
         'id' => 12,
         'name' => 'Gestión de calidad para los servicios públicos',
         'state' => "Activa",
      ],
      (object) [
         'id' => 13,
         'name' => 'Desarrollo de Instrumentos para la competitividad',
         'state' => "Activa",
      ],
      (object) [
         'id' => 14,
         'name' => 'Fortalecimiento de plataforma tecnologías y sistemas de información',
         'state' => "Activa",
      ],
      (object) [
         'id' => 15,
         'name' => 'Desarrollo de Servicios y Negocios para la competitividad',
         'state' => "Activa",
      ],
   ],
   // Area de cobertura de las fichas tecnicas de inversion
   'investment_technical_service_coverage' => [
      (object) [
         'id' => 1,
         'name' => 'Urbana',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'Rural',
         'state' => "Activa",
      ],
   ],
   // Otros documentos de las fichas tecnicas de inversion
   'iv_other_planning_documents' => [
      (object) [
         'id' => 1,
         'name' => 'POIR: Plan de Obras e Inversiones Regulado',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'PUEAA: Programa de Uso Eficiente y Ahorro del Agua ',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'name' => 'PSMV: Plan de Saneamiento y Manejo de Vertimientos',
         'state' => "Activa",
      ],
      (object) [
         'id' => 4,
         'name' => 'PMAA: Plan Maestro de Acueducto y Alcantarillado',
         'state' => "Activa",
      ],
      (object) [
         'id' => 5,
         'name' => 'PGIRS: Plan de Gestión Integral de Residuos Solidos',
         'state' => "Activa",
      ],
      (object) [
         'id' => 6,
         'name' => 'Otro',
         'state' => "Activa",
      ],
   ],
   // Articulo de armonización tarifaria de la ficha tecnica de inversion
   'item_tariff_harmonization' => [
      (object) [
         'id' => 1,
         'name' => 'Servicio Acueducto',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'Servicio Alcantarillado',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'name' => 'Servicio Aseo',
         'state' => "Activa",
      ],
   ],
   // Item del articulo de armonización tarifaria de la ficha tecnica de inversion
   'activity_item_tariff_harmonization' => [
      (object) [
         'id' => 1,
         'item_id' => 1,
         'name' => 'Captación',
         'unit' => 'm<sup>3</sup>/día',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'item_id' => 1,
         'name' => 'Aducción',
         'unit' => 'm',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'item_id' => 1,
         'name' => 'Tratamiento',
         'unit' => 'm<sup>3</sup>/día',
         'state' => "Activa",
      ],
      (object) [
         'id' => 4,
         'item_id' => 1,
         'name' => 'Conducción',
         'unit' => 'm',
         'state' => "Activa",
      ],
      (object) [
         'id' => 5,
         'item_id' => 1,
         'name' => 'Almacenamiento',
         'unit' => 'm<sup>3</sup>',
         'state' => "Activa",
      ],
      (object) [
         'id' => 6,
         'item_id' => 1,
         'name' => 'Distribución',
         'unit' => 'm',
         'state' => "Activa",
      ],
      (object) [
         'id' => 7,
         'item_id' => 2,
         'name' => 'Recolección y Transporte',
         'unit' => 'm',
         'state' => "Activa",
      ],
      (object) [
         'id' => 8,
         'item_id' => 2,
         'name' => 'Elevación y Bombeo',
         'unit' => 'm<sup>3</sup>',
         'state' => "Activa",
      ],
      (object) [
         'id' => 9,
         'item_id' => 2,
         'name' => 'Tratamiento',
         'unit' => 'm<sup>3</sup>/día',
         'state' => "Activa",
      ],
      (object) [
         'id' => 10,
         'item_id' => 2,
         'name' => 'Disposición Final ',
         'unit' => 'm<sup>3</sup>/día',
         'state' => "Activa",
      ],
      (object) [
         'id' => 11,
         'item_id' => 2,
         'name' => 'Recuperación de Humedales',
         'unit' => 'm<sup>3</sup>/día',
         'state' => "Activa",
      ],
      (object) [
         'id' => 12,
         'item_id' => 3,
         'name' => 'Aprovechamiento ',
         'unit' => 'Ton',
         'state' => "Activa",
      ],
      (object) [
         'id' => 13,
         'item_id' => 3,
         'name' => 'Barrido y limpieza',
         'unit' => 'Km',
         'state' => "Activa",
      ],
      (object) [
         'id' => 14,
         'item_id' => 3,
         'name' => 'Comercialización',
         'unit' => 'Matrícula',
         'state' => "Activa",
      ],
      (object) [
         'id' => 15,
         'item_id' => 3,
         'name' => 'Disposición Final de residuos ',
         'unit' => 'Ton',
         'state' => "Activa",
      ],
      (object) [
         'id' => 16,
         'item_id' => 3,
         'name' => 'Recolección y transporte',
         'unit' => 'Ton',
         'state' => "Activa",
      ],
      (object) [
         'id' => 17,
         'item_id' => 3,
         'name' => 'Transferencia',
         'unit' => 'N/A',
         'state' => "Activa",
      ],
      (object) [
         'id' => 18,
         'item_id' => 3,
         'name' => 'Lavado de Áreas Publicas',
         'unit' => 'N/A',
         'state' => "Activa",
      ],
      (object) [
         'id' => 19,
         'item_id' => 3,
         'name' => 'Incineración',
         'unit' => 'N/A',
         'state' => "Activa",
      ],
      (object) [
         'id' => 20,
         'item_id' => 3,
         'name' => 'Corte de Césped y poda de Árboles',
         'unit' => 'm<sup>2</sup>',
         'state' => "Activa",
      ],
      (object) [
         'id' => 21,
         'item_id' => 3,
         'name' => 'Recolección de residuos especiales',
         'unit' => 'Ton',
         'state' => "Activa",
      ],
   ],
   // Estado de tipo de inversion de armonización tarifaria de la ficha tecnica de inversion
   'state_type_investment_tariff_harmonization' => [
      (object) [
         'id' => 1,
         'name' => 'Factibilidad',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'Prediseño',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'name' => 'Diseño',
         'state' => "Activa",
      ],
   ],
   // Tipo de estudio soporte de armonización tarifaria de la ficha tecnica de inversion
   'support_study_type_tariff_harmonization' => [
      (object) [
         'id' => 1,
         'name' => 'Estudio de Costo Mínimo',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'Estudio de Soporte Especifico',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'name' => 'Análisis de Cuellos de Botella',
         'state' => "Activa",
      ],
      (object) [
         'id' => 4,
         'name' => 'Plan Departamental de Agua',
         'state' => "Activa",
      ],
      (object) [
         'id' => 5,
         'name' => 'Plan de Saneamiento y Manejo de Vertimientos PSMV',
         'state' => "Activa",
      ],
      (object) [
         'id' => 6,
         'name' => 'Plan de Gestión Integral de Residuos Sólidos PGIRS',
         'state' => "Activa",
      ],
      (object) [
         'id' => 7,
         'name' => 'Plan de Gestión Ambiental',
         'state' => "Activa",
      ],
      (object) [
         'id' => 8,
         'name' => 'Plan de Ordenamiento Territorial',
         'state' => "Activa",
      ],
      (object) [
         'id' => 9,
         'name' => 'Estudio de Vulnerabilidad',
         'state' => "Activa",
      ],
      (object) [
         'id' => 10,
         'name' => 'Plan de Desarrollo',
         'state' => "Activa",
      ],
   ],
   // Componente ambiental de la ficha tecnica de inversion
   'environmental_component' => [
      (object) [
         'id' => 1,
         'name' => 'Aire',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'Agua',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'name' => 'Suelo',
         'state' => "Activa",
      ],
      (object) [
         'id' => 4,
         'name' => 'Fauna',
         'state' => "Activa",
      ],
      (object) [
         'id' => 5,
         'name' => 'Flora',
         'state' => "Activa",
      ],
      (object) [
         'id' => 6,
         'name' => 'Paisaje',
         'state' => "Activa",
      ],
      (object) [
         'id' => 7,
         'name' => 'Social',
         'state' => "Activa",
      ],
      (object) [
         'id' => 8,
         'name' => 'Otros (Especifique)',
         'state' => "Activa",
      ],
   ],
   // Meses del cronograma de la ficha tecnica de inversion
   'schedule_month' => [
      (object) [
         'month' => 'Enero',
         'week'  => [
            (object) [
               'id' => 1,
               'name' => 'Ene - Sem. 1',
            ],
            (object) [
               'id' => 2,
               'name' => 'Ene - Sem. 2',
            ],
            (object) [
               'id' => 3,
               'name' => 'Ene - Sem. 3',
            ],
            (object) [
               'id' => 4,
               'name' => 'Ene - Sem. 4',
            ],
         ],
      ],
      (object) [
         'month' => 'Febrero',
         'week'  => [
            (object) [
               'id' => 5,
               'name' => 'Feb - Sem. 1',
            ],
            (object) [
               'id' => 6,
               'name' => 'Feb - Sem. 2',
            ],
            (object) [
               'id' => 7,
               'name' => 'Feb - Sem. 3',
            ],
            (object) [
               'id' => 8,
               'name' => 'Feb - Sem. 4',
            ],
         ],
      ],
      (object) [
         'month' => 'Marzo',
         'week'  => [
            (object) [
               'id' => 9,
               'name' => 'Mar - Sem. 1',
            ],
            (object) [
               'id' => 10,
               'name' => 'Mar - Sem. 2',
            ],
            (object) [
               'id' => 11,
               'name' => 'Mar - Sem. 3',
            ],
            (object) [
               'id' => 12,
               'name' => 'Mar - Sem. 4',
            ],
         ],
      ],
      (object) [
         'month' => 'Abril',
         'week'  => [
            (object) [
               'id' => 13,
               'name' => 'Abr - Sem. 1',
            ],
            (object) [
               'id' => 14,
               'name' => 'Abr - Sem. 2',
            ],
            (object) [
               'id' => 15,
               'name' => 'Abr - Sem. 3',
            ],
            (object) [
               'id' => 16,
               'name' => 'Abr - Sem. 4',
            ],
         ],
      ],
      (object) [
         'month' => 'Mayo',
         'week'  => [
            (object) [
               'id' => 17,
               'name' => 'May - Sem. 1',
            ],
            (object) [
               'id' => 18,
               'name' => 'May - Sem. 2',
            ],
            (object) [
               'id' => 19,
               'name' => 'May - Sem. 3',
            ],
            (object) [
               'id' => 20,
               'name' => 'May - Sem. 4',
            ],
         ],
      ],
      (object) [
         'month' => 'Junio',
         'week'  => [
            (object) [
               'id' => 21,
               'name' => 'Jun - Sem. 1',
            ],
            (object) [
               'id' => 22,
               'name' => 'Jun - Sem. 2',
            ],
            (object) [
               'id' => 23,
               'name' => 'Jun - Sem. 3',
            ],
            (object) [
               'id' => 24,
               'name' => 'Jun - Sem. 4',
            ],
         ],
      ],
      (object) [
         'month' => 'Julio',
         'week'  => [
            (object) [
               'id' => 25,
               'name' => 'Jul - Sem. 1',
            ],
            (object) [
               'id' => 26,
               'name' => 'Jul - Sem. 2',
            ],
            (object) [
               'id' => 27,
               'name' => 'Jul - Sem. 3',
            ],
            (object) [
               'id' => 28,
               'name' => 'Jul - Sem. 4',
            ],
         ],
      ],
      (object) [
         'month' => 'Agosto',
         'week'  => [
            (object) [
               'id' => 29,
               'name' => 'Ago - Sem. 1',
            ],
            (object) [
               'id' => 30,
               'name' => 'Ago - Sem. 2',
            ],
            (object) [
               'id' => 31,
               'name' => 'Ago - Sem. 3',
            ],
            (object) [
               'id' => 32,
               'name' => 'Ago - Sem. 4',
            ],
         ],
      ],
      (object) [
         'month' => 'Septiembre',
         'week'  => [
            (object) [
               'id' => 33,
               'name' => 'Sep - Sem. 1',
            ],
            (object) [
               'id' => 34,
               'name' => 'Sep - Sem. 2',
            ],
            (object) [
               'id' => 35,
               'name' => 'Sep - Sem. 3',
            ],
            (object) [
               'id' => 36,
               'name' => 'Sep - Sem. 4',
            ],
         ],
      ],
      (object) [
         'month' => 'Octubtre',
         'week'  => [
            (object) [
               'id' => 37,
               'name' => 'Oct - Sem. 1',
            ],
            (object) [
               'id' => 38,
               'name' => 'Oct - Sem. 2',
            ],
            (object) [
               'id' => 39,
               'name' => 'Oct - Sem. 3',
            ],
            (object) [
               'id' => 40,
               'name' => 'Oct - Sem. 4',
            ],
         ],
      ],
      (object) [
         'month' => 'Noviembre',
         'week'  => [
            (object) [
               'id' => 41,
               'name' => 'Nov - Sem. 1',
            ],
            (object) [
               'id' => 42,
               'name' => 'Nov - Sem. 2',
            ],
            (object) [
               'id' => 43,
               'name' => 'Nov - Sem. 3',
            ],
            (object) [
               'id' => 44,
               'name' => 'Nov - Sem. 4',
            ],
         ],
      ],
      (object) [
         'month' => 'Diciembre',
         'week'  => [
            (object) [
               'id' => 45,
               'name' => 'Dic - Sem. 1',
            ],
            (object) [
               'id' => 46,
               'name' => 'Dic - Sem. 2',
            ],
            (object) [
               'id' => 47,
               'name' => 'Dic - Sem. 3',
            ],
            (object) [
               'id' => 48,
               'name' => 'Dic - Sem. 4',
            ],
         ],
      ],
   ],
   
   // Estado de tipo de inversion de armonización tarifaria de la ficha tecnica de inversion
   'indicator_type_monitoring_indicators' => [
      (object) [
         'id' => 1,
         'name' => 'Gestión',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'Producto',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'name' => 'Servicio',
         'state' => "Activa",
      ],
      (object) [
         'id' => 4,
         'name' => 'Resultado',
         'state' => "Activa",
      ],
   ],

   // Estados de estudios previos
   'pc_studies_previous' => [
      
      (object) [
         'id' => 1,
         'name' => "En elaboración",
         'colour' => "bg-orange text-white p-5",
      ],
      (object) [
         'id' => 2,
         'name' => "En revisión por parte de Asistente de gerencia",
         'colour' => "bg-blue-darker text-white p-5",
      ],
      (object) [
         'id' => 3,
         'name' => "Verificación de la ficha en Planeación corporativa",
         'colour' => "bg-grey-darker text-white p-5",
      ],
      (object) [
         'id' => 4,
         'name' => "Gestionando CDP por parte de presupuesto",
         'colour' => "bg-orange text-white p-5",
      ],
      (object) [
         'id' => 5,
         'name' => "Gestionando Plan Anual de Adquisiciones por parte de Gestión de Recursos",
         'colour' => "bg-lime-lighter text-white p-5",
      ],
      (object) [
         'id' => 6,
         'name' => "Asignación de abogado",
         'colour' => "bg-teal-transparent-5 text-white p-5",
      ],
      (object) [
         'id' => 7,
         'name' => "Gestionando Reglas por parte de Jurídica",
         'colour' => "bg-indigo-transparent-5 text-white p-5",
      ],
      (object) [
         'id' => 8,
         'name' => "Gestionando invitación por parte de Asistente de Gerencia",
         'colour' => "bg-indigo-transparent-8 text-white p-5",
      ],
      (object) [
         'id' => 9,
         'name' => "Invitación generada",
         'colour' => "bg-purple-transparent-7 text-white p-5",
      ],
      (object) [
         'id' => 10,
         'name' => "Revisión de jurídica con expediente",
         'colour' => "bg-purple text-white p-5",
      ],
      (object) [
         'id' => 11,
         'name' => "Evaluando propuestas",
         'colour' => "bg-blue-transparent-5 text-white p-5",
      ],
      (object) [
         'id' => 12,
         'name' => "Elaborando minuta del contrato",
         'colour' => "bg-orange text-white p-5",
      ],
      (object) [
         'id' => 13,
         'name' => "Revisando minuta del contrato",
         'colour' => "bg-yellow-darker text-white p-5",
      ],
      (object) [
         'id' => 14,
         'name' => "Revisión y firma del contrato",
         'colour' => "bg-aqua text-white p-5",
      ],
      (object) [
         'id' => 15,
         'name' => "Contrato legalizado",
         'colour' => "bg-green text-white p-5",
      ],
      (object) [
         'id' => 17,
         'name' => "Devuelto al líder del proceso para mejoras",
         'colour' => "bg-info text-white p-5",
      ],
      (object) [
         'id' => 18,
         'name' => "Mejorando observaciones hechas a la minuta",
         'colour' => "bg-green-transparent-6 text-white p-5",
      ],
      (object) [
         'id' => 19,
         'name' => "Estudio previo desierto",
         'colour' => "bg-red-darker text-white p-5",
      ],
      (object) [
         'id' => 20,
         'name' => "Revisando plan anual de adquisiciones",
         'colour' => "bg-red-darker text-white p-5",
      ],
      (object) [
         'id' => 21,
         'name' => "Evaluando las propuestas de todos",
         'colour' => "bg-green text-white p-5",
      ],
      (object) [
         'id' => 22,
         'name' => "Pendiente de visto bueno",
         'colour' => "bg-green text-white p-5",
      ],
      (object) [
         'id' => 23,
         'name' => "Finalizado",
         'colour' => "bg-warning text-white p-5",
      ],
      (object) [
         'id' => 24,
         'name' => "Devolución de propuesta",
         'colour' => "bg-warning text-white p-5",
      ],
      (object) [
         'id' => 25,
         'name' => "Solicitando CRP",
         'colour' => "bg-warning text-white p-5",
      ],
      (object) [
         'id' => 26,
         'name' => "Generando CRP",
         'colour' => "bg-warning text-white p-5",
      ]
   
   ],
   
   // Roles de estudios previos y estados involucrados
   'pc_roles_studies_previous_states' => [
      (object) [
         'id' => 1,
         'name' => 'Líder de proceso',
         'states' => [ 1 , 17 ]
      ],
      (object) [
         'id' => 2,
         'name' => 'Asistente de gerencia',
         'states' => [ 2, 8, 9 ]

      ],
      (object) [
         'id' => 3,
         'name' => 'Jefe de jurídica',
         'states' => [ 6, 10, 11, 13, 15 ]
      ],

      (object) [
         'id' => 4,
         'name' => 'Abogado de jurídica',
         'states' => [ 7, 12 ]
      ],
      (object) [
         'id' => 5,
         'name' => 'Planeación',
         'states' => [ 3 ]
      ],
      (object) [
         'id' => 6,
         'name' => 'Presupuesto',
         'states' => [ 4 ]
      ],
      (object) [
         'id' => 7,
         'name' => 'Gestión de recursos',
         'states' => [ 5 ]
      ],
      (object) [
         'id' => 8,
         'name' => 'Gerente',
         'states' => [ 14 ]
      ],  
   ],
   
   // Roles de estudios previos
   'pc_roles_studies_previous' => [
      'PC Líder de proceso',
      'PC Asistente de gerencia',
      'PC Jefe de jurídica',
      'PC Revisor de jurídico',
      'PC Gestor planeación',
      'PC Gestor presupuesto',
      'PC Gestor de recursos',
      'PC Gerente'
   ],
   // Tipo de costo del presupuesto alternativo
   'cost_type' => [
      (object) [
         'id' => 1,
         'name' => 'Administración',
         'state' => "Activa",
      ],
      (object) [
         'id' => 2,
         'name' => 'Imprevistos',
         'state' => "Activa",
      ],
      (object) [
         'id' => 3,
         'name' => 'Utilidades',
         'state' => "Activa",
      ],
      (object) [
         'id' => 4,
         'name' => 'IVA (Sobre Utilidades o Costo Directo)',
         'state' => "Activa",
      ],
      (object) [
         'id' => 5,
         'name' => 'Otros',
         'state' => "Activa",
      ],
   ],

    // Estados de las necesidades del plan anual de adquisiones
    'paa_needs_status' => [
        (object) [
            'id' => 1,
            'name' => "En revisión",
            'state' => "Activa",
        ],
        (object) [
            'id' => 2,
            'name' => "Aprobada",
            'state' => "Activa",
        ],
        (object) [
            'id' => 3,
            'name' => "Devuelta",
            'state' => "Activa",
        ],
        (object) [
            'id' => 4,
            'name' => "Rechazada",
            'state' => "Activa",
        ],
    ],

    // Estados de las solicitudes de modificacion del plan anual de adquisiones
    'paa_modification_request' => [
        (object) [
            'id' => 1,
            'name' => "En revisión",
            'state' => "Activa",
        ],
        (object) [
            'id' => 2,
            'name' => "PAA habilitado para modificación",
            'state' => "Activa",
        ],
        (object) [
            'id' => 3,
            'name' => "Rechazada",
            'state' => "Activa",
        ],
    ],

];

?>
