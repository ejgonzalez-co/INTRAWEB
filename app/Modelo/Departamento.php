<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

//Instancia  de la clase softdeletes que protegue los datos a la hora de eliminar
use Illuminate\Database\Eloquent\SoftDeletes;

//Instancia para la conexión a la base de datos
use DB;

class Departamento extends Model
{
    use SoftDeletes;
    // Conexion al motor de base de datos
    protected $connection= 'joomla_vuv1';
    protected $table = 'departamento';


    //Campos de la tabla necesarios para crear un registro
    protected $fillable = [
        'nombreDpto',
    ];

    //Campo cuando se elimina un registro
    protected $dates = ['deleted_at'];


    public function ciudades()
    {
        return $this->hasMany('App\Modelo\Ciudad', 'dpto_id');
    }


    //Función que consulta los departamentos del sistema
    public static function departamentos() {
        //Consulta para retornar el listado de departamentos
        $departamentos = DB::table('departamento')
                    ->select('*')
                    ->orderBy('nombreDpto','ASC')
                    ->get();

        return $departamentos;
    }
}
