<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateAssetTypeRequest;
use Modules\Maintenance\Http\Requests\UpdateAssetTypeRequest;
use Modules\Maintenance\Models\AssetType;
use Modules\Maintenance\Repositories\AssetTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

use Modules\Maintenance\Models\Category;

use Modules\Maintenance\Models\AuthorizedCategories;

use Modules\Maintenance\Models\ResumeEquipmentLeca;
use Modules\Maintenance\Models\ResumeEquipmentMachinery;
use Modules\Maintenance\Models\ResumeEquipmentMachineryLeca;
use Modules\Maintenance\Models\ResumeInventoryLeca;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class AssetTypeController extends AppBaseController {

    /** @var  AssetTypeRepository */
    private $assetTypeRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(AssetTypeRepository $assetTypeRepo) {
        $this->assetTypeRepository = $assetTypeRepo;
    }

    /**
     * Muestra la vista para el CRUD de AssetType.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::asset_types.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $asset_types = $this->assetTypeRepository->all();
        // $asset_types = AssetType::with(['dependency'])->latest()->get();
        return $this->sendResponse($asset_types->toArray(), trans('data_obtained_successfully'));
    }
    
    /**
     * Obtiene todos los elementos existentes, incluyendo los eliminados
     *
     * @author German Gonzalez V. - Sep. 15 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function allAssetFull(Request $request) {
        $asset_types = AssetType::all();
        // $asset_types = AssetType::with(['dependency'])->latest()->get();
        return $this->sendResponse($asset_types->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateAssetTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateAssetTypeRequest $request) {

        $input = $request->all();

        $assetType = $this->assetTypeRepository->create($input);

        return $this->sendResponse($assetType->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateAssetTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAssetTypeRequest $request) {

        $input = $request->all();

        /** @var AssetType $assetType */
        $assetType = $this->assetTypeRepository->find($id);

        if (empty($assetType)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $assetType = $this->assetTypeRepository->update($input, $id);

        return $this->sendResponse($assetType->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un AssetType del almacenamiento
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

        /** @var AssetType $assetType */
        $assetType = $this->assetTypeRepository->find($id);

        if (empty($assetType)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $assetType->delete();

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
        $fileName = time().'-'.trans('asset_types').'.'.$fileType;

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
     * Obtiene el número de veces que esta relacionado un tipo de activo con alguna categoría
     *
     * @author German Gonzalez V. - Sep. 14 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function getCountAssetType(Request $request) {
        // Obtiene el número de veces que esta relacionado un tipo de activo (recibido por parámetro), con al menos una categoría
        $countAssetType = Category::where("mant_asset_type_id", $request->idAssetType)->count();

        // Obtiene el número de veces que esta relacionado un tipo de activo (recibido por parámetro), con una autorización
        $countAssetType_AuthorizedCategories = AuthorizedCategories::where("mant_asset_type_id", $request->idAssetType)->count();

        // Obtiene el número de veces que esta relacionado un tipo de activo (recibido por parámetro), con una hoja de vida
        $countAssetType_AssetYellow = ResumeMachineryVehiclesYellow::where("mant_asset_type_id", $request->idAssetType)->count();
        // Obtiene el número de veces que esta relacionado un tipo de activo (recibido por parámetro), con una hoja de vida
        $countAssetType_AssetInventory = ResumeInventoryLeca::where("mant_asset_type_id", $request->idAssetType)->count();
        // Obtiene el número de veces que esta relacionado un tipo de activo (recibido por parámetro), con una hoja de vida
        $countAssetType_AssetMaquinariaLeca = ResumeEquipmentMachineryLeca::where("mant_asset_type_id", $request->idAssetType)->count();
        // Obtiene el número de veces que esta relacionado un tipo de activo (recibido por parámetro), con una hoja de vida
        $countAssetType_AssetMaquinaria = ResumeEquipmentMachinery::where("mant_asset_type_id", $request->idAssetType)->count();
        // Obtiene el número de veces que esta relacionado un tipo de activo (recibido por parámetro), con una hoja de vida
        $countAssetType_AssetLeca = ResumeEquipmentLeca::where("mant_asset_type_id", $request->idAssetType)->count();

        // Mensaje por defecto para retornar al usuario
        $mensaje = "";
        // Condición para validar si exite al
        if($countAssetType > 0) {
            $mensaje = "No se puede eliminar este tipo de activo ya que se esta relacionado con una categoría";
        }
        // Condición para validar si el tipo de activo esta relacionado con alguna categoría
        if($countAssetType_AuthorizedCategories > 0) {
            $countAssetType = $countAssetType_AuthorizedCategories;
            if($mensaje){
                $mensaje .= " y una autorización de creación de activos";
            } else {
                $mensaje = "No se puede eliminar este tipo de activo ya que se esta relacionado con una autorización de creación de activos";
            }
        } else if($countAssetType_AssetYellow > 0) {
            $countAssetType = $countAssetType_AssetYellow;
            if($mensaje){
                $mensaje .= ", además de una hoja de vida de maquinaria amarilla";
            } else {
                $mensaje = "No se puede eliminar este tipo de activo ya que se esta relacionado con una hoja de vida de maquinaria amarilla";
            }
        } else if($countAssetType_AssetInventory > 0) {
            $countAssetType = $countAssetType_AssetInventory;
            if($mensaje){
                $mensaje .= ", además de una hoja de vida del aseguramiento metrológico";
            } else {
                $mensaje = "No se puede eliminar este tipo de activo ya que se esta relacionado con una hoja de vida del aseguramiento metrológico";
            }
        } else if($countAssetType_AssetMaquinariaLeca > 0) {
            $countAssetType = $countAssetType_AssetMaquinariaLeca;
            if($mensaje){
                $mensaje .= ", además de una hoja de vida de platas y medidores";
            } else {
                $mensaje = "No se puede eliminar este tipo de activo ya que se esta relacionado con una hoja de vida de platas y medidores";
            }
        } else if($countAssetType_AssetMaquinaria > 0) {
            $countAssetType = $countAssetType_AssetMaquinaria;
            if($mensaje){
                $mensaje .= ", además de una hoja de vida de equipos menores";
            } else {
                $mensaje = "No se puede eliminar este tipo de activo ya que se esta relacionado con una hoja de vida de equipos menores";
            }
        } else if($countAssetType_AssetLeca > 0) {
            $countAssetType = $countAssetType_AssetLeca;
            if($mensaje){
                $mensaje .= ", además de una hoja de vida del equipamiento LECA";
            } else {
                $mensaje = "No se puede eliminar este tipo de activo ya que se esta relacionado con una hoja de vida del equipamiento LECA";
            }
        }

        // Retorna el resultado obtenido anteriormente
        return $this->sendResponse($countAssetType, $mensaje);
    }
}
