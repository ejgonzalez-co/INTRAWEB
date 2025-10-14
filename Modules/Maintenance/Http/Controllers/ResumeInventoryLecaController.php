<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateResumeInventoryLecaRequest;
use Modules\Maintenance\Http\Requests\UpdateResumeInventoryLecaRequest;
use Modules\Maintenance\Repositories\ResumeInventoryLecaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

use Modules\Maintenance\Models\ScheduleInventoryLeca;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ResumeInventoryLecaController extends AppBaseController {

    /** @var  ResumeInventoryLecaRepository */
    private $resumeInventoryLecaRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ResumeInventoryLecaRepository $resumeInventoryLecaRepo) {
        $this->resumeInventoryLecaRepository = $resumeInventoryLecaRepo;
    }

    /**
     * Muestra la vista para el CRUD de ResumeInventoryLeca.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('resume_inventory_lecas.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $resume_inventory_lecas = $this->resumeInventoryLecaRepository->all();
        return $this->sendResponse($resume_inventory_lecas->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateResumeInventoryLecaRequest $request
     *
     * @return Response
     */
    public function store(CreateResumeInventoryLecaRequest $request) {

        $input = $request->all();

        $input["total_interventions"] = count($input['schedule_inventory_leca'] ?? []);

        $resumeInventoryLeca = $this->resumeInventoryLecaRepository->create($input);

        // Condición para validar si existe algún registro de espeficicación del equipo LECA
        if (!empty($input['schedule_inventory_leca'])) {
            // Ciclo para recorrer todos los registros de espeficicación del equipo LECA
            foreach($input['schedule_inventory_leca'] as $option){

                $arrayScheduleInventoryLeca = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                ScheduleInventoryLeca::create([
                    'month' => $arrayScheduleInventoryLeca->month,
                    'metrological_activity' => $arrayScheduleInventoryLeca->metrological_activity,
                    'description' => $arrayScheduleInventoryLeca->description,
                    'mant_inventory_metrological_schedule_leca_id' => $resumeInventoryLeca->id
                    ]);
            }

        }

        // Ejecuta el modelo de proveedor
        $resumeInventoryLeca->provider;
        // Ejecuta el modelo de cronograma del equipamiento
        $resumeInventoryLeca->scheduleInventoryLeca;
        // Ejecuta el modelo de categoría
        $resumeInventoryLeca->mantCategory;
        // Ejecuta el modelo de documentos adjuntos relacionados
        $resumeInventoryLeca->mantDocumentsAsset;

        return $this->sendResponse($resumeInventoryLeca->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateResumeInventoryLecaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateResumeInventoryLecaRequest $request) {

        $input = $request->all();

        /** @var ResumeInventoryLeca $resumeInventoryLeca */
        $resumeInventoryLeca = $this->resumeInventoryLecaRepository->find($id);

        if (empty($resumeInventoryLeca)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $resumeInventoryLeca = $this->resumeInventoryLecaRepository->update($input, $id);
        // Eliminar los registros de cronograma del equipo LECA existentes según el id del registro principal
        ScheduleInventoryLeca::where('mant_inventory_metrological_schedule_leca_id', $resumeInventoryLeca->id)->delete();
        // Ciclo para recorrer todos los registros de espeficicación del equipo LECA
        foreach($input['schedule_inventory_leca'] ?? [] as $option) {

            $arrayScheduleInventoryLeca = json_decode($option);
            // Se crean la cantidad de registros ingresados por el usuario
            ScheduleInventoryLeca::create([
                'month' => $arrayScheduleInventoryLeca->month,
                'metrological_activity' => $arrayScheduleInventoryLeca->metrological_activity,
                'description' => $arrayScheduleInventoryLeca->description,
                'mant_inventory_metrological_schedule_leca_id' => $resumeInventoryLeca->id
                ]);
        }

        // Ejecuta el modelo de proveedor
        $resumeInventoryLeca->provider;
        // Ejecuta el modelo de cronograma del equipamiento
        $resumeInventoryLeca->scheduleInventoryLeca;
        // Ejecuta el modelo de categoría
        $resumeInventoryLeca->mantCategory;
        // Ejecuta el modelo de documentos adjuntos relacionados
        $resumeInventoryLeca->mantDocumentsAsset;

        return $this->sendResponse($resumeInventoryLeca->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un ResumeInventoryLeca del almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var ResumeInventoryLeca $resumeInventoryLeca */
        $resumeInventoryLeca = $this->resumeInventoryLecaRepository->find($id);

        if (empty($resumeInventoryLeca)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $resumeInventoryLeca->delete();

        return $this->sendSuccess(trans('msg_success_drop'));

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('resume_inventory_lecas').'.'.$fileType;

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
