<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

class Calendario extends Model
{
    // Conexion al motor de base de datos
    protected $connection= 'joomla_vuv1';
    //Tabla donde se almacena los registros
    protected $table = 'calendario_festivo';

    //Campos de la tabla necesarios para crear un registro
    protected $fillable = [
        'fecha',
    ];
}
