<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

//Instancia  de la clase softdeletes que protegue los datos a la hora de eliminar
use Illuminate\Database\Eloquent\SoftDeletes;

//Instancia para la conexión a la base de datos
use DB;

class Ciudad extends Model
{
    use SoftDeletes;
    // Conexion al motor de base de datos
    protected $connection= 'joomla_vuv1';

    protected $table = 'ciudad';


    //Campos de la tabla necesarios para crear un registro
    protected $fillable = [
        'nombreCiudad',
        'dpto_id',
    ];

    //Campo cuando se elimina un registro
    protected $dates = ['deleted_at'];


    public function departamento()
    {
        return $this->belongsTo('App\Modelo\Departamento', 'dpto_id');
    }

    
    // public function departamento(){
    //     return $this->hasOne('App\Modelo\Departamento');
    // }
    

     //Función que consulta las ciudades del sistema
     public static function listaCiudades() {
        //Consulta para retornar el listado de ciudades
        $ciudades = DB::table('ciudad')
                    ->select('*')
                    ->orderBy('nombreCiudad','ASC')
                    ->get();

        return $ciudades;
    }
}
