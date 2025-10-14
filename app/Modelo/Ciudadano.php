<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

class Ciudadano extends Model
{
    // Conexion al motor de base de datos
    protected $connection= 'joomla_vuv1';
    //Tabla donde se almacena los registros
    protected $table = 'ventanilla_ciudadano';

    public function user(){
        return $this->belongsTo('App\User');
    }
}
