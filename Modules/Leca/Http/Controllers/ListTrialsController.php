<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Http\Requests\CreateListTrialsRequest;
use Modules\Leca\Http\Requests\UpdateListTrialsRequest;
use Modules\Leca\Models\Acidez;
use Modules\Leca\Models\Alcalinidad;
use Modules\Leca\Models\Aluminio;
use Modules\Leca\Models\BacteriasHeterotroficas;
use Modules\Leca\Models\BlancoGeneral;
use Modules\Leca\Models\Blancos;
use Modules\leca\Models\Cadmio;
use Modules\Leca\Models\Calcio;
use Modules\Leca\Models\CarbonoOrganico;
use Modules\Leca\Models\CloroResidual;
use Modules\Leca\Models\Cloruro;
use Modules\Leca\Models\ColiformesTotales;
use Modules\Leca\Models\Color;
use Modules\Leca\Models\Conductividad;
use Modules\Leca\Models\CriticalEquipment;
use Modules\Leca\Models\DurezaTotal;
use Modules\Leca\Models\EnsayoAcidez;
use Modules\Leca\Models\EnsayoAlcalinidad;
use Modules\Leca\Models\EnsayoAluminio;
use Modules\Leca\Models\EnsayoCalcio;
use Modules\Leca\Models\EnsayoCarbonoOrganico;
use Modules\Leca\Models\EnsayoCloro;
use Modules\Leca\Models\EnsayoCloruro;
use Modules\Leca\Models\EnsayoDureza;
use Modules\leca\Models\EnsayoEspectro;
use Modules\Leca\Models\EnsayoFosfato;
use Modules\Leca\Models\EnsayoHierro;
use Modules\Leca\Models\EnsayoNitratos;
use Modules\Leca\Models\EnsayoNitritos;
use Modules\Leca\Models\EscherichiaColi;
use Modules\Leca\Models\Fluoruros;
use Modules\Leca\Models\Fosfatos;
use Modules\leca\Models\Hidrocarburos;
use Modules\Leca\Models\Hierro;
use Modules\Leca\Models\ListTrials;
use Modules\leca\Models\Mercurio;
use Modules\Leca\Models\Nitratos;
use Modules\Leca\Models\Nitritos;
use Modules\Leca\Models\Olor;
use Modules\Leca\Models\Patron;
use Modules\Leca\Models\PatronGeneral;
use Modules\Leca\Models\Ph;
use Modules\leca\Models\Plaguicida;
use Modules\leca\Models\Plomo;
use Modules\Leca\Models\Solidos;
use Modules\Leca\Models\SolidosSecos;
use Modules\Leca\Models\Sulfatos;
use Modules\Leca\Models\SustanciasFlotantes;
use Modules\leca\Models\Trialometanos;
use Modules\Leca\Models\Turbidez;
use Modules\Leca\Repositories\ListTrialsRepository;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Response;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ListTrialsController extends AppBaseController
{

    /** @var  ListTrialsRepository */
    private $listTrialsRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ListTrialsRepository $listTrialsRepo)
    {
        $this->listTrialsRepository = $listTrialsRepo;
    }

    /**
     * Muestra la vista para el CRUD de ListTrials.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {

        $blanco = BlancoGeneral::get()->last();
        $patron = PatronGeneral::get()->last();
        if($patron == null){
            $patron = 0;
        }
        if($blanco == null){
            $blanco = 0;
        }

        return view('leca::list_trials.index', compact('blanco', 'patron'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all()
    {

        $list_trials = ListTrials::with(['lcNitritos', 'lcNitratos', 'lcHierro', 'lcFosfatos', 'lcAluminio', 'lcCloruro', 'lcCloroResidual', 'lcCalcio', 'lcDurezaTotal', 'lcAcidez', 'lcFluoruros', 'lcSulfatos', 'lcAlcalinidad', 'lcPh', 'lcBlancos', 'lcPatron', 'lcColiformesTotales', 'lcEscherichiaColi', 'lcBacteriasHeterotroficas', 'lcColor', 'lcOlor', 'lcConductividad', 'lcSustanciasFlotantes', 'lcCarbonoOrganico', 'lcSolidos', 'lcSolidosSecos', 'lcPlomo', 'lcCadmio', 'lcMercurio', 'lcHidrocarburos', 'lcPlaguicidas', 'lcTrialometanos', 'lcOlor', 'lcConductividad', 'lcSustanciasFlotantes', 'lcTurbidez'])->latest()->get();

        return $this->sendResponse($list_trials->toArray(), trans('data_obtained_successfully'));

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateListTrialsRequest $request
     *
     * @return Response
     */
    public function store(CreateListTrialsRequest $request)
    {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $listTrials = $this->listTrialsRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($listTrials->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ListTrialsController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ListTrialsController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }

    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateListTrialsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateListTrialsRequest $request)
    {

        $input = $request->all();

        /** @var ListTrials $listTrials */
        $listTrials = $this->listTrialsRepository->find($id);

        if (empty($listTrials)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $listTrials = $this->listTrialsRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($listTrials->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ListTrialsController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ListTrialsController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ListTrials del almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {

        /** @var ListTrials $listTrials */
        $listTrials = $this->listTrialsRepository->find($id);

        if (empty($listTrials)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $listTrials->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ListTrialsController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ListTrialsController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request)
    {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time() . '-' . trans('list_trials') . '.' . $fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName);
        }
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 02 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesNitritos(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Nitritos';
            Nitritos::create([
                'lc_list_trials_id' => 10,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Nitritos",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Nitritos';
            Nitritos::create([
                'lc_list_trials_id' => 10,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Nitritos",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }
        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayNitritos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 10;
                $request->name_trials = "Nitritos";
                $request->equipo_critico = $arrayNitritos->equipo_critico;
                $request->identificacion = $arrayNitritos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesNitratos(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Nitratos';
            Nitratos::create([
                'lc_list_trials_id' => 18,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Nitratos",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Nitratos';
            Nitratos::create([
                'lc_list_trials_id' => 18,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Nitratos",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }
        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayNitratos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 18;
                $request->name_trials = "Nitratos";
                $request->equipo_critico = $arrayNitratos->equipo_critico;
                $request->identificacion = $arrayNitratos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesHierro(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            
            $nameTrial = 'Hierro';
            Hierro::create([
                'lc_list_trials_id' => 14,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Hierro",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'decimales' => $request->decimales,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Hierro';
            Hierro::create([
                'lc_list_trials_id' => 14,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Hierro",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayHierro = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 14;
                $request->name_trials = "Hierro";
                $request->equipo_critico = $arrayHierro->equipo_critico;
                $request->identificacion = $arrayHierro->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesFosfatos(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Fosfatos';
            Fosfatos::create([
                'lc_list_trials_id' => 11,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Fosfatos",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Fosfatos';
            Fosfatos::create([
                'lc_list_trials_id' => 11,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Fosfatos",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayFosfatos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 11;
                $request->name_trials = "Fosfatos";
                $request->equipo_critico = $arrayFosfatos->equipo_critico;
                $request->identificacion = $arrayFosfatos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesAluminio(Request $request)
    {
        $input = $request->all();
        $Year = date("Y");
        $input['vigencia'] = $Year;
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Aluminio';
            Aluminio::create([
                'lc_list_trials_id' => 15,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Aluminio",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'lmt_lic' => $request->lmt_lic,
                'lmt_lsc' => $request->lmt_lsc,
                'lmt_error' => $request->lmt_error,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'vigencia' => $request->vigencia,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Aluminio';
            Aluminio::create([
                'lc_list_trials_id' => 15,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Aluminio",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'lmt_lic' => $request->lmt_lic,
                'lmt_lsc' => $request->lmt_lsc,
                'lmt_error' => $request->lmt_error,
                'k_pendiente' => $request->k_pendiente,
                'vigencia' => $request->vigencia,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayFosfatos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 15;
                $request->name_trials = "Aluminio";
                $request->equipo_critico = $arrayFosfatos->equipo_critico;
                $request->identificacion = $arrayFosfatos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesMercurio(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Mercurio';
            Mercurio::create([
                'lc_list_trials_id' => 29,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Mercurio",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Mercurio';
            Mercurio::create([
                'lc_list_trials_id' => 29,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Mercurio",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayFosfatos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 29;
                $request->name_trials = "Mercurio";
                $request->equipo_critico = $arrayFosfatos->equipo_critico;
                $request->identificacion = $arrayFosfatos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesHidrocarburos(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Hidrocarburos';
            Hidrocarburos::create([
                'lc_list_trials_id' => 30,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Hidrocarburos",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Hidrocarburos';
            Hidrocarburos::create([
                'lc_list_trials_id' => 30,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Hidrocarburos",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayFosfatos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 30;
                $request->name_trials = "Hidrocarburos";
                $request->equipo_critico = $arrayFosfatos->equipo_critico;
                $request->identificacion = $arrayFosfatos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesPlaguicida(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Plaguicida';
            Plaguicida::create([
                'lc_list_trials_id' => 31,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Plaguicida",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Plaguicida';
            Plaguicida::create([
                'lc_list_trials_id' => 31,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Plaguicida",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayFosfatos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 31;
                $request->name_trials = "Plaguicida";
                $request->equipo_critico = $arrayFosfatos->equipo_critico;
                $request->identificacion = $arrayFosfatos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesTrialometanos(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Trialometanos';
            Trialometanos::create([
                'lc_list_trials_id' => 32,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Trialometanos",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Trialometanos';
            Trialometanos::create([
                'lc_list_trials_id' => 32,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Trialometanos",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayFosfatos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 32;
                $request->name_trials = "Trialometanos";
                $request->equipo_critico = $arrayFosfatos->equipo_critico;
                $request->identificacion = $arrayFosfatos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesPlomo(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Plomo';
            Plomo::create([
                'lc_list_trials_id' => 27,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Plomo",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Plomo';
            Plomo::create([
                'lc_list_trials_id' => 27,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Plomo",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayFosfatos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 27;
                $request->name_trials = "Plomo";
                $request->equipo_critico = $arrayFosfatos->equipo_critico;
                $request->identificacion = $arrayFosfatos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesPlaguicidas(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Plaguicida';
            Plaguicida::create([
                'lc_list_trials_id' => 27,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Plaguicida",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Plaguicida';
            Plaguicida::create([
                'lc_list_trials_id' => 27,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Plaguicida",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayFosfatos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 27;
                $request->name_trials = "Plomo";
                $request->equipo_critico = $arrayFosfatos->equipo_critico;
                $request->identificacion = $arrayFosfatos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesCadmio(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Cadmio';
            Cadmio::create([
                'lc_list_trials_id' => 28,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Cadmio",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Cadmio';
            Cadmio::create([
                'lc_list_trials_id' => 28,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Cadmio",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayFosfatos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 28;
                $request->name_trials = "Cadmio";
                $request->equipo_critico = $arrayFosfatos->equipo_critico;
                $request->identificacion = $arrayFosfatos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesCloruro(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Cloruro';
            Cloruro::create([
                'lc_list_trials_id' => 8,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'concentracion_agno' => $request->concentracion_agno,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'factor_calculo' => $request->factor_calculo,
                'cloruros' => $request->cloruros,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Cloruro';
            Cloruro::create([
                'lc_list_trials_id' => 8,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'concentracion_agno' => $request->concentracion_agno,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'factor_calculo' => $request->factor_calculo,
                'cloruros' => $request->cloruros,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayCloruro = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 8;
                $request->name_trials = "Cloruro";
                $request->equipo_critico = $arrayCloruro->equipo_critico;
                $request->identificacion = $arrayCloruro->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesCloroResidual(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Cloro Residual';
            CloroResidual::create([
                'lc_list_trials_id' => 16,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'concentracion_fas' => $request->concentracion_fas,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'factor_calculo' => $request->factor_calculo,
                'cloro_residual_desde' => $request->cloro_residual_desde,
                'cloro_residual_hasta' => $request->cloro_residual_hasta,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Cloro Residual';
            CloroResidual::create([
                'lc_list_trials_id' => 16,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'concentracion_fas' => $request->concentracion_fas,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'factor_calculo' => $request->factor_calculo,
                'cloro_residual_desde' => $request->cloro_residual_desde,
                'cloro_residual_hasta' => $request->cloro_residual_hasta,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayCloroResidual = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 16;
                $request->name_trials = "Cloro Residual";
                $request->equipo_critico = $arrayCloroResidual->equipo_critico;
                $request->identificacion = $arrayCloroResidual->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesCalcio(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Calcio';
            Calcio::create([
                'lc_list_trials_id' => 12,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'concentracion' => $request->concentracion,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'factor_calculo' => $request->factor_calculo,
                'calcio' => $request->calcio,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Calcio';
            Calcio::create([
                'lc_list_trials_id' => 12,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'concentracion' => $request->concentracion,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'factor_calculo' => $request->factor_calculo,
                'calcio' => $request->calcio,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayCalcio = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 12;
                $request->name_trials = "Calcio";
                $request->equipo_critico = $arrayCalcio->equipo_critico;
                $request->identificacion = $arrayCalcio->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesDurezaTotal(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Dureza Total';
            DurezaTotal::create([
                'lc_list_trials_id' => 13,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'concentracion' => $request->concentracion,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'factor_calculo' => $request->factor_calculo,
                'dureza_total' => $request->dureza_total,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Dureza Total';
            DurezaTotal::create([
                'lc_list_trials_id' => 13,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'concentracion' => $request->concentracion,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'factor_calculo' => $request->factor_calculo,
                'dureza_total' => $request->dureza_total,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $ArrayDurezaTotal = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 13;
                $request->name_trials = "Dureza Total";
                $request->equipo_critico = $ArrayDurezaTotal->equipo_critico;
                $request->identificacion = $ArrayDurezaTotal->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesAcidez(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Acidez';
            Acidez::create([
                'lc_list_trials_id' => 17,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'nombre_calidad' => $request->nombre_calidad,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'concentracion' => $request->concentracion,
                'factor' => $request->factor,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Acidez';
            Acidez::create([
                'lc_list_trials_id' => 17,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'nombre_calidad' => $request->nombre_calidad,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'concentracion' => $request->concentracion,
                'factor' => $request->factor,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayAcidez = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 17;
                $request->name_trials = "Acidez";
                $request->equipo_critico = $arrayAcidez->equipo_critico;
                $request->identificacion = $arrayAcidez->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesFluoruros(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Fluoruros';
            Fluoruros::create([
                'lc_list_trials_id' => 20,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Fluoruros';
            Fluoruros::create([
                'lc_list_trials_id' => 20,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayFluoruros = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 20;
                $request->name_trials = "Fluoruros";
                $request->equipo_critico = $arrayFluoruros->equipo_critico;
                $request->identificacion = $arrayFluoruros->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesSulfatos(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Sulfatos';
            Sulfatos::create([
                'lc_list_trials_id' => 9,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'pendiente_uno' => $request->pendiente_uno,
                'intercepto_uno' => $request->intercepto_uno,
                'pendiente_dos' => $request->pendiente_dos,
                'intercepto_dos' => $request->intercepto_dos,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'sulfatos' => $request->sulfatos,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Sulfatos';
            Sulfatos::create([
                'lc_list_trials_id' => 9,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'pendiente_uno' => $request->pendiente_uno,
                'intercepto_uno' => $request->intercepto_uno,
                'pendiente_dos' => $request->pendiente_dos,
                'intercepto_dos' => $request->intercepto_dos,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'sulfatos' => $request->sulfatos,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arraySulfatos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 9;
                $request->name_trials = "Sulfatos";
                $request->equipo_critico = $arraySulfatos->equipo_critico;
                $request->identificacion = $arraySulfatos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesAlcalinidad(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Alcalinidad';
            Alcalinidad::create([
                'lc_list_trials_id' => 7,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'patron_esperado' => $request->patron_esperado,
                'patron_esperado_dos' => $request->patron_esperado_dos,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'v_muestra' => $request->v_muestra,
                'alcalinidad_alta' => $request->alcalinidad_alta,
                'factor_alcal_alta' => $request->factor_alcal_alta,
                'alcalinidad_baja' => $request->alcalinidad_baja,
                'factor_alcal_baja' => $request->factor_alcal_baja,
                'curva' => $request->curva,
                'fehca_curva' => $request->fehca_curva,
                'alcalinidad_total' => $request->alcalinidad_total,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Alcalinidad';
            Alcalinidad::create([
                'lc_list_trials_id' => 7,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'patron_esperado' => $request->patron_esperado,
                'patron_esperado_dos' => $request->patron_esperado_dos,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'v_muestra' => $request->v_muestra,
                'alcalinidad_alta' => $request->alcalinidad_alta,
                'factor_alcal_alta' => $request->factor_alcal_alta,
                'alcalinidad_baja' => $request->alcalinidad_baja,
                'factor_alcal_baja' => $request->factor_alcal_baja,
                'curva' => $request->curva,
                'fehca_curva' => $request->fehca_curva,
                'alcalinidad_total' => $request->alcalinidad_total,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayAlcalinidad = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 7;
                $request->name_trials = "Alcalinidad";
                $request->equipo_critico = $arrayAlcalinidad->equipo_critico;
                $request->identificacion = $arrayAlcalinidad->identificacion;
                // $request->patron = $arrayAlcalinidad->patron;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesPh(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'PH';
            Ph::create([
                'lc_list_trials_id' => 5,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'limite_cuantificacion_dos' => $request->limite_cuantificacion_dos,
                'nombre_patron_dos' => $request->nombre_patron_dos,
                'valor_esperado_dos' => $request->valor_esperado_dos,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'fecha_curva' => $request->fecha_curva,
                'pendiente' => $request->pendiente,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'PH';
            Ph::create([
                'lc_list_trials_id' => 5,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'limite_cuantificacion_dos' => $request->limite_cuantificacion_dos,
                'nombre_patron_dos' => $request->nombre_patron_dos,
                'valor_esperado_dos' => $request->valor_esperado_dos,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'fecha_curva' => $request->fecha_curva,
                'pendiente' => $request->pendiente,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayPh = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 5;
                $request->name_trials = "pH";
                $request->equipo_critico = $arrayPh->equipo_critico;
                $request->identificacion = $arrayPh->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesTurbidez(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Turbidez';
            Turbidez::create([
                'lc_list_trials_id' => 4,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'limite_cuantificacion_uno' => $request->limite_cuantificacion_uno,
                'nombre_patron_uno' => $request->nombre_patron_uno,
                'valor_esperador_uno' => $request->valor_esperador_uno,
                'nombre_patron_dos' => $request->nombre_patron_dos,
                'valor_esperado_dos' => $request->valor_esperado_dos,
                'rango_t_ntu_uno' => $request->rango_t_ntu_uno,
                'rango_t_ntu_dos' => $request->rango_t_ntu_dos,
                'rango_t_ntu_tres' => $request->rango_t_ntu_tres,
                'rango_t_ntu_cuatro' => $request->rango_t_ntu_cuatro,
                'rango_t_ntu_cinco' => $request->rango_t_ntu_cinco,
                'rango_t_ntu_seis' => $request->rango_t_ntu_seis,
                'rango_t_ntu_siete' => $request->rango_t_ntu_siete,
                'valor_mas_cer_uno' => $request->valor_mas_cer_uno,
                'valor_mas_cer_dos' => $request->valor_mas_cer_dos,
                'valor_mas_cer_tres' => $request->valor_mas_cer_tres,
                'valor_mas_cer_cuatro' => $request->valor_mas_cer_cuatro,
                'valor_mas_cer_cinco' => $request->valor_mas_cer_cinco,
                'valor_mas_cer_seis' => $request->valor_mas_cer_seis,
                'valor_mas_cer_siete' => $request->valor_mas_cer_siete,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'turbiedad' => $request->turbiedad,
                'fecha_curva' => $request->fecha_curva,
                'pendiente' => $request->pendiente,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Turbidez';
            Turbidez::create([
                'lc_list_trials_id' => 4,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'limite_cuantificacion_uno' => $request->limite_cuantificacion_uno,
                'nombre_patron_uno' => $request->nombre_patron_uno,
                'valor_esperador_uno' => $request->valor_esperador_uno,
                'nombre_patron_dos' => $request->nombre_patron_dos,
                'valor_esperado_dos' => $request->valor_esperado_dos,
                'rango_t_ntu_uno' => $request->rango_t_ntu_uno,
                'rango_t_ntu_dos' => $request->rango_t_ntu_dos,
                'rango_t_ntu_tres' => $request->rango_t_ntu_tres,
                'rango_t_ntu_cuatro' => $request->rango_t_ntu_cuatro,
                'rango_t_ntu_cinco' => $request->rango_t_ntu_cinco,
                'rango_t_ntu_seis' => $request->rango_t_ntu_seis,
                'rango_t_ntu_siete' => $request->rango_t_ntu_siete,
                'valor_mas_cer_uno' => $request->valor_mas_cer_uno,
                'valor_mas_cer_dos' => $request->valor_mas_cer_dos,
                'valor_mas_cer_tres' => $request->valor_mas_cer_tres,
                'valor_mas_cer_cuatro' => $request->valor_mas_cer_cuatro,
                'valor_mas_cer_cinco' => $request->valor_mas_cer_cinco,
                'valor_mas_cer_seis' => $request->valor_mas_cer_seis,
                'valor_mas_cer_siete' => $request->valor_mas_cer_siete,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'turbiedad' => $request->turbiedad,
                'fecha_curva' => $request->fecha_curva,
                'pendiente' => $request->pendiente,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayPh = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 4;
                $request->name_trials = "Turbidez";
                $request->equipo_critico = $arrayPh->equipo_critico;
                $request->identificacion = $arrayPh->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesColiformesTotales(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Coliformes totales';
            ColiformesTotales::create([
                'lc_list_trials_id' => 21,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'processo' => $request->processo,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'tecnica' => $request->tecnica,
                'ensayo' => "Ensayo Coliformes totales",
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Coliformes totales';
            ColiformesTotales::create([
                'lc_list_trials_id' => 21,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'processo' => $request->processo,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'tecnica' => $request->tecnica,
                'ensayo' => "Ensayo Coliformes totales",
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesEscherichiaColi(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Escherichia coli';
            EscherichiaColi::create([
                'lc_list_trials_id' => 22,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'processo' => $request->processo,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'tecnica' => $request->tecnica,
                'ensayo' => "Ensayo de Escherichia coli",
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Escherichia coli';
            EscherichiaColi::create([
                'lc_list_trials_id' => 22,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'processo' => $request->processo,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'tecnica' => $request->tecnica,
                'ensayo' => "Ensayo de Escherichia coli",
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesBacteriasHeterotroficas(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Bacterias heterotróficas (Mesófilos)';
            BacteriasHeterotroficas::create([
                'lc_list_trials_id' => 23,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'processo' => $request->processo,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'tecnica' => $request->tecnica,
                'ensayo' => "Ensayo Bacterias heterotróficas (Mesófilos)",
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Bacterias heterotróficas (Mesófilos)';
            BacteriasHeterotroficas::create([
                'lc_list_trials_id' => 23,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'processo' => $request->processo,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'tecnica' => $request->tecnica,
                'ensayo' => "Ensayo Bacterias heterotróficas (Mesófilos)",
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesColor(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Ensayo de Color';
            Color::create([
                'lc_list_trials_id' => 1,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'fecha_ajuste_curva_uno' => $request->fecha_ajuste_curva_uno,
                'documento_referencia_uno' => $request->documento_referencia_uno,
                'limite_cuantificacion_uno' => $request->limite_cuantificacion_uno,
                'sustancias_flotantes' => $request->sustancias_flotantes,
                'nombre_patron_uno' => $request->nombre_patron_uno,
                'valor_esperador_uno' => $request->valor_esperador_uno,
                'nombre_patron_dos' => $request->nombre_patron_dos,
                'valor_esperado_dos' => $request->valor_esperado_dos,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Ensayo de Color';
            Color::create([
                'lc_list_trials_id' => 1,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'fecha_ajuste_curva_uno' => $request->fecha_ajuste_curva_uno,
                'documento_referencia_uno' => $request->documento_referencia_uno,
                'limite_cuantificacion_uno' => $request->limite_cuantificacion_uno,
                'sustancias_flotantes' => $request->sustancias_flotantes,
                'nombre_patron_uno' => $request->nombre_patron_uno,
                'valor_esperador_uno' => $request->valor_esperador_uno,
                'nombre_patron_dos' => $request->nombre_patron_dos,
                'valor_esperado_dos' => $request->valor_esperado_dos,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayColor = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 1;
                $request->name_trials = "Ensayo de Color";
                $request->equipo_critico = $arrayColor->equipo_critico;
                $request->identificacion = $arrayColor->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesOlor(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Ensayo de Olor';
            Olor::create([
                'lc_list_trials_id' => 33,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'fecha_ajuste_curva_tres' => $request->fecha_ajuste_curva_tres,
                'documento_referencia_tres' => $request->documento_referencia_tres,
                'limite_cuantificacion_tres' => $request->limite_cuantificacion_tres,
                'sustancias_flotantes' => $request->sustancias_flotantes,
                'nombre_patron_tres' => $request->nombre_patron_tres,
                'valor_esperador_tres' => $request->valor_esperador_tres,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Ensayo de Olor';
            Olor::create([
                'lc_list_trials_id' => 33,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'fecha_ajuste_curva_tres' => $request->fecha_ajuste_curva_tres,
                'documento_referencia_tres' => $request->documento_referencia_tres,
                'limite_cuantificacion_tres' => $request->limite_cuantificacion_tres,
                'sustancias_flotantes' => $request->sustancias_flotantes,
                'nombre_patron_tres' => $request->nombre_patron_tres,
                'valor_esperador_tres' => $request->valor_esperador_tres,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayColor = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 33;
                $request->name_trials = "Ensayo de Olor";
                $request->equipo_critico = $arrayColor->equipo_critico;
                $request->identificacion = $arrayColor->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesConductividad(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Ensayo de Conductividad';
            Conductividad::create([
                'lc_list_trials_id' => 34,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'fecha_ajuste_curva_dos' => $request->fecha_ajuste_curva_dos,
                'documento_referencia_dos' => $request->documento_referencia_dos,
                'limite_cuantificacion_dos' => $request->limite_cuantificacion_dos,
                'nombre_patron_dos' => $request->nombre_patron_dos,
                'valor_esperado_dos' => $request->valor_esperado_dos,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Ensayo de Conductividad';
            Conductividad::create([
                'lc_list_trials_id' => 34,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'fecha_ajuste_curva_dos' => $request->fecha_ajuste_curva_dos,
                'documento_referencia_dos' => $request->documento_referencia_dos,
                'limite_cuantificacion_dos' => $request->limite_cuantificacion_dos,
                'nombre_patron_dos' => $request->nombre_patron_dos,
                'valor_esperado_dos' => $request->valor_esperado_dos,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayColor = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 34;
                $request->name_trials = "Ensayo de Conductividad";
                $request->equipo_critico = $arrayColor->equipo_critico;
                $request->identificacion = $arrayColor->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesSustanciasFlotantes(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Ensayo de Sustancias Flotantes';
            SustanciasFlotantes::create([
                'lc_list_trials_id' => 35,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'año' => $request->año,
                'decimales' => $request->decimales,
                'mes' => $request->mes,
                'fecha_ajuste_curva_uno' => $request->fecha_ajuste_curva_uno,
                'fecha_ajuste_curva_dos' => $request->fecha_ajuste_curva_dos,
                'fecha_ajuste_curva_tres' => $request->fecha_ajuste_curva_tres,
                'documento_referencia_uno' => $request->documento_referencia_uno,
                'documento_referencia_dos' => $request->documento_referencia_dos,
                'documento_referencia_tres' => $request->documento_referencia_tres,
                'limite_cuantificacion_uno' => $request->limite_cuantificacion_uno,
                'limite_cuantificacion_dos' => $request->limite_cuantificacion_dos,
                'limite_cuantificacion_tres' => $request->limite_cuantificacion_tres,
                'sustancias_flotantes' => $request->sustancias_flotantes,
                'nombre_patron_uno' => $request->nombre_patron_uno,
                'valor_esperador_uno' => $request->valor_esperador_uno,
                'nombre_patron_dos' => $request->nombre_patron_dos,
                'valor_esperado_dos' => $request->valor_esperado_dos,
                'nombre_patron_tres' => $request->nombre_patron_tres,
                'valor_esperador_tres' => $request->valor_esperador_tres,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Ensayo de Sustancias Flotantes';
            SustanciasFlotantes::create([
                'lc_list_trials_id' => 35,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'fecha_ajuste_curva_uno' => $request->fecha_ajuste_curva_uno,
                'fecha_ajuste_curva_dos' => $request->fecha_ajuste_curva_dos,
                'fecha_ajuste_curva_tres' => $request->fecha_ajuste_curva_tres,
                'documento_referencia_uno' => $request->documento_referencia_uno,
                'documento_referencia_dos' => $request->documento_referencia_dos,
                'documento_referencia_tres' => $request->documento_referencia_tres,
                'limite_cuantificacion_uno' => $request->limite_cuantificacion_uno,
                'limite_cuantificacion_dos' => $request->limite_cuantificacion_dos,
                'limite_cuantificacion_tres' => $request->limite_cuantificacion_tres,
                'sustancias_flotantes' => $request->sustancias_flotantes,
                'nombre_patron_uno' => $request->nombre_patron_uno,
                'valor_esperador_uno' => $request->valor_esperador_uno,
                'nombre_patron_dos' => $request->nombre_patron_dos,
                'valor_esperado_dos' => $request->valor_esperado_dos,
                'nombre_patron_tres' => $request->nombre_patron_tres,
                'valor_esperador_tres' => $request->valor_esperador_tres,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }

        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayColor = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 35;
                $request->name_trials = "Ensayo de Sustancias Flotantes";
                $request->equipo_critico = $arrayColor->equipo_critico;
                $request->identificacion = $arrayColor->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 02 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesCarbonoOrganico(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Carbono Organico Total';
            CarbonoOrganico::create([
                'lc_list_trials_id' => 26,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Carbono Organico Total",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Carbono Organico Total';
            CarbonoOrganico::create([
                'lc_list_trials_id' => 26,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'ensayo' => "Ensayo Carbono Organico Total",
                'processo' => $request->processo,
                'documento_referencia' => $request->documento_referencia,
                'año' => $request->año,
                'mes' => $request->mes,
                'decimales' => $request->decimales,
                'k_pendiente' => $request->k_pendiente,
                'b_intercepto' => $request->b_intercepto,
                'fd_factor_dilucion' => $request->fd_factor_dilucion,
                'nombre_patron' => $request->nombre_patron,
                'valor_esperado' => $request->valor_esperado,
                'valor_esperado_adc' => $request->valor_esperado_adc,
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'recuperacion_adicionado' => $request->recuperacion_adicionado,
                'limite_cuantificacion' => $request->limite_cuantificacion,
                'curva_numero' => $request->curva_numero,
                'fecha_curva' => $request->fecha_curva,
                'ensayo_mgl_no3' => $request->ensayo_mgl_no3,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }
        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arrayCarbonoOrganico = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 26;
                $request->name_trials = "Carbono Organico Total";
                $request->equipo_critico = $arrayCarbonoOrganico->equipo_critico;
                $request->identificacion = $arrayCarbonoOrganico->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 02 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesSolidos(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Ensayo de Solidos (Sólidos totales disueltos)';
            Solidos::create([
                'lc_list_trials_id' => 24,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'decimales' => $request->decimales,
                'ensayo_uno' => "Ensayo de sólidos totales disueltos a 180°C",
                // 'ensayo_dos' => "Ensayo de sólidos totales suspendidos secos a (103-105)°C",
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'documento_referencia_uno' => $request->documento_referencia_uno,
                'documento_referencia_dos' => $request->documento_referencia_dos,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Ensayo de Solidos (Sólidos totales disueltos)';
            Solidos::create([
                'lc_list_trials_id' => 24,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'decimales' => $request->decimales,
                'ensayo_uno' => "Ensayo de sólidos totales disueltos a 180°C",
                // 'ensayo_dos' => "Ensayo de sólidos totales suspendidos secos a (103-105)°C",
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'documento_referencia_uno' => $request->documento_referencia_uno,
                'documento_referencia_dos' => $request->documento_referencia_dos,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }
        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arraySolidos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 24;
                $request->name_trials = "Ensayo de Solidos (Sólidos totales disueltos)";
                $request->equipo_critico = $arraySolidos->equipo_critico;
                $request->identificacion = $arraySolidos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 02 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function generalitiesSolidosSecos(Request $request)
    {
        $input = $request->all();
        if ($input['user_remplace'] == 'Si') {
            

            $nameTrial = 'Ensayo de Sólidos (Sólidos totales suspendidos secos)';
            SolidosSecos::create([
                'lc_list_trials_id' => 25,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'decimales' => $request->decimales,
                // 'ensayo_uno' => "Ensayo de sólidos totales disueltos a 180°C",
                'ensayo_dos' => "Ensayo de sólidos totales suspendidos secos a (103-105)°C",
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'documento_referencia_uno' => $request->documento_referencia_uno,
                'documento_referencia_dos' => $request->documento_referencia_dos,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'user_remplace' => $request->user_remplace,
                'user_remplace_admin' => $input['funcionario_remplace']['name'],
                'firma' => $input['funcionario_remplace']['url_digital_signature'],
                'cargo' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        } else {
            $nameTrial = 'Ensayo de Sólidos (Sólidos totales suspendidos secos)';
            SolidosSecos::create([
                'lc_list_trials_id' => 25,
                'users_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'proceso' => $request->proceso,
                'decimales' => $request->decimales,
                // 'ensayo_uno' => "Ensayo de sólidos totales disueltos a 180°C",
                'valor_esperado_lcm' => $request->valor_esperado_lcm,
                'ensayo_dos' => "Ensayo de sólidos totales suspendidos secos a (103-105)°C",
                'documento_referencia_uno' => $request->documento_referencia_uno,
                'documento_referencia_dos' => $request->documento_referencia_dos,
                'obervacion' => $request->obervacion,
                'observations_edit' => $request->observations_edit ?? null,
                'nombre' => "Luis Ancizar Arango Vallejo",
                'cargo' => "Vo.Bo: Profesional Especializado | Director Técnico LECA",
            ]);
        }
        if (!empty($input['lc_critical_equipments'])) {
            //Elimina los registros de Equipos criticos
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
            //Cilo que guarda los equipos criticos
            foreach ($input['lc_critical_equipments'] as $option) {
                $arraySolidos = json_decode($option);
                //Se crean la cantidad de registros ingresados por el usuario
                $request = new CriticalEquipment();
                $request->lc_list_trials_id = 25;
                $request->name_trials = "Ensayo de Sólidos (Sólidos totales suspendidos secos)";
                $request->equipo_critico = $arraySolidos->equipo_critico;
                $request->identificacion = $arraySolidos->identificacion;
                $request->save();
            }
        } else {
            CriticalEquipment::where('name_trials', $nameTrial)->delete();
        }
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function controlChartsBlanco(Request $request)
    {
        if ($request->lc_list_trials_id == null) {
            $idListTrials = $request->id;
        } else {
            $idListTrials = $request->lc_list_trials_id;
        }
        $userElaborated = User::where('id', Auth::user()->id)->with(['positions'])->latest()->get();

        if($request['name'] == null){
            $nombreEnsayo = $request['parametro'];
        } else {
            $nombreEnsayo = $request['name'];
        }
        Blancos::create([
            'lc_list_trials_id' => $idListTrials,
            'users_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'parametro' => $nombreEnsayo,
            'tecnica' => $request->tecnica,
            'metodo' => $request->metodo,
            'frecuencia' => $request->frecuencia,
            'año' => $request->año,
            'mes' => $request->mes,
            'n_anterior' => $request->n_anterior,
            'x_anterior' => $request->x_anterior,
            's_anterior' => $request->s_anterior,
            'decimales' => $request->decimales,
            'tipo_grafico' => $request->tipo_grafico,
            'ldm' => $request->ldm,
            'lcm' => $request->lcm,
            'observacion' => $request->observacion,
            'observations_edit' => $request->observations_edit ?? null,
            'nombre_elaboro' => Auth::user()->name,
            'cargo_elaboro' => $userElaborated[0]->positions->nombre,
            'firma_elaboro' => $userElaborated[0]->url_digital_signature,
            'nombre_reviso' => "Luis Ancizar Arango Vallejo",
            'cargo_reviso' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
        ]);
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function controlChartsPatron(Request $request)
    {
        if ($request->lc_list_trials_id == null) {
            $idListTrials = $request->id;
        } else {
            $idListTrials = $request->lc_list_trials_id;
        }
        $userElaborated = User::where('id', Auth::user()->id)->with(['positions'])->latest()->get();

        if($request['name'] == null){
            $nombreEnsayo = $request['parametro'];
        } else {
            $nombreEnsayo = $request['name'];
        }

        if($request->lcs_50 == 'NaN'){
            $request->lcs_50 = null;
        }
        if($request->las_50 == 'NaN'){
            $request->las_50 = null;
        }
        if($request->lai_50 == 'NaN'){
            $request->lai_50 = null;
        }
        if($request->lci_50 == 'NaN'){
            $request->lci_50 = null;
        }
        if($request->x_mas_50 == 'NaN'){
            $request->x_mas_50 = null;
        }
        if($request->x_menos_50 == 'NaN'){
            $request->x_menos_50 = null;
        }

        Patron::create([
            'lc_list_trials_id' => $idListTrials,
            'users_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'parametro' => $nombreEnsayo,
            'metodo' => $request->metodo,
            'recuperacion' => $request->recuperacion,
            'patron_control' => $request->patron_control,
            'emergencia_patron' => $request->emergencia_patron,
            'mes' => $request->mes,
            'año' => $request->año,
            'decimales' => $request->decimales,
            'n_esta' => $request->n_esta,
            'lmt_error' => $request->lmt_error,
            'x_esta' => $request->x_esta,
            's_esta' => $request->s_esta,
            'n_anterior' => $request->n_anterior,
            'x_anterior' => $request->x_anterior,
            's_anterior' => $request->s_anterior,
            'lcs' => $request->lcs,
            'las' => $request->las,
            'lai' => $request->lai,
            'lci' => $request->lci,
            'x_mas' => $request->x_mas,
            'x_menos' => $request->x_menos,
            'observacion' => $request->observacion,
            'observations_edit' => $request->observations_edit ?? null,
            'nombre_elaboro' => Auth::user()->name,
            'cargo_elaboro' => $userElaborated[0]->positions->nombre,
            'firma_elaboro' => $userElaborated[0]->url_digital_signature,
            'nombre_reviso' => "Luis Ancizar Arango Vallejo",
            'cargo_reviso' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            'n_anterior_50' => $request->n_anterior_50,
            'x_anterior_50' => $request->x_anterior_50,
            's_anterior_50' => $request->s_anterior_50,
            'lcs_50' => $request->lcs_50,
            'las_50' => $request->las_50,
            'lai_50' => $request->lai_50,
            'lci_50' => $request->lci_50,
            'x_mas_50' => $request->x_mas_50,
            'x_menos_50' => $request->x_menos_50,
            'n_anterior_lcm' => $request->n_anterior_lcm,
            'x_anterior_lcm' => $request->x_anterior_lcm,
            's_anterior_lcm' => $request->s_anterior_lcm,
            'lcs_lcm' => $request->lcs_lcm,
            'las_lcm' => $request->las_lcm,
            'lai_lcm' => $request->lai_lcm,
            'lci_lcm' => $request->lci_lcm,
            'x_mas_lcm' => $request->x_mas_lcm,
            'x_menos_lcm' => $request->x_menos_lcm,
            'n_ant_dpr' => $request->n_ant_dpr,
            'x_ant_dpr' => $request->x_ant_dpr,
            's_ant_dpr' => $request->s_ant_dpr,
            'n_ant_adicionado' => $request->n_ant_adicionado,
            'x_ant_adicionado' => $request->x_ant_adicionado,
            's_ant_adicionado' => $request->s_ant_adicionado,
            'n_ant_porc_std' => $request->n_ant_porc_std,
            'x_ant_porc_std' => $request->x_ant_porc_std,
            's_ant_porc_std' => $request->s_ant_porc_std,
            'lcs_porc_std' => $request->lcs_porc_std,
            'lci_porc_std' => $request->lci_porc_std,
            'las_porc_std' => $request->las_porc_std,
            'lai_porc_std' => $request->lai_porc_std,
            'x_mas_porc_std' => $request->x_mas_porc_std,
            'x_menos_porc_std' => $request->x_menos_porc_std,
            'lcs_porc_lcm' => $request->lcs_porc_lcm,
            'lci_porc_lcm' => $request->lci_porc_lcm,
            'las_porc_lcm' => $request->las_porc_lcm,
            'lai_porc_lcm' => $request->lai_porc_lcm,
            'x_mas_porc_lcm' => $request->x_mas_porc_lcm,
            'x_menos_porc_lcm' => $request->x_menos_porc_lcm,
        ]);
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function controlChartsPatronAlcalinidad(Request $request)
    {
        if ($request->lc_list_trials_id == null) {
            $idListTrials = $request->id;
        } else {
            $idListTrials = $request->lc_list_trials_id;
        }
        $userElaborated = User::where('id', Auth::user()->id)->with(['positions'])->latest()->get();
        if($request['name'] == null){
            $nombreEnsayo = $request['parametro'];
        } else {
            $nombreEnsayo = $request['name'];
        }

        Patron::create([
            'lc_list_trials_id' => $idListTrials,
            'users_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'parametro' => $nombreEnsayo,
            'metodo' => $request->metodo,
            'recuperacion' => $request->recuperacion,
            'patron_control' => $request->patron_control,
            'emergencia_patron' => $request->emergencia_patron,
            'mes' => $request->mes,
            'año' => $request->año,
            'decimales' => $request->decimales,
            'n_esta' => $request->n_esta,
            'lmt_error' => $request->lmt_error,
            'x_esta' => $request->x_esta,
            's_esta' => $request->s_esta,
            'n_anterior' => $request->n_anterior,
            'x_anterior' => $request->x_anterior,
            's_anterior' => $request->s_anterior,
            'lcs' => $request->lcs,
            'las' => $request->las,
            'lai' => $request->lai,
            'lci' => $request->lci,
            'x_mas' => $request->x_mas,
            'x_menos' => $request->x_menos,
            'observacion' => $request->observacion,
            'observations_edit' => $request->observations_edit ?? null,
            'nombre_elaboro' => Auth::user()->name,
            'cargo_elaboro' => $userElaborated[0]->positions->nombre,
            'firma_elaboro' => $userElaborated[0]->url_digital_signature,
            'nombre_reviso' => "Luis Ancizar Arango Vallejo",
            'cargo_reviso' => "Reemplazo a Vo.Bo: Profesional Especializado | Director Técnico LECA",
            'n_anterior_50' => $request->n_anterior_50,
            'x_anterior_50' => $request->x_anterior_50,
            's_anterior_50' => $request->s_anterior_50,
            'lcs_50' => $request->lcs_50,
            'las_50' => $request->las_50,
            'lai_50' => $request->lai_50,
            'lci_50' => $request->lci_50,
            'x_mas_50' => $request->x_mas_50,
            'x_menos_50' => $request->x_menos_50,
            'n_anterior_lcm' => $request->n_anterior_lcm,
            'x_anterior_lcm' => $request->x_anterior_lcm,
            's_anterior_lcm' => $request->s_anterior_lcm,
            'lcs_lcm' => $request->lcs_lcm,
            'las_lcm' => $request->las_lcm,
            'lai_lcm' => $request->lai_lcm,
            'lci_lcm' => $request->lci_lcm,
            'x_mas_lcm' => $request->x_mas_lcm,
            'x_menos_lcm' => $request->x_menos_lcm,
            'n_ant_dpr' => $request->n_ant_dpr,
            'x_ant_dpr' => $request->x_ant_dpr,
            's_ant_dpr' => $request->s_ant_dpr,
            'n_ant_porc_std' => $request->n_ant_porc_std,
            'x_ant_porc_std' => $request->x_ant_porc_std,
            's_ant_porc_std' => $request->s_ant_porc_std,
            'lcs_porc_std' => $request->lcs_porc_std,
            'lci_porc_std' => $request->lci_porc_std,
            'las_porc_std' => $request->las_porc_std,
            'lai_porc_std' => $request->lai_porc_std,
            'x_mas_porc_std' => $request->x_mas_porc_std,
            'x_menos_porc_std' => $request->x_menos_porc_std,
            'lcs_porc_lcm' => $request->lcs_porc_lcm,
            'lci_porc_lcm' => $request->lci_porc_lcm,
            'las_porc_lcm' => $request->las_porc_lcm,
            'lai_porc_lcm' => $request->lai_porc_lcm,
            'x_mas_porc_lcm' => $request->x_mas_porc_lcm,
            'x_menos_porc_lcm' => $request->x_menos_porc_lcm,
        ]);
        return $this->sendResponse($request->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Feb. 02 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function edit($id, $optional = '')
    {
        $variables = [];
        //Separa lo que recibe para poder validar a cual condicion tiene que entrar
        $ensayoGeneral = explode('-', $optional);
        if ($ensayoGeneral[0] == 'blanco') {
            switch ($ensayoGeneral[1]) {
                case "Ensayo Turbiedad":
                    $ensayo = Blancos::where('lc_list_trials_id', '4')->latest()->first();
                    $relacionEnsayo = ListTrials::where('id', 4)->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 4)->first();
                        $variables = $ensayo;
                    } else {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    }
                    break;
                case "Ensayo pH":
                    $ensayo = Blancos::where('lc_list_trials_id', '5')->latest()->first();
                    $relacionEnsayo = ListTrials::where('id', 5)->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 5)->first();
                        $variables = $ensayo;
                    } else {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    }
                    break;
                case "Ensayo Alcalinidad Total":
                    $ensayo = Blancos::where('lc_list_trials_id', '7')->latest()->first();
                    $relacionEnsayo = ListTrials::where('id', 7)->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 7)->first();
                        $variables = $ensayo;
                    } else {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    }
                    break;
                case "Ensayo Cloruro":
                    $ensayo = Blancos::where('lc_list_trials_id', '8')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 8)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Sulfatos":
                    $ensayo = Blancos::where('lc_list_trials_id', '9')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 9)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Nitritos":
                    $ensayo = Blancos::where('lc_list_trials_id', '10')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 10)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Fosfatos":
                    $ensayo = Blancos::where('lc_list_trials_id', '11')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 11)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Calcio":
                    $ensayo = Blancos::where('lc_list_trials_id', '12')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 12)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Dureza Total":
                    $ensayo = Blancos::where('lc_list_trials_id', '13')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 13)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Hierro":
                    $ensayo = Blancos::where('lc_list_trials_id', '14')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 14)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Aluminio":
                    $ensayo = Blancos::where('lc_list_trials_id', '15')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 15)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Mercurio":
                    $ensayo = Blancos::where('lc_list_trials_id', '29')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 29)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Hidrocarburos":
                    $ensayo = Blancos::where('lc_list_trials_id', '30')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 30)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Plaguicidas":
                    $ensayo = Blancos::where('lc_list_trials_id', '31')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 31)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Trialometanos":
                    $ensayo = Blancos::where('lc_list_trials_id', '32')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 32)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Plomo":
                    $ensayo = Blancos::where('lc_list_trials_id', '27')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 27)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Cadmio":
                    $ensayo = Blancos::where('lc_list_trials_id', '28')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 28)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Cloro Residual":
                    $ensayo = Blancos::where('lc_list_trials_id', '16')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 16)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Acidez":
                    $ensayo = Blancos::where('lc_list_trials_id', '17')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 17)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Nitratos":
                    $ensayo = Blancos::where('lc_list_trials_id', '18')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 18)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Fluoruros":
                    $ensayo = Blancos::where('lc_list_trials_id', '20')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 20)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Coliformes totales":
                    $ensayo = Blancos::where('lc_list_trials_id', '21')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 21)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo de Escherichia coli":
                    $ensayo = Blancos::where('lc_list_trials_id', '22')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 22)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Bacterias heterotróficas (Mesófilos)":
                    $ensayo = Blancos::where('lc_list_trials_id', '23')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 23)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo de Color":
                    $ensayo = Blancos::where('lc_list_trials_id', '1')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 1)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo de Olor":
                    $ensayo = Blancos::where('lc_list_trials_id', '33')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 33)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo de Conductividad":
                    $ensayo = Blancos::where('lc_list_trials_id', '34')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 34)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo de Sustancias Flotantes":
                    $ensayo = Blancos::where('lc_list_trials_id', '35')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 35)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Carbono Orgánico Total":
                    $ensayo = Blancos::where('lc_list_trials_id', '26')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 26)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo de Sólidos (Sólidos totales disueltos)":
                    $ensayo = Blancos::where('lc_list_trials_id', '24')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 24)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo de Sólidos (Sólidos totales suspendidos secos)":
                    $ensayo = Blancos::where('lc_list_trials_id', '25')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 25)->first();
                    }
                    $variables = $ensayo;
                    break;
            }
        } elseif ($ensayoGeneral[0] == 'patron') {
            switch ($ensayoGeneral[1]) {
                case "Ensayo Turbiedad":
                    $ensayo = Patron::where('lc_list_trials_id', '4')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 4)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Turbiedad"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo pH":
                    $ensayo = Patron::where('lc_list_trials_id', '5')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 5)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo pH"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Alcalinidad Total":
                    $ensayo = Patron::where('lc_list_trials_id', '7')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 7)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Cloruro":
                    $ensayo = Patron::where('lc_list_trials_id', '8')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 8)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Cloruro"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Sulfatos":
                    $ensayo = Patron::where('lc_list_trials_id', '9')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 9)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Sulfatos"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Nitritos":
                    $ensayo = Patron::where('lc_list_trials_id', '10')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 10)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Nitritos"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Fosfatos":
                    $ensayo = Patron::where('lc_list_trials_id', '11')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 11)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Fosfatos"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Calcio":
                    $ensayo = Patron::where('lc_list_trials_id', '12')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 12)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Calcio"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Dureza Total":
                    $ensayo = Patron::where('lc_list_trials_id', '13')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 13)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Dureza Total"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Hierro":
                    $ensayo = Patron::where('lc_list_trials_id', '14')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 14)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Hierro"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Aluminio":
                    $ensayo = Patron::where('lc_list_trials_id', '15')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 15)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Aluminio"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Mercurio":
                    $ensayo = Patron::where('lc_list_trials_id', '29')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 29)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Mercurio"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Hidrocarburos":
                    $ensayo = Patron::where('lc_list_trials_id', '30')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 30)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Hidrocarburos"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Plaguicidas":
                    $ensayo = Patron::where('lc_list_trials_id', '31')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 31)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Plaguicidas"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Trialometanos":
                    $ensayo = Patron::where('lc_list_trials_id', '32')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 32)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Trialometanos"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Plomo":
                    $ensayo = Patron::where('lc_list_trials_id', '27')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 27)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Plomo"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Cadmio":
                    $ensayo = Patron::where('lc_list_trials_id', '28')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 28)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Cadmio"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Cloro Residual":
                    $ensayo = Patron::where('lc_list_trials_id', '16')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 16)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Cloro Residual"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Acidez":
                    $ensayo = Patron::where('lc_list_trials_id', '17')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 17)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Acidez"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Nitratos":
                    $ensayo = Patron::where('lc_list_trials_id', '18')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 18)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Nitratos"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Fluoruros":
                    $ensayo = Patron::where('lc_list_trials_id', '20')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 20)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Fluoruros"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Coliformes totales":
                    $ensayo = Patron::where('lc_list_trials_id', '21')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 21)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo de Escherichia coli":
                    $ensayo = Patron::where('lc_list_trials_id', '22')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 22)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo Bacterias heterotróficas (Mesófilos)":
                    $ensayo = Patron::where('lc_list_trials_id', '23')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 23)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo de Color":
                    $ensayo = Patron::where('lc_list_trials_id', '1')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 1)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo de Color"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo de Sustancias Flotantes":
                    $ensayo = Patron::where('lc_list_trials_id', '35')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 35)->first();
                    }
                    $variables = $ensayo;
                    break;
                case "Ensayo de Olor":
                    $ensayo = Patron::where('lc_list_trials_id', '33')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 33)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo de Olor"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo de Conductividad":
                    $ensayo = Patron::where('lc_list_trials_id', '34')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 34)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo de Conductividad"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo Carbono Orgánico Total":
                    $ensayo = Patron::where('lc_list_trials_id', '26')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 26)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo Carbono Orgánico Total"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo de Sólidos (Sólidos totales disueltos)":
                    $ensayo = Patron::where('lc_list_trials_id', '24')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 24)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo de Sólidos (Sólidos totales disueltos)"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
                case "Ensayo de Sólidos (Sólidos totales suspendidos secos)":
                    $ensayo = Patron::where('lc_list_trials_id', '25')->latest()->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 25)->first();
                    }
                    $ensayo = $ensayo->toArray();
                    $all= array(
                        "parametro" => "Ensayo de Sólidos (Sólidos totales suspendidos secos)"
                    );
                    array_push($variables, $all);
                    $variables = array_merge($ensayo, $all);
                    break;
            }
        } else {
            switch ($id) {
                case 10:
                    $ensayo = Nitritos::where('created_at', Nitritos::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 10)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 18:
                    $ensayo = Nitratos::where('created_at', Nitratos::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 18)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 14:
                    $ensayo = Hierro::where('created_at', Hierro::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 14)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 11:
                    $ensayo = Fosfatos::where('created_at', Fosfatos::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 11)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 15:
                    $ensayo = Aluminio::where('created_at', Aluminio::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 15)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 29:
                    $ensayo = Mercurio::where('created_at', Mercurio::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 29)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 30:
                    $ensayo = Hidrocarburos::where('created_at', Hidrocarburos::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 30)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 31:
                    $ensayo = Plaguicida::where('created_at', Plaguicida::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 31)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 32:
                    $ensayo = Trialometanos::where('created_at', Trialometanos::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 32)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 27:
                    $ensayo = Plomo::where('created_at', Plomo::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 27)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 28:
                    $ensayo = Cadmio::where('created_at', Cadmio::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 28)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 8:
                    $ensayo = Cloruro::where('created_at', Cloruro::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 8)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 16:
                    $ensayo = CloroResidual::where('created_at', CloroResidual::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 16)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 12:
                    $ensayo = Calcio::where('created_at', Calcio::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 12)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 13:
                    $ensayo = DurezaTotal::where('created_at', DurezaTotal::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 13)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 17:
                    $ensayo = Acidez::where('created_at', Acidez::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 17)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 20:
                    $ensayo = Fluoruros::where('created_at', Fluoruros::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 20)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 9:
                    $ensayo = Sulfatos::where('created_at', Sulfatos::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 9)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 7:
                    $ensayo = Alcalinidad::where('created_at', Alcalinidad::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 7)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 5:
                    $ensayo = Ph::where('created_at', Ph::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 5)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 4:
                    $ensayo = Turbidez::where('created_at', Turbidez::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 4)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 21:
                    $ensayo = ColiformesTotales::where('created_at', ColiformesTotales::max('created_at'))->orderBy('created_at', 'desc')->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 21)->first();
                    }
                    $variables = $ensayo;
                    break;
                case 22:
                    $ensayo = EscherichiaColi::where('created_at', EscherichiaColi::max('created_at'))->orderBy('created_at', 'desc')->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 22)->first();
                    }
                    $variables = $ensayo;
                    break;
                case 23:
                    $ensayo = BacteriasHeterotroficas::where('created_at', BacteriasHeterotroficas::max('created_at'))->orderBy('created_at', 'desc')->first();
                    if ($ensayo == null) {
                        $ensayo = ListTrials::where('id', 23)->first();
                    }
                    $variables = $ensayo;
                    break;
                case 1:
                    $ensayo = Color::where('created_at', Color::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 1)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;

                case 33:
                    $ensayo = Olor::where('created_at', Olor::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 33)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;

                case 34:
                    $ensayo = Conductividad::where('created_at', Conductividad::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 34)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;

                case 35:
                    $ensayo = SustanciasFlotantes::where('created_at', SustanciasFlotantes::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 35)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 26:
                    $ensayo = CarbonoOrganico::where('created_at', CarbonoOrganico::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 26)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 24:
                    $ensayo = Solidos::where('created_at', Solidos::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 24)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
                case 25:
                    $ensayo = SolidosSecos::where('created_at', SolidosSecos::max('created_at'))->orderBy('created_at', 'desc')->first();
                    $relacionEnsayo = ListTrials::where('id', 25)->with('lcCriticalEquipments')->first();
                    if ($ensayo != null) {
                        $ensayo = $ensayo->toArray();
                        $relacionEnsayo = $relacionEnsayo->toArray();
                        $variables = array_merge($ensayo, $relacionEnsayo);
                    } else {
                        $variables = $relacionEnsayo;
                    }
                    break;
            }
        }

        return $this->sendResponse($variables, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los funcionarios para el automcomplete
     *
     * @author Josè Manuel Marìn Londoño. - Mar. 03 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getRemplaceAdmin(Request $request)
    {
        $usersOfficials = User::role(['Administrador Leca'])->where('name', "like", "%" . $request['query'] . "%")->get();
        // $usersOfficials = User::all();
        return $this->sendResponse($usersOfficials, trans('data_obtained_successfully'));
    }

    public function guardarConfiguracion(Request $request)
    {

        $blanco = new BlancoGeneral();
        $blanco->periodicidad = $request['periodicidad'];
        $blanco->hora_inicio = $request['hora_inicio'];
        $blanco->hora_fin = $request['hora_fin'];
        $blanco->save();

        $patron = new PatronGeneral();
        $patron->periodicidad = $request['periodicidad_p'];
        $patron->hora_inicio = $request['hora_inicio_p'];
        $patron->hora_final = $request['hora_final_p'];
        $patron->save();

        return $this->sendResponse('', trans('msg_success_update'));
    }

    public function enviaMedia($id)
    {

        $Year = date("Y");
        $total = 0;
        $acumulado = 0;

        if ($id == 15) {
            $cantidadMuestras = EnsayoAluminio::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }

        }

        if ($id == 14) {
            $cantidadMuestras = EnsayoHierro::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }

        }

        if ($id == 7) {
            $cantidadMuestras = EnsayoAlcalinidad::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }

        }
        if ($id == 8) {
            $cantidadMuestras = EnsayoCloruro::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }

        }

        if ($id == 9) {
            $cantidadMuestras = EnsayoSulfato::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }

        }

        if ($id == 10) {
            $cantidadMuestras = EnsayoNitritos::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }

        }

        if ($id == 11) {
            $cantidadMuestras = EnsayoFosfato::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 12) {
            $cantidadMuestras = EnsayoCalcio::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 12) {
            $cantidadMuestras = EnsayoCalcio::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 13) {
            $cantidadMuestras = EnsayoDureza::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 13) {
            $cantidadMuestras = EnsayoDureza::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 16) {
            $cantidadMuestras = EnsayoCloro::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 17) {
            $cantidadMuestras = EnsayoAcidez::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 18) {
            $cantidadMuestras = EnsayoNitratos::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 26) {
            $cantidadMuestras = EnsayoCarbonoOrganico::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 27) {
            $cantidadMuestras = EnsayoEspectro::whereYear('created_at', $Year)->where('tipo', 'Patrón')->where('ensayo', 'Plomo')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 28) {
            $cantidadMuestras = EnsayoEspectro::whereYear('created_at', $Year)->where('tipo', 'Patrón')->where('ensayo', 'Cadmio')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 29) {
            $cantidadMuestras = EnsayoEspectro::whereYear('created_at', $Year)->where('tipo', 'Patrón')->where('ensayo', 'Mercurio')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 30) {
            $cantidadMuestras = EnsayoEspectro::whereYear('created_at', $Year)->where('tipo', 'Patrón')->where('ensayo', 'Hidrocarburos')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 31) {
            $cantidadMuestras = EnsayoEspectro::whereYear('created_at', $Year)->where('tipo', 'Patrón')->where('ensayo', 'Plaguicida')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        if ($id == 32) {
            $cantidadMuestras = EnsayoEspectro::whereYear('created_at', $Year)->where('tipo', 'Patrón')->where('ensayo', 'Trialometanos')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            if (count($cantidadMuestras) > 0) {
                $total = $acumulado / count($cantidadMuestras);
            }
        }

        return $this->sendResponse($total, trans('msg_success_update'));
    }

    public function enviaDesviacion($id)
    {
        $Year = date("Y");
        $total = 0;
        $desviacion = 0;
        $acumulado = 0;
        if ($id == 15) {
            $cantidadMuestras = EnsayoAluminio::whereYear('created_at', $Year)->where('tipo', 'Patrón')->get();
            foreach ($cantidadMuestras as $cantidadA) {
                $acumulado += $cantidadA['pr_inicio'];
            }
            $total = $acumulado / count($cantidadMuestras);

            $acumulado = 0;
            foreach ($cantidadMuestras as $cantidadA) {
                $resta = $cantidadA['pr_inicio'] - $total;
                $acumulado += pow(($resta), 2);
            }
            if (count($cantidadMuestras) > 0) {
                $desviacion = $acumulado / (count($cantidadMuestras));
                $total = sqrt($desviacion);
            }

        }
        return $this->sendResponse($total, trans('msg_success_update'));
    }

    public function getCartaMuestraDias($id)
    {
        $cantidadMuestras = 0;
        $Year = date("Y");
        if ($id == 15) {

            $cantidadMuestras = EnsayoAluminio::whereYear('created_at', $Year)->where('tipo', 'Patrón')->count();

        }

        return $this->sendResponse($cantidadMuestras, trans('msg_success_update'));
    }
}