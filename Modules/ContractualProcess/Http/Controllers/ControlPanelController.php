<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Modules\ContractualProcess\Models\PcPreviousStudies;
use Illuminate\Http\Request;
use Flash;
use Response;
use DB;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Ene. 26 - 2021
 * @version 1.0.0
 */
class ControlPanelController extends AppBaseController {


    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 26 - 2021
     * @version 1.0.0
     */
    public function __construct() {
    }

    /**
     * Muestra la vista para el CRUD de ControlPanel.
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 26 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('contractual_process::control_panels.index')
            ->with('sidebarHide', true);
    }


     /**
     * Obtiene informacion general por totales de los estudios previos
     *
     * @author Erika Johana Gonzalez. - Marzo. 09 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getInformationPreviousStudies() {

        $previousStudies = PcPreviousStudies::select(
            DB::raw('count(*) as total,
                COUNT(IF(type = "Funcionamiento", 1, NULL)) as fun_qty,
                COUNT(IF(type = "Proyecto de inversión", 1, NULL)) as inv_qty,
                COUNT(IF(type = "Funcionamiento e inversión", 1, NULL)) as fun_inv_qty',
            )
        )->get()->first();
       // dd($previousStudies);

        return $this->sendResponse($previousStudies, trans('msg_success_update'));

    }


    /**
     * Obtiene los estudios previos por roles
     *
     * @author Jhoan Sebastian Chilito S. - Feb. 09 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getRolesPreviousStudies() {

        /*
        $previousStudies = PcPreviousStudies::select(['type', 'users_id'])
        ->with(['users:id', 'users.roles:id,name'])
        ->get()
        ->map(function($studie) {
            // Obtiene el rol del usuario que pertenece al proceso contractual
            $studie->role = $studie->users->roles->filter(function($rol) {
                return $this->isPreviousStudieRoleName($rol->name);
            })->first()->name;
            // Elimina los datos de usuario
            unset($studie->users);

            return $studie;
        })
        // Agrupa en roles y en tipo de proyecto
        ->groupBy(['role', function ($item) {
            return $item['type'];
        }])
        // Contabiliza por cantidad de tipos de proyectos
        ->map(function($item) {
            // Cuenta la cantidad de proyectos de tipo funcionamiento
            $item['Funcionamiento'] = count($item['Funcionamiento']);
            // Cuenta la cantidad de proyectos de tipo proyecto de inversion
            $item['Proyecto de inversión'] = count($item['Proyecto de inversión']);

            return $item;
        })
        ->toArray();

        return $this->sendResponse($previousStudies, trans('msg_success_update'));*/

        $roles = config('contractual_process.pc_roles_studies_previous_states');
        $rolesPreviousStudies = array();
        foreach ($roles as $key => $value) {
            $previousStudie = PcPreviousStudies::groupBy('state')
            ->select('state',
                DB::raw('count(*) as total,
                 COUNT(IF(type = "Funcionamiento", 1, NULL)) as fun_qty,
                 COUNT(IF(type = "Proyecto de inversión", 1, NULL)) as inv_qty,
                 COUNT(IF(type = "Funcionamiento e inversión", 1, NULL)) as fun_inv_qty',
                )
            )->whereIn('state', [$value->states])->get()->first();

           if($previousStudie){
                $previousStudie->name_role = $value->name;
                $rolesPreviousStudies[] = $previousStudie;

           }else{

        
                //crea objeto si no existen registros por estado en la BD
                $previousStudiesObj = new \stdClass();
                $previousStudiesObj->state = $value->id;
                $previousStudiesObj->total = "0";
                $previousStudiesObj->fun_qty = "0";
                $previousStudiesObj->inv_qty = "0";
                $previousStudiesObj->fun_inv_qty = "0";
                $previousStudiesObj->name_role = $value->name;
                $rolesPreviousStudies[] = $previousStudiesObj;

           }

        }
      
        //dd($rolesPreviousStudies);
        return $this->sendResponse($rolesPreviousStudies, trans('msg_success_update'));

    }

    /**
     * Valida si es un rol que participa en los estudios previos
     */
    public function isPreviousStudieRoleName($roleName): bool {
        // Roles de estudios previos
        $roles = config('contractual_process.pc_roles_studies_previous');
        // Recorre la lista de roles de estudios previos
        for ($i = 0; $i < count($roles); $i++) {
            // Valida que sea el mismo nomnbre del rol
            if ($roles[$i] == $roleName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Obtiene los estados de estudios previos
     *
     * @author Jhoan Sebastian Chilito S. - Feb. 08 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getStatesPreviousStudies() {

        /*$previousStudies = PcPreviousStudies::select(['state', 'type'])
        ->get()
        ->groupBy(['state_name', function ($item) {
            return $item['type'];
        }])
        // Contabiliza por cantidad de tipos de proyectos
        ->map(function($item) {
            // Cuenta la cantidad de proyectos de tipo funcionamiento
            $item['Funcionamiento'] = count($item['Funcionamiento']);
            // Cuenta la cantidad de proyectos de tipo proyecto de inversion
            $item['Proyecto de inversión'] = count($item['Proyecto de inversión']);

            return $item;
        })
        ->toArray();
        dd($previousStudies);
        */

        $previousStudies = array();
        $states = config('contractual_process.pc_studies_previous');
        foreach ($states as $key => $value) {

           $previousStudiesBd = PcPreviousStudies::groupBy('state')
           ->select('state',
               DB::raw('count(*) as total,
                COUNT(IF(type = "Funcionamiento", 1, NULL)) as fun_qty,
                COUNT(IF(type = "Proyecto de inversión", 1, NULL)) as inv_qty,
                COUNT(IF(type = "Funcionamiento e inversión", 1, NULL)) as fun_inv_qty'
               )
           )->where("state",$value->id)->get()->first();

           if($previousStudiesBd){

            $previousStudies[]  = $previousStudiesBd;

           }else{
                //crea objeto si no existen registros por estado en la BD
                $previousStudiesObj = new \stdClass();
                $previousStudiesObj->state = $value->id;
                $previousStudiesObj->state_name = $value->name;
                $previousStudiesObj->inv_qty = "0";
                $previousStudiesObj->fun_qty = "0";
                $previousStudiesObj->fun_inv_qty = "0";
                $previousStudiesObj->total = "0";
                $previousStudiesObj->state_colour = $value->colour;
                $previousStudies[]  = $previousStudiesObj;

           }
        }
        
        return $this->sendResponse($previousStudies, trans('msg_success_update'));
    }

}
