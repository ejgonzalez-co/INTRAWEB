<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateProvidersRequest;
use Modules\Maintenance\Http\Requests\UpdateProvidersRequest;
use Modules\Maintenance\Repositories\ProvidersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\Maintenance\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\Maintenance\Models\Providers;
use Modules\Maintenance\Models\OptionalContactEmails;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ProvidersController extends AppBaseController {

    /** @var  ProvidersRepository */
    private $providersRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ProvidersRepository $providersRepo) {
        $this->providersRepository = $providersRepo;
    }

    /**
     * Muestra la vista para el CRUD de Providers.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::providers.index');
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
        $query =$request->input('query');
        $providers = Providers::with(['mantTypesActivity', 'optionalContactEmails'])->where('identification','like','%'.$query.'%')->orWhere('name','like','%'.$query.'%')->latest()->get();
        return $this->sendResponse($providers->toArray(), trans('data_obtained_successfully'));
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

        $provider = $this->providersRepository->find($id);

        if (empty($provider)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $provider->mantSupportsProvider;
        $provider->optionalContactEmails;
        $provider->mantTypesActivity;

        return $this->sendResponse($provider->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateProvidersRequest $request
     *
     * @return Response
     */
    public function store(CreateProvidersRequest $request) {

        $input = $request->all();

        $providers = $this->providersRepository->create($input);
        // Condición para validar si existe algún registro de contacto
        if (!empty($input['optional_contact_emails'])) {
            // Ciclo para recorrer todos los registros de contactos
            foreach($input['optional_contact_emails'] as $option){

                $arrayContactEmails = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                OptionalContactEmails::create([
                    'name' => $arrayContactEmails->name,
                    'mail' => $arrayContactEmails->mail,
                    'phone' => $arrayContactEmails->phone,
                    'observation' => $arrayContactEmails->observation,
                    'mant_providers_id' => $providers->id
                    ]);
            }

        }

        // Ejecuta el modelo de contactos de email opcional
        $providers->optionalContactEmails;
        // Ejecuta el modelo de tipos de actividad
        $providers->mantTypesActivity;

        return $this->sendResponse($providers->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateProvidersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProvidersRequest $request) {

        $input = $request->all();
 
        /** @var Providers $providers */
        $providers = $this->providersRepository->find($id);

        if (empty($providers)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $providers = $this->providersRepository->update($input, $id);

        // Condición para validar si existe algún registro de contacto
        if (!empty($input['optional_contact_emails'])) {
            // Eliminar los registros de contactos existentes según el id del registro principal
            OptionalContactEmails::where('mant_providers_id', $providers->id)->delete();
            // Ciclo para recorrer todos los registros de contactos
            foreach($input['optional_contact_emails'] as $option) {

                $arrayContactEmails = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                OptionalContactEmails::create([
                    'name' => $arrayContactEmails->name,
                    'mail' => $arrayContactEmails->mail,
                    'phone' => $arrayContactEmails->phone,
                    'observation' => $arrayContactEmails->observation,
                    'mant_providers_id' => $providers->id
                    ]);
            }
        } else {
            OptionalContactEmails::where('mant_providers_id', $providers->id)->delete();
        }
        // Ejecuta el modelo de contactos de email opcional
        $providers->optionalContactEmails;
        // Ejecuta el modelo de tipos de actividad
        $providers->mantTypesActivity;

        return $this->sendResponse($providers->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un Providers del almacenamiento
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

        /** @var Providers $providers */
        $providers = $this->providersRepository->find($id);

        if (empty($providers)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $providers->delete();

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
    /*public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('providers').'.'.$fileType;

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
     * Genera el reporte de proveedores en hoja de calculo
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
        $fileName = date('Y-m-d H:i:s').'-'.trans('providers').'.'.$fileType;
        
        return Excel::download(new RequestExport('maintenance::providers.report_excel', $input['data']), $fileName);
    }
}
