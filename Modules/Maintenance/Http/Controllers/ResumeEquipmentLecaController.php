<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateResumeEquipmentLecaRequest;
use Modules\Maintenance\Http\Requests\UpdateResumeEquipmentLecaRequest;
use Modules\Maintenance\Repositories\ResumeEquipmentLecaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use Modules\Maintenance\Models\ResumeEquipmentMachinery;
use Modules\Maintenance\Models\ResumeEquipmentMachineryLeca;
use Modules\Maintenance\Models\ResumeEquipmentLeca;
use Modules\Maintenance\Models\ResumeInventoryLeca;
use Modules\Maintenance\Models\SpecificationsEquipmentLeca;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ResumeEquipmentLecaController extends AppBaseController {

    /** @var  ResumeEquipmentLecaRepository */
    private $resumeEquipmentLecaRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ResumeEquipmentLecaRepository $resumeEquipmentLecaRepo) {
        $this->resumeEquipmentLecaRepository = $resumeEquipmentLecaRepo;
    }

    /**
     * Muestra la vista para el CRUD de ResumeEquipmentLeca.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('resume_equipment_lecas.index');
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
        $resume_equipment_lecas = $this->resumeEquipmentLecaRepository->all();
        return $this->sendResponse($resume_equipment_lecas->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateResumeEquipmentLecaRequest $request
     *
     * @return Response
     */
    public function store(CreateResumeEquipmentLecaRequest $request) {

        $input = $request->all();
        $code = 0;
        $code1 = 0;
        $code2 = 0;
        $code3 = 0;
        $code4 = 0;

        if ($request['inventory_no']) {
            $code = ResumeEquipmentLeca::where('inventory_no',$request['inventory_no'])->get()->count();
            $code1 = ResumeEquipmentMachinery::where('no_inventory',$request['inventory_no'])->get()->count();
            $code2 = ResumeMachineryVehiclesYellow::where('no_inventory',$request['inventory_no'])->get()->count();
            $code3 = ResumeInventoryLeca::where('no_inventory_epa_esp',$request['inventory_no'])->get()->count();
            $code4 = ResumeEquipmentMachineryLeca::where('no_inventory_epa_esp',$request['inventory_no'])->get()->count();
        }

        if ($code > 0 || $code1 > 0 || $code2 > 0 || $code3 > 0 || $code4 > 0) {

            return $this->sendSuccess('El número de inventario ingresado ya esta en uso', 'error');

        }else{

            $resumeEquipmentLeca = $this->resumeEquipmentLecaRepository->create($input);

            // Condición para validar si existe algún registro de espeficicación del equipo LECA
            if (!empty($input['specifications_equipment_leca'])) {
                // Ciclo para recorrer todos los registros de espeficicación del equipo LECA
                foreach($input['specifications_equipment_leca'] as $option){

                    $arraySpecificationsEquipmentLeca = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    SpecificationsEquipmentLeca::create([
                        'calibration_verification_points' => $arraySpecificationsEquipmentLeca->calibration_verification_points,
                        'reference_standard_calibration_verification' => $arraySpecificationsEquipmentLeca->reference_standard_calibration_verification,
                        'acceptance_requirements' => $arraySpecificationsEquipmentLeca->acceptance_requirements,
                        'mant_resume_equipment_leca_id' => $resumeEquipmentLeca->id
                        ]);
                }

            }

            // Ejecuta el modelo de proveedor
            $resumeEquipmentLeca->provider;
            // Ejecuta el modelo de especificaciones técnicas del equipamiento
            $resumeEquipmentLeca->specificationsEquipmentLeca;
            // Ejecuta el modelo de categoría
            $resumeEquipmentLeca->mantCategory;
            // Ejecuta el modelo de dependencia
            $resumeEquipmentLeca->dependencies;
            // Ejecuta el modelo de documentos adjuntos relacionados
            $resumeEquipmentLeca->mantDocumentsAsset;

            return $this->sendResponse($resumeEquipmentLeca->toArray(), trans('msg_success_save'));

        }
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateResumeEquipmentLecaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateResumeEquipmentLecaRequest $request) {

        $input = $request->all();
        $code = 0;
        $code1 = 0;
        $code2 = 0;
        $code3 = 0;
        $code4 = 0;

        if (array_key_exists("provider", $input) && ($input['mant_providers_id'] === null)) {
            $input['mant_providers_id'] = $input['provider']['id'] ?? null;
            unset($input['provider']);
        }

        if ($request['inventory_no']) {
            $code = ResumeEquipmentLeca::where('inventory_no',$request['inventory_no'])->where('id','!=',$id)->get()->count();
            $code1 = ResumeEquipmentMachinery::where('no_inventory',$request['inventory_no'])->get()->count();
            $code2 = ResumeMachineryVehiclesYellow::where('no_inventory',$request['inventory_no'])->get()->count();
            $code3 = ResumeInventoryLeca::where('no_inventory_epa_esp',$request['inventory_no'])->get()->count();
            $code4 = ResumeEquipmentMachineryLeca::where('no_inventory_epa_esp',$request['inventory_no'])->get()->count();
        }

        /** @var ResumeEquipmentLeca $resumeEquipmentLeca */
        $resumeEquipmentLeca = $this->resumeEquipmentLecaRepository->find($id);

        if (empty($resumeEquipmentLeca)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        if ($code > 0 || $code1 > 0 || $code2 > 0 || $code3 > 0 || $code4 > 0) {

            return $this->sendSuccess('El número de inventario ingresado ya esta en uso', 'error');

        }else{

            $resumeEquipmentLeca = $this->resumeEquipmentLecaRepository->update($input, $id);
            // Eliminar los registros de especificación del equipo LECA existentes según el id del registro principal
            SpecificationsEquipmentLeca::where('mant_resume_equipment_leca_id', $resumeEquipmentLeca->id)->delete();
            // Ciclo para recorrer todos los registros de espeficicación del equipo LECA
            foreach($input['specifications_equipment_leca'] ?? [] as $option){

                $arraySpecificationsEquipmentLeca = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                SpecificationsEquipmentLeca::create([
                    'calibration_verification_points' => $arraySpecificationsEquipmentLeca->calibration_verification_points,
                    'reference_standard_calibration_verification' => $arraySpecificationsEquipmentLeca->reference_standard_calibration_verification,
                    'acceptance_requirements' => $arraySpecificationsEquipmentLeca->acceptance_requirements,
                    'mant_resume_equipment_leca_id' => $resumeEquipmentLeca->id
                    ]);
            }

            // Ejecuta el modelo de proveedor
            $resumeEquipmentLeca->provider;
            // Ejecuta el modelo de especificaciones técnicas del equipamiento
            $resumeEquipmentLeca->specificationsEquipmentLeca;
            // Ejecuta el modelo de categoría
            $resumeEquipmentLeca->mantCategory;
            // Ejecuta el modelo de dependencia
            $resumeEquipmentLeca->dependencies;
            // Ejecuta el modelo de documentos adjuntos relacionados
            $resumeEquipmentLeca->mantDocumentsAsset;

            return $this->sendResponse($resumeEquipmentLeca->toArray(), trans('msg_success_update'));

        }

    }

    /**
     * Elimina un ResumeEquipmentLeca del almacenamiento
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

        /** @var ResumeEquipmentLeca $resumeEquipmentLeca */
        $resumeEquipmentLeca = $this->resumeEquipmentLecaRepository->find($id);

        if (empty($resumeEquipmentLeca)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $resumeEquipmentLeca->delete();

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
        $fileName = time().'-'.trans('resume_equipment_lecas').'.'.$fileType;

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
