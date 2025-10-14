<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateTicMaintenanceRequest;
use Modules\HelpTable\Http\Requests\UpdateTicMaintenanceRequest;
use Modules\HelpTable\Repositories\TicMaintenanceRepository;
use Modules\HelpTable\Models\TicMaintenance;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

use Auth;

use App\Exports\RequestExport;

use Modules\Intranet\Models\User;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TicMaintenanceController extends AppBaseController {

    /** @var  TicMaintenanceRepository */
    private $ticMaintenanceRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TicMaintenanceRepository $ticMaintenanceRepo) {
        $this->ticMaintenanceRepository = $ticMaintenanceRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicMaintenance.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $tic_maintenances = TicMaintenance::with(['dependencias', 'ticAssets', 'ticProvider', 'ticRequests'])->get();
        
        

        if(Auth::user()->hasRole(["Administrador TIC","Soporte TIC"])){
            return view('help_table::tic_maintenances.index')->with("ht_tic_assets_id", $request['asset_id'] ?? null);
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
    public function all(Request $request) {
        $tic_maintenances = TicMaintenance:: with(['dependencias', 'ticAssets', 'ticProvider', 'ticRequests'])
        ->when($request, function ($query) use($request) {
            // Valida si existe un id de un activo
            if (!empty($request['ht_tic_assets_id'])) {
                return $query->where('ht_tic_assets_id', $request['ht_tic_assets_id']);
            }
        })
        ->latest()
        ->get();
        return $this->sendResponse($tic_maintenances->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTicMaintenanceRequest $request
     *
     * @return Response
     */
    public function store(CreateTicMaintenanceRequest $request) {

        $input = $request->all();

        try {
            $input['user_id'] = Auth::user()->id;
            $input['user_name'] = User::find($input['user_id'])->name;

            // Inserta el registro en la base de datos
            $ticMaintenance = $this->ticMaintenanceRepository->create($input);

            $ticMaintenance->dependencias;
            $ticMaintenance->ticAssets;
            $ticMaintenance->ticProvider;
            $ticMaintenance->ticRequests;

            return $this->sendResponse($ticMaintenance->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicMaintenanceController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicMaintenanceController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateTicMaintenanceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicMaintenanceRequest $request) {

        $input = $request->all();

        /** @var TicMaintenance $ticMaintenance */
        $ticMaintenance = $this->ticMaintenanceRepository->find($id);

        if (empty($ticMaintenance)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $ticMaintenance = $this->ticMaintenanceRepository->update($input, $id);
            $ticMaintenance->dependencias;
            $ticMaintenance->ticAssets;
            $ticMaintenance->ticProvider;
            $ticMaintenance->ticRequests;
        
            return $this->sendResponse($ticMaintenance->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicMaintenanceController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicMaintenanceController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un TicMaintenance del almacenamiento
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

        /** @var TicMaintenance $ticMaintenance */
        $ticMaintenance = $this->ticMaintenanceRepository->find($id);

        if (empty($ticMaintenance)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $ticMaintenance->delete();

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
    //     $fileName = time().'-'.trans('tic_maintenances').'.'.$fileType;

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
     * @author Andres Stiven Pinzon G. - May. 26 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('tic_maintenances').'.'.$fileType;
        
        return Excel::download(new RequestExport('help_table::tic_maintenances.report_excel', $input['data'], 'q'), $fileName);
    }

    public function indexMaintenanceEquipment(Request $request) {
        $equipmentId = $request->query('equipmentId');
        return view('help_table::tic_maintenances.indexResume', compact("equipmentId") );
    }

    public function getMaintenanceEquipment(Request $request) {
        $equipmentId = $request->query('equipmentId');
        $TicMaintenance = TicMaintenance::with(['dependencias', 'ticAssets', 'ticProvider', 'ticRequests'])->where('id_tower_inventory',$equipmentId)->get();
        return $this->sendResponse($TicMaintenance->toArray(), trans('msg_success_save'));
    }
}
