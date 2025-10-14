<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\RequestExport;
use Modules\ContractualProcess\Http\Requests\CreatePcPreviousStudiesRequest;
use Modules\ContractualProcess\Http\Requests\UpdatePcPreviousStudiesRequest;
use Modules\ContractualProcess\Repositories\PcPreviousStudiesRepository;
use Modules\ContractualProcess\Repositories\PcPreviousStudiesNewsRepository;
use Modules\ContractualProcess\Repositories\PcPreviousStudiesHistoryRepository;
use Modules\ContractualProcess\Repositories\PcPreviousStudiesRadicationRepository;
use Modules\ContractualProcess\Models\ProcessLeaders;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Illuminate\Support\Facades\Auth;
use Modules\Intranet\Models\Dependency;
use Modules\ContractualProcess\Models\PcPreviousStudiesTipification;
use Modules\ContractualProcess\Models\PcPreviousStudiesTipificationHistory;

use Modules\ContractualProcess\Models\PcPreviousStudies;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\User;
use Modules\ContractualProcess\Models\Need;
use Modules\ContractualProcess\Models\InvestmentTechnicalSheet;
use Modules\ContractualProcess\Models\PreviousStudiesInvestmentSheets;
use Modules\ContractualProcess\Models\FunctioningNeed;


/**
 * DEscripcion de la clase
 *
 * @author Erika Johana Gonzalez. Ene. 20 - 2021
 * @version 1.0.0
 */
class PcPreviousStudiesController extends AppBaseController {

    /** @var  PcPreviousStudiesRepository */
    private $pcPreviousStudiesRepository;

    /** @var  PcPreviousStudiesNewsRepository */
    private $pcPreviousStudiesNewsRepository;

    /** @var  PcPreviousStudiesHistoryRepository */
    private $pcPreviousStudiesHistoryRepository;

    /** @var  PcPreviousStudiesRadicationRepository */
    private $pcPreviousStudiesRadicationRepository;


    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     */
    public function __construct(PcPreviousStudiesRadicationRepository $pcPreviousStudiesRadicationRepo, PcPreviousStudiesRepository $pcPreviousStudiesRepo, PcPreviousStudiesNewsRepository $pcPreviousStudiesNewsRepo, PcPreviousStudiesHistoryRepository $pcPreviousStudiesHistoryRepo) {
        $this->pcPreviousStudiesRepository = $pcPreviousStudiesRepo;
        $this->pcPreviousStudiesNewsRepository = $pcPreviousStudiesNewsRepo;
        $this->pcPreviousStudiesHistoryRepository = $pcPreviousStudiesHistoryRepo;
        $this->pcPreviousStudiesRadicationRepository = $pcPreviousStudiesRadicationRepo;
    }

    /**
     * Muestra la vista para el CRUD de PcPreviousStudies.
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {


        if ( !empty ($request['f'])) {
            $filter = base64_decode($request['f']);
            return view('contractual_process::pc_previous_studies.index')->with('filter', $filter);
        }
        return view('contractual_process::pc_previous_studies.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {

        // Array con las relaciones que se van a utilizar en todo el All.
        $relations = ['pcPreviousStudiesTipifications','pcPreviousStudiesDocuments','pcPreviousStudiesNews','pcPreviousStudiesHistory','InvestmentSheets','functioningNeeds'];

        //Validación por cada rol que existe en el sistema.
        //Rol PC Asistente de gerencia
        if(Auth::user()->hasRole('PC Asistente de gerencia')){

            $pc_previous_studies = PcPreviousStudies::with($relations)->latest()->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));
        }
        //Rol PC Jefe de jurídica
        else if(Auth::user()->hasRole('PC Jefe de jurídica')){

            $pc_previous_studies = PcPreviousStudies::with($relations)->where("state","6")->orwhere("state","10")->orwhere("state","11")->orwhere("state","13")->orwhere("state","15")->orwhere("state","25")->latest()->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));

        }
        //Rol PC Revisor de jurídico
        else if(Auth::user()->hasRole('PC Revisor de jurídico')){

            $pc_previous_studies = PcPreviousStudies::with($relations)->where("state","7")->orwhere("state","12")->orwhere("state","18")->orwhere("state","21")->orwhere("state","22")->orwhere("state","24")->latest()->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));

        }
        //Rol PC Gestor planeación
        else if(Auth::user()->hasRole('PC Gestor planeación')){
            $pc_previous_studies = PcPreviousStudies::with($relations)->where("state","3")->latest()->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));

        }
        //Rol PC Gestor presupuesto
        else if(Auth::user()->hasRole('PC Gestor presupuesto')){

            $pc_previous_studies = PcPreviousStudies::with($relations)->where("state","4")->orwhere("state","26")->latest()->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));


        }
        //Rol PC Gestor de recursos
        else if(Auth::user()->hasRole('PC Gestor de recursos')){
            $pc_previous_studies = PcPreviousStudies::with($relations)->where("state","5")->latest()->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));
        }
        //Rol PC Gerente
        else if(Auth::user()->hasRole('PC Gerente')){

            // Consulta generica de estudios previos
            $query = PcPreviousStudies::with($relations)->latest();

            // Si desde el tablero de control viene con F1, se filtra por tipo= funcionamiento
            if ($request['f'] == 'F1') {
                $query->where('type','Funcionamiento');
            }
             // Si desde el tablero de control viene con F2, se filtra por tipo= Proyecto de inversión
            else if ($request['f'] == 'F2') {
                $query->where('type','Proyecto de inversión');
            }
             // Si desde el tablero de control viene con F3, se filtra por tipo= Funcionamiento e inversión
            else if ($request['f'] == 'F3') {
                $query->where('type','Funcionamiento e inversión');
            }

            // Guarda en la variable  $pc_previous_studies, el query que se haya construido, con filtro o sin filtro.
            $pc_previous_studies = $query->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));

        }

        else if(Auth::user()->hasRole('PC Líder de proceso')){
            $pc_previous_studies = PcPreviousStudies::with($relations)->where("users_id",Auth::user()->id)->latest()->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));
        }

         //Rol PC tesorero
        else if(Auth::user()->hasRole('PC tesorero')){
            $pc_previous_studies = PcPreviousStudies::with($relations)->where("state","22")->where("invitation_type","Invitación simplificada o contratación directa")->latest()->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));
        }

        //Rol PC jurídica especializado 3
        else if(Auth::user()->hasRole('PC jurídica especializado 3')){
            $pc_previous_studies = PcPreviousStudies::with($relations)->where("state","22")->where("invitation_type","Invitación simplificada o contratación directa")->latest()->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));
        }

            //Rol PC director financiero
        else if(Auth::user()->hasRole('PC director financiero')){
            $pc_previous_studies = PcPreviousStudies::with($relations)->where("state","22")->where("invitation_type","Invitación abreviada, detallada o pública")->latest()->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));
        }


            //Rol PC director financiero
            else if(Auth::user()->hasRole('PC director jurídico')){
            $pc_previous_studies = PcPreviousStudies::with($relations)->where("state","22")->where("invitation_type","Invitación abreviada, detallada o pública")->latest()->get()->toArray();

            // Recorre recorre las encuestas del usuario
            for ($i=0; $i < count($pc_previous_studies) ; $i++) {
                foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
                    $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
                    $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

                }
            }
            return $this->sendResponse($pc_previous_studies, trans('data_obtained_successfully'));
        }

    }

    /**
         * Obtine usuarios segun el rol especificado en el parametro.
         *
         * @author Leonardo Fabio Herrera 22 de mayo 2024
         * @version 1.0.0
         *
         * @param string  $rol
         * @return Response
     */
    public function getUserByRol(Request $request, string $rol): array
    {
        // Obtiene la consulta de búsqueda del parámetro de la solicitud
        $query = $request->input('query');

        // Consulta los usuarios con relaciones cargadas y aplica los siguientes filtros:
        // 1. Busca usuarios cuyo nombre coincida con la consulta (búsqueda parcial)
        // 2. Filtra usuarios que tengan el rol especificado
        // 3. Ordena los resultados por fecha de creación descendente
        $users = User::with(['positions', 'dependencies', 'roles', 'workGroups', 'usersHistory'])
            ->where('name', 'like', '%' . $query . '%')
            ->whereHas('roles', function ($query) use ($rol) {
                $query->where('name', $rol);
            })
            ->latest()
            ->get();

        // Envía una respuesta con los usuarios encontrados y un mensaje de éxito
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    public function getNeedsLeader(Request $request) {
        // $needs = Need::with(['paaCalls', 'processLeaders', 'functioningNeeds', 'investmentTechnicalSheets'])
        // ->when($request, function ($query) use($request) {
        //     // Valida si existe un id de una convocatoria
        //     if (!empty($request['pc_paa_calls_id'])) {
        //         return $query->where('pc_paa_calls_id', $request['pc_paa_calls_id']);
        //     }
        // })
        // ->whereHas('paaCalls', function($query) {
        //     $query->where('deleted_at', '=', null);
        // })
        // ->whereHas('processLeaders', function($query) {
        //     // Valida cuando el rol es un lider de proceso
        //     if (Auth::user()->hasRole('PC Líder de proceso')) {
        //         $query->where('users_id', Auth::user()->id);
        //     }
        // })
        // ->latest()->get();
        $userId = isset($request['id_leaders']) ? $request['id_leaders'] : Auth::user()->id;
        $process = ProcessLeaders::where('users_id', $userId)->get();


        return $this->sendResponse($process->toArray(), trans('data_obtained_successfully'));

    }
    public function getNeedsSheetLeader(Request $request) {

        $process = $request->id_proccess;
        $query =$request->input('query');
        $sheets = InvestmentTechnicalSheet::with(['nameProjects'])->where("pc_needs_id","=",$process)->where('description_problem_need','like','%'.$query.'%')->latest()->get();
        return $this->sendResponse($sheets->toArray(), trans('data_obtained_successfully'));

    }

    public function getNeedsFunctioning(Request $request) {

        $process = $request->id_proccess;
        $query =$request->input('query');

        $functioningNeeds = FunctioningNeed::where('pc_needs_id', $process)->where('description','like','%'.$query.'%')->get();
        return $this->sendResponse($functioningNeeds->toArray(), trans('data_obtained_successfully'));

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @param CreatePcPreviousStudiesRequest $request
     *
     * @return Response
     */
    public function store(CreatePcPreviousStudiesRequest $request) {

        $input = $request->all();
        //organizational_unit, obtiene la dependencia a la que pertenece el usuario que esta creando el registro
       // $dependecy = Dependency::find(Auth::user()->id_dependencia);
       // $input['organizational_unit'] = $dependecy->nombre;

       // $dependecy = Need::find($input['process']);
        $process = ProcessLeaders :: find($input['process']);
        $input['organizational_unit'] = $process->name_process;

        $input['date_project'] = date("Y-m-d");

        //Obtiene el id del estado
        $input['state']  = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', 'En elaboración')->id;

        //Campos boolean
        $input['obligation_principal_documentation'] = $this->toBoolean($input['obligation_principal_documentation']);
        $input['situation_estates_public'] = $this->toBoolean($input['situation_estates_public']);
        $input['situation_estates_private'] = $this->toBoolean($input['situation_estates_private']);
        $input['solution_servitude'] = $this->toBoolean($input['solution_servitude']);
        $input['solution_owner'] = $this->toBoolean($input['solution_owner']);
        $input['process_licenses_environment'] = $this->toBoolean($input['process_licenses_environment']);
        $input['process_licenses_beach'] = $this->toBoolean($input['process_licenses_beach']);
        $input['process_licenses_forestal'] = $this->toBoolean($input['process_licenses_forestal']);
        $input['process_licenses_guadua'] = $this->toBoolean($input['process_licenses_guadua']);
        $input['process_licenses_tree'] = $this->toBoolean($input['process_licenses_tree']);
        $input['process_licenses_road'] = $this->toBoolean($input['process_licenses_road']);
        $input['process_licenses_demolition'] = $this->toBoolean($input['process_licenses_demolition']);
        $input['process_licenses_tree_urban'] = $this->toBoolean($input['process_licenses_tree_urban']);

        //Datos del usuario que esta creando el registro
        $input['leaders_name'] = Auth::user()->name;
        $input['leaders_id'] = Auth::user()->id;
        $input['users_id'] = Auth::user()->id;

        $pcPreviousStudies = $this->pcPreviousStudiesRepository->create($input);

        //Crea el registro del historial
        $input['pc_previous_studies_id'] = $pcPreviousStudies->id;
        $input['users_name'] = Auth::user()->name;
        $pcPreviousStudiesH = $this->pcPreviousStudiesHistoryRepository->create($input);
        $pcPreviousStudies->pcPreviousStudiesHistory;

        //Crea la tipificacion del estudio previo
        if (!empty($input['pc_previous_studies_tipifications'])) {
            $syncData = array();
            foreach($input['pc_previous_studies_tipifications'] as $option){
                $arrayTipification = json_decode($option);
                $tipication = PcPreviousStudiesTipification::create([
                    'type_danger' => $arrayTipification->type_danger,
                    'danger' => $arrayTipification->danger,
                    'effect' => $arrayTipification->effect,
                    'probability' => $arrayTipification->probability,
                    'impact' => $arrayTipification->impact,
                    'allocation_danger' => $arrayTipification->allocation_danger,
                    'pc_previous_studies_id' => $pcPreviousStudies->id
                  ]);

                $tipicationHistory = PcPreviousStudiesTipificationHistory::create([
                'type_danger' => $arrayTipification->type_danger,
                'danger' => $arrayTipification->danger,
                'effect' => $arrayTipification->effect,
                'probability' => $arrayTipification->probability,
                'impact' => $arrayTipification->impact,
                'allocation_danger' => $arrayTipification->allocation_danger,
                'pc_previous_studies_h_id' => $pcPreviousStudiesH->id
                ]);
            }
        }
       $pcPreviousStudies->pcPreviousStudiesTipifications;


        // // Valida si viene areas de influencia para asignar
        if (!empty($input['projects'])) {
            // Recorre las areas de influencia de la lista dinamica
            foreach($input['projects'] as $option){
                // Inserta relacion con areas de influencia
                $project = PreviousStudiesInvestmentSheets::create([
                    'pc_investment_technical_sheets_id'   => $option,
                    'pc_previous_studies_id' => $pcPreviousStudies->id
                ]);
            }
            $sheets = $pcPreviousStudies->InvestmentSheets;
            $projectsTemp = array();
            $projectsListTemp = array();

            // Recorre recorre las encuestas del usuario
                foreach ($sheets as  $value) {
                    $projectsTemp[] = $value['pc_investment_technical_sheets_id'];
                    $projectsListTemp[] = $value['pcInvestmentTechnicalSheets'];

                }
                $pcPreviousStudies->projects = $projectsTemp;
                $pcPreviousStudies->projects_list = $projectsListTemp;

        }

        if($pcPreviousStudies->type=='Funcionamiento'){
            $pcPreviousStudies->functioningNeeds;
        }


        //Crea novedad
        $inputNew["observation"]="Creación del estudio previo";
        $inputNew["state"]=$input["state"];
        $inputNew["pc_previous_studies_id"]=$pcPreviousStudies->id;
        $inputNew["user_name"]=Auth::user()->name;
        $pcPreviousStudiesNews = $this->pcPreviousStudiesNewsRepository->create($inputNew);
        $pcPreviousStudies->pcPreviousStudiesNews;

        return $this->sendResponse($pcPreviousStudies->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePcPreviousStudiesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePcPreviousStudiesRequest $request) {

        $input = $request->all();
        $pcPreviousStudies = $this->pcPreviousStudiesRepository->find($id);

        //Campos boolean del estudio previo
        $input['obligation_principal_documentation'] = $this->toBoolean($input['obligation_principal_documentation']);
        $input['situation_estates_public'] = $this->toBoolean($input['situation_estates_public']);
        $input['situation_estates_private'] = $this->toBoolean($input['situation_estates_private']);
        $input['solution_servitude'] = $this->toBoolean($input['solution_servitude']);
        $input['solution_owner'] = $this->toBoolean($input['solution_owner']);
        $input['process_licenses_environment'] = $this->toBoolean($input['process_licenses_environment']);
        $input['process_licenses_beach'] = $this->toBoolean($input['process_licenses_beach']);
        $input['process_licenses_forestal'] = $this->toBoolean($input['process_licenses_forestal']);
        $input['process_licenses_guadua'] = $this->toBoolean($input['process_licenses_guadua']);
        $input['process_licenses_tree'] = $this->toBoolean($input['process_licenses_tree']);
        $input['process_licenses_road'] = $this->toBoolean($input['process_licenses_road']);
        $input['process_licenses_demolition'] = $this->toBoolean($input['process_licenses_demolition']);
        $input['process_licenses_tree_urban'] = $this->toBoolean($input['process_licenses_tree_urban']);

        if (empty($pcPreviousStudies)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $pcPreviousStudies = $this->pcPreviousStudiesRepository->update($input, $id);

        //Crea historial
        $input['pc_previous_studies_id'] = $pcPreviousStudies->id;
        $input['users_name'] = Auth::user()->name;
        $pcPreviousStudiesH = $this->pcPreviousStudiesHistoryRepository->create($input);
        $pcPreviousStudies->pcPreviousStudiesHistory;

        //Crea tipificacion del estudio previo
        if (!empty($input['pc_previous_studies_tipifications'])) {

            $syncData = array();
            PcPreviousStudiesTipification::where('pc_previous_studies_id', $pcPreviousStudies->id)->delete();

            foreach($input['pc_previous_studies_tipifications'] as $option){

                $arrayTipification = json_decode($option);
                $tipication = PcPreviousStudiesTipification::create([
                    'type_danger' => $arrayTipification->type_danger,
                    'danger' => $arrayTipification->danger,
                    'effect' => $arrayTipification->effect,
                    'probability' => $arrayTipification->probability,
                    'impact' => $arrayTipification->impact,
                    'allocation_danger' => $arrayTipification->allocation_danger,
                    'pc_previous_studies_id' => $pcPreviousStudies->id
                    ]);

                $tipicationHistory = PcPreviousStudiesTipificationHistory::create([
                    'type_danger' => $arrayTipification->type_danger,
                    'danger' => $arrayTipification->danger,
                    'effect' => $arrayTipification->effect,
                    'probability' => $arrayTipification->probability,
                    'impact' => $arrayTipification->impact,
                    'allocation_danger' => $arrayTipification->allocation_danger,
                    'pc_previous_studies_h_id' => $pcPreviousStudiesH->id
                    ]);
            }

        }
        $pcPreviousStudies->pcPreviousStudiesTipifications;



        // // Valida si viene areas de influencia para asignar
        if (!empty($input['projects'])) {
            PreviousStudiesInvestmentSheets::where('pc_previous_studies_id', $pcPreviousStudies->id)->delete();

            // Recorre las areas de influencia de la lista dinamica
            foreach($input['projects'] as $option){

                // Inserta relacion con areas deinfluencia
                $project = PreviousStudiesInvestmentSheets::create([
                    'pc_investment_technical_sheets_id'   => $option,
                    'pc_previous_studies_id' => $pcPreviousStudies->id
                ]);
            }
            $pcPreviousStudies->InvestmentSheets;

        }

        if($pcPreviousStudies->type=='Funcionamiento'){
            $pcPreviousStudies->functioningNeeds;
        }


        return $this->sendResponse($pcPreviousStudies->toArray(), trans('msg_success_update'));

    }

     /**
     * Actualiza un registro especifico para cambiar estado.
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePcPreviousStudiesRequest $request
     *
     * @return Response
     */
    public function sendStudie(UpdatePcPreviousStudiesRequest $request) {

        $input = $request->all();
        $id = $input["id"];

        //Campos boolean del estudio previo
        $input['obligation_principal_documentation'] = $this->toBoolean($input['obligation_principal_documentation']);
        $input['situation_estates_public'] = $this->toBoolean($input['situation_estates_public']);
        $input['situation_estates_private'] = $this->toBoolean($input['situation_estates_private']);
        $input['solution_servitude'] = $this->toBoolean($input['solution_servitude']);
        $input['solution_owner'] = $this->toBoolean($input['solution_owner']);
        $input['process_licenses_environment'] = $this->toBoolean($input['process_licenses_environment']);
        $input['process_licenses_beach'] = $this->toBoolean($input['process_licenses_beach']);
        $input['process_licenses_forestal'] = $this->toBoolean($input['process_licenses_forestal']);
        $input['process_licenses_guadua'] = $this->toBoolean($input['process_licenses_guadua']);
        $input['process_licenses_tree'] = $this->toBoolean($input['process_licenses_tree']);
        $input['process_licenses_road'] = $this->toBoolean($input['process_licenses_road']);
        $input['process_licenses_demolition'] = $this->toBoolean($input['process_licenses_demolition']);
        $input['process_licenses_tree_urban'] = $this->toBoolean($input['process_licenses_tree_urban']);

        $pcPreviousStudies = $this->pcPreviousStudiesRepository->find($id);
        //**Estados de un estudio previo.

        // Validad si el estado del estudio previo es En elaboración
        if($input["state"] == 1 || $input["state"] == 17) {

            $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', 'En revisión por parte de Asistente de gerencia')->id;

            //obtiene usuarios con el rol especificado
            $users = User::role('PC Asistente de gerencia')->get();
            $emails = array();
            foreach ($users as $user) {
                $emails[] = $user->email;
            }
            //dd($emails);
            $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_revision_asistente";
            $pcPreviousStudies["title"] = "En revisión por parte de Asistente de gerencia";
            // Envia el correo
            $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

        }
        // En revisión por parte de Asistente de gerencia
        else if($input["state"] == 2) {

            if($input["type_send"]=="Verificación de la ficha en Planeación corporativa") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene usuarios con el rol especificado
                $users = User::role('PC Gestor planeación')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_revision_planeacion";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia el correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else if($input["type_send"]=="Gestionando CDP por parte de presupuesto") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene usuarios con el rol especificado
                $users = User::role('PC Gestor presupuesto')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_presupuesto";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia el correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else {
                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario lider
                $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_devuelto_lider";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
        }
        // Verificación de la ficha en Planeación corporativa
        else if($input["state"] == 3) {

            if($input["type_send"]=="Gestionando CDP por parte de presupuesto") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene usuarios con el rol especificado
                $users = User::role('PC Gestor presupuesto')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_presupuesto";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia el correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else {
                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario lider
                $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_devuelto_lider";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
        }
        // Gestionando CDP por parte de presupuesto
        else if($input["state"] == 4) {

            if($input["type_send"]=="Gestionando Plan Anual de Adquisiciones por parte de Gestión de Recursos") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene usuarios con el rol especificado
                $users = User::role('PC Gestor de recursos')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_recursos";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia el correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else {
                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario lider
                $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_devuelto_lider";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
        }
        // Gestionando Plan Anual de Adquisiciones por parte de Gestión de Recursos"
        else if($input["state"] == 5 || $input["state"] == 20 ) {

            if($input["type_send"]=="Asignación de abogado") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene usuarios con el rol especificado
                $users = User::role('PC Jefe de jurídica')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_jefe_asignar_abogado";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else if($input["type_send"]=="Revisando plan anual de adquisiciones") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene usuarios con el rol especificado
                $users = User::role('PC Asistente de gerencia')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_asistente_plan_anual";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else {
                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario lider
                $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_devuelto_lider";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
        }
        // Asignación de abogado
        else if($input["state"] == 6) {

            if($input["type_send"]=="Gestionando Reglas por parte de Jurídica") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario abogado
                $user = User::where('id', $input["lawyer"])->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_abogado_reglas";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;
                $pcPreviousStudies["nameLawyer"] = $user->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else {
                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario lider
                $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_devuelto_lider";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
        }
        //Gestionando Reglas por parte de Jurídica
        else if($input["state"] == 7) {

            if($input["type_send"]=="Gestionando invitación por parte de Asistente de Gerencia") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene usuarios con el rol especificado
                $users = User::role('PC Asistente de gerencia')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_asistente_gestionar_invitacion";
                $pcPreviousStudies["title"] = $input["type_send"];

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else {
                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario lider
                $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_devuelto_lider";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
        }
        //Gestionando invitación por parte de Asistente de Gerencia
        else if($input["state"] == 8) {

               $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', "Invitación generada")->id;

               $pcPreviousStudies["title"] = "Invitación generada";

                // Envia correo
                //$this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");
        }
        // Invitación generada
        else if($input["state"] == 9) {

            if($input["type_send"]=="Evaluando propuestas") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene usuarios con el rol especificado
                $users = User::role('PC Jefe de jurídica')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_jefe_revision";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else {
                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario lider
                $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_devuelto_lider";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
        }
        // Evaluando propuestas - Revisión de jurídica con expediente
        else if($input["state"] == 11) {

            if($input["type_send"]=="Elaborando minuta del contrato") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario abogado
                $user = User::where('id', $input["lawyer"])->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_abogado_minuta";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;
                $pcPreviousStudies["nameLawyer"] = $user->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else if($input["type_send"]=="Evaluando las propuestas de todos") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario abogado
                $user = User::where('id', $input["lawyer"])->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_abogado_evaluar_propuesta";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;
                $pcPreviousStudies["nameLawyer"] = $user->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else {
                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario lider
                $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_devuelto_lider";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
        }
        // Elaborando minuta del contrato
        else if($input["state"] == 12 || $input["state"] == 18) {

            if($input["type_send"]=="Revisando minuta del contrato") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene usuarios con el rol especificado
                $users = User::role('PC Jefe de jurídica')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.jefe_revisar_minuta";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else {
                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario lider
                $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_devuelto_lider";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
        }
        // Revisando minuta del contrato
        else if($input["state"] == 13) {

            if($input["type_send"]=="Revisión y firma del contrato") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene usuarios con el rol especificado
                $users = User::role('PC Gerente')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_gerente_revision";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia el correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
            else {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario abogado
                $user = User::where('id', $input["lawyer"])->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_abogado_minuta";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;
                $pcPreviousStudies["nameLawyer"] = $user->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
        }

        // Revisión y firma del contrato
        else if($input["state"] == 14) {


            if($input["type_send"]=="Solicitando CRP") {

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene usuarios con el rol especificado
                $users = User::role('PC Jefe de jurídica')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.emaill_jefe_juridica_crp";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }

        }
         // Revisión y aprobacion de todos
        else if($input["state"] == 21 || $input["state"] == 24) {
            $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;
            if($input["type_send"]=="Pendiente de visto bueno") {
                $input["invitation_type"]=$input["type_welcome"];
                $input["proposed_status"]="Adjudicada";
                if(  $input["type_welcome"] == "Invitación simplificada o contratación directa"){

                    $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                    $emails = $user->email;
                    $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_lider_proceso_evalua_propuesta";
                    $pcPreviousStudies["title"] = $input["type_send"];
                    $pcPreviousStudies["userSend"] = Auth::user()->name;
                    // Envia correo
                    $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

                    //obtiene usuarios con el rol especificado
                    $users = User::role('PC tesorero')->get();
                    $emails = array();
                    foreach ($users as $user) {
                        $emails[] = $user->email;
                    }
                    $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_lider_proceso_evalua_propuesta";
                    $pcPreviousStudies["title"] = $input["type_send"];
                    $pcPreviousStudies["userSend"] = Auth::user()->name;
                    // Envia el correo
                    $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

                    //obtiene usuarios con el rol especificado
                    $users = User::role('PC jurídica especializado 3')->get();
                    $emails = array();
                    foreach ($users as $user) {
                        $emails[] = $user->email;
                    }
                    $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_lider_proceso_evalua_propuesta";
                    $pcPreviousStudies["title"] = $input["type_send"];
                    $pcPreviousStudies["userSend"] = Auth::user()->name;
                    // Envia el correo
                    $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

                }else{
                    $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                    $emails = $user->email;

                    $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_lider_proceso_evalua_propuesta";
                    $pcPreviousStudies["title"] = $input["type_send"];
                    $pcPreviousStudies["userSend"] = Auth::user()->name;

                    // Envia correo
                    $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

                     //obtiene usuarios con el rol especificado
                    $users = User::role('PC director financiero')->get();
                    $emails = array();
                    foreach ($users as $user) {
                        $emails[] = $user->email;
                    }

                    $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_lider_proceso_evalua_propuesta";
                    $pcPreviousStudies["title"] = $input["type_send"];
                    $pcPreviousStudies["userSend"] = Auth::user()->name;

                     // Envia el correo
                    $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

                       //obtiene usuarios con el rol especificado
                    $users = User::role('PC director jurídico')->get();
                    $emails = array();
                    foreach ($users as $user) {
                        $emails[] = $user->email;
                    }

                    $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_lider_proceso_evalua_propuesta";
                    $pcPreviousStudies["title"] = $input["type_send"];
                    $pcPreviousStudies["userSend"] = Auth::user()->name;

                      // Envia el correo
                    $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");


                }

            }else{
                $input["proposed_status"]="Desierta";
                //obtiene email del usuario lider
                $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_lider_proceso_finaliza";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");
            }

        }
          // Revisión y aprobacion de todos
        else if($input["state"] == 22) {


            //El tipo de respuesta si es visto bueno ingresa
            if($input['type_send']=='Visto bueno'){
                //Mira si el otro tipo de respuesta es incvitacion simplificada para saber que usuarios tiene que darle visto bueno
                if(  $input["invitation_type"] == "Invitación simplificada o contratación directa"){
                    //se verifica el rol del usuario en sesion y se le edita la variable
                    if(Auth::user()->hasRole('PC tesorero')){
                        $input["approval_treasurer"]=1;
                    }
                    if(Auth::user()->hasRole('PC Líder de proceso')){
                        $input["approval_leader"]=1;

                    }
                    if(Auth::user()->hasRole('PC jurídica especializado 3')){
                        $input["approval_legal"]=1;
                    }


                    //Si los tres campos de los usuarios tiene algo asignado entra y cambia el estado a elaborando minuta
                    if( isset($input["approval_treasurer"]) &&  isset($input["approval_leader"]) && isset($input["approval_legal"])){

                        $input["type_send"]="Elaborando minuta del contrato";

                        $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                        //obtiene email del usuario abogado
                        $user = User::where('id', $input["lawyer"])->first();
                        $emails = $user->email;

                        $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_abogado_minuta";
                        $pcPreviousStudies["title"] = $input["type_send"];
                        $pcPreviousStudies["userSend"] = Auth::user()->name;
                        $pcPreviousStudies["nameLawyer"] = $user->name;

                        // Envia correo
                        $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

                    }
                    //El else es si no es de invitacion simplificada es de invitacion abreviada
                }else{
                    //se verifica el rol del usuario en sesion y se le edita la variable
                    if(Auth::user()->hasRole('PC director financiero')){
                        $input["approval_financial"]=1;
                    }
                    if(Auth::user()->hasRole('PC Líder de proceso')){
                        $input["approval_leader"]=1;
                    }
                    if(Auth::user()->hasRole('PC director jurídico')){
                        $input["approval_counsel"]=1;
                    }

                    //Si los tres campos de los usuarios tiene algo asignado entra y cambia el estado a elaborando minuta
                    if( isset($input["approval_financial"]) &&  isset($input["approval_leader"]) && isset($input["approval_counsel"])){

                        $input["type_send"]="Elaborando minuta del contrato";

                        $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                        //obtiene email del usuario abogado
                        $user = User::where('id', $input["lawyer"])->first();
                        $emails = $user->email;

                        $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_abogado_minuta";
                        $pcPreviousStudies["title"] = $input["type_send"];
                        $pcPreviousStudies["userSend"] = Auth::user()->name;
                        $pcPreviousStudies["nameLawyer"] = $user->name;

                        // Envia correo
                        $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

                    }

                }
                //Este else es que si no es visto bueno se devuelve al abogado
            }else{

                $input["approval_treasurer"]=null;
                $input["approval_leader"]=null;
                $input["approval_legal"]=null;
                $input["approval_financial"]=null;
                $input["approval_leader"]=null;
                $input["approval_counsel"]=null;

                $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

                //obtiene email del usuario abogado
                $user = User::where('id', $input["lawyer"])->first();
                $emails = $user->email;

                $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_abogado_evaluar_propuesta";
                $pcPreviousStudies["title"] = $input["type_send"];
                $pcPreviousStudies["userSend"] = Auth::user()->name;
                $pcPreviousStudies["nameLawyer"] = $user->name;

                // Envia correo
                $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

            }
        }
        else if($input["state"] == 25) {
            $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

            //obtiene usuarios con el rol especificado
            $users = User::role('PC Gestor presupuesto')->get();
            $emails = array();
            foreach ($users as $user) {
                $emails[] = $user->email;
            }
            $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_presupuesto_crp";
            $pcPreviousStudies["title"] = $input["type_send"];
            $pcPreviousStudies["userSend"] = Auth::user()->name;


            // Envia correo
            $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");
        }

        else if($input["state"] == 26) {


        if($input["type_send"]=="Contrato legalizado") {

            $input["state"] = $this->getObjectOfList(config('contractual_process.pc_studies_previous'), 'name', $input["type_send"])->id;

            //obtiene email del usuario lider
            $user = User::where('id', $pcPreviousStudies->leaders_id)->first();
            $emails = $user->email;

            $pcPreviousStudies["view"] = "contractual_process::pc_previous_studies.emails.email_lider_contrato_legalizado";
            $pcPreviousStudies["title"] = $input["type_send"];
            $pcPreviousStudies["userSend"] = Auth::user()->name;

            // Envia correo
            $this->sendEmail('contractual_process::pc_previous_studies.emails.template', compact('pcPreviousStudies'), $emails, "Proceso contractual - estudios previo");

        }

        }

        if (empty($pcPreviousStudies)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $pcPreviousStudies = $this->pcPreviousStudiesRepository->update($input, $id);

        //crea novedad
        $inputNew["observation"] = $input["observations"];
        $inputNew["state"] = $input["state"];
        $inputNew["pc_previous_studies_id"] = $id;
        $inputNew["user_name"] = Auth::user()->name;
        $DocumentsPensionersNew = $this->pcPreviousStudiesNewsRepository->create($inputNew);
        $pcPreviousStudies->pcPreviousStudiesNews;

        return $this->sendResponse($pcPreviousStudies->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un PcPreviousStudies del almacenamiento
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var PcPreviousStudies $pcPreviousStudies */
        $pcPreviousStudies = $this->pcPreviousStudiesRepository->find($id);

        if (empty($pcPreviousStudies)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $pcPreviousStudies->delete();

        return $this->sendSuccess(trans('msg_success_drop'));

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = 'setting.' . $fileType;
        return Excel::download(new RequestExport('contractual_process::pc_previous_studies.exports.report_excel', $input['data'], 'G'), $fileName);

    }

    /**
     * Genera el documento de la historia laboral
     *
     * @author Erika Johana Gonzalez - Dic. 29 - 2020
     * @version 1.0.0
     */
    public static function generateDocument($idPreviousStudy) {

        $previousStudy = PcPreviousStudies::find($idPreviousStudy);
        $previousStudy->pcPreviousStudiesTipifications;

        $dateExplode = explode("-",$previousStudy->date_project);
        $previousStudy->dateYear = substr($dateExplode[0], -2);;
        $previousStudy->dateMonth = $dateExplode[1];
        $previousStudy->dateDay = $dateExplode[2];

       if($previousStudy->usersLawyer){

        // Obtiene los datos del usuario en joomla
        $userJoomlaLawyer = DB::connection('joomla')
        ->table('users')
        ->join('intranet_usuario', 'intranet_usuario.userid', '=', 'users.id')
        ->select('users.*', 'intranet_usuario.firma', 'intranet_usuario.cargo')
        ->where('users.id', $previousStudy->usersLawyer->user_joomla_id)->first();


        if (!empty($userJoomlaLawyer)) {
            $firmLawyer = "https://intraepa.gov.co/".$userJoomlaLawyer->firma;
            $previousStudy->cargoLawyer =  $userJoomlaLawyer->cargo;

        }else{
            $firmLawyer = "https://intraepa.gov.co/images/com_intranet/usuarios/demo.png";
            $previousStudy->cargoLawyer =  "";

        }
        $previousStudy->firmLawyer = $firmLawyer;


       }
        if($previousStudy->usersBoss && $previousStudy->usersLeader && $previousStudy->usersSubgerente){
        // Obtiene los datos del usuario en joomla
        $userJoomlaBoss = DB::connection('joomla')
        ->table('users')
        ->join('intranet_usuario', 'intranet_usuario.userid', '=', 'users.id')
        ->select('users.*', 'intranet_usuario.firma', 'intranet_usuario.dependencia')
        ->where('users.id', $previousStudy->usersBoss->user_joomla_id)->first();

        // Obtiene los datos del usuario en joomla
        $userJoomlaLeader = DB::connection('joomla')
        ->table('users')
        ->join('intranet_usuario', 'intranet_usuario.userid', '=', 'users.id')
        ->select('users.*', 'intranet_usuario.firma', 'intranet_usuario.dependencia')
        ->where('users.id', $previousStudy->usersLeader->user_joomla_id)->first();

        // Obtiene los datos del usuario en joomla
        $userJoomlaSubgerente = DB::connection('joomla')
        ->table('users')
        ->join('intranet_usuario', 'intranet_usuario.userid', '=', 'users.id')
        ->select('users.*', 'intranet_usuario.firma', 'intranet_usuario.dependencia')
        ->where('users.id', $previousStudy->usersSubgerente->user_joomla_id)->first();


        //dd($userJoomlaLeader);
        if (!empty($userJoomlaBoss)) {
            $firmBoss = "https://intraepa.gov.co/".$userJoomlaBoss->firma;
            $previousStudy->dependencyBoss = $userJoomlaBoss->dependencia;

        }else{
            $firmBoss = "https://intraepa.gov.co/images/com_intranet/usuarios/demo.png";
            $previousStudy->dependencyBoss = "";
        }

        if (!empty($userJoomlaLeader)) {
            $firmLeader = "https://intraepa.gov.co/".$userJoomlaLeader->firma;
            $previousStudy->dependencyLeader = $userJoomlaLeader->dependencia;

        }else{
            $firmLeader = "https://intraepa.gov.co/images/com_intranet/usuarios/demo.png";
            $previousStudy->dependencyLeader = "";

        }

        if (!empty($userJoomlaSubgerente)) {
            $firmSubgerente = "https://intraepa.gov.co/".$userJoomlaSubgerente->firma;
            $previousStudy->dependencySubgerente =  $userJoomlaSubgerente->dependencia;

        }else{
            $firmSubgerente = "https://intraepa.gov.co/images/com_intranet/usuarios/demo.png";
            $previousStudy->dependencySubgerente =  "";

        }

        $previousStudy->firmBoss = $firmBoss;
        $previousStudy->firmLeader = $firmLeader;
        $previousStudy->firmSubgerente = $firmSubgerente;
    }else{
        $previousStudy->firmBoss = "";
        $previousStudy->firmLeader = "";
        $previousStudy->firmSubgerente = "";
        $previousStudy->firmLawyer = "";
        $previousStudy->cargoLawyer =  "";
        $previousStudy->dependencySubgerente =  "";
        $previousStudy->dependencyLeader = "";
        $previousStudy->dependencyBoss = "";
    }

    if($previousStudy->type=='Funcionamiento'){

        $previousStudy->functioningNeeds;

    }else{

        $previousStudy->InvestmentSheets;

    }
    $sheets = $previousStudy->InvestmentSheets;
    $subprograms = array();
    $programs = array();
    $lineprojects = array();
    $projects = array();

    foreach ($sheets as $sheet) {
        $programs[] = $sheet->pcInvestmentTechnicalSheets->program_name;
        $subprograms[] = $sheet->pcInvestmentTechnicalSheets->subprogram_name;
        $lineprojects[] = $sheet->pcInvestmentTechnicalSheets->strategic_line_name;

        $projects[] = $sheet->pcInvestmentTechnicalSheets->nameProjects->name." - ".$sheet->pcInvestmentTechnicalSheets->description_problem_need;
    }

    $previousStudy->project = implode("<br><br>" , $projects);
    $previousStudy->program = implode("<br><br>" , $programs);
    $previousStudy->lineproject = implode("<br><br>" , $lineprojects);
    $previousStudy->subprogram = implode("<br><br>" , $subprograms);

    // // Recorre recorre las encuestas del usuario
    // for ($i=0; $i < count($pc_previous_studies) ; $i++) {
    //     foreach ($pc_previous_studies[$i]['investment_sheets'] as  $value) {
    //         $pc_previous_studies[$i]['projects'][] = $value['pc_investment_technical_sheets_id'];
    //         $pc_previous_studies[$i]['projects_list'][] = $value['pc_investment_technical_sheets'];

    //     }
    // }

     // $filePDF = PDF::loadView('request_user::requests_users.pdf.acceptance', ['data' => $usuario])->setPaper('a4', 'landscape');
    //  $filePDF = PDF::loadView('contractual_process::pc_previous_studies.exports.pdf_estudios', ['data' => $usuario])->setPaper(([0, 0, 612.00, 996.00]));




        // Genera el archivo a base de una plantilla
        $pdf = PDF::loadView('contractual_process::pc_previous_studies.exports.pdf_estudios', ['previousStudy' => $previousStudy])
        ->setPaper(([0, 0, 612.00, 996.00])); // Tamaño legal (oficio) y orientación vertical

    return $pdf->stream();
    }


    /**
     * Visualiza el documento pdf de la historia laboral
     *
     * @author Erika Johana Gonzalez - Dic. 29 - 2020
     * @version 1.0.0
     */
    public function showDocument($idPreviousStudy) {
        return $this->generateDocument($idPreviousStudy)->stream('archivo.pdf');
    }

    /**
     * Envia correo electronico segun los parametros
     *
     * @author Erika Johana Gonzalez - Mazzo. 04 - 2021
     * @version 1.0.0
     */
    public static function sendEmail($view, $data, $emails, $title){

        if(isset($emails)) {
            Mail::send($view, $data, function ($message) use ( $emails, $title){
                $message->from(config('mail.username'), $title);
                $message->subject($title);
                $message->to($emails);
            });
        }
    }

}
