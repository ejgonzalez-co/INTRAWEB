<?php

namespace Modules\CitizenPoll\Http\Controllers;

use App\Exports\GenericExport;
use Modules\CitizenPoll\Http\Requests\CreateImageManagerRequest;
use Modules\CitizenPoll\Http\Requests\UpdateImageManagerRequest;
use Modules\CitizenPoll\Repositories\ImageManagerRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ImageManagerController extends AppBaseController {

    /** @var  ImageManagerRepository */
    private $imageManagerRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ImageManagerRepository $imageManagerRepo) {
        $this->imageManagerRepository = $imageManagerRepo;
    }

    /**
     * Muestra la vista para el CRUD de ImageManager.
     *
     * @author José Manuel Marín Londoño. - Dic. 16 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('citizen_poll::image_managers.index');
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
        $image_managers = $this->imageManagerRepository->all();
        return $this->sendResponse($image_managers->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author José Manuel Marín Londoño. - Dic. 16 - 2021
     * @version 1.0.0
     *
     * @param CreateImageManagerRequest $request
     *
     * @return Response
     */
    public function store(CreateImageManagerRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {

        // Valida si se ingresa la firma digital
        if ($request->hasFile('url_image')) {
            $input['url_image'] = substr($input['url_image']->store('public/citizen_poll/config/url_image'), 7);
        }
            // Inserta el registro en la base de datos
            $imageManager = $this->imageManagerRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($imageManager->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\CitizenPoll\Http\Controllers\ImageManagerController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\CitizenPoll\Http\Controllers\ImageManagerController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author José Manuel Marín Londoño. - Dic. 16 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateImageManagerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImageManagerRequest $request) {

        $input = $request->all();

        /** @var ImageManager $imageManager */
        $imageManager = $this->imageManagerRepository->find($id);

        if (empty($imageManager)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            // Busca si el archivo existe 
            if(Storage::disk('public')->exists($imageManager->url_image)){
                // Si el archivo existe entonces lo va a eliminar
                Storage::disk('public')->delete($imageManager->url_image);
            }

            // Valida si se ingresa la imagen
            if ($request->hasFile('url_image')) {
                $input['url_image'] = substr($input['url_image']->store('public/citizen_poll/config/url_image'), 7);
            }
            // Actualiza el registro
            $imageManager = $this->imageManagerRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($imageManager->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\CitizenPoll\Http\Controllers\ImageManagerController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\CitizenPoll\Http\Controllers\ImageManagerController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ImageManager del almacenamiento
     *
     * @author José Manuel Marín Londoño. - Dic. 16 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var ImageManager $imageManager */
        $imageManager = $this->imageManagerRepository->find($id);

        if (empty($imageManager)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            //Esta validando que si el objeto trae una imagen, asi mismo eliminara la imagen
            if($imageManager['url_image']){
                Storage::disk('public')->delete($imageManager['url_image']);
            }
            // Elimina el registro
            $imageManager->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\CitizenPoll\Http\Controllers\ImageManagerController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\CitizenPoll\Http\Controllers\ImageManagerController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
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
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('image_managers').'.'.$fileType;

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
