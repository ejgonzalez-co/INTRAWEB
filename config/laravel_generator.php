<?php

$isModule = true;
$modulePath = 'Modules';
$moduleName = 'ExpedientesElectronicos';

// Validacion si se va a generar archivos par un modulo
if ($isModule) {

    return [

        /*
        |--------------------------------------------------------------------------
        | Paths
        |--------------------------------------------------------------------------
        |
        */

        'path' => [

            'migration'         => base_path($modulePath . '/' . $moduleName . '/Database/migrations/'),

            'model'             => base_path($modulePath . '/' . $moduleName . '/Models/'),

            'datatables'        => base_path($modulePath . '/' . $moduleName . '/DataTables/'),

            'livewire_tables'   => base_path($modulePath . '/' . $moduleName . '/Http/Livewire/'),

            'repository'        => base_path($modulePath . '/' . $moduleName . '/Repositories/'),

            'routes'            => base_path($modulePath . '/' . $moduleName . '/routes/web.php'),

            'api_routes'        => base_path($modulePath . '/' . $moduleName . '/routes/api.php'),

            'request'           => base_path($modulePath . '/' . $moduleName . '/Http/Requests/'),

            'api_request'       => base_path($modulePath . '/' . $moduleName . '/Http/Requests/API/'),

            'controller'        => base_path($modulePath . '/' . $moduleName . '/Http/Controllers/'),

            'api_controller'    => base_path($modulePath . '/' . $moduleName . '/Http/Controllers/API/'),

            'api_resource'      => base_path($modulePath . '/' . $moduleName . '/Http/Resources/'),

            'schema_files'      => base_path($modulePath . '/' . $moduleName . '/model_schemas/'),

            'seeder'            => base_path($modulePath . '/' . $moduleName . '/Database/seeders/'),

            'database_seeder'   => base_path($modulePath . '/' . $moduleName . '/Database/seeders/DatabaseSeeder.php'),

            'factory'           => base_path($modulePath . '/' . $moduleName . '/Database/factories/'),

            'view_provider'     => base_path($modulePath . '/' . $moduleName . '/Providers/ViewServiceProvider.php'),

            'tests'             => base_path($modulePath . '/' . $moduleName . '/tests/'),

            'repository_test'   => base_path($modulePath . '/' . $moduleName . '/tests/Repositories/'),

            'api_test'          => base_path($modulePath . '/' . $moduleName . '/tests/APIs/'),

            'views'             => base_path($modulePath . '/' . $moduleName . '/Resources/views/'),

            'menu_file'         => base_path($modulePath . '/' . $moduleName . '/Resources/views/layouts/menu.blade.php'),
        ],

        /*
        |--------------------------------------------------------------------------
        | Namespaces
        |--------------------------------------------------------------------------
        |
        */

        'namespace' => [

            'model'             => $modulePath . '\\' . $moduleName . '\Models',

            'datatables'        => $modulePath . '\\' . $moduleName . '\DataTables',

            'livewire_tables'   => $modulePath . '\\' . $moduleName . '\Http\Livewire',

            'repository'        => $modulePath . '\\' . $moduleName . '\Repositories',

            'controller'        => $modulePath . '\\' . $moduleName . '\Http\Controllers',

            'api_controller'    => $modulePath . '\\' . $moduleName . '\Http\Controllers\API',

            'api_resource'      => $modulePath . '\\' . $moduleName . '\Http\Resources',

            'request'           => $modulePath . '\\' . $moduleName . '\Http\Requests',

            'api_request'       => $modulePath . '\\' . $moduleName . '\Http\Requests\API',

            'seeder'            => $modulePath . '\\' . $moduleName . 'Database\Seeders',

            'factory'           => $modulePath . '\\' . $moduleName . 'Database\Factories',

            'tests'             => $modulePath . '\\' . $moduleName . 'Tests',

            'repository_test'   => $modulePath . '\\' . $moduleName . 'Tests\Repositories',

            'api_test'          => $modulePath . '\\' . $moduleName . 'Tests\APIs',
        ],

        /*
        |--------------------------------------------------------------------------
        | Templates
        |--------------------------------------------------------------------------
        |
        */

        'templates' => 'adminlte-templates',

        /*
        |--------------------------------------------------------------------------
        | Model extend class
        |--------------------------------------------------------------------------
        |
        */

        'model_extend_class' => 'Illuminate\Database\Eloquent\Model',

        /*
        |--------------------------------------------------------------------------
        | API routes prefix & version
        |--------------------------------------------------------------------------
        |
        */

        'api_prefix'  => 'api',

        /*
        |--------------------------------------------------------------------------
        | Options
        |--------------------------------------------------------------------------
        |
        */

        'options' => [

            'soft_delete' => false,

            'save_schema_file' => true,

            'localized' => false,

            'repository_pattern' => true,

            'resources' => false,

            'factory' => false,

            'seeder' => false,

            'swagger' => false, // generate swagger for your APIs

            'tests' => false, // generate test cases for your APIs

            'excluded_fields' => ['id'], // Array of columns that doesn't required while creating module
        ],

        /*
        |--------------------------------------------------------------------------
        | Prefixes
        |--------------------------------------------------------------------------
        |
        */

        'prefixes' => [

            'route' => '',  // e.g. admin or admin.shipping or admin.shipping.logistics

            'namespace' => '',  // e.g. Admin or Admin\Shipping or Admin\Shipping\Logistics

            'view' => '',  // e.g. admin or admin/shipping or admin/shipping/logistics
        ],

        /*
        |--------------------------------------------------------------------------
        | Table Types
        |
        | Possible Options: blade, datatables, livewire
        |--------------------------------------------------------------------------
        |
        */

        'tables' => 'blade',

        /*
        |--------------------------------------------------------------------------
        | Timestamp Fields
        |--------------------------------------------------------------------------
        |
        */

        'timestamps' => [

            'enabled'       => true,

            'created_at'    => 'created_at',

            'updated_at'    => 'updated_at',

            'deleted_at'    => 'deleted_at',
        ],

        /*
        |--------------------------------------------------------------------------
        | Specify custom doctrine mappings as per your need
        |--------------------------------------------------------------------------
        |
        */

        'from_table' => [

            'doctrine_mappings' => [],
        ],

    ];

} else {

    return [

        /*
        |--------------------------------------------------------------------------
        | Paths
        |--------------------------------------------------------------------------
        |
        */

        'path' => [

            'migration'         => database_path('migrations/'),

            'model'             => app_path('Models/'),

            'datatables'        => app_path('DataTables/'),

            'livewire_tables'   => app_path('Http/Livewire/'),

            'repository'        => app_path('Repositories/'),

            'routes'            => base_path('routes/web.php'),

            'api_routes'        => base_path('routes/api.php'),

            'request'           => app_path('Http/Requests/'),

            'api_request'       => app_path('Http/Requests/API/'),

            'controller'        => app_path('Http/Controllers/'),

            'api_controller'    => app_path('Http/Controllers/API/'),

            'api_resource'      => app_path('Http/Resources/'),

            'schema_files'      => resource_path('model_schemas/'),

            'seeder'            => database_path('seeders/'),

            'database_seeder'   => database_path('seeders/DatabaseSeeder.php'),

            'factory'           => database_path('factories/'),

            'view_provider'     => app_path('Providers/ViewServiceProvider.php'),

            'tests'             => base_path('tests/'),

            'repository_test'   => base_path('tests/Repositories/'),

            'api_test'          => base_path('tests/APIs/'),

            'views'             => resource_path('views/'),

            'menu_file'         => resource_path('views/layouts/menu.blade.php'),
        ],

        /*
        |--------------------------------------------------------------------------
        | Namespaces
        |--------------------------------------------------------------------------
        |
        */

        'namespace' => [

            'model'             => 'App\Models',

            'datatables'        => 'App\DataTables',

            'livewire_tables'   => 'App\Http\Livewire',

            'repository'        => 'App\Repositories',

            'controller'        => 'App\Http\Controllers',

            'api_controller'    => 'App\Http\Controllers\API',

            'api_resource'      => 'App\Http\Resources',

            'request'           => 'App\Http\Requests',

            'api_request'       => 'App\Http\Requests\API',

            'seeder'            => 'Database\Seeders',

            'factory'           => 'Database\Factories',

            'tests'             => 'Tests',

            'repository_test'   => 'Tests\Repositories',

            'api_test'          => 'Tests\APIs',
        ],

        /*
        |--------------------------------------------------------------------------
        | Templates
        |--------------------------------------------------------------------------
        |
        */

        'templates' => 'adminlte-templates',

        /*
        |--------------------------------------------------------------------------
        | Model extend class
        |--------------------------------------------------------------------------
        |
        */

        'model_extend_class' => 'Illuminate\Database\Eloquent\Model',

        /*
        |--------------------------------------------------------------------------
        | API routes prefix & version
        |--------------------------------------------------------------------------
        |
        */

        'api_prefix'  => 'api',

        /*
        |--------------------------------------------------------------------------
        | Options
        |--------------------------------------------------------------------------
        |
        */

        'options' => [

            'soft_delete' => false,

            'save_schema_file' => true,

            'localized' => false,

            'repository_pattern' => true,

            'resources' => false,

            'factory' => false,

            'seeder' => false,

            'swagger' => false, // generate swagger for your APIs

            'tests' => false, // generate test cases for your APIs

            'excluded_fields' => ['id'], // Array of columns that doesn't required while creating module
        ],

        /*
        |--------------------------------------------------------------------------
        | Prefixes
        |--------------------------------------------------------------------------
        |
        */

        'prefixes' => [

            'route' => '',  // e.g. admin or admin.shipping or admin.shipping.logistics

            'namespace' => '',  // e.g. Admin or Admin\Shipping or Admin\Shipping\Logistics

            'view' => '',  // e.g. admin or admin/shipping or admin/shipping/logistics
        ],

        /*
        |--------------------------------------------------------------------------
        | Table Types
        |
        | Possible Options: blade, datatables, livewire
        |--------------------------------------------------------------------------
        |
        */

        'tables' => 'blade',

        /*
        |--------------------------------------------------------------------------
        | Timestamp Fields
        |--------------------------------------------------------------------------
        |
        */

        'timestamps' => [

            'enabled'       => true,

            'created_at'    => 'created_at',

            'updated_at'    => 'updated_at',

            'deleted_at'    => 'deleted_at',
        ],

        /*
        |--------------------------------------------------------------------------
        | Specify custom doctrine mappings as per your need
        |--------------------------------------------------------------------------
        |
        */

        'from_table' => [

            'doctrine_mappings' => [],
        ],

    ];
}
