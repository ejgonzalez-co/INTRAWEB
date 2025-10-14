<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreateSentRequest;
use Modules\Correspondence\Http\Requests\UpdateSentRequest;
use Modules\Correspondence\Repositories\SentRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Correspondence\Models\Sent;
use Modules\Correspondence\Models\SentTypes;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class SentController extends AppBaseController {

    /** @var  SentRepository */
    private $sentRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(SentRepository $sentRepo) {
        $this->sentRepository = $sentRepo;
    }

    /**
     * Muestra la vista para el CRUD de Sent.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('correspondence::sents.index');
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
        $sents = $this->sentRepository->all();
        return $this->sendResponse($sents->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateSentRequest $request
     *
     * @return Response
     */
    public function store(CreateSentRequest $request)
    {
        $input = $request->all();
        // dd($input);

    
        $input["users_id"]=Auth::user()->id;
        $input["dependencias_id"]=5;

        $input["state"]="Público";
        // $input["consecutive"]="TEST-001";
        $input["year"] = date("Y");
        $prefixType = SentTypes::where('id', $input["type"])->pluck('prefix')->first();

        $lastConsecutive = Sent::where('state', "Público")->latest()->pluck('consecutive')->first();

        if (!$lastConsecutive) {
            $nextConsecutive = 1;
        } else {
            $lastConsecutiveArray = explode("-", $lastConsecutive);

            $nextConsecutive = $lastConsecutiveArray[2]+1;
        }

        $input["consecutive"] = date("Y")."-".$prefixType."-".$nextConsecutive;

        // Inicia la transaccion
        DB::beginTransaction();
        // try {
        // Inserta el registro en la base de datos
        $sent = $this->sentRepository->create($input);

        /*
        // Valida si viene usuarios para asignar
        if (!empty($input['recipientsList'])) {
            //texto para almacenar en la tabla interna 
            $textRecipients = array();
            //recorre los destinatarios
            foreach ($input['recipientsList'] as $recipent) {
                //array de destinatarios
                $recipentArray = json_decode($recipent,true);
                $textRecipients[] = $recipentArray["name"];
                $recipentArray["correspondence_sent_id"] = $sent->id;
                SentRecipient::create($recipentArray);
            }
            $sent->sentRecipients;
            $updateSent["recipients"] = implode("<br>", $textRecipients);
            $sent = $this->sentRepository->update($updateSent,$sent->id);
        }
        */

        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($sent->toArray(), trans('msg_success_save'));
       /* } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\SentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\SentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }*/
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateSentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSentRequest $request)
    {
        $input = $request->all();

        /** @var Sent $sent */
        $sent = $this->sentRepository->find($id);

        if (empty($sent)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $sent = $this->sentRepository->update($input, $id);

            /*
            // Valida si viene usuarios para asignar
            if (!empty($input['recipientsList'])) {

                //borra todo para volver a insertarlo
                SentRecipient::where('correspondence_sent_id', $id)->delete();

                //texto para almacenar en la tabla interna 
                $textRecipients = array();
                //recorre los destinatarios
                foreach ($input['recipientsList'] as $recipent) {
                    //array de destinatarios
                    $recipentArray = json_decode($recipent,true);
                    $textRecipients[] = $recipentArray["name"];
                    $recipentArray["correspondence_sent_id"] = $id;
                    SentRecipient::create($recipentArray);
                }
                $sent->sentRecipients;
                $updateSent["recipients"] = implode("<br>", $textRecipients);
                $sent = $this->sentRepository->update($updateSent,$id);
            }else{

                //borra todo para volver a insertarlo
                SentRecipient::where('correspondence_sent_id', $id)->delete();
                $updateSent["recipients"] = "";
                $sent = $this->sentRepository->update($updateSent,$id);
            }
            */

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($sent->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\SentController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\SentController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine().'  Consecutivo: '.($send['consecutive'] ?? 'Desconocido') . '- ID:' . ($id ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un Sent del almacenamiento
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

        /** @var Sent $sent */
        $sent = $this->sentRepository->find($id);

        if (empty($sent)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $sent->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\SentController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\SentController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine().'  Consecutivo: '.($send['consecutive'] ?? 'Desconocido') . ' -ID: ' . ($id ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
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
        $fileName = time().'-'.trans('sents').'.'.$fileType;

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
