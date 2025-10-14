<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateResumeEquipmentMachineryRequest;
use Modules\Maintenance\Http\Requests\UpdateResumeEquipmentMachineryRequest;
use Modules\Maintenance\Repositories\ResumeEquipmentMachineryRepository;
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
use Modules\Maintenance\Models\AssetType;
use Modules\Maintenance\Models\CharacteristicsEquipment;
use Modules\Maintenance\Models\SnActivesHeading;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ResumeEquipmentMachineryController extends AppBaseController {

    /** @var  ResumeEquipmentMachineryRepository */
    private $resumeEquipmentMachineryRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ResumeEquipmentMachineryRepository $resumeEquipmentMachineryRepo) {
        $this->resumeEquipmentMachineryRepository = $resumeEquipmentMachineryRepo;
    }

    /**
     * Muestra la vista para el CRUD de ResumeEquipmentMachinery.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        // return view('resume_equipment_machineries.index');

        $resume_machinery_vehicles_yellows2 = AssetType::latest()->get();

        // dd($resume_machinery_vehicles_yellows2->toArray());
        return view('maintenance::resume_machinery_vehicles_yellows.index')->with('consolidatedRequestBoard', $resume_machinery_vehicles_yellows2->toArray());
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
        $resume_equipment_machineries = $this->resumeEquipmentMachineryRepository->all();
        return $this->sendResponse($resume_equipment_machineries->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateResumeEquipmentMachineryRequest $request
     *
     * @return Response
     */
    public function store(CreateResumeEquipmentMachineryRequest $request) {

       

        $input = $request->all();
        $code = 0;
        $code1 = 0;
        $code2 = 0;
        $code3 = 0;
        $code4 = 0;


        
        if ($request['no_inventory']) {
            $code = ResumeEquipmentMachinery::where('no_inventory',$request['no_inventory'])->get()->count();
            $code1 = ResumeEquipmentLeca::where('inventory_no',$request['no_inventory'])->get()->count();
            $code2 = ResumeMachineryVehiclesYellow::where('no_inventory',$request['no_inventory'])->get()->count();
            $code3 = ResumeInventoryLeca::where('no_inventory_epa_esp',$request['no_inventory'])->get()->count();
            $code4 = ResumeEquipmentMachineryLeca::where('no_inventory_epa_esp',$request['no_inventory'])->get()->count();
        }

        if ($code > 0 || $code1 > 0 || $code2 > 0 || $code3 > 0 || $code4 > 0) {

            return $this->sendSuccess('El número de inventario ingresado ya esta en uso', 'error');

        }else{

            unset($input["type_person"]);
            unset($input["document_type"]);
            unset($input["name"]);
            unset($input["mail"]);
            unset($input["transit_license_number"]);
            unset($input["phone"]);
            unset($input["address"]);


            // dd( $request->toArray());
            $resumeEquipmentMachinery = $this->resumeEquipmentMachineryRepository->create($input);

            // Condición para validar si existe algún registro de carácterísticas del equipo
            if (!empty($input['characteristics_equipment'])) {
                // Ciclo para recorrer todos los registros de carácterísticas del equipo
                foreach($input['characteristics_equipment'] as $option){

                    $arrayContactEmails = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    CharacteristicsEquipment::create([
                        'accessory_parts' => $arrayContactEmails->accessory_parts,
                        'amount' => $arrayContactEmails->amount,
                        'reference_part_number' => $arrayContactEmails->reference_part_number,
                        'mant_resume_equipment_machinery_id' => $resumeEquipmentMachinery->id
                        ]);
                }

            }

            // Ejecuta el modelo de proveedor
            $resumeEquipmentMachinery->provider;
            // Ejecuta el modelo de carácterísticas del equipo
            $resumeEquipmentMachinery->characteristicsEquipment;
            // Ejecuta el modelo de proveedor
            $resumeEquipmentMachinery->provider;
            // Ejecuta el modelo de categoría
            $resumeEquipmentMachinery->mantCategory;
            // Ejecuta el modelo de dependencia
            $resumeEquipmentMachinery->dependencies;
            // Ejecuta el modelo de documentos adjuntos relacionados
            $resumeEquipmentMachinery->mantDocumentsAsset;

            $resumeEquipmentMachinery->assetType;

            return $this->sendResponse($resumeEquipmentMachinery->toArray(), trans('msg_success_save'));

        }
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateResumeEquipmentMachineryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateResumeEquipmentMachineryRequest $request) {

        $input = $request->all();
        $code = 0;
        $code1 = 0;
        $code2 = 0;
        $code3 = 0;
        $code4 = 0;

        if (array_key_exists("provider", $input) && ($input['mant_providers_id'] === null)) {
            $input['mant_providers_id'] = $input['provider']['id'] ?? null;
        }
        

        if ($request['no_inventory']) {
            $code = ResumeEquipmentMachinery::where('no_inventory',$request['no_inventory'])->where('id','!=',$id)->get()->count();
            $code1 = ResumeEquipmentLeca::where('inventory_no',$request['no_inventory'])->get()->count();
            $code2 = ResumeMachineryVehiclesYellow::where('no_inventory',$request['no_inventory'])->get()->count();
            $code3 = ResumeInventoryLeca::where('no_inventory_epa_esp',$request['no_inventory'])->get()->count();
            $code4 = ResumeEquipmentMachineryLeca::where('no_inventory_epa_esp',$request['no_inventory'])->get()->count();
        }

        /** @var ResumeEquipmentMachinery $resumeEquipmentMachinery */
        $resumeEquipmentMachinery = $this->resumeEquipmentMachineryRepository->find($id);

        if (empty($resumeEquipmentMachinery)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        if ($code > 0 || $code1 > 0 || $code2 > 0 || $code3 > 0 || $code4 > 0) {

            return $this->sendSuccess('El número de inventario ingresado ya esta en uso', 'error');

        }else{

            $resumeEquipmentMachinery = $this->resumeEquipmentMachineryRepository->update($input, $id);
            // Eliminar los registros de carácterísticas del equipo existentes según el id del registro principal
            CharacteristicsEquipment::where('mant_resume_equipment_machinery_id', $resumeEquipmentMachinery->id)->delete();
            // Ciclo para recorrer todos los registros de carácterísticas del equipo
            foreach($input['characteristics_equipment'] ?? [] as $option) {

                $arrayContactEmails = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                CharacteristicsEquipment::create([
                    'accessory_parts' => $arrayContactEmails->accessory_parts,
                    'amount' => $arrayContactEmails->amount,
                    'reference_part_number' => $arrayContactEmails->reference_part_number,
                    'mant_resume_equipment_machinery_id' => $resumeEquipmentMachinery->id
                    ]);
            }
            
            // Eliminar los registros de rubros asignados en mant_sn_actives_heading
            SnActivesHeading::where('activo_id', $resumeEquipmentMachinery->id)->where('activo_tipo', 'ResumeEquipmentMachinery')->delete();
            // Ciclo para recorrer los rubros que se ingresan en el formulario (el formulario esta en AssetsCreate)
            foreach($input['rubros_asignados'] ?? [] as $option) {

                $arrayActual = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                SnActivesHeading::create([
                    'activo_tipo' => 'ResumeEquipmentMachinery',
                    'activo_id' => $resumeEquipmentMachinery->id,
                    'rubro_id' => $arrayActual->rubro_id,
                    'rubro_codigo' => $arrayActual->rubro_codigo,
                    'centro_costo_id' => $arrayActual->centro_costo_id,
                    'centro_costo_codigo' => $arrayActual->centro_costo_codigo,
                    ]);
            }
     


            // Ejecuta el modelo de proveedor
            $resumeEquipmentMachinery->provider;
            // Ejecuta el modelo de carácterísticas del equipo
            $resumeEquipmentMachinery->characteristicsEquipment;
            // Ejecuta el modelo de proveedor
            $resumeEquipmentMachinery->provider;
            // Ejecuta el modelo de categoría
            $resumeEquipmentMachinery->mantCategory;
            // Ejecuta el modelo de dependencia
            $resumeEquipmentMachinery->dependencies;
            // Ejecuta el modelo de documentos adjuntos relacionados
            $resumeEquipmentMachinery->mantDocumentsAsset;

            //Relacion con rubros 
            $resumeEquipmentMachinery->rubrosAsignados;

            return $this->sendResponse($resumeEquipmentMachinery->toArray(), trans('msg_success_update'));
        }

    }

    /**
     * Elimina un ResumeEquipmentMachinery del almacenamiento
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

        /** @var ResumeEquipmentMachinery $resumeEquipmentMachinery */
        $resumeEquipmentMachinery = $this->resumeEquipmentMachineryRepository->find($id);

        if (empty($resumeEquipmentMachinery)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $resumeEquipmentMachinery->delete();

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
        $fileName = time().'-'.trans('resume_equipment_machineries').'.'.$fileType;

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
