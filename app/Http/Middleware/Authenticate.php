<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            // Valida si tiene integración con un sitio de Joomla, para hacer la solicitud de cerrar sesión en ese sitio
            if (config("app.integracion_sitio_joomla")) {
                 return route('login');

                //  return config("app.url_joomla")."/sesion_joomla_repositorio.php?accion=logout";

            } else {
                // De lo contrario redirige al login del sitio actual
                return route('login');
            }
        }
    }
}
