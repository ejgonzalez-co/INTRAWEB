<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

//Instancia para la conezión a la base de datos
use DB;

class HorarioLaboral extends Model
{
    // Conexion al motor de base de datos
    protected $connection= 'joomla_vuv1';
    //Tabla donde se almacena los registros
    protected $table = 'horario_laboral';

    //Campos de la tabla necesarios para crear un registro
    protected $fillable = [
        'dia',
        'horaInicioAM',
        'horaFinAM',
        'horaInicioPM',
        'horaFinPM',
    ];


    //Función que consulta el listado de las hoas laborales
   public static function listadoHorasLaborales() {

    //Consulta para retornar el listado horario_laboral
    $horarioLaboral = DB::table('horario_laboral')
             ->select(DB::raw("CONCAT('11/22/2013 ', horaInicioAM) AS horaInicioAM"), DB::raw("CONCAT('11/22/2013 ',horaFinAM) AS horaFinAM"), DB::raw("CONCAT('11/22/2013 ',horaInicioPM) AS horaInicioPM"), DB::raw("CONCAT('11/22/2013 ',horaFinPM) AS horaFinPM"))
             ->get();

    return $horarioLaboral;
 }
}
