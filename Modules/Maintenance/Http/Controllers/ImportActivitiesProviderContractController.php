<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateImportActivitiesProviderContractRequest;
use Modules\Maintenance\Http\Requests\UpdateImportActivitiesProviderContractRequest;
use Modules\Maintenance\Repositories\ImportActivitiesProviderContractRepository;
use App\Exports\Maintenance\ContractExport\ActivitiesExport;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use App\Exports\Maintenance\RequestExport;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\Maintenance\Models\ProviderContract;
use Modules\Maintenance\Models\Providers;
use Modules\Maintenance\Models\ImportActivitiesProviderContract;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ImportActivitiesProviderContractController extends AppBaseController {

    /** @var  ImportActivitiesProviderContractRepository */
    private $importActivitiesProviderContractRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ImportActivitiesProviderContractRepository $importActivitiesProviderContractRepo) {
        $this->importActivitiesProviderContractRepository = $importActivitiesProviderContractRepo;
    }

    /**
     * Muestra la vista para el CRUD de ImportActivitiesProviderContract.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $name_provider=null;
        $provider = ProviderContract::where('id',$request->mpc)->first();
        $contract_id = $request->mpc;
        if($provider != null){
            $name_provider = $provider->providers->name;
        }
        return view('maintenance::import_activities_provider_contracts.index',compact(['contract_id','name_provider']));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all($id) {
        $import_activities_provider_contracts = ImportActivitiesProviderContract::where('mant_provider_contract_id', $id)->with(['providerContract'])->orderBy('item')->get()->toArray();
        return $this->sendResponse($import_activities_provider_contracts, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Andres Stiven Pinzon G. - Ago. 12 - 2020
     * @version 1.1.0
     *
     * @param CreateImportActivitiesProviderContractRequest $request
     *
     * @return Response
     */
    public function store(CreateImportActivitiesProviderContractRequest $request, $id) {

        $input = $request->all();

        $registrosExitosos = 0;
        $registrosFallidos = 0;
        $registrosAlmacenados = [];
        
        // Valida si se ingresa un archivo
        if ($request->hasFile('file_import')) {

            $data = Excel::toArray(new ImportActivitiesProviderContract, $input["file_import"]);
            
            $contArray = count($data[0][0]);
            // dd($data[0]);
            unset($data[0][0]);
            
            if($contArray==9){
                foreach($data[0] as $row) {
                    
                    try {
                        if($row[0]!=null || $row[1]!=null || $row[2]!=null || $row[3]!=null || $row[4]!=null || $row[5]!=null || $row[6]!=null || $row[7]!=null || $row[8]!=null){
                            $registro = ImportActivitiesProviderContract::create([
                                'item' => $row[0],
                                'description' => $row[1],
                                'type' => $row[2],
                                'system' => $row[3],
                                'unit_measurement' => $row[4],
                                'quantity' => $row[5],
                                'unit_value' => $row[6],
                                'iva' => $row[7],
                                'total_value' => $row[8],
                                'mant_provider_contract_id' => $id
                            ]);
    
                            $registrosAlmacenados[] = $registro->toArray();
    
                            $registrosExitosos++;
                        }

                    } catch (\Illuminate\Database\QueryException $error) {
                        $registrosFallidos++;
                    }
                
                }
            }else{
                return $this->sendError( 'Error,por favor verifique que el número de columnas con datos en el excel coincida con el número de columnas del formulario de importación de actividades',[]);
            }
        }
        usort($registrosAlmacenados, function ($a,$b) {
            return $a['item']<$b['item'];
        });


        return $this->sendResponse($registrosAlmacenados, trans('msg_success_save') . "<br /><br />Registros exitosos: $registrosExitosos<br />Registros fallidos: $registrosFallidos");
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Andres Stiven Pinzon G. - Ago. 12 - 2021
     * @version 1.0.1
     *
     * @param int $id
     * @param UpdateImportActivitiesProviderContractRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImportActivitiesProviderContractRequest $request) {

        $input = $request->all();
        // dd($input);

        /** @var ImportActivitiesProviderContract $importActivitiesProviderContract */
        $importActivitiesProviderContract = $this->importActivitiesProviderContractRepository->find($id);

        if (empty($importActivitiesProviderContract)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $importActivitiesProviderContract = $this->importActivitiesProviderContractRepository->update($input, $id);

        $importActivitiesProviderContract->mantProviders;

        return $this->sendResponse($importActivitiesProviderContract->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un ImportActivitiesProviderContract del almacenamiento
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

        /** @var ImportActivitiesProviderContract $importActivitiesProviderContract */
        $importActivitiesProviderContract = $this->importActivitiesProviderContractRepository->find($id);

        if (empty($importActivitiesProviderContract)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $importActivitiesProviderContract->delete();

        return $this->sendSuccess(trans('msg_success_drop'));

    }

    /**
     * Genera el reporte de excel
     *
     * @author Nicolas Dario Ortiz Peña. - Sep. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('Actividades').'.'.$fileType;
        
        return Excel::download(new RequestExport('maintenance::import_activities_provider_contracts.report_excel', $input['data'],'i'), $fileName);
    }
}
