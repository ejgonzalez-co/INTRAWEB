<?php

namespace Modules\DocumentaryClassification\Http\Controllers;

use App\Exports\GenericExport;
use Modules\DocumentaryClassification\Http\Requests\CreatecriteriosBusquedaRequest;
use Modules\DocumentaryClassification\Http\Requests\UpdatecriteriosBusquedaRequest;
use Modules\DocumentaryClassification\Repositories\criteriosBusquedaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
use Modules\DocumentaryClassification\Models\criteriosBusqueda;
use Response;
use Auth;
use DB;
use Modules\DocumentaryClassification\Models\typeDocumentaries;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class criteriosBusquedaController extends AppBaseController {

    /** @var  criteriosBusquedaRepository */
    private $criteriosBusquedaRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(criteriosBusquedaRepository $criteriosBusquedaRepo) {
        $this->criteriosBusquedaRepository = $criteriosBusquedaRepo;
    }

    /**
     * Muestra la vista para el CRUD de criteriosBusqueda.
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
            return view('documentaryclassification::criterios_busquedas.index');
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
    public function all() {

        $criterios_busquedas = criteriosBusqueda::with([
            'SeriesSubseries',
            'SeriesSubseries.tipoDocumental' // Cargar el tipo documental
        ])->latest()->get();

        // dd($criterios_busquedas->toArray());
        // $criterios_busquedas = criteriosBusqueda::with('SeriesSubseries')->latest()->get();
        return $this->sendResponse($criterios_busquedas->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene la informacion de la serie
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function addSerieSubserie(Request $request)
    {
        $input = $request->toArray();

        $dataSerie = seriesSubSeries::where('id',$input['id_series_subseries'])->get()->toArray();
        // Valida si tiene un tipo documental seleccionado
        if(!empty($input["id_type_documentaries"])) {
            $tipo_documental = typeDocumentaries::where("id", $input["id_type_documentaries"])->first();
            // Si tipo $tipo_documental no trae nada (no existe), no hace la asiganción del tipo documental seleccionado
            $tipo_documental && $dataSerie[0]['tipo_documental'] = typeDocumentaries::where("id", $input["id_type_documentaries"])->first()->toArray();
        }

        return $this->sendResponse($dataSerie, trans('data_obtained_successfully'));

    }

    /**
     * Obtiene la informacion de la serie
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function addsSeriesSubseries(Request $request)
    {
        $input = $request->toArray();

        $dataSerie = seriesSubSeries::where('id',$input['id_series_subseries'])->get()->toArray();

        $data = [];

        if ($dataSerie[0]['type'] == 'Serie') {
            $data1 = seriesSubSeries::where('id_serie',$dataSerie[0]['id'])->get()->toArray();

            $data = array_merge($dataSerie,$data1);

        }else{
            $data1 = seriesSubSeries::where('id',$dataSerie[0]['id_serie'])->get()->toArray();
            $data2 = seriesSubSeries::where('id_serie',$data1[0]['id'])->get()->toArray();

            $data = array_merge($data1,$data2);

        }

        return $this->sendResponse($data, trans('data_obtained_successfully'));

        $input = $request->toArray();

    }

    /**
     * Consulta los criterios de búsqueda relacionados a la
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    function consultDataIndex(Request $request) {

        $input = $request->toArray();

        $dataSerie = seriesSubSeries::with('CriteriosBusqueda')->where('id',$input['id'])->get()->first();

        return $this->sendResponse($dataSerie, trans('data_obtained_successfully'));


    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatecriteriosBusquedaRequest $request
     *
     * @return Response
     */
    public function store(Request $request) {

        $input = $request->all();


        if ($input['tipo_campo'] == 'Lista') {
            $input['opciones'] = json_encode($input['list_options']);
        }

        $data = [];
        foreach ($input['serie_subserie'] as $key => $value) {
            $data[] = [
                'cd_series_subseries_id' => $value['id'], // Relaciona la serie/subserie correctamente
                'cd_type_documentaries' => !empty($value['tipo_documental']) ? $value['tipo_documental']["id"] : null
            ];
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $criteriosBusqueda = $this->criteriosBusquedaRepository->create($input);

            if (!empty($data)) {
                $criteriosBusqueda->SeriesSubseries()->sync($data);
            } else {
                $criteriosBusqueda->SeriesSubseries()->detach();
            }

            // Efectua los cambios realizados
            DB::commit();

            // Recargar relaciones para incluir tipoDocumental
            $criteriosBusqueda->load([
                'SeriesSubseries',
                'SeriesSubseries.tipoDocumental' // Asegura que el tipo documental se cargue
            ]);

            return $this->sendResponse($criteriosBusqueda->toArray(), trans('msg_success_save'),'success');
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('documentaryclassification', 'Modules\DocumentaryClassification\Http\Controllers\criteriosBusquedaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('documentaryclassification', 'Modules\DocumentaryClassification\Http\Controllers\criteriosBusquedaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdatecriteriosBusquedaRequest $request
     *
     * @return Response
     */
    public function update(Request $request) {

        $input = $request->all();
        // dd($input['serie_subserie'] );

        if ($input['tipo_campo'] == 'Lista') {
            $input['opciones'] = json_encode($input['list_options']);
        }

        $data = [];

        foreach ($input['serie_subserie'] as $key => $value) {
            $data[] = [
                'cd_series_subseries_id' => $value['id'], // Relaciona la serie/subserie correctamente
                'cd_type_documentaries' => !empty($value['tipo_documental']) ? $value['tipo_documental']["id"] : null
            ];
        }
        // dd($data);
        /** @var criteriosBusqueda $criteriosBusqueda */
        $criteriosBusqueda = $this->criteriosBusquedaRepository->find($input['id']);

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $criteriosBusqueda = $this->criteriosBusquedaRepository->update($input, $input['id']);

            // dd($data);
            if (!empty($data)) {
                $criteriosBusqueda->SeriesSubseries()->sync($data);
            } else {
                $criteriosBusqueda->SeriesSubseries()->detach();
            }

            if (empty($criteriosBusqueda)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            // Efectua los cambios realizados
                DB::commit();
            // Recargar relaciones para incluir tipoDocumental
            $criteriosBusqueda->load([
                'SeriesSubseries',
                'SeriesSubseries.tipoDocumental' // Asegura que el tipo documental se cargue
            ]);
            return $this->sendResponse($criteriosBusqueda->toArray(), trans('msg_success_save'),'success');
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('documentaryclassification', 'Modules\DocumentaryClassification\Http\Controllers\criteriosBusquedaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('documentaryclassification', 'Modules\DocumentaryClassification\Http\Controllers\criteriosBusquedaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un criteriosBusqueda del almacenamiento
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

        /** @var criteriosBusqueda $criteriosBusqueda */
        $criteriosBusqueda = $this->criteriosBusquedaRepository->find($id);

        if (empty($criteriosBusqueda)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $criteriosBusqueda->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('documentaryclassification', 'Modules\DocumentaryClassification\Http\Controllers\criteriosBusquedaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('documentaryclassification', 'Modules\DocumentaryClassification\Http\Controllers\criteriosBusquedaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('criterios_busquedas').'.'.$fileType;

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
}
