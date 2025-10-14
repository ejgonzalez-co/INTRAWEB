<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateImportSparePartsProviderContractRequest;
use Modules\Maintenance\Http\Requests\UpdateImportSparePartsProviderContractRequest;
use Modules\Maintenance\Repositories\ImportSparePartsProviderContractRepository;
use App\Exports\Maintenance\ContractExport\PartsExport;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Maintenance\RequestExport;
use Response;
use Modules\Maintenance\Models\ProviderContract;
use Modules\Maintenance\Models\Providers;
use Modules\Maintenance\Models\ImportSparePartsProviderContract;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ImportSparePartsProviderContractController extends AppBaseController {

    /** @var  ImportSparePartsProviderContractRepository */
    private $importSparePartsProviderContractRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ImportSparePartsProviderContractRepository $importSparePartsProviderContractRepo) {
        $this->importSparePartsProviderContractRepository = $importSparePartsProviderContractRepo;
    }

    /**
     * Muestra la vista para el CRUD de ImportSparePartsProviderContract.
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
        $provider_id = $request->mpc;
        if( $provider!=null){
            $name_provider = $provider->providers->name;
        }
        

        return view('maintenance::import_spare_parts_provider_contracts.index',compact(['provider_id','name_provider']));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Mayo. 10 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all($id) {
        $import_spare_parts_provider_contracts = ImportSparePartsProviderContract::where('mant_provider_contract_id', $id)->with(['providerContract'])->orderBy('item')->get()->toArray();
        return $this->sendResponse($import_spare_parts_provider_contracts, trans('data_obtained_successfully'));
    }

    public function prueba($id) {
        $import_spare_parts_provider_contracts = ImportSparePartsProviderContract::where('mant_provider_contract_id', $id)->with(['providerContract'])->latest()->get()->toArray();
        return $this->sendResponse($import_spare_parts_provider_contracts, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateImportSparePartsProviderContractRequest $request
     *
     * @return Response
     */
    public function store(CreateImportSparePartsProviderContractRequest $request, $id) {

        $input = $request->all();

        $registrosExitosos = 0;
        $registrosFallidos = 0;
        $registrosAlmacenados = [];
        
        // Valida si se ingresa la imagen de perfil
        if ($request->hasFile('file_import')) {

            $data = Excel::toArray(new ImportSparePartsProviderContract, $input["file_import"]);
            
            unset($data[0][0]);
            // dd($id);

            // dd($data[0]);
            foreach($data[0] as $row) {
                
                try {
                    $registro = ImportSparePartsProviderContract::create([
                        'item' => $row[0],
                        'description' => $row[1],
                        'unit_measure' => $row[2],
                        'unit_value' => $row[3],
                        'iva' => $row[4],
                        'total_value' => $row[5],
                        'mant_provider_contract_id' => $id
                    ]);
                    $registrosAlmacenados[] = $registro->toArray();

                    $registrosExitosos++;
                } catch (\Illuminate\Database\QueryException $error) {
                    $registrosFallidos++;
                }
            }

        }
        usort($registrosAlmacenados, function ($a,$b) {
            return $a['item']<$b['item'];
        });
        // return redirect("/maintenance/provider-contracts");

        return $this->sendResponse($registrosAlmacenados, trans('msg_success_save') . "<br /><br />Registros exitosos: $registrosExitosos<br />Registros fallidos: $registrosFallidos");
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateImportSparePartsProviderContractRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImportSparePartsProviderContractRequest $request) {

        $input = $request->all();

        /** @var ImportSparePartsProviderContract $importSparePartsProviderContract */
        $importSparePartsProviderContract = $this->importSparePartsProviderContractRepository->find($id);

        if (empty($importSparePartsProviderContract)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        $importSparePartsProviderContract = $this->importSparePartsProviderContractRepository->update($input, $id);

        // Ejecuta el modelo del proveedor
        $importSparePartsProviderContract->mantProviders;

        return $this->sendResponse($importSparePartsProviderContract->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un ImportSparePartsProviderContract del almacenamiento
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

        /** @var ImportSparePartsProviderContract $importSparePartsProviderContract */
        $importSparePartsProviderContract = $this->importSparePartsProviderContractRepository->find($id);

        if (empty($importSparePartsProviderContract)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $importSparePartsProviderContract->delete();

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
        
        return Excel::download(new RequestExport('maintenance::import_spare_parts_provider_contracts.report_excel', $input['data'],'f'), $fileName);
    }
}
