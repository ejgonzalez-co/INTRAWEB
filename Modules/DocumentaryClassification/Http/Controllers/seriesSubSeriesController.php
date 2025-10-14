<?php

namespace Modules\DocumentaryClassification\Http\Controllers;

use App\Exports\GenericExport;
use Modules\DocumentaryClassification\Http\Requests\CreateseriesSubSeriesRequest;
use Modules\DocumentaryClassification\Http\Requests\UpdateseriesSubSeriesRequest;
use Modules\DocumentaryClassification\Repositories\seriesSubSeriesRepository;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
use Modules\DocumentaryClassification\Models\dependenciasSerieSubseries;
use Modules\DocumentaryClassification\Models\documentarySerieSubseries;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use PhpParser\Node\Expr\Cast\String_;
use Modules\DocumentaryClassification\Models\dependencias;
use Modules\ExpedientesElectronicos\Models\Expediente;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class seriesSubSeriesController extends AppBaseController {

    /** @var  seriesSubSeriesRepository */
    private $seriesSubSeriesRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(seriesSubSeriesRepository $seriesSubSeriesRepo) {
        $this->seriesSubSeriesRepository = $seriesSubSeriesRepo;
    }

    /**
     * Muestra la vista para el CRUD de seriesSubSeries.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole("Gestión Documental Admin")){
            return view('documentaryclassification::series_sub_series.index');
        }
        return view("auth.forbidden");
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {

        $query =$request->input('query');

        if($query){

            if($query=="all"){
                $seriesSubSeries = seriesSubSeries::select([
                    'type',
                    'id',
                    \DB::raw('id as id_series_subseries'),
                    \DB::raw('CASE WHEN type = "subserie" THEN CONCAT(no_serie, "-", no_subserie) ELSE no_serie END as no_serieosubserie'),
                    'no_serie',
                    'no_subserie',
                    \DB::raw('CASE WHEN type = "serie" THEN name_serie ELSE name_subserie END as name')
                ])
                ->with("typesList")
                ->orderBy('no_serie', 'ASC')
                ->orderBy('no_subserie', 'ASC')
                ->get();

            }else{

                $seriesSubSeries = seriesSubSeries::select([
                    'type',
                    'id',
                    \DB::raw('id as id_series_subseries'),
                    \DB::raw('CASE WHEN type = "subserie" THEN CONCAT(no_serie, "-", no_subserie) ELSE no_serie END as no_serieosubserie'),
                    'no_serie',
                    'no_subserie',
                    \DB::raw('CASE WHEN type = "serie" THEN name_serie ELSE name_subserie END as name')
                ])
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('name_serie', 'like', '%' . $query . '%')
                          ->orWhere('name_subserie', 'like', '%' . $query . '%')
                          ->orWhere('no_serie', 'like', '%' . $query . '%')
                          ->orWhere('no_subserie', 'like', '%' . $query . '%');

                })
                ->orderBy('no_serie', 'ASC')
                ->orderBy('no_subserie', 'ASC')
                ->get();
            }

        }else{
            $seriesSubSeries = seriesSubSeries::with(['typesList', 'serie'])->orderBy('no_serie', 'ASC')->orderBy('no_subserie','ASC')->get();
        }

        return $this->sendResponse($seriesSubSeries->toArray(), trans('data_obtained_successfully'));
    }

    //consulta para registro de subseries en modal
    public function all_series(){
        $series = seriesSubSeries::select('id','no_serie','name_serie')->with('CriteriosBusqueda')->where('type','Serie')->orderBy('name_serie','ASC')->latest()->get();
        return $this->sendResponse($series->toArray(),trans('data_obtained_successfully'));
    }

    /**
     * Obtiene las subseries de acuerdo a la serie, esto se utiliza para la clasificación documental
     *
     * @param Request $request
     * @return void
     */
    public function get_subseries_clasificacion(Request $request) {
        $request = $request->all();
        // Consulta las subseries de acuerdo a la serie recibida por parámetro
        $series = seriesSubSeries::select('id','no_serie','name_subserie')->with('CriteriosBusqueda')->where('id_serie', $request["serie"])->where('type', "Subserie")->orderBy('no_serie','ASC')->latest()->get();
        return $this->sendResponse($series->toArray(),trans('data_obtained_successfully'));
    }


    //consulta de series para  select-aotocomplete de tabalas de retencion
    public function all_series_dependencias(Request $request){
        $series = seriesSubSeries::select('id','no_serie','name_serie')->where('name_serie','like',"%".$request['query']."%")->where('no_subserie',NULL)->where('name_subserie',NULL)->latest()->get();

        return $this->sendResponse($series->toArray(),trans('data_obtained_successfully'));
    }

    //consulta de subseries para  select-aotocomplete de tabalas de retencion
    public function all_subseries_dependencias(Request $request){
        $subseries = seriesSubSeries::select('id','no_serie','no_subserie', 'name_subserie')->where('name_subserie','like',"%".$request['query']."%")->where('no_subserie','!=',NULL)->where('no_subserie','!=',NULL)->latest()->get();

        return $this->sendResponse($subseries->toArray(),trans('data_obtained_successfully'));

    }

    //Obtiene las series y subseries de una dependencia
    public function getSeriesSubseriesToDependency($id){

        $trdListDeDependencia = dependencias::where('id', $id)
            ->with('trdList')
            ->get()
            ->pluck('trdList')
            ->collapse();

            // dd($trdListDeDependencia->toArray());

        return $this->sendResponse($trdListDeDependencia->toArray(),trans('data_obtained_successfully'));
    }

    public function getSeriesToDependency($id){

            $trdListDeDependencia = dependencias::where('id', $id)
                ->with('trdListSeries')
                ->get()
                ->pluck('trdListSeries');
                // dd($trdListDeDependencia[0]->toArray());

            return $this->sendResponse($trdListDeDependencia[0]->toArray(),trans('data_obtained_successfully'));
        }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateseriesSubSeriesRequest $request
     *
     * @return Response
     */
    public function store(CreateseriesSubSeriesRequest $request) {

        $input = $request->all();
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            // $input['enable_expediente'] = $this->toBoolean($input['enable_expediente']);

            if($input['type'] == 'Subserie'){

                $validationCode = seriesSubSeries::where('id_serie', $input['id_serie'])->where('no_subserie',$input['no_subserie'])->count();

                if ($validationCode > 0) {
                    return $this->sendSuccess('Ya existe una subserie con el código ingresado.', 'error');
                }

                $consult = seriesSubSeries::where('id', $input['id_serie'])->select('no_serie','name_serie')->latest()->get();

                $input['no_serie'] = $consult[0]['no_serie'];
                $input['name_serie']= $consult[0]['name_serie'];
            }
            else{

                $validationCode = seriesSubSeries::where('no_serie',$input['no_serie'])->count();

                if ($validationCode > 0) {
                    return $this->sendSuccess('Ya existe una serie con el código ingresado.', 'error');
                }

                $input['no_subserie'] = "";

            }
            // Inserta el registro en la base de datos
            $seriesSubSerie = $this->seriesSubSeriesRepository->create($input);

                // Valida si viene usuarios para asignar en las copias
                if (!empty($input['types_list'])) {
                    $delete = documentarySerieSubseries::where("id_series_subseries", $seriesSubSerie->id)->delete();
                    //recorre los destinatarios
                    foreach ($input['types_list'] as $type) {

                        //array de destinatarios
                        $typesArray = json_decode($type,true);
                        // $delete = documentarySerieSubseries::where("id_series_subseries", $seriesSubSerie->id)->where("id_type_documentaries", $typesArray["id"])->delete();

                        // dd($typesArray);
                        $typesArray["id_series_subseries"] = $seriesSubSerie->id;
                        $typesArray["id_type_documentaries"] = $typesArray["id"] ?? $typesArray["tipo_documental"]["id"];
                        $typesArray["name"] = $typesArray["name"] ?? $typesArray["tipo_documental"]["name"];

                        documentarySerieSubseries::create($typesArray);
                    }
                    $seriesSubSerie->typesList;

                }else{
                    $delete = documentarySerieSubseries::where("id_series_subseries", $seriesSubSerie->id)->delete();

                }

                // Efectua los cambios realizados
                DB::commit();
            return $this->sendResponse($seriesSubSerie->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\seriesSubSeriesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\seriesSubSeriesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateseriesSubSeriesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateseriesSubSeriesRequest $request) {

        $input = $request->all();


        /** @var seriesSubSeries $seriesSubSeries */
        $seriesSubSeries = $this->seriesSubSeriesRepository->find($id);

        if (empty($seriesSubSeries)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        // try {
            // Organiza campos booleanos
            $input['enable_expediente'] = isset($input['enable_expediente']) && ($this->toBoolean($input['enable_expediente']) || $input['enable_expediente'] == 1) ? 1 : 0;

            if($input['type'] == 'Subserie'){

                $validationCode = seriesSubSeries::where('id_serie', $input['id_serie'])->where('no_subserie',$input['no_subserie'])->count();

                if ($validationCode > 0) {
                    return $this->sendSuccess('Ya existe una subserie con el código ingresado.', 'error');
                }
                
            
                $consult = seriesSubSeries::where('id', $input['id_serie'])->select('no_serie','name_serie')->latest()->get();

                $input['no_serie'] = $consult[0]['no_serie'];
                $input['name_serie']= $consult[0]['name_serie'];
            }else{

                $validationCode = seriesSubSeries::where('no_serie',$input['no_serie'])->count();

                if ($validationCode > 0) {
                    return $this->sendSuccess('Ya existe una serie con el código ingresado.', 'error');
                }

                $input['no_subserie'] = "";


            }


            // Actualiza el registro
            $seriesSubSerie = $this->seriesSubSeriesRepository->update($input, $id);


                // Valida si viene usuarios para asignar en las copias
                if (!empty($input['types_list'])) {
                    $delete = documentarySerieSubseries::where("id_series_subseries", $seriesSubSerie->id)->delete();
                    //recorre los destinatarios
                    foreach ($input['types_list'] as $type) {

                        $typesArray = json_decode($type,true);
                        $typesArray["id_series_subseries"] = $seriesSubSerie->id;
                        $typesArray["id_type_documentaries"] = $typesArray["id_type_documentaries"] ?? $typesArray["id"];
                        $typesArray["name"] = $typesArray["name"] ?? $typesArray["tipo_documental"]["name"];

                        documentarySerieSubseries::create($typesArray);
                    }
                    $seriesSubSerie->typesList;

                }else{
                    $delete = documentarySerieSubseries::where("id_series_subseries", $seriesSubSerie->id)->delete();

                }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($seriesSubSerie->toArray(), trans('msg_success_update'));
        // } catch (\Illuminate\Database\QueryException $error) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\seriesSubSeriesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
        //     // Retorna mensaje de error de base de datos
        //     return $this->sendSuccess(config('constants.support_message'), 'info');
        // } catch (\Exception $e) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\seriesSubSeriesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
        //     // Retorna error de tipo logico
        //     return $this->sendSuccess(config('constants.support_message'), 'info');
        // }
    }


    /**
     * Elimina un seriesSubSeries del almacenamiento
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
    public function destroy($id) {

        /** @var seriesSubSeries $seriesSubSeries */
        $seriesSubSeries = $this->seriesSubSeriesRepository->find($id);
        if (empty($seriesSubSeries)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $consult_delete_serie = 0;
            $consult_delete_dependencia = 0;
            $expe = Expediente::where('classification_serie', $seriesSubSeries['id_serie'])->orWhere('classification_subserie',$seriesSubSeries['id'])->count();
            if ($expe > 0) {
                return $this->sendSuccess("La Serie o Subserie no se pudo eliminar porque está vinculada a un expediente.",'error');
            }

            if ($seriesSubSeries['no_subserie'] !== null || $seriesSubSeries['name_subserie'] !== null) {
                $consult_delete_dependencia = dependenciasSerieSubseries::join('cd_series_subseries', 'cd_series_subseries.id', '=', 'cd_dependecias_has_cd_series_subseries.id_series_subseries')
                    ->where('id_series_subseries', (int) $id)
                    ->count();
            }
            if ($consult_delete_dependencia === 0) {
                $consult_delete_serie = seriesSubSeries::where('no_serie', $seriesSubSeries['no_serie'])
                    ->where('no_subserie', '!=', null)
                    ->where('name_subserie', '!=', null)
                    ->count();
            }

            if ($consult_delete_serie > 0) {
                return $this->sendSuccess('La Serie Documental NO fue borrada porque está asociada a una Subserie.', 'error');
            }

            if ($consult_delete_dependencia > 0) {
                return $this->sendSuccess('La Serie Documental NO fue borrada porque está asociada a TRDS o inventarios documentales.', 'error');
            }

            // Realiza el borrado de la Serie Documental y los cambios en una transacción
            DB::transaction(function () use ($id, $seriesSubSeries) {
                documentarySerieSubseries::where('id_series_subseries', (int) $id)->delete();
                $seriesSubSeries->delete();
            });

            return $this->sendSuccess(trans('msg_success_drop'));



        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\seriesSubSeriesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\seriesSubSeriesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
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
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('series_sub_series').'.'.$fileType;

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
     * Obtiene las series de acuerdo a si está habilitada para usar en expedientes, esto se utiliza para la creación de expedientes
     *
     * @param Request $request
     * @return void
     */
    public function getSeriesToDependencyExpediente($id){

        $trdListDeDependencia = dependencias::where('id', $id)
            ->with(['trdListSeriesExpedientes' => function ($query) {
                $query->whereHas('seriesOsubseries', function ($subQuery) {
                    $subQuery->where('enable_expediente', 1);
                });
            }])
            ->get()
            ->pluck('trdListSeriesExpedientes');

        return $this->sendResponse($trdListDeDependencia[0]->toArray(),trans('data_obtained_successfully'));
    }

    /**
     * Obtiene las subseries de acuerdo a la serie y si está habilitada para usar en expedientes, esto se utiliza para la creación de expedientes
     *
     * @param Request $request
     * @return void
     */
    public function get_subseries_clasificacion_expediente(Request $request) {
        $request = $request->all();
        // Consulta las subseries de acuerdo a la serie recibida por parámetro
        $series = seriesSubSeries::select('id','no_serie','name_subserie','no_subserie')
            ->with('CriteriosBusquedaExpedientes')
            ->where('id_serie', $request["serie"])
            ->where('type', "Subserie")
            ->where('enable_expediente', 1)
            ->orderBy('no_serie','ASC')
            ->latest()
            ->get();
        return $this->sendResponse($series->toArray(),trans('data_obtained_successfully'));
    }
}
