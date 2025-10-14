<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Leca\Http\Requests\CreateCustomersRequest;
use Modules\Leca\Http\Requests\UpdateCustomersRequest;
use Modules\Leca\Repositories\CustomersRepository;
use Modules\Leca\Models\Customers;
use Modules\Leca\Models\SamplePoints;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
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
class CustomersController extends AppBaseController {

    /** @var  CustomersRepository */
    private $customersRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(CustomersRepository $customersRepo) {
        $this->customersRepository = $customersRepo;
    }

    /**
     * Muestra la vista para el CRUD de Customers.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('leca::customers.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Nov. 11 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $customers = Customers::with(['pointLocation'])->latest()->get();
        return $this->sendResponse($customers->toArray(), trans('data_obtained_successfully'));
    }
    
    /**
     * Obtiene los estados del cliente
     *
     * @author José Manuel Marín Londoño. - Nov. 8 - 2021
     * @version 1.0.0
     */
    public function getConstants() {
        return $this->sendResponse(config('leca.status_list'), 'Obtiene los estados correctamente');
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author José Manuel Marín Londoño. - Nov. 11 - 2021
     * @version 1.0.0
     *
     * @param CreateCustomersRequest $request
     *
     * @return Response
     */
    public function store(CreateCustomersRequest $request) {


        //Recupera el id del usuario que esta en sesion
        $user = Auth::user();
        $input = $request->all();
        
        $point_location = $input['point_location'];
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            //Guarda el campo users_id del usuarios que esta en sesion
            $input['users_id'] = Auth::user()->id;
            $input['state'] = 1;

            if(!array_key_exists("telephone",$input)){
                $input['telephone']=null;
            }
            // if ($input['email'] != null) {
            //     //retorna un mensaje de que posee un registro pendiente
            //     return $this->sendResponse("error", 'Esta seguro que este es el correo del cliente: '.$input['email'].'<br><br>'.'Este software le notificará a este correo '.$input['email'].' El PIN y la contraseña del cliente que se esta creando', 'warning');
            // }

                //Carácteres para la contraseña
                $chain = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
                $chain .= '0123456789' ;
                $chain .= '.';
                //Se obtiene la longitud de la cadena de caracteres
                $chainLength=strlen($chain);
                //Se define la varibale que va a tener la contraseña
                $password = "";
                //Se define la longitud de la contraseña, en este caso son 10, pero se pueden poner lo que se necesite
                $lengthPassword = 10;

                //Se empieza a crear la contraseña
                for($i = 1; $i <= $lengthPassword ; $i++) {
                    //Definimos un numero aleatorio entre 0 y la longitud de la cadena de caracteres -1
                    $position=rand(0,$chainLength-1);
                   //obtenemos un caracter aleatorio escogido de la cadena de caracteres
                    $password .= substr($chain,$position,1);
                }
                $input['password'] = $password;
                $pin = "";

                //Se crea un valor aleatorio de 6 digitos
                $pinRand = rand(1000,999999);
                //Se crea la variable
                $pin = date("Y")."-LC-".$pinRand;
                $input['pin'] = $pin;
            // Inserta el registro en la base de datos
            $customers = $this->customersRepository->create($input);
            //Separa un array en dos posisciones
            // foreach($point_location as $option){
            //     $array[]=explode(",", $option);
            // }
            // //Toma la primera posicion de cada array
            // foreach($array as $option){
            //     $arrayTwo[]=$option[0].'}';
            // }
            // //Forma un array asociativo
            // foreach($arrayTwo as $data){
            //     $arrayPointData[] = json_decode($data);
            // }
            // //Recibe el array asociativo y lo convierte en un array
            // foreach($arrayPointData as $finalOption){
            //     //Valida que escoja cualquiera de las dos posiciones que se estan recibiendo
            //     $finalArray[]=$finalOption->point_location;
            // }
            //Guarda el dato en la tabla puente
            $customers->pointLocation()->sync($point_location);
            $customers->pointLocation;
            //Recupera el correo que se ingreso al cual se le enviara el correo
            $mailToSend = $input['email'];
            // $user_appointmetn->register_appointment=$appointment;
            $custom = json_decode('{"subject": "LECA (EPA)"}');
            //Envia notificacion al usuario asignado
            // Mail::to($mailToSend)->send(new SendMail('leca::customers.email.email_to_customer_leca', $customers, $custom));
            // Efectua los cambios realizados
            DB::commit();
            return $this->sendResponse($customers->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\CustomersController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\CustomersController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateCustomersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCustomersRequest $request) {

        $input = $request->all();
        $point_location = $input['point_location'];

        /** @var Customers $customers */
        $customers = $this->customersRepository->find($id);

        if (empty($customers)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $customers = $this->customersRepository->update($input, $id);
            // Eliminar los registros de categorías autorizadas existentes según el id del registro principal
            DB::table('lc_customers_has_lc_sample_points')->where('lc_customers_id', $customers->id)->delete();
            
            
            // //Separa un array en dos posisciones
            // foreach($point_location as $option){
            //     $array[]=explode(",", $option);
            // }
            
            // //Toma la primera posicion de cada array
            // foreach($array as $option){
            //     $arrayTwo[]=$option[0].'}';
            // }
            // //Forma un array asociativo
            // foreach($arrayTwo as $data){
            //     $arrayPointData[] = json_decode($data);
            // }

            
            
            // //Recibe el array asociativo y lo convierte en un array
            // foreach($arrayPointData as $finalOption){
                
            //     //Valida que escoja cualquiera de las dos posiciones que se estan recibiendo
            //     $finalArray[]=$finalOption->id ?? $finalOption->point_location;
            // }

            //Guarda el dato en la tabla puente
            $customers->pointLocation()->sync($point_location);
            $customers->pointLocation;
            // Efectua los cambios realizados
            DB::commit();
            return $this->sendResponse($customers->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\CustomersController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\CustomersController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un Customers del almacenamiento
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

        /** @var Customers $customers */
        $customers = $this->customersRepository->find($id);

        if (empty($customers)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $customers->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\CustomersController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\CustomersController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *ñ
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
        $fileName = time().'-'.trans('customers').'.'.$fileType;

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

    /**
     * Actualiza el estado del cliente
     *
     * @author José Manuel Marín Londoño. - Nov. 8 - 2021
     * @version 1.0.0
     */
    public function changeStatusCustomers(Request $request) {
        $input = $request->toArray();
        
        $idCustomer = $input['id'];
        $customers = Customers::find($idCustomer);
        $customers->state = $input['state'];

        $customers->save();

        return $this->sendResponse($customers->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene todos los puntos que esten guardados
     *
     * @author José Manuel Marín Londoño. - Nov. 09 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getPoints()
    {
        $points = SamplePoints::where("point_location", "!=", "Otro...")->where("point_location", "!=", "Otro")->get();

        return $this->sendResponse($points->toArray(), trans('data_obtained_successfully'));
    }
}