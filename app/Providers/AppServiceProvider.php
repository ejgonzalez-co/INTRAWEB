<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Se obtiene información de la configuración del sitio
        $ultima_configuracion = DB::table('configuration_general')->latest()->first();
        // Valida si ya esta parametrizado un nombre para la entidad
        if($ultima_configuracion->nombre_entidad)
        // Se asigna el nombre de la entidad parametrizado por el admin, a la variable del archivo de configuración
        $this->app['config']->set('app.name', $ultima_configuracion->nombre_entidad);
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Obtiene los elementos html para crear un botón
        Blade::include('elements.button.button', 'button');

        //Obtiene los elementos html para crear un botón de acción de vue
        Blade::include('elements.button.buttonAccionVue', 'btnAccion');

        //Obtiene los elementos html para crear un botón más grande
        Blade::include('elements.button.buttonPanel', 'btnPanel');

        //Obtiene los elementos html para crear un input
        Blade::include('elements.input.input', 'input');

        //Obtiene los elementos html para crear un textarea
        Blade::include('elements.textarea.textarea', 'textarea');

        //Obtiene los elementos html para crear un textarea
        Blade::include('elements.input.inputFileVue', 'inputFileVue');

        //Obtiene los elementos html para crear un select
        Blade::component('elements.select.select', 'select');

        //Obtiene los elementos html para crear un select
        Blade::component('elements.select.select_array', 'select_array');
        
        //Obtiene los elementos html para crear un select
        Blade::include('elements.select.select_php', 'select_php');
        

        /*************************************************
        Los siguientes elementos son código personalizados
        **************************************************/
        //Obtiene el template de header de la parte del front
        Blade::include('front.layouts.header', 'headerFront');

        //Obtiene el template de header de la parte del front
        Blade::include('front.layouts.footer', 'footerFront');


        //Obtiene los elementos html para crear el paginador
        Blade::component('componentes.contenidoFront', 'contenidoFront');

        //Obtiene los elementos html para crear el paginador
        Blade::component('componentes.contenidoFrontEstandar', 'contenidoFrontEstandar');


        //Obtiene los elementos html para crear el paginador
        Blade::component('componentes.contenidoBackend', 'contenidoBackend');
    
        //Se crear el template para crear un modal generico
        Blade::component('elements.modal.modalGenerico', 'modal');

        //Se crear el template para crear un modal generico
        Blade::component('elements.modal.crearEditarPHP', 'modalIframe');

        //Se crea un template para ser usado como componente para crear un modal para crear y editar registros
        Blade::component('elements.modal.crearEditar', 'modalCrearEditar');

        //Se crea el template de filtros de busqueda para ser usado como componente
        Blade::component('componentes.filtroBusqueda', 'filtros');

        Blade::component('elements.tablas.tablaRegistros', 'tablaRegistros');

        Blade::component('elements.tablas.tabla', 'tabla');

        //Obtiene los elementos html para crear el paginador
        Blade::component('componentes.paginador', 'paginador');


        //Obtiene los elementos html para crear el paginador
        Blade::component('componentes.selectBuscar', 'selectBuscar');

        //Obtiene los elementos html para crear un select
        Blade::component('componentes.panelFormulario', 'panel');

        //Se crear el template para crear botón de cerrar sesión
        Blade::include('componentes.btnCerrarSesion', 'btnCerrarSesion');

        //Se crear el template para crear los botones de búsqueda
        Blade::include('componentes.btnBuscar', 'btnBuscar');


        //Obtiene los elementos html para crear el paginador
        Blade::component('componentes.email', 'correo');

        //Obtiene los elementos html para crear el paginador
        Blade::component('componentes.pdf', 'pdf');

        // $ultima_configuracion = DB::table('configuration_general')->latest()->first();

        // config(['name' => $ultima_configuracion->nombre_entidad]);
    }
}
