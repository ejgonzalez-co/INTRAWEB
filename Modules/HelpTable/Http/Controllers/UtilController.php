<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Modules\HelpTable\Models\TicProvider;
use App\User;

class UtilController extends AppBaseController {

	/**
	 * Obtiene todos los roles existentes
	 *
	 * @author Jhoan Sebastian Chilito S. - Jul. 07 - 2020
	 * @version 1.0.0
	 *
	 * @return Response
	 */
	public function roles() {
		$roles = Role::where('name', 'Administrador TIC')
					->orWhere('name', 'Soporte TIC')
					->orWhere('name', 'Proveedor TIC')
					->orWhere('name', 'Usuario TIC')
					->get();
		return $this->sendResponse($roles->toArray(), trans('data_obtained_successfully'));
	}

	/**
     * Obtiene la linea de tiempo de un estado
	 *
	 * @author Carlos Moises Garcia T. - Ene. 20 - 2021
	 * @version 1.0.0
	 *
	 * @param Object $object objeto contenedor de propiedades
	 */
	public static function stateTimeline($object){
		
		// Valida si el estado es cancelada
		if ($object->ht_tic_request_status_id == 8) {
			// Asigna los datos
			$object->status_name =  $object->request_status;
			$object->status_color = "#97E39F";
		} 
		else if ($object->ht_tic_request_status_id > 8) {
			// Asigna los datos
			$object->status_name =  $object->request_status;
			$object->status_color = "#be5acf";
		} 
		else {
			// Valida que la fecha de atencion no este vacia
			if ($object->date_attention) {
				$dateAttention = strtotime($object->date_attention);
				// Obtiene la fecha de vencimiento de la solicitud
				$expirationDate = strtotime($object->expiration_date);
			
				// Valida que la fecha de atencion es mayor a la fecha de vencimiento
				if ($dateAttention > $expirationDate) {
					// Asigna los datos
					$object->status_name =  $object->request_status."<br>"."(Vencida)";
					$object->status_color = "#dc3545";
				}
				// Valida que la fecha de atencion es menor a la fecha de vencimiento
				else if ($dateAttention < $expirationDate) {
					// Asigna los datos
					$object->status_name =  $object->request_status."<br>"."(A tiempo)";

					if($object->status_name == "Abierta<br>(A tiempo)"){
						$object->status_color = "#0d6efd";

					}elseif ($object->status_name == "Asignada<br>(A tiempo)") {
						$object->status_color = "#ffc107";
						
					}elseif ($object->status_name == "En proceso<br>(A tiempo)") {
						$object->status_color = "#fd7e14";

					}elseif ($object->status_name == "Cerrada (Encuesta pendiente)<br>(A tiempo)") {
						$object->status_color = "#2F5997";

					}elseif ($object->status_name == "Cerrada (Encuesta realizada)<br>(A tiempo)") {
						$object->status_color = "#0BC568";
						
					}elseif ($object->status_name == "Cerrada (Sin Encuesta)<br>(A tiempo)") {
						$object->status_color = "#6c757d";
						
					}elseif ($object->status_name == "Devuelta<br>(A tiempo)") {
						$object->status_color = "#20c997";	
					}
					
				}
			} else {
				// Obtiene la fecha actual
				$currentDateTime = strtotime(date('Y-m-d H:i:s'));
				// Obtiene la fecha de proxima a vencerse de la solicitud
				$proxDateToExpire = strtotime($object->prox_date_to_expire);
				// Obtiene la fecha de vencimiento de la solicitud
				$expirationDate = strtotime($object->expiration_date);

				// Valida que exista una fecha de vencimiento
				if ($object->expiration_date) {
					// Valida que la fecha actual es mayor a la fecha de vencimiento
					if ($currentDateTime >  $expirationDate) {
						$object->status_name =  $object->request_status."<br>"."(Vencida)";
						$object->status_color = "#dc3545";
					}
					// Valida que la fecha actual es mayor a la fecha de proxima a vencerse
					else if ($currentDateTime >  $proxDateToExpire) {
						$object->status_name =  $object->request_status."<br>"."(Próximo a vencer)";
						$object->status_color = "#ffc107";
					} else {
						// Asigna los datos
						$object->status_name =  $object->request_status."<br>"."(A tiempo)";
						
						if($object->status_name == "Abierta<br>(A tiempo)"){
							$object->status_color = "#0d6efd";

						}elseif ($object->status_name == "Asignada<br>(A tiempo)") {
							$object->status_color = "#ffc107";
							
						}elseif ($object->status_name == "En proceso<br>(A tiempo)") {
							$object->status_color = "#fd7e14";

						}elseif ($object->status_name == "Cerrada (Encuesta pendiente)<br>(A tiempo)") {
							$object->status_color = "#2F5997";

						}elseif ($object->status_name == "Cerrada (Encuesta realizada)<br>(A tiempo)") {
							$object->status_color = "#0BC568";
							
						}elseif ($object->status_name == "Cerrada (Sin Encuesta)<br>(A tiempo)") {
							$object->status_color = "#9C9E9D";
							
						}elseif ($object->status_name == "Devuelta<br>(A tiempo)") {
							$object->status_color = "#20c997";	
						}
					}
				} else {
					// Asigna los datos
					$object->status_name =  $object->request_status."<br>"."(Sin fecha de vencimiento)";
					$object->status_color = "#17a2b8";
				}
			}
		}
		
		return $object;
	}

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
	 * Obtiene todos los roles existentes
	 *
	 * @author Carlos Moises Garcia T. - Oct. 27 - 2020
	 * @version 1.0.0
	 *
	 * @return Response
	 */
	public function getOperatingSystemsBySO($id) {

		$operatingSystemsVersion = $this->getListOfReferedObject(config('help_table.operating_systems_version'), 'operating_systems_id', $id);
		return $this->sendResponse($operatingSystemsVersion, trans('data_obtained_successfully'));
	}	 

	/**
	 * Obtiene las versiones de ofimatica
	 *
	 * @author Carlos Moises Garcia T. - Oct. 27 - 2020
	 * @version 1.0.0
	 *
	 * @return Response
	 */
   public function getUsersTic(Request $request) {
		$query = $request->input('query');
		$users = User::role('Usuario TIC')
			->where('name','like','%'.$query.'%')
			->where('deleted_at', '=', null)
			->latest()
			->get()
			->map(function($user) {
				// Obtiene el nombre de la dependencia del usuario
				$user->dependency = $user->dependencies ? $user->dependencies->nombre : '';

				return $user;
			});
		return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
	}

	/**
	 * Obtiene las versiones de ofimatica
	 *
	 * @author Carlos Moises Garcia T. - Oct. 27 - 2020
	 * @version 1.0.0
	 *
	 * @return Response
	 */
	public function getSupplierUsersTic(Request $request) {
		$whereQuery = $request->input('query');
	
		$users = TicProvider::
            with(['users'])
            ->latest()
			->where('state',1)
            ->whereHas('users', function($query) use($whereQuery) {
				$query->where('name','like','%'.$whereQuery.'%');
				$query->where('deleted_at', '=', null);
            })
            ->get()
            ->map(function($item, $key){
						
					if ($item->users) {
						$item->email     = $item->users->email;
						$item->name      =  $item->users->name;
						$item->block     =  $item->users->block;
						$item->sendEmail =  $item->users->sendEmail;
					}
					return $item;
            });

		return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
	}

	/**
     * Obtiene los usuarios de soporte
     *
     * @author Carlos Moises Garcia T. - Oct. 27 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function getSupportUsersTic(Request $request) {
		$query = $request->input('query');
		$users = User::role(['Soporte TIC', 'Administrador TIC'])
		->where('name','like','%'.$query.'%')
		->where('deleted_at', '=', null)
		->latest()
		->get();
      return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
	}

	/**
     * Obtiene los usuarios administradores
     *
     * @author Carlos Moises Garcia T. - Jun. 03 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
	public function getAdminUsersTic(Request $request) {
        $query = $request->input('query');
        $users = User::role(['Administrador TIC'])
			->where('name','like','%'.$query.'%')
			->where('deleted_at', '=', null)
			->get();
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
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
		
		$dateConstant = config('help_table.'.$nameConstant);
		return $this->sendResponse($dateConstant, trans('data_obtained_successfully'));
	}


	/**
     * Obtiene los usuarios de soporte
     *
     * @author Carlos Moises Garcia T. - Oct. 29 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
	public function calculateFutureDate($arrayDateNoWorks, $date, $unit_term, $termType, $daysAttention, $workingHours = null){

		$created = $date;
		$l1 = explode(" ",$created);
		$l2 = explode("-",$l1[0]);
		$l3 = mktime(0,0,0,$l2[1],$l2[2],$l2[0]);
		$mcreated0 = $l3;
		$mcreated = $l3;
        $dias = 0;

        switch($unit_term){
			case "Días":
				while ($dias < $daysAttention) {
					$date = date("Y-m-d",$mcreated);
					if(in_array($date, $arrayDateNoWorks) == false or $termType == "Calendario")
					$dias++;
					$l1 = explode(" ",$date);
					$l2 = explode("-",$l1[0]);
					$l3 = mktime(0,0,0,$l2[1],$l2[2]+1,$l2[0]);
					$mcreated = $l3;
            }
                
				//verificar que la fecha de vencimiento no esté entre los dias no habiles, si lo está buscar el siguiente dia habil
				$date = date("Y-m-d",$mcreated);
				if (in_array($date,$arrayDateNoWorks) and $termType != "Calendario") {	
					while(in_array($date, $arrayDateNoWorks)){
						$l1 = explode(" ",$date);
						$l2 = explode("-",$l1[0]);
						$l3 = mktime(0,0,0,$l2[1],$l2[2]+1,$l2[0]);
						$mcreated = $l3;
						$date = date("Y-m-d",$mcreated);
					}
            }
				$datos[] = date("Y-m-d H:i:s",$mcreated);
				$datos[] = null;

				return $datos;
			break;
			case "Horas":
				//OBTENER LA FECHA Y HORA DE VENCIMIENTO
				
				//Horario que se ha definido en el calendario y queda guardado en la tabla horario_laboral
				$horainiam = date("H:i", strtotime($workingHours->horaInicioAM));
				$horafinam = date("H:i", strtotime($workingHours->horaFinAM));
				$horainipm = date("H:i", strtotime($workingHours->horaInicioPM));
				$horafinpm = date("H:i", strtotime($workingHours->horaFinPM));

				//MOVERSE POR CADA MINUTO
				
				//OBTENER LA FECHA Y HORA INICIAL, PREVINIENDO QUE SE HAYA GENERADO EL PQR EN UNA HORA NO LABORAL ENTONCES SE UBICA
				//LA FECHA Y HORA DE CREACIÓN EN UNA HORA LABORAL DEFINIDA
				
				$cf_created = $date;
				$created_lista = explode(" ",$cf_created);
				
				$created_dia  = $created_lista[0];
				$created_hora = $created_lista[1];
				
				$dia_lista = explode("-",$created_dia);
				$hora_lista = explode(":",$created_hora);
				
				//CONSTRUIR EL MKTIME PARA OBTENER EL TIEMPO EN SEGUNDOS DE LA FECHA DE CREACIÓN DE PQR Y ASI MOVERSE MAS FACIL Y
				//OBTENER LA FECHA DE VENCIMIENTO
				
				$created_mktime = mktime($hora_lista[0],$hora_lista[1],$hora_lista[2],$dia_lista[1],$dia_lista[2],$dia_lista[0]);
				
				// EL WHILE SEGUIRÁ HASTA QUE EL TIEMPO QUE HAYA SUMADO A CREATED_MKTIME HAYA IGUALADO AL TIEMPO DE PLAZO (daysAttention)
				
				// CONVERTIR A diasatencion A SEGUNDOS (TIEMPO ABSOLUTO OSEA SIN DIAS FESTIVOS NI RANGOS YA QUE ESTE TIEMPO ES EL REAL DE TRABAJO)
				//DIASATENCION ESTA EN HORAS ENTONCES CONVERTIR HORAS A SEGUNDOS
				
            // 1 HORA -> 60 MINUTOS -> 1 MINUTO -> 60 SEGUNDOS = 3600 SEGUNDOS	
				$segundos_atencion = $daysAttention * 60 * 60;
				
				// CREAR VARIABLE QUE CUENTE LOS SEGUNDOS Y SE COMPARE CON SEGUNDOS_ATENCION
				$segundos_recorridos = 0;
				
				$lastdiff = 0;

				// OBTIENE LA FECHA Y HORA INICIAL LABORAL
				$inicial_mktime = NULL;
				
				// ARRAY DE HORAS QUE CONTENDRÁ LOS TIEMPOS ABSOLUTOS Y RELATIVOS DE CADA HORA HABIL ENTRE LA FECHA DE CREACIÓN Y LA FECHA CALCULADA
				// DE VENCIMIENTO.
				$HORAS = array();
				
				while($segundos_recorridos < $segundos_atencion){
					
					$date = date("Y-m-d",$created_mktime);
					
					//ESTO VA AFUERA DEL IF PORQUE INDEPENDIENTE DE QUE SEA DIA HABIL O NO, DEBE CALCULARSEN LOS RANGOS
					
					//OBTENER MKTIMES DE LIMITES DE HORARIO LABORAL PARA USARLOS EN LA VALIDACION DE HORAS
					//HAY QUE HACERLO DENTRO DEL WHILE PORQUE LAS HORAS LABORALES NO CONTIENEN LOS DIAS ENTONCES HAY QUE CALCULARLOS
					//LOS MKTIMES POR CADA DIA EN EL QUE SE VA MOVIENDO
					$date_lista = explode("-",$date);
					
					//OBTENER HORA Y MINUTOS DE PARAMETROS
					$horainiam_lista = explode(":",$horainiam);
					$horafinam_lista = explode(":",$horafinam);
					$horainipm_lista = explode(":",$horainipm);
					$horafinpm_lista = explode(":",$horafinpm);
					
					//NO TIENEN SEGUNDOS ENTONCES SE LES AGREGA TENIENDO EN CUENTA QUE LA HORAS INICIALES ARRANCAN EN CERO Y LAS FINALES
					//TERMINAN EN 59 PARA ABARCAR TODO EL RANGO HASTA LOS SEGUNDOS
					$horainiam_mktime = mktime($horainiam_lista[0],$horainiam_lista[1],0,$date_lista[1],$date_lista[2],$date_lista[0]);
					$horafinam_mktime = mktime($horafinam_lista[0],$horafinam_lista[1],59,$date_lista[1],$date_lista[2],$date_lista[0]);
					$horainipm_mktime = mktime($horainipm_lista[0],$horainipm_lista[1],0,$date_lista[1],$date_lista[2],$date_lista[0]);
					$horafinpm_mktime = mktime($horafinpm_lista[0],$horafinpm_lista[1],59,$date_lista[1],$date_lista[2],$date_lista[0]);

					//NO ES UN DIA FESTIVO O EL TIPO DE PLAZO ES CALENDARIO ENTONCES NO IMPORTA QUE SEA FESTIVO ?
					if(in_array($date, $arrayDateNoWorks) == false or $termType == "Calendario"){
						//SI EL TIPO DE PLAZO ES CALENDARIO
						if($termType == "Calendario"){
							//CONTAR EL TIEMPO EN 24 HORAS
							
							//ALMACENA EN HORAS EL TIEMPO ACTUAL
							$HORAS[] = $created_mktime."-3600";
							//SE INCREMENTA UNA HORA (3600 SEGUNDOS)
							$created_mktime += 3600;
							//SE INCREMENTA UNA HORA EL CONTADOR
							$segundos_recorridos += 3600;
						} else {
							//CALCULAR QUE EL ACTUAL CREATED_MKTIME NO ESTA FUERA DE LOS RANGOS HORARIOS
							
							//POR LA MAÑANA
							if ($horainiam_mktime <= $created_mktime and $created_mktime <= $horafinam_mktime){
								//ALMACENA EN HORAS EL TIEMPO ACTUAL
								$HORAS[] = $created_mktime."-3600";
								//SE INCREMENTA UNA HORA (3600 SEGUNDOS)
								$created_mktime += 3600;
								//SE INCREMENTA UNA HORA EL CONTADOR
								$segundos_recorridos += 3600;
							} else {
								//ESTA FUERA DEL RANGO DE POR LA MAÑANA O ES DE LA TARDE ?	
								if ($horainipm_mktime <= $created_mktime and $created_mktime <= $horafinpm_mktime) {
									//ALMACENA EN HORAS EL TIEMPO ACTUAL
									$HORAS[] = $created_mktime."-3600";
									//SE INCREMENTA UNA HORA (3600 SEGUNDOS)
									$created_mktime += 3600;
									//SE INCREMENTA UNA HORA EL CONTADOR
									$segundos_recorridos += 3600;
								} else {
									//ESTA FUERA DE LOS RANGOS
									//DE CUAL ? DE LOS DOS ? O ESTA EN EL MEDIO DE LOS DOS O ANTES DEL PRIMERO O DESPUES DEL SEGUNDO RANGO ?
									
									//EL SEGUNDERO segundos_recorridos NO SE INCREMENTA EN ESTOS CASOS
									//PRIMER CASO: ESTA ANTES DEL PRIMERO, EJEMPLO: CREATED (6:30) < 7:00 , ENTONCES SE INCREMENTA EL CREATED
									if($horainiam_mktime > $created_mktime){
										//SE MUEVE A LA SIGUIENTE HORA 
										
										//SE INCREMENTA UNA HORA (3600 SEGUNDOS)
										$created_mktime += 3600;
									} else{
										//SEGUNDO CASO: ESTA EN EL MEDIO, EJEMPLO CREATED (13:30) > 12:00 Y ES < 14:00, ENTONCES SE INCREMENTA EL CREATED
										if ($horafinam_mktime < $created_mktime and $created_mktime < $horainipm_mktime) {
											//SE INCREMENTA UNA HORA (3600 SEGUNDOS)
											$created_mktime += 3600;
										} else{
											//TERCER CASO: ESTA DESPUES DEL FINAL DEL SEGUNDO RANGO, EJEMPLO CREATED (19:00) > 18:00, ENTONCES 
											//SE MUEVE EL CREATED AL DIA SIGUIENTE HABIL A LAS 7, SI EL DIA ES HABIL O NO NO SE CALCULA AQUI SINO ARRIBA
											//EN EL IF PRINCIPAL DENTRO DEL WHILE, ENTONCES SOLO SUMAMOS 1 DIA PERO NO AL CREATED PORQUE DEBE QUEDAR
											//EL DIA A LA HORA INICIAL DE LA MAÑANA, ENTONCES USAMOS A horainiam_mktime Y SE LO ASIGNAMOS AL CREATED CON
											//UN DIA MAS (1 DIA -> 24 HORAS * 60 MINUTOS * 60 SEGUNDOS)
											//TENIENDO EN CUENTA QUE HAY QUE REVERTIR AL CONTADOR segundos_recorridos EL TIEMPO QUE SE PASÓ PARA QUE DE
											//EL TIEMPO EXACTO
											
											if ($horafinpm_mktime < $created_mktime) {
												//SE INCREMENTA UNA HORA (3600 SEGUNDOS)
												$created_mktime += 3600;
											} else{
												//	ESTA EN OTRO UNIVERSO PARALELO
											}
										}
									}
								}
							}
						}
					} else{
						//SE INCREMENTA UNA HORA (3600 SEGUNDOS)
						$created_mktime += 3600;
					}
		
					if($inicial_mktime == NULL){
						$inicial_mktime = $created_mktime;
					}
				}
				//SI EL TIPO DE PLAZO ES LABORAL
				if ($termType == "Laboral") {
				
					do{
						//VERIFICAR PRIMERO QUE LA FECHA OBTENIDA SEA HABIL
						$date = date("Y-m-d",$created_mktime);
						
						//ESTO VA AFUERA DEL IF PORQUE INDEPENDIENTE DE QUE SEA DIA HABIL O NO, DEBE CALCULARSEN LOS RANGOS
						
						//OBTENER MKTIMES DE LIMITES DE HORARIO LABORAL PARA USARLOS EN LA VALIDACION DE HORAS
						//HAY QUE HACERLO DENTRO DEL WHILE PORQUE LAS HORAS LABORALES NO CONTIENEN LOS DIAS ENTONCES HAY QUE CALCULARLOS
						//LOS MKTIMES POR CADA DIA EN EL QUE SE VA MOVIENDO
						
						$date_lista = explode("-",$date);
						
						//OBTENER HORA Y MINUTOS DE PARAMETROS
						$horainiam_lista = explode(":",$horainiam);
						$horafinam_lista = explode(":",$horafinam);
						$horainipm_lista = explode(":",$horainipm);
						$horafinpm_lista = explode(":",$horafinpm);
						
						//NO TIENEN SEGUNDOS ENTONCES SE LES AGREGA TENIENDO EN CUENTA QUE LA HORAS INICIALES ARRANCAN EN CERO Y LAS FINALES
						//TERMINAN EN 59 PARA ABARCAR TODO EL RANGO HASTA LOS SEGUNDOS
						$horainiam_mktime = mktime($horainiam_lista[0],$horainiam_lista[1],0,$date_lista[1],$date_lista[2],$date_lista[0]);
						$horafinam_mktime = mktime($horafinam_lista[0],$horafinam_lista[1],59,$date_lista[1],$date_lista[2],$date_lista[0]);
						$horainipm_mktime = mktime($horainipm_lista[0],$horainipm_lista[1],0,$date_lista[1],$date_lista[2],$date_lista[0]);
						$horafinpm_mktime = mktime($horafinpm_lista[0],$horafinpm_lista[1],59,$date_lista[1],$date_lista[2],$date_lista[0]);
						
						//NO ES UN DIA FESTIVO O EL TIPO DE PLAZO ES CALENDARIO ENTONCES NO IMPORTA QUE SEA FESTIVO ?
						$habil = false;

						//CALCULAR QUE EL ACTUAL CREATED_MKTIME NO ESTA FUERA DE LOS RANGOS HORARIOS
						//POR LA MAÑANA
						if ($horainiam_mktime <= $created_mktime and $created_mktime <= $horafinam_mktime) {
							//SE SALE PORQUE ES HABIL
							$habil = true;
						} else{
							//ESTA FUERA DEL RANGO DE POR LA MAÑANA O ES DE LA TARDE ?	
							if($horainipm_mktime <= $created_mktime and $created_mktime <= $horafinpm_mktime){
								//SE SALE PORQUE ES HABIL
								$habil = true;
							} else{
								//ESTA FUERA DE LOS RANGOS
								//DE CUAL ? DE LOS DOS ? O ESTA EN EL MEDIO DE LOS DOS O ANTES DEL PRIMERO O DESPUES DEL SEGUNDO RANGO ?
								
								//EL SEGUNDERO segundos_recorridos NO SE INCREMENTA EN ESTOS CASOS
								//PRIMER CASO: ESTA ANTES DEL PRIMERO, EJEMPLO: CREATED (6:30) < 7:00 , ENTONCES SE INCREMENTA EL CREATED
								if($horainiam_mktime > $created_mktime){									
									//SE MUEVE A LA SIGUIENTE HORA 
									
									//SE INCREMENTA UNA HORA (3600 SEGUNDOS)
									$created_mktime += 3600;
									$habil = false;
								} else{
									//SEGUNDO CASO: ESTA EN EL MEDIO, EJEMPLO CREATED (13:30) > 12:00 Y ES < 14:00, ENTONCES SE INCREMENTA EL CREATED
									
									if ($horafinam_mktime < $created_mktime and $created_mktime < $horainipm_mktime) {
										//SE INCREMENTA UNA HORA (3600 SEGUNDOS)
										$created_mktime += 3600;
										$habil = false;
										
									} else{
										//TERCER CASO: ESTA DESPUES DEL FINAL DEL SEGUNDO RANGO, EJEMPLO CREATED (19:00) > 18:00, ENTONCES 
										//SE MUEVE EL CREATED AL DIA SIGUIENTE HABIL A LAS 7, SI EL DIA ES HABIL O NO NO SE CALCULA AQUI SINO ARRIBA
										//EN EL IF PRINCIPAL DENTRO DEL WHILE, ENTONCES SOLO SUMAMOS 1 DIA PERO NO AL CREATED PORQUE DEBE QUEDAR
										//EL DIA A LA HORA INICIAL DE LA MAÑANA, ENTONCES USAMOS A horainiam_mktime Y SE LO ASIGNAMOS AL CREATED CON
										//UN DIA MAS (1 DIA -> 24 HORAS * 60 MINUTOS * 60 SEGUNDOS)
										//TENIENDO EN CUENTA QUE HAY QUE REVERTIR AL CONTADOR segundos_recorridos EL TIEMPO QUE SE PASÓ PARA QUE DE
										//EL TIEMPO EXACTO
										
										if ($horafinpm_mktime < $created_mktime) { 
											//SE INCREMENTA UNA HORA (3600 SEGUNDOS)
											$created_mktime += 3600;
											$habil = false;
										} else{
											//ESTA EN OTRO UNIVERSO PARALELO	
											$habil = false;
										}
									}
								}
							}
						}
					}
					while( ( in_array($date, $arrayDateNoWorks) == true or $habil == false ) and $termType != "Calendario");
					
				}//SALE DEL WHILE Y SE OBTIENE LA FECHA Y HORA FINAL DEL CREATED_MKTIME
				
				$mcreated  = date("Y-m-d H:i",$created_mktime).":59";
				$datos[] = $mcreated;
				$datos[] = implode(",", $HORAS);
			
				return $datos;
			break;
		}
	}

	/**
    * Ruta de subida de archivos en la crecacion de la experiencia
    */
	public function uploadTempFile(Request $request) {
		dd($request->file->getClientOriginalExtension());
		dd($request, $request->files, $request->file('files'),$request->input('file'), request()->all(), request()->isXmlHttpRequest());
	}


}
