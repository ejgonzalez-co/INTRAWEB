<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreateCitizenRequest;
use Modules\Intranet\Http\Requests\UpdateCitizenRequest;
use Modules\Intranet\Repositories\CitizenRepository;
use App\User;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Modules\Intranet\Models\Citizen;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class CitizenController extends AppBaseController {

    /** @var  CitizenRepository */
    private $citizenRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(CitizenRepository $citizenRepo) {
        $this->citizenRepository = $citizenRepo;
    }

    /**
     * Muestra la vista para el CRUD de Citizen.
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
            return view('intranet::citizens.index');
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
    public function all(Request $request) {
        $query =$request->input('query');

        // Variable para contar el número total de registros de la consulta realizada
        $count_citizens = 0;

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros

        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
            // dd("filtro1");

            $decoded_filter = base64_decode($request["f"]);
            $decoded_filter = str_replace('name', 'citizens.name', $decoded_filter); // Reemplaza `name` por `citizens.name`

            $citizens = Citizen::join('users', 'citizens.user_id', '=', 'users.id')
                ->with(['states', 'cities'])
                ->where('citizens.name', 'like', '%' . $query . '%')
                ->whereRaw($decoded_filter)
                ->select('citizens.*', 'users.email')
                ->latest('citizens.created_at')
                ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
                ->take(base64_decode($request["pi"]))
                ->get();


            $count_citizens = Citizen::join('users', 'citizens.user_id', '=', 'users.id')
            ->where('citizens.name', 'like', '%' . $query . '%')
            ->whereRaw($decoded_filter)
            ->count();

            // $citizens = Citizen::with(["states", "cities"])->where('name','like','%'.$query.'%')->whereRaw(base64_decode($request["f"]))->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get();
            // $count_citizens = Citizen::with(["states", "cities"])->where('name','like','%'.$query.'%')->whereRaw(base64_decode($request["f"]))->count();
        } else if(isset($request["cp"]) && isset($request["pi"])) {

            $citizens = Citizen::join('users', 'citizens.user_id', '=', 'users.id')
            ->with(['states', 'cities'])
            ->where('citizens.name', 'like', '%' . $query . '%')
            ->latest('citizens.created_at') // Asegúrate de ordenar por un campo específico de la tabla `citizens`
            ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
            ->take(base64_decode($request["pi"]))
            ->select('citizens.*', 'users.email') // Selecciona todos los campos de citizens y solo el email de users
            ->get();

            $count_citizens = Citizen::join('users', 'citizens.user_id', '=', 'users.id')
            ->where('citizens.name', 'like', '%' . $query . '%')
            ->count();

            // $citizens = Citizen::with(["states", "cities"])->where('name','like','%'.$query.'%')->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get();
            // $count_citizens = Citizen::with(["states", "cities"])->where('name','like','%'.$query.'%')->count();
        } else {

            $citizens = Citizen::join('users', 'citizens.user_id', '=', 'users.id')
            ->with(['states', 'cities'])
            ->where('citizens.name', 'like', '%' . $query . '%')
            ->latest('citizens.created_at') // Ordenar por la fecha de creación en la tabla `citizens`
            ->select('citizens.*', 'users.email') // Selecciona todos los campos de citizens y solo el email de users
            ->get();

            $count_citizens = Citizen::join('users', 'citizens.user_id', '=', 'users.id')
            ->with(['states', 'cities'])
            ->where('citizens.name', 'like', '%' . $query . '%')
            ->count();


            // $citizens = Citizen::with(["states", "cities"])->where('name','like','%'.$query.'%')->latest()->get();
            // $count_citizens = Citizen::with(["states", "cities"])->where('name','like','%'.$query.'%')->count();
        }

        // return $this->sendResponse($citizens->toArray(), trans('data_obtained_successfully'));

        return $this->sendResponseAvanzado($citizens, trans('data_obtained_successfully'), null, ["total_registros" => $count_citizens]);
    }

    public function getCitizensByName(Request $request){
        $query =$request->input('query');
        $offset = isset($request["cp"]) ? (int) base64_decode($request["cp"]) : 0;

        // Si es igual a uno es porque no esta utilizando el loadScroll
        $citizens = Citizen::join('users', 'citizens.user_id', '=', 'users.id')
        ->with(['states', 'cities'])
        ->where('citizens.name', 'like', '%' . $query . '%')
        ->select('citizens.*', 'users.email')
        ->orderBy('citizens.name')
        ->skip($offset)
        ->take(5)
        ->get();

        return $this->sendResponse($citizens->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateCitizenRequest $request
     *
     * @return Response
     */
    public function store(CreateCitizenRequest $request) {

        $input = $request->all();

        if (empty($input)){
            return $this->sendSuccess("El formulario no contiene información. Le solicitamos verificar los campos antes de proceder." , 'error');
        }
        // Valida si el usuario ingreso contraseñas para su respectivas validaciones
        if(!empty($input["password"])){
            if(empty($input["password_confirmation"])){
                return $this->sendSuccess("Debe de diligenciar el campo <strong>Confirmar contraseña</strong>", 'info');
            }

            if($input["password"] != $input["password_confirmation"]){
                return $this->sendSuccess("La contraseña de confirmación no coincide", 'info');
            }

            if($this->isNotSecurePassword($input)){
                return $this->sendSuccess("La contraseña debe tener mínimo 12 caracteres, debe contener al menos una letra mayúscula, una minúscula, un número y un símbolo - /; 0) & @.?! %*", 'info');
            }
        }

        // Reglas para el registro del ciudadano, estas se usan mas que todo para el formulario desde Intranet
        $rules = [
            'type_person' => 'required',
            'type_document' => 'required',
            'document_number' => ['required', 'unique:citizens'],
            'email' => 'unique:users',
            'states_id' => 'required',
            'city_id' => 'required',
            'address' => 'required',
            'phone' => 'required'
        ];
        // Valida el tipo de persona, si es natural, es requerido el primer nombre y el primer apellido
        if($input['type_person'] == 1) {
            $rules['first_name'] = 'required';
            $rules['first_surname'] = 'required';
        } else {
            // De lo contrario es persona jurídica, es requerido el nombre de la razón social
            $rules['name'] = 'required';
        }

        // Mensajes para las reglas estipuladas anteriormente
        $customMessages = [
            'type_person.required' => 'El tipo de persona es obligatorio',
            'type_document.required' => 'El tipo de documento es obligatorio',
            'document_number.required' => 'El número de documento es obligatorio',
            'email.required' => 'El correo es obligatorio',
            'first_name.required' => 'El primer nombre es obligatorio',
            'first_surname.required' => 'El primer apellido es obligatorio',
            'name.required' => 'El nombre de la razón social es obligatorio',
            'states_id.required' => 'El departamento es obligatorio',
            'city_id.required' => 'La ciudad es obligatoria',
            'address.required' => 'La dirección es obligatoria',
            'phone.required' => 'El teléfono es obligatorio',
            'document_number.unique' => 'El número de documento ya está en uso',
            'email.unique' => 'El correo ya está en uso',
        ];


        // Ejecuta la validación de las reglas
        $this->validate($request, $rules, $customMessages);
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Tipo de usuario de tipo ciudadano
            $input['user_type'] = 'Ciudadano';

            $input['name'] = $input['first_name'] ?? $input['name'];

            // Valida si existe un segundo nombre
            if (!empty($input['second_name'])) {
                $input['name'] .= " ".$input['second_name'];
            }

            // Valida si existe el primer apellido
            if(!empty($input['first_surname']))
                $input['name'] .=  " ".$input['first_surname'];

            // Valida si existe un segundo apellido
            if (!empty($input['second_surname'])) {
                $input['name'] .= " ".$input['second_surname'];
            }

            $input['password'] = !empty($input["password"]) ? Hash::make($input['password']) : Hash::make($input['document_number']);
            $input['sendEmail'] = 1;
            // Inserta el registro del usuario
            if (isset($input['email'])) {
                // valida que el correo digitado no se encuentre ya asignado
                if (User:: where('email',$input['email'] )->exists()) {
                    return $this->sendSuccess('El correo '. $input['email']. ' ya se encuentra registrado, por favor, verifique e intente de nuevo.', 'info');
                }
            }else{
                $input['email'] = rand(1, 999).$input['document_number'].'@'.'random.com';
            }
            $user = User::create($input);

            // Asigna rol a usuario
            $user->assignRole('Ciudadano');

            $input['user_id']  = $user->id;

            // Inserta el registro en la base de datos
            $citizen = $this->citizenRepository->create($input);
            // Obtiene el departamento del ciudadano
            $citizen->states;
            // Obtiene la ciudad del ciudadano
            $citizen->cities;
            // Obtiene la información con la relación users
            $citizen->users;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($citizen->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Citizen', 'Modules\Intranet\Http\Controllers\CitizenController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Citizen', 'Modules\Intranet\Http\Controllers\CitizenController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
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
     * @param UpdateCitizenRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $input = $request->all();

        // Valida si el usuario ingreso contraseñas para su respectivas validaciones
        if(!empty($input["password"])){
            if(empty($input["password_confirmation"])){
                return $this->sendSuccess("Debe de diligenciar el campo <strong>Confirmar contraseña</strong>", 'info');
            }

            if($input["password"] != $input["password_confirmation"]){
                return $this->sendSuccess("La contraseña de confirmación no coincide", 'info');
            }

            if($this->isNotSecurePassword($input)){
                return $this->sendSuccess("La contraseña debe tener mínimo 12 caracteres, debe contener al menos una letra mayúscula, una minúscula, un número y un símbolo - /; 0) & @.?! %*", 'info');
            }
        }

        /** @var Citizen $citizen */
        $citizen = $this->citizenRepository->find($id);

        if (empty($citizen)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        try {
            // Valida si se debe actualizar la contrasena
            if (!empty($input['password'])) {
                $request->validate([
                    'password' => ['required', 'string',  Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                    'confirmed']
                ]);
                // Encripta la contrasena
                $input['password'] = Hash::make($input['password']);
            }

             // Valida si se va a actualizar el correo del usuario, verificando si el correo a actualizar es diferente al actual
            if($citizen["email"] != $input["email"]) {
                // Valida que el correo del usuario a actualizar, no este ya registrado en la tabla users
                $validaciones = $request->validate([
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura la excepción de validación y retorna un mensaje personalizado
            return $this->sendSuccess("Hay un dato incorrecto por favor verifique: " . $e->getMessage(), 'warning');
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $input['name'] = $input['first_name'];

            // Valida si existe un segundo nombre
            if (!empty($input['second_name'])) {
                $input['name'] .= " ".$input['second_name'];
            }

            $input['name'] .=  " ".$input['first_surname'];

            try {
                // Valida si existe un segundo apellido
                if (!empty($input['second_surname'])) {
                    $input['name'] .= " ".$input['second_surname'];
                }
                // Valida si se va a actualizar el correo del usuario, verificando si el correo a actualizar es diferente al actual
                if($citizen["email"] != $input["email"]) {
                    // Valida que el correo del usuario a actualizar, no este ya registrado en la tabla users
                    $validaciones = $request->validate([
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
                    ]);
                }
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Captura la excepción de validación y retorna un mensaje personalizado
                return $this->sendSuccess("Hay un dato incorrecto por favor verifique: " . $e->getMessage(), 'warning');
            }
            // Actualiza el registro del ciudadano
            $citizen = $this->citizenRepository->update($input, $id);
            // Actualiza el registro de la tabla users relacionado a la foránea user_id
            User::where("id", $citizen->user_id)->update(["name" => $input['name'], "email" => $input['email'], "password" => !empty($input["password"]) ? $input['password']  : $citizen->users->password]);
            // Obtiene el departamento del ciudadano
            $citizen->states;
            // Obtiene la ciudad del ciudadano
            $citizen->cities;
            // Obtiene y recarga la información con la relación users
            $citizen->load('users');
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($citizen->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Citizen', 'Modules\Intranet\Http\Controllers\CitizenController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Citizen', 'Modules\Intranet\Http\Controllers\CitizenController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($citizen['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un Citizen del almacenamiento
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

        /** @var Citizen $citizen */
        $citizen = $this->citizenRepository->find($id);

        if (empty($citizen)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $citizen->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Citizen', 'Modules\Intranet\Http\Controllers\CitizenController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Citizen', 'Modules\Intranet\Http\Controllers\CitizenController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($citizen['id'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('citizens').'.'.$fileType;

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

    private function isNotSecurePassword(array $data) : bool {
        $validator = Validator::make($data, [
            'password' => ['required', 'string',  Password::min(12)
                ->mixedCase()
                ->numbers()
                ->symbols(),
        ]]);

        return $validator->fails();
    }


      /**
     * Busca y retorna el email de un ciudadano enmascarado.
     *
     * @param  string $documentNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchMailCitizen($param)
    {

        try {
            // Decodifica Base64
            $documentNumber = base64_decode($param, true);

            if ($documentNumber === false) {
                return $this->sendError(
                    'El número de identificación proporcionado no es válido.',
                    [],
                    400 // Bad Request
                );
            }
        } catch (\Exception $e) {
            return $this->sendError(
                'Error al procesar el número de identificación.',
                [],
                400
            );
        }
        $citizen = Citizen::where('document_number', $documentNumber)->first();

        if ($citizen) {
            $data = [
                'email' => $this->maskFieldEmail($citizen->email ?? ''),
            ];

            return $this->sendResponse($data, trans('data_obtained_successfully'));
        }

        return $this->sendError(
            'No se encontró un ciudadano asociado al número de identificación proporcionado.',
            [],
            404 // 404 Not Found es el código correcto
        );
    }

    function maskFieldEmail(string $email, array $opt = []): string {
        // Opciones
        $keepLocal         = $opt['keep_local']          ?? 3;   // caracteres visibles al inicio del local
        $keepDomain        = $opt['keep_domain']         ?? 2;   // visibles al inicio del primer label del dominio
        $maskChar          = $opt['mask_char']           ?? '*';
        $fixedLocalStars   = $opt['fixed_local_stars']   ?? null; // si lo pones (int), usa ese # de *
        $fixedDomainStars  = $opt['fixed_domain_stars']  ?? null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return $email;

        [$local, $domain] = explode('@', $email, 2);

        $strlen = function($s){ return function_exists('mb_strlen') ? mb_strlen($s, 'UTF-8') : strlen($s); };
        $substr = function($s, $start, $len=null){
            if (function_exists('mb_substr')) return mb_substr($s, $start, $len ?? (mb_strlen($s,'UTF-8') - $start), 'UTF-8');
            return substr($s, $start, $len ?? (strlen($s) - $start));
        };

        // Local
        $lenLocal     = $strlen($local);
        $visibleLocal = $substr($local, 0, min($keepLocal, $lenLocal));
        $starsLocal   = $fixedLocalStars ?? max(1, $lenLocal - $strlen($visibleLocal));
        $maskedLocal  = $visibleLocal . str_repeat($maskChar, $starsLocal);

        // Dominio: solo enmascaramos el PRIMER label (ej. "seven" en seven.com.co)
        $labels = explode('.', $domain);
        if (!empty($labels)) {
            $first        = $labels[0];
            $lenFirst     = $strlen($first);
            $visibleFirst = $substr($first, 0, min($keepDomain, $lenFirst));
            $starsDomain  = $fixedDomainStars ?? max(1, $lenFirst - $strlen($visibleFirst));
            $labels[0]    = $visibleFirst . str_repeat($maskChar, $starsDomain);
        }

        return $maskedLocal . '@' . implode('.', $labels);
    }
}
