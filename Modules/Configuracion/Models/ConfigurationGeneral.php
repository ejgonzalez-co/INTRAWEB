<?php

namespace Modules\Configuracion\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigurationGeneral extends Model
{
    public $table = 'configuration_general';

    public $fillable = [
        'logo',
        'color_barra',
        'color_modal',
        'imagen_fondo',
        'imagen_fondo_responsive',
        'nombre_entidad',
        'horario'
    ];

    protected $casts = [
        'logo' => 'string',
        'color_barra' => 'string',
        'color_modal' => 'string',
        'imagen_fondo' => 'string',
        'imagen_fondo_responsive' => 'string',
        'nombre_entidad' => 'string'
    ];

    public static array $rules = [
        
    ];

    
}
