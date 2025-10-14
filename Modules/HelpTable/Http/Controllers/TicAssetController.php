<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateTicAssetRequest;
use Modules\HelpTable\Http\Requests\UpdateTicAssetRequest;
use Modules\HelpTable\Repositories\TicAssetRepository;
use Modules\HelpTable\Models\TicAsset;
use Modules\HelpTable\Models\TicProvider;
use Modules\HelpTable\Models\TicAssetsHistory;
use Modules\HelpTable\Models\TicPeriodValidity;
use Modules\HelpTable\Models\TicAssetsComponent;
use Modules\HelpTable\Models\TicTypeAsset;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\RequestExport;
use Modules\Intranet\Models\Dependency;
use Flash;
use App\User;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TicAssetController extends AppBaseController {

    /** @var  TicAssetRepository */
    private $ticAssetRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TicAssetRepository $ticAssetRepo) {
        $this->ticAssetRepository = $ticAssetRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicAsset.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador TIC","Soporte TIC"])){
            return view('help_table::tic_assets.index');
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
        $tic_assets = TicAsset::with([ 'ticTypeAssets', 'ticPeriodValidity', 'users', 'ticAssetsHistories', 'ticMaintenances', 'ticAssetsComponents'])
        ->latest()
        ->get()
        ->map(function($item, $key){
            if ($item->ticTypeAssets) {
                $item->type_tic_category = $item->ticTypeAssets->ht_tic_type_tic_categories_id;
            }
            return $item;
        });
        return $this->sendResponse($tic_assets->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTicAssetRequest $request
     *
     * @return Response
     */
    public function store(CreateTicAssetRequest $request) {

        $input = $request->all();
        // Inicia la transaccion
        DB::beginTransaction();
        // try {
            // Asignar consecutivo
            // $activeRegister= TicAsset::where('ht_tic_period_validity_id', $input['ht_tic_period_validity_id'])->get()->last();
            $activeRegister = TicAsset::where('consecutive', 'like', date('Y').'%')->get()->last();
            // Valida si existe un activo con la vigencia seleccionada
            if($activeRegister){
                // Realiza el explode del ultimo consecutivo para crear el nuevo sumando 1
                $consecutiveArray = explode("-",$activeRegister->consecutive);
                $order = $consecutiveArray[1]+1;
                // Asigna el valor del consecutivo
                $input['consecutive'] = $consecutiveArray[0]."-".$order;

            }else{
                // Obtiene los datos de la vigencia seleccionada
                // $ticPeriodValidity = TicPeriodValidity::find($input['ht_tic_period_validity_id']);
                $ticPeriodValidity = date('Y');
                // Asigna el valor del consecutivo
                $input['consecutive'] = $ticPeriodValidity."-1";
            }

            // Valida si trae un proveedor
            if (!empty($input['ht_tic_provider_id'])) {

                // Obtiene los datos del proveedor
                $ticProvider = TicProvider::with(['users'])->where('users_id', $input['ht_tic_provider_id'])->first();
                // Asigna el id del proveedor
                $input['ht_tic_provider_id'] = $ticProvider->id;
                // Asigna el nombre del proveedor
                $input['provider_name'] = $ticProvider->users->name;
            }

            // Inserta el registro en la base de datos
            $ticAsset = $this->ticAssetRepository->create($input);

            // Valida si viene componentes relacionados al activo
            if (!empty($input['tic_assets_component'])) {
                // Elimina si existe componentes relacionadas
                TicAssetsComponent::where('ht_tic_assets_id', $ticAsset->id)->delete();
                // Recorre los componentes de la lista dinamica
                foreach($input['tic_assets_component'] as $option){
                    $component = json_decode($option);
                    // Inserta relacion con activos
                    $assetComponent = TicAssetsComponent::create([
                        'ht_tic_assets_id' => $ticAsset->id,
                        'name' => $component->name,
                        'date_purchase' => $component->date_purchase,
                        'provider_name' => $component->provider_name,
                        'serial' => $component->serial,
                        'description' => $component->description
                    ]);
                }
            }

            // Inserta el registro en el historial del activo
            $input['ht_tic_assets_id'] = $ticAsset->id;
            TicAssetsHistory::create($input);

            $ticAsset->dependencias;
            $ticAsset->ticPeriodValidity;
            $ticAsset->ticTypeAssets;
            $ticAsset->users;
            $ticAsset->ticAssetsHistories;

            
        // } catch (\Illuminate\Database\QueryException $error) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicAssetController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
        //     // Retorna mensaje de error de base de datos
        //     return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        // } catch (\Exception $e) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicAssetController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
        //     // Retorna error de tipo logico
        //     return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        // }
        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($ticAsset->toArray(), trans('msg_success_save'));
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTicAssetRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicAssetRequest $request) {

        $input = $request->all();
        /** @var TicAsset $ticAsset */
        $ticAsset = $this->ticAssetRepository->find($id);
        // dd($ticAsset);

        if(empty($ticAsset)) {

            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // try {
            // Valida si trae un proveedor
            // dd($input['ht_tic_provider_id']);
            if (!empty($input['ht_tic_provider_id'])) {

                // Obtiene los datos del proveedor
                $ticProvider = TicProvider::with(['users'])->where('users_id', $input['ht_tic_provider_id'])->first();
                // Asigna el id del proveedor
                $input['ht_tic_provider_id'] = $ticProvider->id;
                // Asigna el nombre del proveedor
                $input['provider_name'] = $ticProvider->users->name;
            }

            // Actualiza el registro
            $ticAsset = $this->ticAssetRepository->update($input, $id);

            $ticAsset->dependencias;
            $ticAsset->ticPeriodValidity;
            $ticAsset->ticTypeAssets;
            $ticAsset->users;
            $ticAsset->ticAssetsHistories;
        
            return $this->sendResponse($ticAsset->toArray(), trans('msg_success_update'));
        // } 
        // catch (\Illuminate\Database\QueryException $error) {
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicAssetController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
        //     // Retorna mensaje de error de base de datos
        //     return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        // } catch (\Exception $e) {
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicAssetController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
        //     // Retorna error de tipo logico
        //     return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        // }
    }

    /**
     * Elimina un TicAsset del almacenamiento
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

        /** @var TicAsset $ticAsset */
        $ticAsset = $this->ticAssetRepository->find($id);

        if (empty($ticAsset)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $ticAsset->delete();

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
    //     $fileName = time().'-'.trans('tic_assets').'.'.$fileType;

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

    public function migrateTicAssets(Request $request) {
        $input = $request->all();

        $successFulRegistration = 0;
        $failedRegistration = 0;
        $totalRegistrations = [];

        if($request->hasFile('file_import')) {
            $data = Excel::toArray(new TicAsset, $input["file_import"]);

            
            // Elimina los textos y formatos para que solo queden los registros a migrar
            array_splice($data[0], 0, 5);

            // Obtiene los dias no habiles para validar si la fecha de la celda es valida

            $no_cell = 5; // Valor de la celda donde se empezara a iterar los registros
            
            // Itera cada fila para crear su respectivo registro
            foreach ($data[0] as $ticAsset) {
                try{
                    // $activeRegister= TicAsset::where('ht_tic_period_validity_id', $input['ht_tic_period_validity_id'])->get()->last();
                    $activeRegister = TicAsset::where('consecutive', 'like', date('Y').'%')->get()->last();
                    // Valida si existe un activo con la vigencia seleccionada
                    if($activeRegister){
                        // Realiza el explode del ultimo consecutivo para crear el nuevo sumando 1
                        $consecutiveArray = explode("-",$activeRegister->consecutive);
                        $order = $consecutiveArray[1]+1;
                        // Asigna el valor del consecutivo
                        $input['consecutive'] = $consecutiveArray[0]."-".$order;

                    }else{
                        // Obtiene los datos de la vigencia seleccionada
                        // $ticPeriodValidity = TicPeriodValidity::find($input['ht_tic_period_validity_id']);
                        $ticPeriodValidity = date('Y');
                        // Asigna el valor del consecutivo
                        $input['consecutive'] = $ticPeriodValidity."-1";
                    }

                    $nombreAcitvo = $ticAsset[12];
                    $tipoActivo = TicTypeAsset::where('name', 'like', "%$nombreAcitvo%")->get();
                    
                    $nombreProveedor = $ticAsset[8];

                    $ticProvider = 0; 
                    
                    $userProvedor = User::where('name', 'like', "%$nombreProveedor%")->get();

                    if (isset($userProvedor[0])) {
                        $ticProvider = TicProvider::where('users_id',$user[0]->id)->get();      
                    }


                    $nombreUser = $ticAsset[10];
                    $user = User::where('name', 'like', "%$nombreUser%")->get();


                    $nombreDependencia = $ticAsset[0];
                    $dependencia = Dependency::where('nombre', 'like', "%$nombreDependencia%")->get();

                    $newticAsset = TicAsset::create([
                        'consecutive' => $input['consecutive'],
                        'name' => $ticAsset[2],
                        'brand' => $ticAsset[5],
                        'serial' => $ticAsset[4],
                        'model' => $ticAsset[6],
                        'inventory_plate' => $ticAsset[3],
                        'description' => 'Migrado desde Excel',
                        'general_description' => $ticAsset[7],
                        'purchase_date' => Date::excelToDateTimeObject($ticAsset[9])->format('Y-m-d'),
                        'location_address' => $ticAsset[1],
                        'state' => $ticAsset[11] == 'Activo' ? 1 : 0,
                        'name_user' => $ticAsset[10],
                        'provider_name' => $ticAsset[8],
                        'ht_tic_provider_id' => ($ticProvider == 0|| !isset($ticProvider[0])) ? null : $ticProvider[0]->id,
                        'users_id' => !isset($user[0]) ? null : $user[0]->id,
                        'dependencias_id' => !isset($dependencia[0]) ? null : $dependencia[0]->id,
                        'ht_tic_type_assets_id' => !isset($tipoActivo[0]) ? null : $tipoActivo[0]->id,
                    ]);
    
                    $successFulRegistration++;
                    $totalRegistrations[] = $newticAsset->toArray();
                } catch (\Illuminate\Database\QueryException $error) {
                    $failedRegistration++;
                }
                
                $no_cell++;
            }
        }

        return $this->sendResponse($totalRegistrations, trans('msg_success_save') . "<br /><br />Registros exitosos: $successFulRegistration<br />Registros fallidos: $failedRegistration");
    }



    /**
     * Genera el reporte de encuestas en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - May. 26 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('tic_assets').'.'.$fileType;
        
        return Excel::download(new RequestExport('help_table::tic_assets.report_excel', $input['data'], 'z'), $fileName);
    }

    /**
     * Obtiene todos los elementos existentes por la categoria
     *
     * @author Carlos Moises Garcia T. - Nov. 19 - 2020
     * @version 1.0.0
     * 
     * @param Request $category id de la categoria
     *
     * @return Response
     */
    public function getTicAssetsByCategory($category) {
        $tic_assets = TicAsset::with(['dependencias', 'ticPeriodValidity', 'ticTypeAssets'])
            ->whereHas('ticTypeAssets', function($query)  use ($category) {
                $query->where('ht_tic_type_tic_categories_id', $category);
            })->latest()->get();
        return $this->sendResponse($tic_assets->toArray(), trans('data_obtained_successfully'));
    }
}
