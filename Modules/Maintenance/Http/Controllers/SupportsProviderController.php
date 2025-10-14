<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateSupportsProviderRequest;
use Modules\Maintenance\Http\Requests\UpdateSupportsProviderRequest;
use Modules\Maintenance\Repositories\SupportsProviderRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\Providers;
use Modules\Maintenance\Models\SupportsProvider;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class SupportsProviderController extends AppBaseController {

    /** @var  SupportsProviderRepository */
    private $supportsProviderRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(SupportsProviderRepository $supportsProviderRepo) {
        $this->supportsProviderRepository = $supportsProviderRepo;
    }

    /**
     * Muestra la vista para el CRUD de SupportsProvider.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $provider = Providers::where('id',$request->mp)->first();
        $provider_id = $request->mp;
        $name_provider =$provider["name"];
        return view('maintenance::supports_providers.index',compact(['provider_id','name_provider']));
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
        $supports_providers = SupportsProvider::where('mant_providers_id', $id)->with(['mantProviders'])->latest()->get()->toArray();
        return $this->sendResponse($supports_providers, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateSupportsProviderRequest $request
     *
     * @return Response
     */
    public function store(CreateSupportsProviderRequest $request, $id) {

        $input = $request->all();
        $input["mant_providers_id"] = $id;
        $input['url_document'] = implode(",", $input["url_document"]);
        // Validación para saber si selecciono un adjunto
        // if ($request->hasFile('url_document')) {
        //     $input['url_document'] = substr($input['url_document']->store('public/maintenance/supports_provider'), 7);
        // }

        $supportsProvider = $this->supportsProviderRepository->create($input);

        // Ejecuta el modelo del proveedor
        $supportsProvider->mantProviders;

        return $this->sendResponse($supportsProvider->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateSupportsProviderRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSupportsProviderRequest $request) {

        $input = $request->all();
        // Validación para saber si selecciono un adjunto
        // if ($request->hasFile('url_document')) {
        //     $input['url_document'] = substr($input['url_document']->store('public/maintenance/supports_provider'), 7);
        // }

        /** @var SupportsProvider $supportsProvider */
        $supportsProvider = $this->supportsProviderRepository->find($id);

        if (empty($supportsProvider)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $input['url_document'] = implode(",", $input["url_document"]);

        $supportsProvider = $this->supportsProviderRepository->update($input, $id);

        // Ejecuta el modelo del proveedor
        $supportsProvider->mantProviders;

        return $this->sendResponse($supportsProvider->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un SupportsProvider del almacenamiento
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

        /** @var SupportsProvider $supportsProvider */
        $supportsProvider = $this->supportsProviderRepository->find($id);

        if (empty($supportsProvider)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $supportsProvider->delete();

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
        $fileName = time().'-'.trans('supports_providers').'.'.$fileType;

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
