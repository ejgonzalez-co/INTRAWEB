<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\RequestExport;
use Modules\HelpTable\Http\Requests\CreateTechnicalConceptRequest;
use Modules\HelpTable\Http\Requests\UpdateTechnicalConceptRequest;
use Modules\HelpTable\Repositories\TechnicalConceptRepository;
use Modules\HelpTable\Models\TechnicalConcept;
use Modules\HelpTable\Models\TechnicalConceptsHistory;
use Modules\Intranet\Models\Dependency;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\JwtController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Http\Controllers\SendNotificationController;
use Modules\Configuracion\Models\ConfigurationGeneral;

/**
 * Descripcion de la clase
 *
 * @author Kleverman Salazar Florez. - Abr . 12 - 2023
 * @version 1.0.0
 */
class TechnicalConceptController extends AppBaseController
{

    /** @var  TechnicalConceptRepository */
    private $technicalConceptRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Abr . 12 - 2023
     * @version 1.0.0
     */
    public function __construct(TechnicalConceptRepository $technicalConceptRepo)
    {
        $this->technicalConceptRepository = $technicalConceptRepo;
    }

    /**
     * Muestra la vista para el CRUD de TechnicalConcept.
     *
     * @author Kleverman Salazar Florez. - Abr . 12 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return view('help_table::technical_concepts.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar Florez. - Abr . 12 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all()
    {
        if (Auth::user()->hasRole("Usuario TIC")) {
            $technical_concepts = TechnicalConcept::with("TechnicalConceptsHistory")->where("id_staff_member", Auth::user()->id)->latest()->get();
            return $this->sendResponse($technical_concepts->toArray(), trans('data_obtained_successfully'));
        }
        if (Auth::user()->hasRole("Administrador TIC")) {
            $technical_concepts = TechnicalConcept::with(["TechnicalConceptsHistory", "StaffMember","Technicians"])->latest()->get();
            return $this->sendResponse($technical_concepts->toArray(), trans('data_obtained_successfully'));
        }
        if (Auth::user()->hasRole("Soporte TIC")) {
            $technical_concepts = TechnicalConcept::where("technician_id",Auth::user()->id)->with(["TechnicalConceptsHistory", "StaffMember","Technicians"])->latest()->get();
            return $this->sendResponse($technical_concepts->toArray(), trans('data_obtained_successfully'));
        }
        if (Auth::user()->hasRole("Revisor concepto técnico TIC")) {
            $technical_concepts = TechnicalConcept::where("reviewer_id",Auth::user()->id)->with(["TechnicalConceptsHistory", "StaffMember","Technicians"])->latest()->get();
            return $this->sendResponse($technical_concepts->toArray(), trans('data_obtained_successfully'));
        }
        if (Auth::user()->hasRole("Aprobación concepto técnico TIC")) {
            $technical_concepts = TechnicalConcept::where("approver_id",Auth::user()->id)->with(["TechnicalConceptsHistory", "StaffMember","Technicians"])->latest()->get();
            return $this->sendResponse($technical_concepts->toArray(), trans('data_obtained_successfully'));
        }
    }

    /**
     * Obtiene todos los tecnicos
     *
     * @author Kleverman Salazar Florez. - Abr . 17 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getTechnicians()
    {
        $technicians = User::select(["id", "name"])->role(['Soporte TIC'])->orderBy("name")->get();
        return $this->sendResponse($technicians->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los usuarios TIC
     *
     * @author Kleverman Salazar Florez. - Dec . 07 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getTicsUsers()
    {
        $tics_users = User::select(["id", "name"])->where('block','0')->role(['Usuario TIC'])->orderBy("name")->get();
        return $this->sendResponse($tics_users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los usuarios cuyo rol sea Revisor concepto técnico TIC
     *
     * @author Kleverman Salazar Florez. - Abr . 17 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getReviewers()
    {
        $technicians = User::select(["id", "name"])->role(['Revisor concepto técnico TIC'])->get();
        return $this->sendResponse($technicians->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los usuarios cuyo rol sea Usuario TIC
     *
     * @author Kleverman Salazar Florez. - Abr . 18 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getStaffMembers()
    {
        $staffMmembers = User::select(["id", "name"])->role(['Usuario TIC'])->get();
        return $this->sendResponse($staffMmembers->toArray(), trans('data_obtained_successfully'));
    }

    public function getDependencies(){
        $dependencies = Dependency::select("nombre")->orderBy("nombre")->get()->toArray();
        return $this->sendResponse($dependencies, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Abr . 12 - 2023
     * @version 1.0.0
     *
     * @param CreateTechnicalConceptRequest $request
     *
     * @return Response
     */
    public function store(CreateTechnicalConceptRequest $request)
    {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $technicalConcept = $this->technicalConceptRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($technicalConcept->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Guarda un nuevo registro de una solicitud
     *
     * @author Kleverman Salazar Florez. - Abr . 12 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createRequestTechnicalConcept(Request $request)
    {
        $input = $request->all();

        $input["status"] = "Pendiente";
        if(Auth::user()->hasRole("Usuario TIC")){
            $input["id_staff_member"] = Auth::user()->id;
        }

        if(Auth::user()->hasRole("Administrador TIC")){
            $input["status"] = "Asignado";

            $technician = User::select(["name","email"])->where("id",$input["technician_id"])->first(); // Obtiene la informacion del tecnico

            $this->_sendEmail($technician->email,"help_table::technical_concepts.mails.technician_assistance",$technician,"Notificación Concepto Técnico"); // Envia el correo al tecnico
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $technicalConcept = $this->technicalConceptRepository->create($input);

            if(Auth::user()->hasRole("Administrador TIC")){
                $this->_createTechnicalConceptsHistory($technicalConcept->id,Auth::user()->id,Auth::user()->name,"Asignado","Solicitud creada y asignada al técnico TI"); // Crea el registro del historial
            }
            else{
                $this->_createTechnicalConceptsHistory($technicalConcept->id,Auth::user()->id,Auth::user()->name,"Pendiente","Solicitud enviada a la Secretaria TIC´s");
            }

            // Efectua los cambios realizados
            DB::commit();

            // Guardas las relaciones
            $technicalConcept->TechnicalConceptsHistory;
            $technicalConcept->Technicians;
            $technicalConcept->StaffMember;

            return $this->sendResponse($technicalConcept->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Envia la solicitud del concepto tecnico a revisión
     *
     * @author Kleverman Salazar Florez. - Abr . 18 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function sendRequestTechnicalConceptToReview(Request $request){
        $input = $request->all();

        $input["status"] = "En revisión";

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $technicalConcept = $this->technicalConceptRepository->update($input, $input["id"]);

            $this->_createTechnicalConceptsHistory($technicalConcept->id,Auth::user()->id,Auth::user()->name,"En revisión","Envío concepto técnico a revisión"); // Crea el registro del historial

            $reviewer = User::select(["name","email"])->where("id",$input["reviewer_id"])->first(); // Obtiene la informacion del revisor

            $this->_sendEmail($reviewer->email,"help_table::technical_concepts.mails.send_review",$reviewer,"Notificación Concepto Técnico"); // Envia el correo al tecnico

            // Efectua los cambios realizados
            DB::commit();

            // Guardas las relaciones
            $technicalConcept->TechnicalConceptsHistory;

            return $this->sendResponse($technicalConcept->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Envia la solicitud del concepto tecnico para aprobación pendiente
     *
     * @author Kleverman Salazar Florez. - Abr . 18 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function validateRequetStatus(Request $request){
        $input = $request->all();

        return $input["status"] === "Devolver al técnico" ? $this->_returnRequestToTechnician($input) : $this->_sendRequestTechnicalConceptToApproval($input);
    }

    /**
     * Valida si devuelvo a aprueba la solicitud
     *
     * @author Kleverman Salazar Florez. - Abr . 20 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function validateApprovalRequet(Request $request){
        $input = $request->all();

        return $input["status"] === "Devolver al revisor" ? $this->_returnRequestToReviewer($input) : $this->_approveRequest($input);
    }

    /**
     * Devuelve la solicitud al tecnico correspondiente
     *
     * @author Kleverman Salazar Florez. - Abr . 18 - 2023
     * @version 1.0.0
     *
     * @param array $data datos de la solicitud
     *
     * @return Response
     */
    private function _returnRequestToReviewer(array $data){
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $technicalConcept = $this->technicalConceptRepository->update($data, $data["id"]);

            $this->_createTechnicalConceptsHistory($technicalConcept->id,Auth::user()->id,Auth::user()->name,"Devuelto",$data["observation_return"]); // Crea el registro del historial

            $reviewer = User::select(["name","email"])->where("id",$technicalConcept->reviewer_id)->first(); // Obtiene la informacion del revisor

            $reviewer["observation_return"] = $data["observation_return"];

            $this->_sendEmail($reviewer->email,"help_table::technical_concepts.mails.request_returned_by_approver",$reviewer,"Notificación Devolución Concepto Técnico"); // Envia el correo al revisor

            // Efectua los cambios realizados
            DB::commit();

            // Guardas las relaciones
            $technicalConcept->TechnicalConceptsHistory;

            return $this->sendResponse($technicalConcept->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Devuelve la solicitud al tecnico correspondiente
     *
     * @author Kleverman Salazar Florez. - Abr . 18 - 2023
     * @version 1.0.0
     *
     * @param array $data datos de la solicitud
     *
     * @return Response
     */
    private function _returnRequestToTechnician(array $data){

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $technicalConcept = $this->technicalConceptRepository->update($data, $data["id"]);

            $this->_createTechnicalConceptsHistory($technicalConcept->id,Auth::user()->id,Auth::user()->name,"Devuelto",$data["observation_return"]); // Crea el registro del historial

            $technician = User::select(["name","email"])->where("id",$technicalConcept->technician_id)->first(); // Obtiene la informacion del tecnico

            $technician["observation_return"] = $data["observation_return"];

            $this->_sendEmail($technician->email,"help_table::technical_concepts.mails.request_returned_by_reviewer",$technician,"Notificación Devolución Concepto Técnico"); // Envia el correo al tecnico

            // Efectua los cambios realizados
            DB::commit();

            // Guardas las relaciones
            $technicalConcept->TechnicalConceptsHistory;

            return $this->sendResponse($technicalConcept->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Envia la solicitud del concepto tecnico para aprobación pendiente
     *
     * @author Kleverman Salazar Florez. - Abr . 18 - 2023
     * @version 1.0.0
     *
     * @param array $data datos de la solicitud
     *
     * @return Response
     */
    private function _sendRequestTechnicalConceptToApproval(array $data){
        $data["status"] = "Aprobación pendiente";

        $approver = User::select(["id"])->role(["Aprobación concepto técnico TIC"])->first(); // Obtiene la informacion del aprobador

        $data["approver_id"] = $approver->id;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $technicalConcept = $this->technicalConceptRepository->update($data, $data["id"]);

            $this->_createTechnicalConceptsHistory($technicalConcept->id,Auth::user()->id,Auth::user()->name,"Aprobación pendiente","Envía concepto técnico para aprobación y firma"); // Crea el registro del historial

            $approver = User::select(["name","email"])->role(["Aprobación concepto técnico TIC"])->first(); // Obtiene la informacion del aprobador

            $this->_sendEmail($approver->email,"help_table::technical_concepts.mails.send_approval",$approver,"Notificación Concepto Técnico"); // Envia el correo al tecnico

            // Efectua los cambios realizados
            DB::commit();

            // Guardas las relaciones
            $technicalConcept->TechnicalConceptsHistory;

            return $this->sendResponse($technicalConcept->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Aprueba la solicitud
     *
     * @author Kleverman Salazar Florez. - Abr . 20 - 2023
     * @version 1.0.0
     *
     * @param array $data datos de la solicitud
     *
     * @return Response
     */
    private function _approveRequest(array $data){
        $data["status"] = "Aprobado";

        $approver = User::select(["id"])->role(["Aprobación concepto técnico TIC"])->first(); // Obtiene la informacion del aprobador

        $data["approver_id"] = $approver->id;

        $currentConsecutive = TechnicalConcept::select("consecutive")->where("status","Aprobado")->orderBy("consecutive","DESC")->first();

        $data["consecutive"] = !isset($currentConsecutive->consecutive) ? 1 : $currentConsecutive->consecutive + 1;

        $data["date_issue_concept"] = date("Y-m-d H:i:s");

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $technicalConcept = $this->technicalConceptRepository->update($data, $data["id"]);

            $this->_createTechnicalConceptsHistory($technicalConcept->id,Auth::user()->id,Auth::user()->name,"Aprobado","Aprueba y firma concepto técnico"); // Crea el registro del historial

            $staffMmember = User::select(["name","email"])->where("id",$technicalConcept->id_staff_member)->first(); // Obtiene la informacion del funcionario

            $this->_sendEmail($staffMmember->email,"help_table::technical_concepts.mails.request_approved",$staffMmember,"Notificación Concepto Técnico"); // Envia el correo al funcionario

            // Efectua los cambios realizados
            DB::commit();

            // Guardas las relaciones
            $technicalConcept->TechnicalConceptsHistory;

            return $this->sendResponse($technicalConcept->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Abr . 12 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTechnicalConceptRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTechnicalConceptRequest $request)
    {

        $input = $request->all();

        if(!empty($input["url_attachments"])){
            if(is_array($input["url_attachments"])){
                $input["url_attachments"] = implode(",",$input["url_attachments"]);
            }
        }

        /** @var TechnicalConcept $technicalConcept */
        $technicalConcept = $this->technicalConceptRepository->find($id);

        if (empty($technicalConcept)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            if(Auth::user()->hasRole("Administrador TIC")){
                if(isset($input["technician_id"])){
                    $input["status"] = "Asignado";

                    $technician = User::select(["name","email"])->where("id",$input["technician_id"])->first(); // Obtiene la informacion del tecnico

                    $technicalConcept = $this->technicalConceptRepository->update($input, $id);

                    $this->_createTechnicalConceptsHistory($id,Auth::user()->id,Auth::user()->name,$technicalConcept["status"],""); // Crea el registro del historial

                    $this->_sendEmail($technician->email,"help_table::technical_concepts.mails.technician_assistance",$technician,"Notificación Concepto Técnico"); // Envia el correo al tecnico
                }
                else{
                    $technicalConcept = $this->technicalConceptRepository->update($input, $id);

                    $this->_createTechnicalConceptsHistory($id,Auth::user()->id,Auth::user()->name,$technicalConcept["status"],""); // Crea el registro del historial
                }


            }
            if(Auth::user()->hasRole("Soporte TIC")){
                $technicalConcept = $this->technicalConceptRepository->update($input, $id);

                $this->_createTechnicalConceptsHistory($id,Auth::user()->id,Auth::user()->name,$input["status"],"Elaboró concepto técnico"); // Crea el registro del historial

                $technician = User::select(["name","email"])->where("id",$input["technician_id"])->first(); // Obtiene la informacion del tecnico

                $this->_sendEmail($technician->email,"help_table::technical_concepts.mails.technician_assistance",$technician,"Notificación Concepto Técnico"); // Envia el correo al tecnico
            }

            if(Auth::user()->hasRole("Revisor concepto técnico TIC")){
                $technicalConcept = $this->technicalConceptRepository->update($input, $id);

                $this->_createTechnicalConceptsHistory($id,Auth::user()->id,Auth::user()->name,$input["status"],""); // Crea el registro del historial
            }

            if(Auth::user()->hasRole("Aprobación concepto técnico TIC")){
                $input["status"] = "Aprobación pendiente";

                $technicalConcept = $this->technicalConceptRepository->update($input, $id);

                $this->_createTechnicalConceptsHistory($id,Auth::user()->id,Auth::user()->name,"Aprobación pendiente",""); // Crea el registro del historial
            }

            // Efectua los cambios realizados
            DB::commit();

            // Guardas las relaciones
            $technicalConcept->TechnicalConceptsHistory;

            return $this->sendResponse($technicalConcept->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un TechnicalConcept del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Abr . 12 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {

        /** @var TechnicalConcept $technicalConcept */
        $technicalConcept = $this->technicalConceptRepository->find($id);

        if (empty($technicalConcept)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $technicalConcept->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\TechnicalConceptController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Kleverman Salazar Florez. - Abr . 12 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request)
    {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time() . '-' . trans('technical_concepts') . '.' . $fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $technical_concepts_id = [];

            // Bucle para guardar el id de los conceptos tecnicos para obtener despues las relaciones
            foreach ($input["data"] as $technical_concept) {
                array_push($technical_concepts_id,$technical_concept["id"]);
            }

            $technical_concepts = TechnicalConcept::select(["consecutive","date_issue_concept","inventory_manager","equipment_type","created_at","equipment_mark",
            "equipment_model",
            "equipment_serial","inventory_plate","technical_concept","observations","status","id_staff_member","reviewer_id","approver_id"])->with(["StaffMember","Reviewers","Approvers"])->whereIn("id",$technical_concepts_id)->get();
            return $this->_exportDataToXlsxFile('help_table::technical_concepts.exports.xlsx.technical_concepts',$technical_concepts,'O','Conceptos técnicos.xlsx');
        }
    }

     /**
     * Exporta un certificado en formato xlsx.
     *
     * @author Kleverman Salazar Florez - Abr. 23 - 2022
     * @version 1.0.0
     *
     * @param int $locationOfTheTemplate
     * @param object $data
     * @param string $finalColum
     * @param string $fileTypeName
     *
     * @return object
     *
     */
    private function _exportDataToXlsxFile(string $locationOfTheTemplate,object $data,string $finalColum, string $fileTypeName) : object {
        return Excel::download(new RequestExport($locationOfTheTemplate, JwtController::generateToken($data), $finalColum), $fileTypeName);
    }

    /**
    * Organiza la exportacion de datos
    *
    * @author Kleverman Salazar Florez. - Abr . 21 - 2023
    * @version 1.0.0
    *
    * @param int $technicalConceptId id del concepto tecnico
    */
    public function exportTechnicalConceptPdf(int $technicalConceptId){
        $technicalConcept = TechnicalConcept::where('id',$technicalConceptId)->with(['StaffMember','Reviewers','Approvers'])->first();

        // Obtiene el logo de la entidad para ubicar en el header
        $technicalConcept["entity_logo"] = ConfigurationGeneral::select("logo")->latest()->first()->logo;

        $filePDF = PDF::loadView('help_table::technical_concepts.exports.pdf.certificate_approved_request', ['data' => $technicalConcept])->setPaper('a4', 'portrait');
        return $filePDF->download("Concepto Tecnico No ".$technicalConcept["consecutive"].".pdf");
    }

    /**
     * Guarda el registro del historial de cambios de una solicitud de concepto tecnico
     *
     * @author Kleverman Salazar Florez. - Abr . 14 - 2023
     * @version 1.0.0
     *
     * @param int $technicalConceptId id del concepto tecnico
     * @param int $userId id del usuario quien realiza el cambio
     * @param string $userName nombre del usuario quien realiza el cambio
     * @param string $status estado del concepto tecnico
     */
    private function _createTechnicalConceptsHistory(int $technicalConceptId, int $userId, string $userName, string $status, string $observations): void
    {
        TechnicalConceptsHistory::create(["ht_technical_concepts_id" => $technicalConceptId, "user_id" => $userId, "user_name" => $userName, "status" => $status, "observations" => $observations]);
    }

     /**
     * Envia correo
     *
     * @author Kleverman Salazar Florez. - Abr . 17 - 2023
     * @version 1.0.0
     *
     * @param string $emails correos de los destinatarios
     * @param string $storageTemplate ubicacion de la plantilla del correo
     * @param object $data datos que se mostraran en el correo
     * @param string $subject texto del asunto
     */
    private function _sendEmail(string $emails, string $storageTemplate, object $data, string $subject): void
    {
        $custom = json_decode('{"subject": "' . $subject . '"}');

        // Mail::to($emails)->send(new SendMail($storageTemplate, $data, $custom));
        // Envia notificacion al usuario asignado
        SendNotificationController::SendNotification($storageTemplate,$custom,$data,$emails,'Mesa de ayuda');
    }
}
