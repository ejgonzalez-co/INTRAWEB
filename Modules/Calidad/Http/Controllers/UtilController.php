<?php

namespace Modules\Calidad\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Correspondence\Models\Internal;
use Modules\Correspondence\Models\External;
use Modules\Calidad\Models\Documento;

class UtilController extends AppBaseController {

	/**
     * Obtiene los datos de una constante dependiendo del nombre
     *
     * @author Seven Soluciones Informáticas S.A.S - Mar. 21 - 2024
     * @version 1.0.0
     *
	  * @param nameConstant nombre de la constante a obtener
	  *
     * @return Response
     */
	public function getConstants($nameConstant) {
		$dateConstant = config('calidad.'.$nameConstant);
		return $this->sendResponse($dateConstant, trans('data_obtained_successfully'));
	}

	/**
     * Calcula consecutivo y numero de orden proximo (prefijo)
     *
     * @author Seven Soluciones Informáticas S.A.S - Mar. 21 - 2024
     * @version 1.0.0
     *
     * @param String $formatoConsecutivo Formato de consecutivo tomado de variable
     * @param String $formatoConsecutivoPrefijo Formato de preifjo de consecutivo para calcular siguiente
     * @param String $formatoConsecutivoValores Codigo de la dependencia que publica el documento
     *
     * @return Response $dataConsecutive consecutivo y orden para guardar
     */
	public static  function getNextConsecutive($formatoConsecutivo, $formatoConsecutivoPrefijo, $formatoConsecutivoValores) {

		// Calcular consecutivo
		$numberDigits = 1;

		// Reemplaza valores en el formato del consecutivo
		$formatoConsecutivo = str_replace("prefijo_dependencia", $formatoConsecutivoValores["prefijo_dependencia"], $formatoConsecutivo);

		$formatoConsecutivo = str_replace("prefijo_tipo_proceso", $formatoConsecutivoValores["prefijo_tipo_proceso"], $formatoConsecutivo);
		$formatoConsecutivo = str_replace("prefijo_proceso", $formatoConsecutivoValores["prefijo_proceso"], $formatoConsecutivo);
		// $formatoConsecutivo = str_replace("prefijo_subproceso", $formatoConsecutivoValores["prefijo_subproceso"], $formatoConsecutivo);
		$formatoConsecutivo = str_replace("orden_proceso", $formatoConsecutivoValores["orden_proceso"], $formatoConsecutivo);
		$formatoConsecutivo = str_replace("prefijo_tipo_documento", $formatoConsecutivoValores["prefijo_tipo_documento"], $formatoConsecutivo);

		$formatoConsecutivo = str_replace("serie_documental", $formatoConsecutivoValores["serie_documental"] ? $formatoConsecutivoValores["serie_documental"] : '', $formatoConsecutivo);
		$formatoConsecutivo = str_replace("subserie_documental", $formatoConsecutivoValores["subserie_documental"] ? $formatoConsecutivoValores["subserie_documental"] : '', $formatoConsecutivo);
		$formatoConsecutivo = str_replace("vigencia_actual", $formatoConsecutivoValores["vigencia_actual"], $formatoConsecutivo);
        // Reemplaza valores al prefijo del formato del consecutivo
		$formatoConsecutivoPrefijo = str_replace("prefijo_dependencia", $formatoConsecutivoValores["prefijo_dependencia"], $formatoConsecutivoPrefijo);

        $formatoConsecutivoPrefijo = str_replace("prefijo_tipo_proceso", $formatoConsecutivoValores["prefijo_tipo_proceso"], $formatoConsecutivoPrefijo);
		$formatoConsecutivoPrefijo = str_replace("prefijo_proceso", $formatoConsecutivoValores["prefijo_proceso"], $formatoConsecutivoPrefijo);
		// $formatoConsecutivoPrefijo = str_replace("prefijo_subproceso", $formatoConsecutivoValores["prefijo_subproceso"], $formatoConsecutivoPrefijo);
		$formatoConsecutivoPrefijo = str_replace("orden_proceso", $formatoConsecutivoValores["orden_proceso"], $formatoConsecutivoPrefijo);
		$formatoConsecutivoPrefijo = str_replace("prefijo_tipo_documento", $formatoConsecutivoValores["prefijo_tipo_documento"], $formatoConsecutivoPrefijo);

		$formatoConsecutivoPrefijo = str_replace("serie_documental", $formatoConsecutivoValores["serie_documental"] ? $formatoConsecutivoValores["serie_documental"] : '', $formatoConsecutivoPrefijo);
		$formatoConsecutivoPrefijo = str_replace("subserie_documental", $formatoConsecutivoValores["subserie_documental"] ? $formatoConsecutivoValores["subserie_documental"] : '', $formatoConsecutivoPrefijo);
		$formatoConsecutivoPrefijo = str_replace("vigencia_actual", $formatoConsecutivoValores["vigencia_actual"], $formatoConsecutivoPrefijo);
		//consulta segun el numero de correspondencia
        $order = Documento::where('consecutivo', 'like', '%' . str_replace(", ", $formatoConsecutivoValores["separador_consecutivo"], $formatoConsecutivoPrefijo) . '%')->max('consecutivo_prefijo');

		//valida el numero de order obtenido de la consulta
		if(!$order){
			$orden = 0;
		}

		$orden = (int)$order+1;

		$length = strlen($orden);

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
		$nextConsecutivo = str_replace("consecutivo_documento", $norden, $formatoConsecutivo);
        $datosConsecutivo['consecutivo'] = str_replace(", ", $formatoConsecutivoValores["separador_consecutivo"], $nextConsecutivo);
		$datosConsecutivo['consecutivo_prefijo'] = $orden;

		return $datosConsecutivo;
	}
}
