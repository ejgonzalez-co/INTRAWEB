<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateAssetCreateAuthorizationRequest;
use Modules\Maintenance\Http\Requests\UpdateAssetCreateAuthorizationRequest;
use Modules\Maintenance\Repositories\AssetCreateAuthorizationRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\Maintenance\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\Maintenance\Models\AuthorizedCategories;
use Modules\Maintenance\Models\AssetCreateAuthorization;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use App\Http\Controllers\JwtController;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class AssetCreateAuthorizationController extends AppBaseController {

    /** @var  AssetCreateAuthorizationRepository */
    private $assetCreateAuthorizationRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(AssetCreateAuthorizationRepository $assetCreateAuthorizationRepo) {
        $this->assetCreateAuthorizationRepository = $assetCreateAuthorizationRepo;
    }

    /**
     * Muestra la vista para el CRUD de AssetCreateAuthorization.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::asset_create_authorizations.index');
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
        // $asset_create_authorizations = $this->assetCreateAuthorizationRepository->all();
        $asset_create_authorizations = AssetCreateAuthorization::with(['dependencias', 'usuarios', 'authorizedCategoriesModel'])->latest()->get();
        
        return $this->sendResponse($asset_create_authorizations->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author German Gonzalez V. - Abr. 16 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function show($id) {

        $assetCreateAuthorization = $this->assetCreateAuthorizationRepository->find($id);

        if (empty($assetCreateAuthorization)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $assetCreateAuthorization->authorizedCategoriesModel;
        $assetCreateAuthorization->dependencias;
        $assetCreateAuthorization->usuarios;

        return $this->sendResponse($assetCreateAuthorization->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateAssetCreateAuthorizationRequest $request
     *
     * @return Response
     */
    public function store(CreateAssetCreateAuthorizationRequest $request) {

        $input = $request->all();

        $assetCreateAuthorization = $this->assetCreateAuthorizationRepository->create($input);
        // Condición para validar si existe algún registro de categorías autorizadas
        if (!empty($input['authorized_categories_model'])) {
            // Ciclo para recorrer todos los registros de categorías autorizadas
            foreach($input['authorized_categories_model'] as $option){

                $arrayCategoryAuthorized = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                AuthorizedCategories::create([
                    'mant_asset_type_id' => $arrayCategoryAuthorized->mant_asset_type_id,
                    'mant_category_id' => $arrayCategoryAuthorized->mant_category_id,
                    'asset_authorization_id' => $assetCreateAuthorization->id
                    ]);
            }
        }
        // Ejecuta el modelo de autorización de categorías de activos
        $assetCreateAuthorization->authorizedCategoriesModel;
        // Ejecuta el modelo de categorías
        $assetCreateAuthorization->dependencias;
        // Ejecuta el modelo de usuarios
        $assetCreateAuthorization->usuarios;

        return $this->sendResponse($assetCreateAuthorization->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateAssetCreateAuthorizationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAssetCreateAuthorizationRequest $request) {

        $input = $request->all();

        /** @var AssetCreateAuthorization $assetCreateAuthorization */
        $assetCreateAuthorization = $this->assetCreateAuthorizationRepository->find($id);

        if (empty($assetCreateAuthorization)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $assetCreateAuthorization = $this->assetCreateAuthorizationRepository->update($input, $id);
        // Condición para validar si existe algún registro de categorías autorizadas
        if (!empty($input['authorized_categories_model'])) {
            // Eliminar los registros de categorías autorizadas existentes según el id del registro principal
            AuthorizedCategories::where('asset_authorization_id', $assetCreateAuthorization->id)->delete();
            // Ciclo para recorrer todos los registros de categorías autorizadas
            foreach($input['authorized_categories_model'] as $option){

                $arrayCategoryAuthorized = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                AuthorizedCategories::create([
                    'mant_asset_type_id' => $arrayCategoryAuthorized->mant_asset_type_id,
                    'mant_category_id' => $arrayCategoryAuthorized->mant_category_id,
                    'asset_authorization_id' => $assetCreateAuthorization->id
                    ]);
            }
        }
        // Ejecuta el modelo de autorización de categorías de activos
        $assetCreateAuthorization->authorizedCategoriesModel;
        // Ejecuta el modelo de categorías
        $assetCreateAuthorization->dependencias;
        // Ejecuta el modelo de usuarios
        $assetCreateAuthorization->usuarios;

        return $this->sendResponse($assetCreateAuthorization->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un AssetCreateAuthorization del almacenamiento
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

        /** @var AssetCreateAuthorization $assetCreateAuthorization */
        $assetCreateAuthorization = $this->assetCreateAuthorizationRepository->find($id);
        $resumeMachineryVehiclesYellowData = ResumeMachineryVehiclesYellow::where('responsable',$assetCreateAuthorization['responsable'])->get()->count();
        if($resumeMachineryVehiclesYellowData > 0){
            return $this->sendSuccess('No se puede eliminar el registro ya que se esta utilizando en la hoja de vida.', 'error');
        }else{
            $authorizedCategories = AuthorizedCategories::where('asset_authorization_id', $assetCreateAuthorization['id'])->get();
            foreach ($authorizedCategories as $value) {
                $value->delete();
            }       
    
            if (empty($assetCreateAuthorization)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
    
            $assetCreateAuthorization->delete();
    
            return $this->sendSuccess(trans('msg_success_drop'));
        }

      

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    /*public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('asset_create_authorizations').'.'.$fileType;

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

    }*/

    /**
     * Genera el reporte de autorizaciones de creación de activos en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - jun. 04 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('asset-create-authorizations').'.'.$fileType;

        return Excel::download(new RequestExport('maintenance::asset_create_authorizations.report_excel', $input['data'], 'c'), $fileName);
    }
}
