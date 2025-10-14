<?php

namespace Modules\PQRS\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\User;
use Modules\PQRS\Models\Internal;
use Modules\PQRS\Models\PQR;
use Modules\PQRS\Models\HolidayCalendar;
use Modules\PQRS\Models\PQRTipoSolicitud;

use Modules\PQRS\Models\PQREjeTematico;
use DateTime;
use DB;


class UtilController extends AppBaseController {

	/**
    * Elimina las listas vacias de un objeto o las vuelve nulas
	 *
	 * @author Jhoan Sebastian Chilito S. - Jul. 07 - 2020
	 * @version 1.0.0
	 *
	 * @param Object $object objeto contenedor de propiedades
	 * @param Boolean $nulleable valida si el areglo debe hacerce nulo
	 * @param Array $properties propiedades de objeto
	 */
	public static function dropNullEmptyList($object, $nulleable, ...$properties) {
		// Recorre las propiedades a verificar
		foreach ($properties as $prop) {
			// Valida si la lista no tiene elementos
			if (count($object[$prop]) === 0) {
				// Valida si se debe asignar el valor nulo o se debe eliminar el arreglo vacio
				if ($nulleable === true) {
					// Asigna valor nulo
					$object[$prop] = null;
				} else {
					// Elimina la propiedad vacia
					unset($object[$prop]);
				}
			}
		}
		return $object;
	}

	/**
     * Obtiene los datos de una constante dependiendo del nombre
     *
     * @author Carlos Moises Garcia T. - Oct. 27 - 2020
     * @version 1.0.0
     *
	  * @param nameConstant nombre de la constante a obtener
	  * 
     * @return Response
     */
	public function getConstants($nameConstant) {
		$dateConstant = config('pqrs.'.$nameConstant);
		return $this->sendResponse($dateConstant, trans('data_obtained_successfully'));
	}

	/**
     * Calcula consecutivo y numero de orden proximo
     *
     * @author Erika Johana Gonzalez - Ene. 07 - 2022
     * @version 1.0.0
     *
	  * @param String $type tipo de correspondencia
	  * @param String $formatConsecutive Formato de consecutivo tomado de variable
	  * @param String $formatConsecutivePrefix Formato de preifjo de consecutivo para calcular siguiente
	  * @param String $DP Codigo de la dependencia que publica el documento
	  * @param String $PL Codigo de la plantilla que publica el documento
	  * 
     * @return Response $dataConsecutive consecutivo y orden para guardar
     */
	public static  function getNextConsecutive($type,$formatConsecutive,$formatConsecutivePrefix,$DP,$PL) {
						
		//calcular consecutivo
		$numberDigits = 3;
					
		//reemplaza valores en el formato del consecutivo
		$formatConsecutive = str_replace("DP",$DP,$formatConsecutive);
		$formatConsecutive = str_replace("PL",$PL,$formatConsecutive);
		$formatConsecutive = str_replace("Y",date("Y"),$formatConsecutive);
		
		//reemplaza valores en el prefijo del formato del consecutivo
		$formatConsecutivePrefix = str_replace("DP",$DP,$formatConsecutivePrefix);
		$formatConsecutivePrefix = str_replace("PL",$PL,$formatConsecutivePrefix);
		$formatConsecutivePrefix = str_replace("Y",date("Y"),$formatConsecutivePrefix);		
		
		//consulta segun el numero de correspondencia
		switch ($type) {
			case 'Internal':
				$order = Internal::where('consecutive', 'like', '%' . $formatConsecutivePrefix. '%')->max('consecutive_order');
				break;
			
			default:
				# code...
				break;
		}
		
		//valida el numero de order obtenido de la consulta
		if(!$order){
			$orden = 0;
		}

		$orden = (int)$order+1;
	
		$length    = strlen($orden);
		
		//agrega cceros al consecutivo si aplica
		if($numberDigits > $length)
		{
			$digs = $numberDigits - $length;	
			$norden = str_repeat("0",$digs).$orden;
		}
		else
		{
			$norden = $orden;
		}
			
		//asigna datos para retornar
		$nextConsecutive = str_replace("CO",$norden,$formatConsecutive);
		$dataConsecutive['consecutive'] = $nextConsecutive;
		$dataConsecutive['consecutive_order'] = $orden;

		return $dataConsecutive;
	}

	/**
     * Calcula el numero de dias que faltan para finalizar el pqr
     *
     * @author Erika Johana Gonzalez - Ene. 07 - 2022
     * @version 1.0.0
     *
	  * @param String $request, trae la fecha vencimiento del pqr y el tipo de plazo
	  * 
     * @return Response $dias numero de dias que faltan para finalizar el pqr
     */
	public static function diasRestantes($tipoPlazo, $fechaVence,$estado,$fechaFin){
		try {

			// Obtiene la fecha actual
			$now = new DateTime();
	
			// Define las variables días, horas y minutos
			$dias = 0;
			$horas = 0;
			$minutos = 0;
	
			// Inicializa la fecha actual y la fecha de vencimiento
			$fechaActualLaboral = $now->getTimestamp();

			if ($estado == 'Finalizado') {

				$fechaFinLaboral = strtotime($fechaFin);
				$fechaVencimientoLaboral = strtotime($fechaVence);


					$diferenciaSegundos = $fechaVencimientoLaboral - $fechaFinLaboral;

			}else{
				$fechaVencimientoLaboral = strtotime($fechaVence);
				// Calcula la diferencia en segundos entre la fecha de vencimiento y la fecha actual
				$diferenciaSegundos = $fechaVencimientoLaboral - $fechaActualLaboral;
			}
	

	
			// Calcula los días restantes
			$dias = floor($diferenciaSegundos / (60 * 60 * 24));
	
			// Calcula las horas y los minutos restantes
			$horas = floor(($diferenciaSegundos % (60 * 60 * 24)) / 3600);
			$minutos = floor(($diferenciaSegundos % 3600) / 60);
			
			// Construye el mensaje basado en los días, horas y minutos restantes
			$mensaje = '';
	
			if ($dias != 0) {
				$mensaje .= ($dias > 0 ? "$dias día(s)" : abs($dias)." día(s)");
			}
	
			if ($horas != 0) {
				$mensaje .= ($mensaje ? ', ' : '') . ($horas > 0 ? "$horas hora(s)" : abs($horas)." hora(s)");
			}
	
			if ($minutos != 0) {
				$mensaje .= ($mensaje ? ' y ' : '') . ($minutos > 0 ? "$minutos minuto(s)" : abs($minutos)." minuto(s)");
			}
	
			// Si no quedan días, horas ni minutos o si alguno es negativo, se asume que se ha vencido
			if ($dias < 0 || $horas < 0 || $minutos < 0) {

				$mensaje = "Este PQR ha superado su fecha de vencimiento. Lleva vencido: ".$mensaje;
			}

			// Retorna el mensaje
			return $mensaje;
		} catch (Exception $e) {
			// En caso de error, retorna un mensaje indicando que no se pudo calcular el tiempo restante
			return "No se pudo calcular el tiempo restante";
		}
	}
	
	public static function diasRestantes1($tipoPlazo,$fechaVence)
	{	
		//Obtiene los dias festivos
		$holidayCalendars = HolidayCalendar::get()->toArray();

		//Obtiene la fecha actual
		$created = date('Y-m-d');
        $dias = 0;

		//Valida el tipo de plazo si es calendario o laboral
		if ($tipoPlazo == 'Calendario') {

			//Inicializa la fecha actual y la fecha de vencimiento
			$FechaActualCalendario = new DateTime($created);
			$FechaVencimientoCalendario = new DateTime($fechaVence);

			//Por Medio de la funcion diff obtiene la diferencia entre $FechaActualCalendario y $FechaVencimientoCalendario
			$intervalo = $FechaActualCalendario->diff($FechaVencimientoCalendario);

			//Asinga a la variable $dias el numero de dias que tiene para finalizar la pqr
			$dias = $intervalo->days+1;

			
			if ($FechaActualCalendario < $FechaVencimientoCalendario) {
				//Por Medio de la funcion diff obtiene la diferencia entre $FechaActualCalendario y $FechaVencimientoCalendario
				$intervalo = $FechaActualCalendario->diff($FechaVencimientoCalendario);

				//Asinga a la variable $dias el numero de dias que tiene para finalizar la pqr
				$dias = $intervalo->days+1;

			}else{
				//Por Medio de la funcion diff obtiene la diferencia entre $FechaActualCalendario y $FechaVencimientoCalendario
				$intervalo = $FechaVencimientoCalendario->diff($FechaActualCalendario);

				//Asinga a la variable $dias el numero de dias que tiene para finalizar la pqr
				$dias = -$intervalo->days-2;


			}
		}else{

			//Inicializa la fecha actual y la fecha de vencimiento
			$FechaActualLaboral = strtotime($created);
			$FechaVencimientoLaboral = strtotime($fechaVence);
	
			//Arry que almacena las fechas que hay entre una fecha y la otra
			$fechas = [];

			if ($FechaActualLaboral < $FechaVencimientoLaboral) {
					
				//Recorre la fecha inicial hasta la fecha de vencimiento y asinga al array las fechas entres estas 2 variables
				for ($i = $FechaActualLaboral; $i <= $FechaVencimientoLaboral; $i += 86400) {
					$fechas[] = date('Y-m-d', $i);
				}

				//Obtiene los dias festivos
				$festivos = Arr::pluck($holidayCalendars, 'date');

				//hace un conteo de las fechas que quedaros y descontando los dias festivos
				$dias = count(array_diff($fechas,$festivos));

			}else{
				//Recorre la fecha inicial hasta la fecha de vencimiento y asinga al array las fechas entres estas 2 variables
				for ($i = $FechaVencimientoLaboral; $i <= $FechaActualLaboral; $i += 86400) {
					$fechas[] = date('Y-m-d', $i);
				}

				//Obtiene los dias festivos
				$festivos = Arr::pluck($holidayCalendars, 'date');

				//hace un conteo de las fechas que quedaros y descontando los dias festivos
				$dias = -count(array_diff($fechas,$festivos));

			}
		}

		return $dias;

	}


	public function importPQR()
	{

		$vigencyPQR = DB::connection('joomla')->Select("SELECT * FROM rghb6_pqr where (estado != 'Finalizado vencido justificado' and estado != 'Finalizado' and estado !='Cancelado') order by cf_created DESC");
		
		foreach ($vigencyPQR as $value) {

			$nombreTipo = $value->tipo;
			$dataId = '';

			if ($nombreTipo != '') {

				$idTipoSolicitud = PQRTipoSolicitud::where("nombre", $nombreTipo)->get()->first()->toArray();

				$dataId = $idTipoSolicitud['id'];
			}

			if (isset($value->id)) {
				$idCorrespondence = DB::connection('joomla')->Select("SELECT consecutivo, adjunto FROM `rghb6_externa` WHERE pqr = '$value->id'");

			}


			$registro = new PQR();
			$registro->pqr_id = $value->id;
			$registro->nombre_ciudadano = empty($value->nombre_ciudadano) ? $value->ciudadano : $value->nombre_ciudadano;
			$registro->documento_ciudadano=$value->documento_ciudadano;
			$registro->email_ciudadano=$value->email_ciudadano ?? '';
			$registro->document_pdf=$value->adjunto;
			$registro->adjunto_ciudadano=$value->adjunto_ciudadano;
			$registro->contenido=$value->contenido;
			$registro->folios=$value->folios;
			$registro->anexos=$value->anexos;
			$registro->canal=$value->canal;
			$registro->respuesta=$value->respuesta;
			$registro->adjunto="";
			$registro->descripcion_tramite=$value->desctramite;
			$registro->devolucion=$value->devolucion;
			$registro->operador=$value->operador;
			$registro->operador_name=$value->operador_asignado;
			$registro->fecha_recibido_fisico=$value->recibidofisico;
			$registro->estado=$value->estado;
			$registro->nombre_ejetematico=$value->nombre_ejetematico;
			$registro->plazo=$value->plazo;
			$registro->tipo_plazo=$value->tipoplazo;
			$registro->temprana=$value->temprana;
			$registro->destacado="";
			$registro->no_oficio_respuesta=$value->oficio;
			$registro->adj_oficio_respuesta="";
			$registro->no_oficio_solicitud="";
			$registro->adj_oficio_solicitud="";
			$registro->tipo_finalizacion="";
			$registro->tipo_adjunto="";
			$registro->correspondence_external_received_id="";
			$registro->correspondence_external_id="";
			$registro->fecha_fin_parcial=Null;
			$registro->respuesta_parcial="N/A";
			$registro->adjunto_r_parcial="";
			$registro->adjunto_r_ciudadano="";
			$registro->fecha_vence=$value->fechavence;
			$registro->fecha_fin=$value->fechafin == '0000-00-00 00:00:00' ? Null : $value->fechafin ;
			$registro->fecha_temprana=Null;
			$registro->funcionario_destinatario=$value->funcionario_asignado;
			$registro->pregunta_ciudadano=$value->pregunta;
			$registro->respuesta_ciudadano=$value->pregunta_respuesta;
			$registro->empresa_traslado="";
			$registro->tipo_solicitud_nombre=$value->tipodoc;
			$registro->vigencia= substr($value->id, 0, 4);
			$registro->users_id=$value->cf_user_id;
			$registro->funcionario_users_id=$value->funcionario;
			$registro->ciudadano_users_id="";
			$registro->pqr_eje_tematico_id=$value->id_ejetematico;
			$registro->pqr_tipo_solicitud_id=$dataId;
			$registro->classification_serie="";
			$registro->classification_subserie="";
			$registro->classification_production_office="";
			$registro->users_name=$value->funcionario_asignado;
			$registro->adjunto_finalizado="";
			$registro->dias_restantes="";
			$registro->id_correspondence=$idCorrespondence[0]->consecutivo ?? null;
			$registro->adjunto_correspondence=$idCorrespondence[0]->adjunto ?? null;
			$registro->created_at = $value->cf_created; // Establece la fecha deseada
			$registro->updated_at = $value->cf_modified; // También establece cf_modified si es necesario
			$registro->save();
		}

		return 'listo';
		
	}
	/**
     * Importa al adjunto y el id de la correspondencia a los pqr mmigrados
     *
     * @author Erika Johana Gonzalez - Ene. 07 - 2022
     * @version 1.0.0
     *
	  * @param String $request, trae la fecha vencimiento del pqr y el tipo de plazo
	  * 
     * @return Response $dias numero de dias que faltan para finalizar el pqr
     */
	public function importDataIdCorresponden(){

		$vigencyPQR = DB::connection('joomla')->Select("SELECT * FROM rghb6_pqr where (estado != 'Finalizado vencido justificado' and estado != 'Finalizado' and estado !='Cancelado') order by cf_created DESC");
		
		foreach ($vigencyPQR as $value) {

			if (isset($value->id)) {
				$idCorrespondence = DB::connection('joomla')->Select("SELECT consecutivo, adjunto FROM `rghb6_externa` WHERE pqr = '$value->id'");


				if (isset($idCorrespondence[0])) {
					$pqr = PQR::where('pqr_id',$value->id)->first();
					$pqr->id_correspondence = $idCorrespondence[0]->consecutivo ?? null;
					$pqr->adjunto_correspondence = $idCorrespondence[0]->adjunto ?? null;
					$pqr->save();
				}
			}

		}
		return 'listo';

	}
}
