<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateResumeEquipmentMachineryLecaRequest;
use Modules\Maintenance\Http\Requests\UpdateResumeEquipmentMachineryLecaRequest;
use Modules\Maintenance\Repositories\ResumeEquipmentMachineryLecaRepository;
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
use Modules\Maintenance\Models\CompositionEquipmentLeca;
use Modules\Maintenance\Models\MaintenanceEquipmentLeca;
/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ResumeEquipmentMachineryLecaController extends AppBaseController {

    /** @var  ResumeEquipmentMachineryLecaRepository */
    private $resumeEquipmentMachineryLecaRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ResumeEquipmentMachineryLecaRepository $resumeEquipmentMachineryLecaRepo) {
        $this->resumeEquipmentMachineryLecaRepository = $resumeEquipmentMachineryLecaRepo;
    }

    /**
     * Muestra la vista para el CRUD de ResumeEquipmentMachineryLeca.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('resume_equipment_machinery_lecas.index');
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
        $resume_equipment_machinery_lecas = $this->resumeEquipmentMachineryLecaRepository->all();
        return $this->sendResponse($resume_equipment_machinery_lecas->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateResumeEquipmentMachineryLecaRequest $request
     *
     * @return Response
     */
    public function store(CreateResumeEquipmentMachineryLecaRequest $request) {

        $input = $request->all();
        $code = 0;
        $code1 = 0;
        $code2 = 0;
        $code3 = 0;
        $code4 = 0;

        if ($request['no_inventory_epa_esp']) {
            $code = ResumeMachineryVehiclesYellow::where('no_inventory',$request['no_inventory_epa_esp'])->get()->count();
            $code1 = ResumeEquipmentLeca::where('inventory_no',$request['no_inventory_epa_esp'])->get()->count();
            $code2 = ResumeEquipmentMachinery::where('no_inventory',$request['no_inventory_epa_esp'])->get()->count();
            $code3 = ResumeInventoryLeca::where('no_inventory_epa_esp',$request['no_inventory_epa_esp'])->get()->count();
            $code4 = ResumeEquipmentMachineryLeca::where('no_inventory_epa_esp',$request['no_inventory_epa_esp'])->get()->count();
        }

        if ($code > 0 || $code1 > 0 || $code2 > 0 || $code3 > 0 || $code4 > 0) {
            
            return $this->sendSuccess('El número de inventario ingresado ya esta en uso', 'error');

        }else{

            $resumeEquipmentMachineryLeca = $this->resumeEquipmentMachineryLecaRepository->create($input);

            // Condición para validar si existe algún registro de composición del equipo LECA
            if (!empty($input['composition_equipment_leca'])) {
                // Ciclo para recorrer todos los registros de composición del equipo LECA
                foreach($input['composition_equipment_leca'] as $option){

                    $arrayCompositionEquipment = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    CompositionEquipmentLeca::create([
                        'accessory_parts' => $arrayCompositionEquipment->accessory_parts,
                        'reference' => $arrayCompositionEquipment->reference,
                        // 'observation' => $arrayCompositionEquipment->observation,
                        'mant_resume_equipment_machinery_leca_id' => $resumeEquipmentMachineryLeca->id
                        ]);
                }

            }

            // Condición para validar si existe algún registro de composición del equipo LECA
            if (!empty($input['maintenance_equipment_leca'])) {
                // Ciclo para recorrer todos los registros de composición del equipo LECA
                foreach($input['maintenance_equipment_leca'] as $option){

                    $arrayMaintenanceEquipment = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    MaintenanceEquipmentLeca::create([
                        'name' => $arrayMaintenanceEquipment->name,
                        'acceptance_requirements' => $arrayMaintenanceEquipment->acceptance_requirements,
                        'mant_resume_equipment_machinery_leca_id' => $resumeEquipmentMachineryLeca->id,
                        'verification' => $arrayMaintenanceEquipment->verification,
                        'calibration_under_accreditation' => $arrayMaintenanceEquipment->calibration_under_accreditation,
                        'rule_reference_calibration' => $arrayMaintenanceEquipment->rule_reference_calibration,
                        'criteria_acceptance_certificate' => $arrayMaintenanceEquipment->criteria_acceptance_certificate,
                        'measure_standard' => $arrayMaintenanceEquipment->measure_standard
                        ]);
                }

            }

            // Ejecuta el modelo de proveedor
            $resumeEquipmentMachineryLeca->provider;
            // Ejecuta el modelo de carácterísticas del equipo
            $resumeEquipmentMachineryLeca->compositionEquipmentLeca;
            // Ejecuta el modelo de carácterísticas del equipo
            $resumeEquipmentMachineryLeca->maintenanceEquipmentLeca;
            // Ejecuta el modelo de categoría
            $resumeEquipmentMachineryLeca->mantCategory;
            // Ejecuta el modelo de dependencia
            $resumeEquipmentMachineryLeca->dependencies;
            // Ejecuta el modelo de documentos adjuntos relacionados
            $resumeEquipmentMachineryLeca->mantDocumentsAsset;

            return $this->sendResponse($resumeEquipmentMachineryLeca->toArray(), trans('msg_success_save'));

        }
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateResumeEquipmentMachineryLecaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateResumeEquipmentMachineryLecaRequest $request) {

        
        $input = $request->all();
        $code = 0;
        $code1 = 0;
        $code2 = 0;
        $code3 = 0;
        $code4 = 0;
        
        if (array_key_exists("provider", $input) && ($input['mant_providers_id'] === null)) {
            $input['mant_providers_id'] = $input['provider']['id'] ?? null;
        }
        
        if ($request['no_inventory_epa_esp']) {
            $code = ResumeEquipmentMachineryLeca::where('no_inventory_epa_esp',$request['no_inventory_epa_esp'])->where('id','!=',$id)->get()->count();
            $code1 = ResumeEquipmentLeca::where('inventory_no',$request['no_inventory_epa_esp'])->get()->count();
            $code2 = ResumeEquipmentMachinery::where('no_inventory',$request['no_inventory_epa_esp'])->get()->count();
            $code3 = ResumeInventoryLeca::where('no_inventory_epa_esp',$request['no_inventory_epa_esp'])->get()->count();
            $code4 = ResumeMachineryVehiclesYellow::where('no_inventory',$request['no_inventory_epa_esp'])->get()->count();    
        }

        /** @var ResumeEquipmentMachineryLeca $resumeEquipmentMachineryLeca */
        $resumeEquipmentMachineryLeca = $this->resumeEquipmentMachineryLecaRepository->find($id);

        if (empty($resumeEquipmentMachineryLeca)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        
        if ($code > 0 || $code1 > 0 || $code2 > 0 || $code3 > 0 || $code4 > 0) {
            
            return $this->sendSuccess('El número de inventario ingresado ya esta en uso', 'error');

        }else{
            //Valida si el precio de compra viene vacio para asignarlo a 0
            if (!array_key_exists('purchase_price',$input)) {
               $input['purchase_price'] = ' ';
            }

            $resumeEquipmentMachineryLeca = $this->resumeEquipmentMachineryLecaRepository->update($input, $id);
            // Eliminar los registros de composición del equipo LECA existentes según el id del registro principal
            CompositionEquipmentLeca::where('mant_resume_equipment_machinery_leca_id', $resumeEquipmentMachineryLeca->id)->delete();
            // Ciclo para recorrer todos los registros de composición del equipo LECA
            foreach($input['composition_equipment_leca'] ?? [] as $option) {

                $arrayCompositionEquipment = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                CompositionEquipmentLeca::create([
                    'accessory_parts' => $arrayCompositionEquipment->accessory_parts,
                    'reference' => $arrayCompositionEquipment->reference,
                    // 'observation' => $arrayCompositionEquipment->observation,
                    'mant_resume_equipment_machinery_leca_id' => $resumeEquipmentMachineryLeca->id
                    ]);
            }

            // Eliminar los registros de mantenimiento del equipo LECA existentes según el id del registro principal
            MaintenanceEquipmentLeca::where('mant_resume_equipment_machinery_leca_id', $resumeEquipmentMachineryLeca->id)->delete();
            // Ciclo para recorrer todos los registros de mantenimiento del equipo LECA
            foreach($input['maintenance_equipment_leca'] ?? [] as $option) {

                $arrayMaintenanceEquipment = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                MaintenanceEquipmentLeca::create([
                    'name' => $arrayMaintenanceEquipment->name ?? '',
                    'acceptance_requirements' => $arrayMaintenanceEquipment->acceptance_requirements ?? '',
                    'mant_resume_equipment_machinery_leca_id' => $resumeEquipmentMachineryLeca->id,
                    'verification' => $arrayMaintenanceEquipment->verification ?? '',
                    'calibration_under_accreditation' => $arrayMaintenanceEquipment->calibration_under_accreditation ?? '',
                    'rule_reference_calibration' => $arrayMaintenanceEquipment->rule_reference_calibration ?? '',
                    'criteria_acceptance_certificate' => $arrayMaintenanceEquipment->criteria_acceptance_certificate ?? '',
                    'measure_standard' => $arrayMaintenanceEquipment->measure_standard ?? ''
                    ]);
            }

            // Ejecuta el modelo de proveedor
            $resumeEquipmentMachineryLeca->provider;
            // Ejecuta el modelo de carácterísticas del equipo
            $resumeEquipmentMachineryLeca->compositionEquipmentLeca;
            // Ejecuta el modelo de carácterísticas del equipo
            $resumeEquipmentMachineryLeca->maintenanceEquipmentLeca;
            // Ejecuta el modelo de categoría
            $resumeEquipmentMachineryLeca->mantCategory;
            // Ejecuta el modelo de dependencia
            $resumeEquipmentMachineryLeca->dependencies;
            // Ejecuta el modelo de documentos adjuntos relacionados
            $resumeEquipmentMachineryLeca->mantDocumentsAsset;

            // dd($resumeEquipmentMachineryLeca->toArray());

            return $this->sendResponse($resumeEquipmentMachineryLeca->toArray(), trans('msg_success_update'));

        }

    }

    /**
     * Elimina un ResumeEquipmentMachineryLeca del almacenamiento
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

        /** @var ResumeEquipmentMachineryLeca $resumeEquipmentMachineryLeca */
        $resumeEquipmentMachineryLeca = $this->resumeEquipmentMachineryLecaRepository->find($id);

        if (empty($resumeEquipmentMachineryLeca)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $resumeEquipmentMachineryLeca->delete();

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
        $fileName = time().'-'.trans('resume_equipment_machinery_lecas').'.'.$fileType;

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
