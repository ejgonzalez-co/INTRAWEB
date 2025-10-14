<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreateDownloadCategorieRequest;
use Modules\Intranet\Http\Requests\UpdateDownloadCategorieRequest;
use Modules\Intranet\Repositories\DownloadCategorieRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;




use Modules\Intranet\Models\DownloadAccessGroup;
use Modules\Intranet\Models\DownloadAccessUser;

use Modules\Intranet\Models\DownloadCategorie;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class DownloadCategorieController extends AppBaseController {

    /** @var  DownloadCategorieRepository */
    private $downloadCategorieRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(DownloadCategorieRepository $downloadCategorieRepo) {
        $this->downloadCategorieRepository = $downloadCategorieRepo;
    }

    /**
     * Muestra la vista para el CRUD de DownloadCategorie.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador intranet","Administrador intranet de descargas","Administrador intranet de eventos","Administrador intranet de encuestas"])){
            return view('intranet::download_categories.index');
        }
        return view("auth.forbidden");
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
        // $download_categories = $this->downloadCategorieRepository->all();

        $download_categories = DownloadCategorie::with(["parentid", "downloadAccessGroups", "downloadAccessUsers"])->latest()->get();
        return $this->sendResponse($download_categories->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateDownloadCategorieRequest $request
     *
     * @return Response
     */
    public function store(CreateDownloadCategorieRequest $request) {

        $input = $request->all();
        // dd($input);

        $order = DownloadCategorie::max("ordering");

        $order = $order + 1;

        $input["ordering"] = $order;
        $input["checked_out"] = Auth::user()->id;
        $input["checked_out_time"] = date("Y-m-d H:i:s");

            // Inserta el registro en la base de datos
            $downloadCategorie = $this->downloadCategorieRepository->create($input);
            // Condición para validar si existe algún registro de grupos con acceso
            if (!empty($input['download_access_groups'])) {
                // Ciclo para recorrer todos los registros de grupos con acceso
                foreach($input['download_access_groups'] as $option){

                    $downloadAccessGroups = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    DownloadAccessGroup::create([
                        'group_id' => $downloadAccessGroups->id,
                        'intranet_downloads_categories_id' => $downloadCategorie->id 
                        ]);
                }

            }

            // Condición para validar si existe algún registro de derechos con acceso
            if (!empty($input['download_access_users'])) {
                // Ciclo para recorrer todos los registros de derechos con acceso
                foreach($input['download_access_users'] as $option){

                    $downloadAccessUsers = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    DownloadAccessUser::create([
                        'user_id' => $downloadAccessUsers->id,
                        'intranet_downloads_categories_id' => $downloadCategorie->id
                        ]);
                }

            }

            $downloadCategorie->parentid;
            $downloadCategorie->downloadAccessGroups;
            $downloadCategorie->downloadAccessUsers;

            return $this->sendResponse($downloadCategorie->toArray(), trans('msg_success_save'));
        
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDownloadCategorieRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDownloadCategorieRequest $request) {

        $input = $request->all();

        $input["checked_out"] = Auth::user()->id;
        $input["checked_out_time"] = date("Y-m-d H:i:s");

        /** @var DownloadCategorie $downloadCategorie */
        $downloadCategorie = $this->downloadCategorieRepository->find($id);

        if (empty($downloadCategorie)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        
            // Actualiza el registro
            $downloadCategorie = $this->downloadCategorieRepository->update($input, $id);

            // Condición para validar si existe algún registro de grupos con acceso
            if (!empty($input['download_access_groups'])) {
                // Eliminar los registros de grupos con acceso existentes según el id del registro principal
                DownloadAccessGroup::where('intranet_downloads_categories_id', $downloadCategorie->id)->forceDelete();
                // Ciclo para recorrer todos los registros de grupos con acceso
                foreach($input['download_access_groups'] as $option){

                    $downloadAccessGroups = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    DownloadAccessGroup::create([
                        'group_id' => $downloadAccessGroups->id,
                        'intranet_downloads_categories_id' => $downloadCategorie->id 
                        ]);
                }

            }

            // Condición para validar si existe algún registro de derechos con acceso
            if (!empty($input['download_access_users'])) {
                // Eliminar los registros de derechos con acceso existentes según el id del registro principal
                DownloadAccessUser::where('intranet_downloads_categories_id', $downloadCategorie->id)->forceDelete();
                // Ciclo para recorrer todos los registros de derechos con acceso
                foreach($input['download_access_users'] as $option){

                    $downloadAccessUsers = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    DownloadAccessUser::create([
                        'user_id' => $downloadAccessUsers->id,
                        'intranet_downloads_categories_id' => $downloadCategorie->id
                        ]);
                }

            }

            $downloadCategorie->parentid;
            $downloadCategorie->downloadAccessGroups;
            $downloadCategorie->downloadAccessUsers;
        
            return $this->sendResponse($downloadCategorie->toArray(), trans('msg_success_update'));
        
    }

    /**
     * Elimina un DownloadCategorie del almacenamiento
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

        /** @var DownloadCategorie $downloadCategorie */
        $downloadCategorie = $this->downloadCategorieRepository->find($id);

        if (empty($downloadCategorie)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $downloadCategorie->delete();

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
        $fileName = time().'-'.trans('download_categories').'.'.$fileType;

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
