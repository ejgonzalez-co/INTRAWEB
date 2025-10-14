<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreateDownloadsTagsRequest;
use Modules\Intranet\Http\Requests\UpdateDownloadsTagsRequest;
use Modules\Intranet\Repositories\DownloadsTagsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;

use Modules\Intranet\Models\DownloadsTags;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class DownloadsTagsController extends AppBaseController {

    /** @var  DownloadsTagsRepository */
    private $downloadsTagsRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(DownloadsTagsRepository $downloadsTagsRepo) {
        $this->downloadsTagsRepository = $downloadsTagsRepo;
    }

    /**
     * Muestra la vista para el CRUD de DownloadsTags.
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
            return view('intranet::downloads_tags.index');
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
        $downloads_tags = DownloadsTags::with(["downloadCategories"])->latest()->get();
        return $this->sendResponse($downloads_tags->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateDownloadsTagsRequest $request
     *
     * @return Response
     */
    public function store(CreateDownloadsTagsRequest $request) {

        $input = $request->all();

        $order = DownloadsTags::max("ordering");

        $order = $order + 1;

        $input["ordering"] = $order;
        $input["checked_out"] = Auth::user()->id;
        $input["checked_out_time"] = date("Y-m-d H:i:s");

        try {
            // Inserta el registro en la base de datos
            $downloadsTags = $this->downloadsTagsRepository->create($input);

            return $this->sendResponse($downloadsTags->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadsTagsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadsTagsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDownloadsTagsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDownloadsTagsRequest $request) {

        $input = $request->all();

        $input["checked_out"] = Auth::user()->id;
        $input["checked_out_time"] = date("Y-m-d H:i:s");

        /** @var DownloadsTags $downloadsTags */
        $downloadsTags = $this->downloadsTagsRepository->find($id);

        if (empty($downloadsTags)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $downloadsTags = $this->downloadsTagsRepository->update($input, $id);
        
            return $this->sendResponse($downloadsTags->toArray(), trans('msg_success_update'));
        } 
        catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadsTagsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadsTagsController - '. Auth::user()->name.' -  Error: '.$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($downloadsTags['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un DownloadsTags del almacenamiento
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

        /** @var DownloadsTags $downloadsTags */
        $downloadsTags = $this->downloadsTagsRepository->find($id);

        if (empty($downloadsTags)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $downloadsTags->delete();

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
        $fileName = time().'-'.trans('downloads_tags').'.'.$fileType;

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
