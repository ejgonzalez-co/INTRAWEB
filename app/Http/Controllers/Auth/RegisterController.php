<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Intranet\Models\Citizen;
use App\Rules\ReCaptcha;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\AntiXSSController;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'document_number' => ['unique:citizens'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string',  Password::min(12)
                ->mixedCase()
                ->numbers()
                ->symbols(), 
            'confirmed'],
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ],['g-recaptcha-response.required' => 'Se requiere el recaptcha de Google']);

        // $domain = sprintf('%s.%s', $data['domain'], env('APP_DOMAIN'));

        // $data["domain"] = $domain;

        // return Validator::make($data, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],

        //     'password' => ['required', 'string', 'min:6', 'confirmed'],
        //     ],  [
        //             'domain.unique' => 'El valor del campo host ya está en uso.', 
        //             'domain.max' => 'El valor del campo host no debe contener más de 15 caracteres.', 
        //         ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // Valida si el tipo de persona es natural = 1, de lo contrario es persona jurídica = 2
        $data = AntiXSSController::xssClean($data,["document_number","first_name","second_name","first_surname","second_surname","email","address","phone"]);

        if($data['type_person'] == 1) {
            $data['name'] = $data['first_name'];
            
            // Valida si existe un segundo nombre
            if (!empty($data['second_name'])) {
                $data['name'] .= " ".$data['second_name'];
            }

            $data['name'] .=  " ".$data['first_surname'];
            
            // Valida si existe un segundo apellido
            if (!empty($data['second_surname'])) {
                $data['name'] .= " ".$data['second_surname'];
            }
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'accept_service_terms' => 1,
            'user_type' => 'Ciudadano'
        ]);

        // Asigna rol a usuario
        $user->assignRole('Ciudadano');
        // Remueve el rol registered asignado por defecto al usuario
        // $user->removeRole('Registered');
        
        Citizen::create([
            'type_person' => $data['type_person'],
            'type_document' => $data['type_document'],
            'document_number' => $data['document_number'],
            'name' => $data['name'],
            'first_name' => $data['first_name'] ?? null,
            'second_name' => $data['second_name'] ?? null,
            'first_surname' => $data['first_surname'] ?? null,
            'second_surname' => $data['second_surname'] ?? null,
            'address' => $data['address'],
            'phone' => $data['phone'],
            'states_id' => $data['states_id'],
            'city_id' => $data['city_id'],
            'user_id' => $user->id,
            'password' => Hash::make($data['password']),
            'solicitudes_via_email' => $data['solicitudes_via_email'] ?? null
        ]);
        
        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());
        
        try{
            // Registro de evento de registro de usuario
            event(new Registered($user));
        }
        catch (\Exception $error) {
            // Envia mensaje de error
            AppBaseController::generateSevenLog('userControllerEmail', 'App\Http\Controllers\ControllerRegisterController - '. ($user ? $user->name : 'System').' -  Error: '.$error->getMessage());
        }

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }
        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect(Auth::user()->hasRole('Ciudadano') ? "/dashboard" : $this->redirectPath());
    }
}
