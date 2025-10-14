<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Configuracion\Models\Variables;
use Modules\Correspondence\Models\Internal;
use Modules\Correspondence\Models\External;
use Modules\Correspondence\Models\ExternalReceived;
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
		$dateConstant = config('correspondence.'.$nameConstant);
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
	public static  function getNextConsecutive($type,$formatConsecutive,$formatConsecutivePrefix,$DP,$PL,$siglas) {

		//calcular consecutivo
		$numberDigits = 3;



		//reemplaza valores en el prefijo del formato del consecutivo
		$formatConsecutivePrefix = str_replace("DP",$DP,$formatConsecutivePrefix);
		$formatConsecutivePrefix = str_replace("PL",$PL,$formatConsecutivePrefix);
		$formatConsecutivePrefix = str_replace("Y",date("Y"),$formatConsecutivePrefix);
		$formatConsecutivePrefix = str_replace("SIGLAS",$siglas,$formatConsecutivePrefix);
		$formatConsecutivePrefix = str_replace("CO",'%',$formatConsecutivePrefix);
		$formatConsecutivePrefix = str_replace("%%",'%',$formatConsecutivePrefix);

		// dd($formatConsecutivePrefix);
		//consulta segun el numero de correspondencia
		switch ($type) {
			case 'Internal':
                //Consulta si es consecutivo compartido con externa
				$share_consecutive = Variables::where('name', 'consecutivo_compartido')->pluck('value')->first();
				if (!empty($share_consecutive) && $share_consecutive == 'Si') {
                    // Obtiene el valor máximo de "consecutive_order" en "interna" y "enviada" con el prefijo especificado en "consecutive"
					$order = collect([
                        Internal::where('consecutive', 'like', '%' . $formatConsecutivePrefix . '%')->max('consecutive_order'),
                        External::where('consecutive', 'like', '%' . $formatConsecutivePrefix . '%')->max('consecutive_order')
                    ])->max();
				}else{
					$order = Internal::where('consecutive', 'like', '%' . $formatConsecutivePrefix. '%')->max('consecutive_order');
				}

				break;

			case 'External':
                //Consulta si es consecutivo compartido con interna
				$share_consecutive = Variables::where('name', 'consecutivo_compartido')->pluck('value')->first();
				if (!empty($share_consecutive) && $share_consecutive == 'Si') {
                    // Obtiene el valor máximo de "consecutive_order" en "interna" y "enviada" con el prefijo especificado en "consecutive"
					$order = collect([
                        Internal::where('consecutive', 'like', '%' . $formatConsecutivePrefix . '%')->max('consecutive_order'),
                        External::where('consecutive', 'like', '%' . $formatConsecutivePrefix . '%')->max('consecutive_order')
                    ])->max();
				}else{
				    $order = External::where('consecutive', 'like', '%' . $formatConsecutivePrefix. '%')->max('consecutive_order');
                }

				break;

			case 'External_received':
				$order = ExternalReceived::where('consecutive', 'like', '%' . $formatConsecutivePrefix. '%')->max('consecutive_order');
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
		// $nextConsecutive = preg_replace('/\bCO\b/', $norden, $formatConsecutive);

		//reemplaza valores en el formato del consecutivo
		$formatConsecutive = str_replace("DP",$DP,$nextConsecutive);
		$formatConsecutive = str_replace("PL",$PL,$formatConsecutive);
		$formatConsecutive = str_replace("Y",date("Y"),$formatConsecutive);
		$formatConsecutive = str_replace("SIGLAS",$siglas,$formatConsecutive);

		$dataConsecutive['consecutive'] = $formatConsecutive;
		$dataConsecutive['consecutive_order'] = $orden;

		return $dataConsecutive;
	}

	/**
     * Valida la segunda contraseña del usuario a la hora de publicar un documento de correspondencia interna o enviada
     *
     * @param Request $request
     * @return void
     */
    public function validarSecondPassword(Request $request) {
        $input = $request->all();
		// Obtiene la segunda contraseña del usuario en sesión
        $usuario_second_password = Auth::user()->second_password;
		// Valida la igualdad de la segunda contraseña ingresada por el usuario, con la contraseña configurada en el sistema
        $second_password_validate = Hash::check($input["second_password_publish"], $usuario_second_password);
		// Retorna la comparación de la contraseñas si son iguales o no
        return $this->sendResponse(["second_password_validate" => $second_password_validate], trans('msg_success_update'));
    }



	/**
	 * Genera una representación formateada de los destinatarios en base a una plantilla de texto.
	 *
	 * Descripción:
	 * - Si el texto es vacío o `null`, devuelve "No aplica".
	 * - Obtiene el formato desde la base de datos (variable `var_internal_formato_destinatario`).
	 * - Divide el texto en destinatarios utilizando `<br>` como separador.
	 * - Si un destinatario coincide con el patrón "Nombre (Cargo, Dependencia)", 
	 *   reemplaza los valores en la plantilla.
	 * - Si no coincide, lo deja sin cambios.
	 * - Devuelve los destinatarios en líneas separadas.
	 *
	 * Parámetro:
	 * @param string|null $text Texto con los destinatarios, separados por `<br>`.
	 *
	 * Retorna:
	 * @return string Lista de destinatarios formateados, separados por saltos de línea.
	 *
	 * Ejemplos de uso:
	 * echo destinatariosText("Juan Pérez (Gerente, Finanzas)<br>María López (Analista, RRHH)");
	 * // Salida:
	 * // Juan Pérez (Gerente, Finanzas)
	 * // María López (Analista, RRHH)
	 *
	 * echo destinatariosText("Carlos Gómez");
	 * // Salida:
	 * // Carlos Gómez
	 *
	 * echo destinatariosText(null);
	 * // Salida: "No aplica"
	 */
	public static function formatTextByVariable($text,$nameVariable) {
		if (empty($text)) {
			return "No aplica"; // Si el texto está vacío, retorna "No aplica".
		}
		switch ($nameVariable) {
			case 'Destinatarios':
				// Obtiene el formato de la base de datos para 'var_internal_formato_destinatario'
				$variable = Variables::where('name', 'var_internal_formato_destinatario')->value('value');
				// Verificar si el variable no está vacío
				if (empty($variable)) {
					$text = str_replace("<br>", "\n", $text); // Reemplaza <br> por saltos de línea
					return $text; // Si variable está vacío, retorna text
				}

				// Divide el texto en destinatarios usando <br> como separador y elimina espacios innecesarios.
				$destinatarios = array_filter(array_map('trim', explode('<br>', $text)));
				break;
				
			case 'Otros':

				// Obtiene el formato de la base de datos para 'var_internal_otros_2'
				$variable = Variables::where('name', 'var_internal_otros')->value('value');

				if (empty($variable)) {
					$text = str_replace("<br>", "\n", $text); // Reemplaza <br> por saltos de línea
					return $text; // Si variable está vacío, retorna text
				}
				$text = str_replace("<br>", ",", $text); // Reemplaza <br> por ,


				$destinatarios = array_map(
					fn($destinatario, $index) => trim($destinatario) . ($index < substr_count($text, '),') ? ')' : ''),
					// Verifica si hay "-" en el texto; si sí, usa "-" como separador, de lo contrario, usa "),"
					strpos($text, ' - ') !== false ? explode(' - ', $text) : explode('),', $text),
					array_keys(strpos($text, ' - ') !== false ? explode(' - ', $text) : explode('),', $text))
				);
				
			
				break;
		}

	
		// Aplica el formato si el destinatario tiene el patrón esperado.
		$resultado = array_map(function ($destinatario) use ($variable) {
			if (preg_match('/^(.*?)\s\((.*?),\s(.*?)\)$/', $destinatario, $matches)) {
				return str_replace(
					["nombre_usuario", "cargo", "dependencia"],
					[trim($matches[1]), trim($matches[2]), trim($matches[3])],
					$variable
				);
			}
			return $destinatario; // Si no coincide con el patrón, se deja igual.
		}, $destinatarios);
		switch ($nameVariable) {
			case 'Destinatarios':
				// Une los destinatarios en líneas separadas.
				return implode("\n\n", $resultado);
			case 'Otros':
				return implode(", ", $resultado);
			}
		// Une los destinatarios en líneas separadas.
	}
	/**
	 * Formatea un nombre o lista de nombres aplicando ciertos reemplazos.
	 *
	 * Descripción:
	 * - Si el valor es `null` o vacío, devuelve "No aplica".
	 * - Reemplaza las etiquetas `<br>` por comas y espacios.
	 * - Luego, reemplaza comas que no estén dentro de paréntesis `()` por saltos de línea.
	 * - Elimina espacios en blanco al inicio de cada línea.
	 * - Si después de limpiar el texto queda vacío, devuelve "No aplica".
	 *
	 * Parámetro:
	 * @param string|null $value El texto con los nombres a formatear.
	 *
	 * Retorna:
	 * @return string El texto formateado con los reemplazos aplicados o "No aplica".
	 *
	 * Ejemplos de uso:
	 * echo formatNames("Juan Pérez, María López<br>Carlos Gómez");
	 * // Salida:
	 * // Juan Pérez
	 * // María López, Carlos Gómez
	 *
	 * echo formatNames("Ana (Coordinadora), Pedro, Luis");
	 * // Salida:
	 * // Ana (Coordinadora)
	 * // Pedro
	 * // Luis
	 *
	 * echo formatNames(null);
	 * // Salida: "No aplica"
	 */
	public static function formatNames($value) {
		if (!$value) return "No aplica"; // Si no hay valor, devuelve "No aplica"

		// 1. Reemplaza <br> por ", "
		// 2. Reemplaza comas fuera de paréntesis por saltos de línea
		$formatted = preg_replace('/,(?![^()]*\))/', "\n", str_replace("<br>", ", ", $value));

		// 3. Elimina espacios en blanco al inicio de cada línea
		return trim($formatted) !== "" ? rtrim(preg_replace('/^\s+/m', '', $formatted))  : "No aplica";
	}

	public function editRotule(Request $request, $modulo){
		$input = $request->all();
		$props = DB::table('rotule_props')->where('modulo',$modulo)->update([
			"rotuleWidth" => $input['rotule_width'],
		]);
        return $this->sendResponse($props, trans('msg_success_update'));
	}

}
