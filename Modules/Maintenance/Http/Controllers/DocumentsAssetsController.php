<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\Maintenance\RequestExport;
use Modules\Maintenance\Http\Requests\CreateDocumentsAssetsRequest;
use Modules\Maintenance\Http\Requests\UpdateDocumentsAssetsRequest;
use Modules\Maintenance\Repositories\DocumentsAssetsRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use Modules\Maintenance\Models\ResumeEquipmentMachinery;
use Modules\Maintenance\Models\ResumeEquipmentMachineryLeca;
use Modules\Maintenance\Models\ResumeEquipmentLeca;
use Modules\Maintenance\Models\ResumeInventoryLeca;
use Modules\Maintenance\Models\DocumentsAssets;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

/**
 * DEscripcion de la clase
 *
 * @author German Gonzalez V. - Mar. 24 - 2021
 * @version 1.0.0
 */
class DocumentsAssetsController extends AppBaseController {

    /** @var  DocumentsAssetsRepository */
    private $documentsAssetsRepository;

    /**
     * Constructor de la clase
     *
     * @author German Gonzalez V. - Mar. 24 - 2021
     * @version 1.0.0
     */
    public function __construct(DocumentsAssetsRepository $documentsAssetsRepo) {
        $this->documentsAssetsRepository = $documentsAssetsRepo;
    }

    /**
     * Muestra la vista para el CRUD de DocumentsAssets.
     *
     * @author German Gonzalez V. - Mar. 24 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        if($request->mft == 1) {
            $resume_machinery = ResumeMachineryVehiclesYellow::where('id',$request->ma)->first();
            $name_resume_machinery = $resume_machinery["name_vehicle_machinery"];
        } else if($request->mft == 2) {
            $resume_machinery = ResumeEquipmentMachinery::where('id',$request->ma)->first();
            $name_resume_machinery = $resume_machinery["name_equipment"];
        } else if($request->mft == 3) {
            $resume_machinery = ResumeEquipmentMachineryLeca::where('id',$request->ma)->first();
            $name_resume_machinery = $resume_machinery["name_equipment_machinery"];
        } else if($request->mft == 4) {
            $resume_machinery = ResumeEquipmentLeca::where('id',$request->ma)->first();
            $name_resume_machinery = $resume_machinery["name_equipment"];
        } else if($request->mft == 5) {
            $resume_machinery = ResumeInventoryLeca::where('id',$request->ma)->first();
            $name_resume_machinery = $resume_machinery["description_equipment_name"];
        }

        $resume_machinery_id = $request->ma;
        $form_type = $request->mft;

        return view('maintenance::documents_assets.index',compact(['resume_machinery_id','name_resume_machinery','form_type']));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author German Gonzalez V. - Mar. 24 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all($id, $form_type) {

        if($form_type == 1) {
            // Asigna el modelo del activo del formulario de los vehículos y maquinaria amarilla y tambien asigna
            // el nombre de la foranea por la cual se realizara la consula
            $model_form_type = 'mantResumeMachineryVehiclesYellow';
            $foreignField = 'mant_resume_machinery_vehicles_yellow_id';
        } else if($form_type == 2) {
            // Asigna el modelo del activo del formulario de los equipps menores y tambien asigna
            // el nombre de la foranea por la cual se realizara la consula
            $model_form_type = 'mantResumeEquipmentMachinery';
            $foreignField = 'mant_resume_equipment_machinery_id';
        } else if($form_type == 3) {
            // Asigna el modelo del activo del formulario del equipamiento y maquinaria (LECA) y tambien asigna
            // el nombre de la foranea por la cual se realizara la consula
            $model_form_type = 'mantResumeEquipmentMachineryLeca';
            $foreignField = 'mant_resume_equipment_machinery_leca_id';
        } else if($form_type == 4) {
            // Asigna el modelo del activo del formulario del equipamiento (LECA) y tambien asigna
            // el nombre de la foranea por la cual se realizara la consula
            $model_form_type = 'mantResumeEquipmentLeca';
            $foreignField = 'mant_resume_equipment_leca_id';
        } else if($form_type == 5) {
            // Asigna el modelo del activo del formulario del inventario (LECA) y tambien asigna
            // el nombre de la foranea por la cual se realizara la consula
            $model_form_type = 'mantResumeInventoryLeca';
            $foreignField = 'mant_inventory_metrological_schedule_leca_id';
        }

        $documents_assets = DocumentsAssets::where($foreignField, $id)->where('form_type', $form_type)->with([$model_form_type])->latest()->get()->toArray();
        return $this->sendResponse($documents_assets, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author German Gonzalez V. - Mar. 24 - 2021
     * @version 1.0.0
     *
     * @param CreateDocumentsAssetsRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentsAssetsRequest $request, $id, $form_type) {
        $input = $request->all();

        if($form_type == 1) {
            // Asigna el id del activo del registro de los vehículos y maquinaria amarilla
            $input["mant_resume_machinery_vehicles_yellow_id"] = $id;
        } else if($form_type == 2) {
            // Asigna el id del activo del registro de los equipos menores
            $input["mant_resume_equipment_machinery_id"] = $id;
            $input['images_equipo'] = isset($input['images_equipo']) && ($this->toBoolean($input['images_equipo']) || $input['images_equipo'] == 1) ? 1 : 0;

        } else if($form_type == 3) {
            // Asigna el id del activo del registro de equipamiento y maquinaria (LECA)
            $input["mant_resume_equipment_machinery_leca_id"] = $id;
        } else if($form_type == 4) {
            // Asigna el id del activo del registro del equipamiento (LECA)
            $input["mant_resume_equipment_leca_id"] = $id;
        } else if($form_type == 5) {
            // Asigna el id del activo del registro del inventario (LECA)
            $input["mant_inventory_metrological_schedule_leca_id"] = $id;
        }
        $input["form_type"] = $form_type;
        $input['url_document'] = isset($input['url_document']) ? implode(",", $input["url_document"]) : null;
        $documentsAssets = $this->documentsAssetsRepository->create($input);

        if($form_type == 1) {
            // Ejecuta el modelo del activo del formulario de los vehículos y maquinaria amarilla
            $documentsAssets->mantResumeMachineryVehiclesYellow;
        } else if($form_type == 2) {
            // Ejecuta el modelo del activo del formulario de los equipps menores
            $documentsAssets->mantResumeEquipmentMachinery;
        } else if($form_type == 3) {
            // Asigna el modelo del activo del formulario del equipamiento y maquinaria (LECA)
            $documentsAssets->mantResumeEquipmentMachineryLeca;
        } else if($form_type == 4) {
            // Asigna el modelo del activo del formulario del equipamiento (LECA)
            $documentsAssets->mantResumeEquipmentLeca;
        } else if($form_type == 5) {
            // Asigna el modelo del activo del formulario del inventario (LECA)
            $documentsAssets->mantResumeInventoryLeca;
        }

        return $this->sendResponse($documentsAssets->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author German Gonzalez V. - Mar. 24 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDocumentsAssetsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentsAssetsRequest $request) {

        $input = $request->all();

        /** @var DocumentsAssets $documentsAssets */
        $documentsAssets = $this->documentsAssetsRepository->find($id);

        if (empty($documentsAssets)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        $input['images_equipo'] = isset($input['images_equipo']) && ($this->toBoolean($input['images_equipo']) || $input['images_equipo'] == 1) ? 1 : 0;

        $input['url_document'] = isset($input['url_document']) ? implode(",", $input["url_document"]) : null;

        $documentsAssets = $this->documentsAssetsRepository->update($input, $id);

        if($documentsAssets->form_type == 1) {
            // Ejecuta el modelo del activo del formulario de los vehículos y maquinaria amarilla
            $documentsAssets->mantResumeMachineryVehiclesYellow;
        } else if($documentsAssets->form_type == 2) {
            // Ejecuta el modelo del activo del formulario de los equipps menores
            $documentsAssets->mantResumeEquipmentMachinery;
        } else if($documentsAssets->form_type == 3) {
            // Asigna el modelo del activo del formulario del equipamiento y maquinaria (LECA)
            $documentsAssets->mantResumeEquipmentMachineryLeca;
        } else if($documentsAssets->form_type == 4) {
            // Asigna el modelo del activo del formulario del equipamiento (LECA)
            $documentsAssets->mantResumeEquipmentLeca;
        } else if($documentsAssets->form_type == 5) {
            // Asigna el modelo del activo del formulario del inventario (LECA)
            $documentsAssets->mantResumeInventoryLeca;
        }

        return $this->sendResponse($documentsAssets->toArray(), trans('msg_success_update'));
    }

    /**
     * Elimina un DocumentsAssets del almacenamiento
     *
     * @author German Gonzalez V. - Mar. 24 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var DocumentsAssets $documentsAssets */
        $documentsAssets = $this->documentsAssetsRepository->find($id);

        if (empty($documentsAssets)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $documentsAssets->delete();

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
    public function export(Request $request)
    {

        $input = $request->all();
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // $fileName = date('Y-m-d H:i:s').'-'.trans('tireBrand').'.'.$fileType;
        $fileName = 'excel.' . $fileType;

        return Excel::download(new RequestExport('maintenance::documents_assets.report_excel', $input['data'], 'c'), $fileName);
    }
}
