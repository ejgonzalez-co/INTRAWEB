<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreateDownloadRequest;
use Modules\Intranet\Http\Requests\UpdateDownloadRequest;
use Modules\Intranet\Repositories\DownloadRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;

use Modules\Intranet\Models\DownloadTagsSelected;
use Modules\Intranet\Models\Download;

use DB;

use Illuminate\Support\Arr;
use Carbon\Carbon;


/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class DownloadController extends AppBaseController {

    /** @var  DownloadRepository */
    private $downloadRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(DownloadRepository $downloadRepo) {
        $this->downloadRepository = $downloadRepo;
    }

    /**
     * Muestra la vista para el CRUD de Download.
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
            return view('intranet::downloads.index');
        }
        return view("auth.forbidden");
    }

    public function homePanel(Request $request) {
        return view('intranet::downloads.index_public');
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

        $admMantenimientos = Auth::user()->hasRole("Administrador intranet de descargas");

        // Valida si es un administrador de mantenimientos debe mostrarle todos los documentos
        if($admMantenimientos) {
            $downloads = Download::with(["downloadTagsSelected", "users", "owner", "categorie", "fileVote", "downloadLicense"])->latest()->get()->toArray();
        } else {
            //valida si el usuario en sesion pertenece a los grupos de trabajo de la encuesta
            $groups =  DB::table('users_work_groups')
                ->where('users_id', Auth::user()->id)
                ->pluck("work_groups_id")->toArray();
            // De no ser un administrador, le muestra solo los archivos que esten publicados, como también en el rago de fechas activas; consulta los grupos y usuarios de los archivos
            $downloads = Download::with(["downloadTagsSelected", "users", "owner", "categorie", "fileVote", "downloadLicense"])->where("published", "Publicado")->where('publish_up', "<=", Carbon::now())->where('publish_down', ">=", Carbon::now())->latest()->get()->filter(function ($download) use ($groups) {
                $download_grupos = $download->categorie->downloadAccessGroups->toArray();
                //El método Arr::flatten convierte un arreglo multidimensional en uno simple, ejemplo: https://styde.net/manejo-de-arreglos-con-laravel/
                $download_grupos_acceso = Arr::flatten($download_grupos);

                $download_users_acceso = $download->categorie->downloadAccessUsers->toArray();

                Arr::flatten($download_users_acceso);
              
                return !!array_intersect($download_grupos_acceso, $groups) ? true : ($download_users_acceso ? $download_users_acceso[0] == Auth::user()->id : false);
            })->values()->toArray();
        }
        
        return $this->sendResponse($downloads, trans('data_obtained_successfully'));
    }

    public function incrementHits(Request $request) {

        $download = Download::where('id', $request->idDownload)->increment('hits', 1);

        return $this->sendResponse($download, trans('msg_success_update'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateDownloadRequest $request
     *
     * @return Response
     */
    public function store(CreateDownloadRequest $request) {

        $input = $request->all();
        $input["users_id"] = Auth::user()->id;
        // dd($input);
 
        // try {
            $order = Download::max("ordering");

            $order = $order + 1;

            $input["ordering"] = $order;
            $input["checked_out"] = Auth::user()->id;
            $input["checked_out_time"] = date("Y-m-d H:i:s");

            if(!isset($input["hits"])) {
                $input["hits"] = 0;
            }

            // Valida si se selecciona el adjunto del archivo
            if ($request->hasFile('filename')) {
                $input['filename'] = substr($input['filename']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto del archivo para reproducir
            if ($request->hasFile('filename_play')) {
                $input['filename_play'] = substr($input['filename_play']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto del archivo para la vista previa
            if ($request->hasFile('filename_preview')) {
                $input['filename_preview'] = substr($input['filename_preview']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto del ícono
            if ($request->hasFile('image_filename')) {
                $input['image_filename'] = substr($input['image_filename']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto del ícono especifico 1
            if ($request->hasFile('image_filename_spec1')) {
                $input['image_filename_spec1'] = substr($input['image_filename_spec1']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto del ícono especifico 2
            if ($request->hasFile('image_filename_spec2')) {
                $input['image_filename_spec2'] = substr($input['image_filename_spec2']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto de la imagen
            if ($request->hasFile('image_download')) {
                $input['image_download'] = substr($input['image_download']->store('public/downloads'), 7);
            }

            // Inserta el registro en la base de datos
            $download = $this->downloadRepository->create($input);

            // Condición para validar si existe algún registro de grupos con acceso
            if (!empty($input['download_tags_selected'])) {
                // Ciclo para recorrer todos los registros de grupos con acceso
                foreach($input['download_tags_selected'] as $option){

                    $downloadTagsSelected = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    DownloadTagsSelected::create([
                        'intranet_downloads_tags_id' => $downloadTagsSelected->id,
                        'intranet_downloads_id' => $download->id 
                        ]);
                }
            }

            $download->downloadTagsSelected;
            $download->users;
            $download->owner;

            return $this->sendResponse($download->toArray(), trans('msg_success_save'));
        // } 
        // catch (\Illuminate\Database\QueryException $error) {
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
        //     // Retorna mensaje de error de base de datos
        //     return $this->sendSuccess(config('constants.support_message'), 'info');
        // }
        // catch (\Exception $e) {
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
        //     // Retorna error de tipo logico
        //     return $this->sendSuccess(config('constants.support_message'), 'info');
        // }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDownloadRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDownloadRequest $request) {

        $input = $request->all();

        /** @var Download $download */
        $download = $this->downloadRepository->find($id);

        if (empty($download)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {

            $input["checked_out"] = Auth::user()->id;
            $input["checked_out_time"] = date("Y-m-d H:i:s");

            // Valida si se selecciona el adjunto del archivo
            if ($request->hasFile('filename')) {
                $input['filename'] = substr($input['filename']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto del archivo para reproducir
            if ($request->hasFile('filename_play')) {
                $input['filename_play'] = substr($input['filename_play']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto del archivo para la vista previa
            if ($request->hasFile('filename_preview')) {
                $input['filename_preview'] = substr($input['filename_preview']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto del ícono
            if ($request->hasFile('image_filename')) {
                $input['image_filename'] = substr($input['image_filename']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto del ícono especifico 1
            if ($request->hasFile('image_filename_spec1')) {
                $input['image_filename_spec1'] = substr($input['image_filename_spec1']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto del ícono especifico 2
            if ($request->hasFile('image_filename_spec2')) {
                $input['image_filename_spec2'] = substr($input['image_filename_spec2']->store('public/downloads'), 7);
            }

            // Valida si se selecciona el adjunto de la imagen
            if ($request->hasFile('image_download')) {
                $input['image_download'] = substr($input['image_download']->store('public/downloads'), 7);
            }

            // Actualiza el registro
            $download = $this->downloadRepository->update($input, $id);

            // Condición para validar si existe algún registro de grupos con acceso
            if (!empty($input['download_tags_selected'])) {
                // Eliminar los registros de las etiquetas existentes según el id del registro principal
                DownloadTagsSelected::where('intranet_downloads_id', $download->id)->delete();
                // Ciclo para recorrer todos los registros de grupos con acceso
                foreach($input['download_tags_selected'] as $option){

                    $downloadTagsSelected = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    DownloadTagsSelected::create([
                        'intranet_downloads_tags_id' => $downloadTagsSelected->id,
                        'intranet_downloads_id' => $download->id 
                        ]);
                }
            }

            $download->downloadTagsSelected;
            $download->users;
            $download->owner;
        
            return $this->sendResponse($download->toArray(), trans('msg_success_update'));
        } 
        catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($download['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un Download del almacenamiento
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

        /** @var Download $download */
        $download = $this->downloadRepository->find($id);

        if (empty($download)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $download->delete();

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
        $fileName = time().'-'.trans('downloads').'.'.$fileType;

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
