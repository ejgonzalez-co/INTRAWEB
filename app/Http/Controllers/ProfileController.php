<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AppBaseController;
use Modules\Intranet\Repositories\UserHistoryRepository;
use Modules\Intranet\Repositories\UserRepository;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Validation\Rules\Password;
use Modules\Intranet\Http\Controllers\UtilController;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ProfileController extends AppBaseController {

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Jul. 27 - 2020
     * @version 1.0.0
     */
    public function __construct(
        UserHistoryRepository $userHistoryRepo,
        UserRepository $userRepo
    ) {
        $this->userHistoryRepository = $userHistoryRepo;
        $this->userRepository = $userRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile.index')
            ->with('sidebarHide', true)
            ->with('contentCssClass', 'hljs-wrapper');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Jun. 20 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAuthUser() {

        // Obtiene usuario logueado
        $user = Auth::user();
        // Cargo
        $user->positions;
        // Dependencia
        $user->dependencies;
        // Roles
        $user->roles;
        // Grupos de trabajo
        $user->workGroups;
        // Path de imagen de perfil
        $user->profile_img_preview = asset('storage').'/'.$user->url_img_profile;

        return $this->sendResponse(json_decode($user), trans('data_obtained_successfully'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request, $id) {
        $input = $request->except(['created_at', 'updated_at', '_method', 'id']);
        // Se eliminan los sgntes del array input, ya que genera error a la hora de guardarlos en la BD en formato (0000-00-00)
        unset($input["contract_start"]);
        unset($input["contract_end"]);
        /** @var User $user */
        $oldUser = $this->userRepository->find($id);

        // Valida si existe el usuario
        if (empty($oldUser)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        /*
            // Obtiene la imagen de perfil que se va a reemplazar
            $oldProfileImg = $oldUser->url_img_profile;
            // Elimina imagen de perfil anterior
            Storage::delete('/public' . $oldUser->url_img_profile);
        */
        // Valida si se debe actualizar la contraseña
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
        } else {
            // Si no va a actualizar la contraseña, se elimina el campo password del array de campos a actualizar
            unset($input['password']);
        }
        // Valida si se debe actualizar la segunda contraseña (esto para la publicación de documentos)
        if (!empty($input['second_password'])) {
            $request->validate([
                'second_password' => 'required|confirmed|min:6'
            ]);
            // Encripta la segunda contraseña
            $input['second_password'] = Hash::make($input['second_password']);
        }

        // Inicia la transaccion
        DB::beginTransaction();

        try {
            // Organiza campos booleanos
            $input['sendEmail'] = $this->toBoolean($input['sendEmail']);
            $input['block'] = $this->toBoolean($input['block']);
            // Valida si habilitó la segunda contraseña
            if(isset($input['enable_second_password']))
                // Habilitar la segunda contraseña para la publicación de documentos
                $input['enable_second_password'] = $this->toBoolean($input['enable_second_password']);
            
            // Valida si se ingresa la imagen de perfil
            if ($request->hasFile('url_img_profile')) {
                $input['url_img_profile'] = substr($input['url_img_profile']->store('public/users/avatar'), 7);
            }

            // Valida si se ingresa la imagen de perfil
            if ($request->hasFile('url_digital_signature')) {
                $input['url_digital_signature'] = substr($input['url_digital_signature']->store('public/users/signature'), 7);
            }

            // Actualiza datos de usuario
            $user = $this->userRepository->update($input, $id);

            // Prepara los datos de usuario para comparar
            $arrDataUser = $user->replicate()->toArray(); // Datos actuales de usuario
            $arrDataOldUser = $oldUser->replicate()->toArray(); // Datos antiguos de usuario

            // Datos diferenciados
            $arrDiff = $this-> array_diff_recursive($arrDataUser, $arrDataOldUser);
            // Valida si los datos antiguos son diferentes a los actuales
            if ( count($arrDiff) > 0) {
                // Asigna el id del usuario para el registro del historial
                $input['users_id'] = $user->id;
                // Agrega el id del usuario que crea el registro
                $input['cf_user_id'] = Auth::id();

                $input['roles'] = null;
                $input['work_groups'] = null;
                // Crea un nuevo registro de historial
                // dd($input);
                $this->userHistoryRepository->create($input);
            }

            /** Obtiene relaciones de usuario */
            // Obtiene el historial de cambios del usuario
            $user->usersHistory;
            // Obtiene cargo
            $user->positions;
            // Obtiene dependencia
            $user->dependencies;
            // Obtiene roles
            $user->roles;
            // Obtiene grupos de trabajo
            $user->workGroups;
            // Path de imagen de perfil
            $user->profile_img_preview = asset('storage').'/'.$user->url_img_profile;

        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Envia mensaje de error
            return $this->sendError(trans('msg_error_update'));
        }
        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($user->toArray(), trans('msg_success_update'));
    }

    public function array_diff_recursive($array1, $array2) {
        $result = [];
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key]) || !is_array($array2[$key])) {
                    $result[$key] = $value;
                } else {
                    $recursiveDiff = $this->array_diff_recursive($value, $array2[$key]);
                    if (!empty($recursiveDiff)) {
                        $result[$key] = $recursiveDiff;
                    }
                }
            } else {
                if (!isset($array2[$key]) || $array2[$key] !== $value) {
                    $result[$key] = $value;
                }
            }
        }
      
        return $result;
    }

    /**
     * Sube una firma al sistema.
     * - Valida que sea imagen.
     * - Elimina la anterior si existe.
     * - Guarda la nueva en el disco y actualiza al usuario.
     */
    public function subirFirma(Request $request)
    {
        $request->validate([
            'firma' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $user = Auth::user();

        // Eliminar firma anterior si existe
        if ($user->url_digital_signature) {
            Storage::disk('public')->delete($user->url_digital_signature);
        }

        // Guardar nueva firma
        $path = $request->file('firma')->store('users/signature', 'public');

        // Actualizar al usuario
        $user->url_digital_signature = $path;
        $user->save();

        return response()->json([
            'message' => 'Firma subida correctamente.',
            'path' => $path
        ]);
    }

    /**
     * Genera una imagen con el nombre del usuario encima de su firma actual.
     * - Reemplaza la firma por una nueva imagen con texto.
     * - Borra el archivo anterior.
     * - Actualiza el campo `url_digital_signature`.
     */
    public function generarImagenConTexto()
    {
        $user = Auth::user();
        $firmaAnterior = $user->url_digital_signature;

        // Validar existencia
        if (!$firmaAnterior) {
            return response()->json(['error' => 'No hay firma cargada.'], 422);
        }

        $rutaOriginal = storage_path('app/public/' . $firmaAnterior);
        if (!file_exists($rutaOriginal)) {
            return response()->json(['error' => 'La firma no fue encontrada en el sistema.'], 422);
        }

        // Crear nuevo nombre y ruta
        $nuevoNombre = 'firma_' . $user->id . '_' . now()->timestamp . '.png';
        $rutaRelativa = 'users/signature/' . $nuevoNombre;
        $rutaDestino = storage_path('app/public/' . $rutaRelativa);

        // Procesar imagen y agregar texto
        $this->procesarFirmaConTexto($rutaOriginal, $rutaDestino, $user->name);

        // Borrar la imagen anterior
        if ($firmaAnterior !== $rutaRelativa) {
            Storage::disk('public')->delete($firmaAnterior);
        }

        // Actualizar al usuario
        $user->url_digital_signature = $rutaRelativa;
        $user->save();

        return response()->json([
            'message' => 'Firma generada con éxito.',
            'path' => $rutaRelativa
        ]);
    }

    /**
     * Genera una imagen con texto centrado sin modificar la firma actual.
     * - Utilizada para descarga sin actualizar el usuario.
     */
    public function generarImagenConTextoGeneral()
    {
        $user = Auth::user();
        $rutaFirma = storage_path('app/public/' . $user->url_digital_signature);

        if (!file_exists($rutaFirma)) {
            abort(404, 'Firma digital no encontrada.');
        }

        $rutaTemporal = public_path('resultado.jpg');

        $this->procesarFirmaConTexto($rutaFirma, $rutaTemporal, $user->name);

        return response()->download($rutaTemporal);
    }

    /**
     * Procesa una imagen colocando texto centrado encima.
     *
     * @param string $rutaEntrada   Ruta absoluta de la imagen base
     * @param string $rutaSalida    Ruta absoluta donde se guarda la nueva imagen
     * @param string $texto         Texto a superponer
     */
    private function procesarFirmaConTexto(string $rutaEntrada, string $rutaSalida, string $texto): void
    {
        $img = Image::make($rutaEntrada);
        $ancho = $img->width();
        $alto = $img->height();

        // Calcular tamaño de fuente dinámico
        $fontSize = max(12, min(($ancho - 20) / (strlen($texto) * 0.6), 100));

        $img->text($texto, $ancho / 2, $alto / 2, function ($font) use ($fontSize) {
            $font->file(public_path('webfonts/OpenSans-Italic.ttf'));
            $font->size($fontSize);
            $font->color('#222222');
            $font->align('center');
            $font->valign('center');
        });

        $img->save($rutaSalida);
    }

    /**
     * Se asegura que cualquier cambio en la información personal del usuario esté protegido mediante la verificación de contraseña.
     *
     */
    public function validarCambioInformacion(Request $request)
    {
        $user = Auth::user();
        $enableSecond = $request->boolean('enable_second_password');

        // Si NO viene enableSecond pero el usuario SÍ tiene segunda contraseña
        if (empty($enableSecond) && !empty($user->enable_second_password)) {
            // Primero validamos contraseña principal
            $request->validate([
                'principal_pass' => 'required|string',
            ]);

            if (!Hash::check($request->input('principal_pass'), $user->password)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'La contraseña ingresada es incorrecta'
                ], 403);
            }

            // Si la principal es correcta → pedir segunda
            return response()->json([
                'require_second_password' => true,
                'message' => 'Debes ingresar tu segunda contraseña para continuar.'
            ], 409);
        }

        // Validación de segunda contraseña
        if ($enableSecond) {
            if (empty($user->enable_second_password)) {
                $isValid = $request->input('second_password') === $request->input('second_password_confirmation')
                    && $request->input('second_password') === $request->input('second_pass');
            } else {
                $request->validate([
                    'second_pass' => 'required|string',
                ]);
                $isValid = Hash::check($request->input('second_pass'), $user->second_password);
            }
        } else {
            // Validación solo de principal (si no tiene segunda contraseña)
            $request->validate([
                'principal_pass' => 'required|string',
            ]);
            $isValid = Hash::check($request->input('principal_pass'), $user->password);
        }

        return $isValid
            ? response()->json(['valid' => true], 200)
            : response()->json([
                'valid' => false,
                'message' => 'La contraseña ingresada es incorrecta'
            ], 403);
    }


}
