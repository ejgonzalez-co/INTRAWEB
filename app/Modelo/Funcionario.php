<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    // Conexion al motor de base de datos
    protected $connection= 'joomla_vuv1';
    //Tabla donde se almacena los registros
    protected $table = 'ventanilla_intranet_usuario';
    
}
