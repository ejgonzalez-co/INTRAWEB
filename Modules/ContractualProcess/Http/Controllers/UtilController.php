<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use App\User;
use Modules\ContractualProcess\Models\Validity;
use Modules\ContractualProcess\Models\NameProject;
use Modules\ContractualProcess\Models\ManagementUnit;
use Modules\ContractualProcess\Models\ProjectLine;
use Modules\ContractualProcess\Models\Poir;
use Modules\ContractualProcess\Models\City;

class UtilController extends AppBaseController {

	/**
	 * Obtiene los usuarios con el grupo lideres de proceso
	 *
	 * @author Carlos Moises Garcia T. - Dic. 01 - 2020
	 * @version 1.0.0
	 *
	 * @return Response
	 */
	public function getProcessLeadUsers(Request $request) {
		$query = $request->input('query');
		$users = User::role('PC Líder de proceso')->where('name','like','%'.$query.'%')->latest()->get();
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
		$users = User::role('Soporte TIC')->where('name','like','%'.$query.'%')->latest()->get();
		return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
	}
	
	/**
     * Obtiene los datos de una constante dependiendo del nombre
     *
     * @author Carlos Moises Garcia T. - Dic. 01 - 2020
     * @version 1.0.0
     *
	  * @param nameConstant nombre de la constante a obtener
	  * 
     * @return Response
     */
   	public function getConstants($nameConstant) {

		$dateConstant = config('contractual_process.'.$nameConstant);
		return $this->sendResponse($dateConstant, trans('data_obtained_successfully'));
	}

	/**
     * Obtiene los datos de una constante activa dependiendo del nombre
     *
     * @author Carlos Moises Garcia T. - Ene. 08 - 2021
     * @version 1.0.0
     *
	  * @param nameConstant nombre de la constante a obtener
	  * 
     * @return Response
     */
	public function getConstantsActive($nameConstant) {

		$dataConstant = $this->getListOfReferedObject(config('contractual_process.'.$nameConstant), 'state', 'Activa');
		return $this->sendResponse($dataConstant, trans('data_obtained_successfully'));
	}

	/**
	 * Obtiene las vigencia de proyectos de proceso contractual
	 *
	 * @author Carlos Moises Garcia T. - Ene. 13 - 2021
	 * @version 1.0.0
	 *
	 * @return Response
	 */
	public function getValidities(Request $request) {
		$validities = Validity::where('state', 1)->latest()->get();
		return $this->sendResponse($validities->toArray(), trans('data_obtained_successfully'));
	}
	  
	/**
	 * Obtiene los nombres de proyectos de proceso contractual
	 *
	 * @author Carlos Moises Garcia T. - Ene. 13 - 2021
	 * @version 1.0.0
	 *
	 * @return Response
	 */
	public function getNameProjects(Request $request) {
		$nameProjects = NameProject::where('state', 1)->latest()->get();
		return $this->sendResponse($nameProjects->toArray(), trans('data_obtained_successfully'));
	}

	/**
	 * Obtiene las unidades de gestion del proceso contractual
	 *
	 * @author Carlos Moises Garcia T. - Ene. 13 - 2021
	 * @version 1.0.0
	 *
	 * @return Response
	 */
	public function getManagementUnit(Request $request) {
		$managementUnit = ManagementUnit::where('state', 1)->latest()->get();
		return $this->sendResponse($managementUnit->toArray(), trans('data_obtained_successfully'));
	}

	/**
	 * Obtiene las lineas de proyecto del proceso contractual
	 *
	 * @author Carlos Moises Garcia T. - Ene. 13 - 2021
	 * @version 1.0.0
	 *
	 * @return Response
	 */
	public function getProjectLines(Request $request) {
		$projectLines = ProjectLine::where('state', 1)->latest()->get();
		return $this->sendResponse($projectLines->toArray(), trans('data_obtained_successfully'));
	}

	/**
	 * Obtiene las identificaciones de proyecto del proceso contractual
	 *
	 * @author Carlos Moises Garcia T. - Ene. 14 - 2021
	 * @version 1.0.0
	 *
	 * @return Response
	 */
	public function getPoir(Request $request) {
		$poir = Poir::where('state', 1)->orderBy('name', 'ASC')->get();
		return $this->sendResponse($poir->toArray(), trans('data_obtained_successfully'));
	}

	/**
    * Obtiene las ciudades por departamento
    * @param Integer $state_id identificador del departamento
    */
   public function getCitiesByDepartment($state_id) {
		$cities = City::where('state_id', $state_id)->latest()->get();
      return $this->sendResponse($cities->toArray(), trans('data_obtained_successfully'));
	}
	
	/**
     * Obtiene los datos de las actividades relacionadas a un iten de armonizacion tarifaria
     *
     * @author Carlos Moises Garcia T. - Ene. 22 - 2021
     * @version 1.0.0
     *
	  * @param item id del item
	  * 
     * @return Response
     */
	public function getActivityTariffHarmonizationByItem($item) {
		
		if ($item != "undefined") {
			$dataConstant = $this->getListOfReferedObject(config('contractual_process.activity_item_tariff_harmonization'), 'item_id', $item);
		} else {
			$dataConstant = $this->getListOfReferedObject(config('contractual_process.activity_item_tariff_harmonization'), 'state', 'Activa');
		}
		return $this->sendResponse($dataConstant, trans('data_obtained_successfully'));
	}

}
