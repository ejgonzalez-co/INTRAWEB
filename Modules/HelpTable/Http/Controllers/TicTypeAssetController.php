<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateTicTypeAssetRequest;
use Modules\HelpTable\Http\Requests\UpdateTicTypeAssetRequest;
use Modules\HelpTable\Repositories\TicTypeAssetRepository;
use Modules\HelpTable\Models\TicTypeAsset;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TicTypeAssetController extends AppBaseController {

    /** @var  TicTypeAssetRepository */
    private $ticTypeAssetRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TicTypeAssetRepository $ticTypeAssetRepo) {
        $this->ticTypeAssetRepository = $ticTypeAssetRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicTypeAsset.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador TIC"])){
            return view('help_table::tic_type_assets.index');
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
        $tic_type_assets = TicTypeAsset::with(['ticTypeTicCategories'])->latest()->get();
        return $this->sendResponse($tic_type_assets->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTicTypeAssetRequest $request
     *
     * @return Response
     */
    public function store(CreateTicTypeAssetRequest $request) {

        $input = $request->all();

        try {
            // Inserta el registro en la base de datos
            $ticTypeAsset = $this->ticTypeAssetRepository->create($input);
            $ticTypeAsset->ticTypeTicCategories;

            return $this->sendResponse($ticTypeAsset->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicTypeAssetController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicTypeAssetController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTicTypeAssetRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicTypeAssetRequest $request) {

        $input = $request->all();

        /** @var TicTypeAsset $ticTypeAsset */
        $ticTypeAsset = $this->ticTypeAssetRepository->find($id);

        if (empty($ticTypeAsset)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $ticTypeAsset = $this->ticTypeAssetRepository->update($input, $id);
            $ticTypeAsset->ticTypeTicCategories;
        
            return $this->sendResponse($ticTypeAsset->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicTypeAssetController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicTypeAssetController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
        
    }

    /**
     * Elimina un TicTypeAsset del almacenamiento
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

        /** @var TicTypeAsset $ticTypeAsset */
        $ticTypeAsset = $this->ticTypeAssetRepository->find($id);

        if (empty($ticTypeAsset)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $ticTypeAsset->delete();

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
    // public function export(Request $request) {
    //     $input = $request->all();

    //     // Tipo de archivo (extencion)
    //     $fileType = $input['fileType'];
    //     // Nombre de archivo con tiempo de creacion
    //     $fileName = time().'-'.trans('tic_type_assets').'.'.$fileType;

    //     // Valida si el tipo de archivo es pdf
    //     if (strcmp($fileType, 'pdf') == 0) {
    //         // Guarda el archivo pdf en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
    //     } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
    //         // Guarda el archivo excel en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName);
    //     }
    // }

    /**
     * Genera el reporte de encuestas en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - May. 27 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extension)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('tic_type_assets').'.'.$fileType;
        
        return Excel::download(new RequestExport('help_table::tic_type_assets.report_excel', $input['data'], 'd'), $fileName);
    }

    /**
     * Obtiene todos los tipos de activos por la categoria
     *
     * @author Carlos Moises Garcia T. - Oct. 24 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function getTypeTicAssetsByCategory($id) {

        $tic_type_assets = TicTypeAsset::with(['ticTypeTicCategories'])->where('ht_tic_type_tic_categories_id', $id)->latest()->get();
        return $this->sendResponse($tic_type_assets->toArray(), trans('data_obtained_successfully'));
    }
}
