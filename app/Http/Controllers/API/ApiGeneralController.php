<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Modules\HelpTable\Models\TicRequest;
use Illuminate\Http\Request;
use Auth;
use DB;

class ApiGeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exporDataHelpTablePowerbi($encodedParam = null)
    {
        // Autenticación usando Basic Auth
        if (Auth::attempt(['email' => request()->getUser(), 'password' => request()->getPassword()])) {

            // Aquí puedes procesar la consulta según el parámetro decodificado (si existe)
            if ($encodedParam == null) {

                return response()->json(TicRequest::join('users', 'users.id', '=', 'ht_tic_requests.users_id')
                ->join('dependencias', 'dependencias.id', '=', 'users.id_dependencia')
                ->join('ht_tic_type_request', 'ht_tic_type_request.id', '=', 'ht_tic_requests.ht_tic_type_request_id') // Asegúrate de que esta relación es correcta
                ->select([
                    'ht_tic_requests.id as Numero',
                    'ht_tic_requests.created_at as Fecha de creación',
                    'dependencias.nombre as Dependencia',
                    'ht_tic_requests.users_name as Usuario' ,
                    'ht_tic_type_request.name as Tipo de solicitud',
                    DB::raw("(CASE ht_tic_requests.priority_request 
                        WHEN 1 THEN 'Baja' 
                        WHEN 2 THEN 'Media' 
                        WHEN 3 THEN 'Alta' 
                        ELSE 'N/A' 
                    END) as 'Prioridad de la solicitud'"),
                    'ht_tic_requests.affair as Asunto',
                    DB::raw("CONCAT(ht_tic_type_request.term, ' ', 
                    CASE ht_tic_type_request.unit_time 
                        WHEN 1 THEN 'horas' 
                        WHEN 2 THEN 'días' 
                        ELSE 'unidad desconocida' 
                    END) as 'Tiempo asignado'"),
                    DB::raw("(CASE ht_tic_requests.support_type 
                        WHEN 1 THEN 'Interno' 
                        WHEN 2 THEN 'Externo' 
                        ELSE 'N/A' 
                    END) as 'Tipo de soporte'"),
                    'ht_tic_requests.assigned_user_name as Usuario asignado',
                    'ht_tic_requests.expiration_date as Fecha de vencimiento',
                    'ht_tic_requests.date_attention as Fecha de atención',
                    'ht_tic_requests.request_status as Estado',
                ])
                ->when(Auth::user()->id, function ($query) {
                    // Valida si el usuario logueado es un tecnico tic o un proveedor
                    if (Auth::user()->hasRole('Proveedor TIC')) {
                        $query->where('assigned_user_id', Auth::user()->id);
                    }
                    // Valida si el usuario logueado usuario normal tic
                    else if (Auth::user()->hasRole('Usuario TIC')) {
                        $query->where('users_id', Auth::user()->id);
                    }
                    else if (Auth::user()->hasRole('Soporte TIC')) {

                        $query->where(function($subQuery) {
                            $subQuery->where('assigned_user_id', Auth::user()->id)
                                        ->orWhere('user_created_id', Auth::user()->id);
                        });

                    }
                    return $query;
                })->get()->makeHidden(['priority_request_name', 'support_type_name', 'id_encrypted']));

            }else{
                $decodedParam = str_replace(" ", "+", $encodedParam);
                // Si el parámetro está presente, intentamos decodificarlo
                $decodedParam = $encodedParam ? urldecode(base64_decode($encodedParam)) : null;

                // Verificar si la decodificación fue exitosa (en caso de que haya parámetro)
                if ($encodedParam && !$decodedParam) {
                    return response()->json(['error' => 'Parámetro inválido'], 400);
                }

                return response()->json(
                    TicRequest::join('users', 'users.id', '=', 'ht_tic_requests.users_id')
                        ->join('dependencias', 'dependencias.id', '=', 'users.id_dependencia')
                        ->join('ht_tic_type_request', 'ht_tic_type_request.id', '=', 'ht_tic_requests.ht_tic_type_request_id') // Asegúrate de que esta relación es correcta
                        ->select([
                            'ht_tic_requests.id as Numero',
                            'ht_tic_requests.created_at as Fecha de creación',
                            'dependencias.nombre as Dependencia',
                            'ht_tic_requests.users_name as Usuario' ,
                            'ht_tic_type_request.name as Tipo de solicitud',
                            DB::raw("(CASE ht_tic_requests.priority_request 
                                WHEN 1 THEN 'Baja' 
                                WHEN 2 THEN 'Media' 
                                WHEN 3 THEN 'Alta' 
                                ELSE 'N/A' 
                            END) as 'Prioridad de la solicitud'"),
                            'ht_tic_requests.affair as Asunto',
                            DB::raw("CONCAT(ht_tic_type_request.term, ' ', 
                            CASE ht_tic_type_request.unit_time 
                                WHEN 1 THEN 'horas' 
                                WHEN 2 THEN 'días' 
                                ELSE 'unidad desconocida' 
                            END) as 'Tiempo asignado'"),
                            DB::raw("(CASE ht_tic_requests.support_type 
                                WHEN 1 THEN 'Interno' 
                                WHEN 2 THEN 'Externo' 
                                ELSE 'N/A' 
                            END) as 'Tipo de soporte'"),
                            'ht_tic_requests.assigned_user_name as Usuario asignado',
                            'ht_tic_requests.expiration_date as Fecha de vencimiento',
                            'ht_tic_requests.date_attention as Fecha de atención',
                            'ht_tic_requests.request_status as Estado',
                        ])
                        ->when(Auth::user()->id, function ($query) {
                            if (Auth::user()->hasRole('Proveedor TIC')) {
                                $query->where('assigned_user_id', Auth::user()->id);
                            } else if (Auth::user()->hasRole('Usuario TIC')) {
                                $query->where('users_id', Auth::user()->id);
                            } else if (Auth::user()->hasRole('Soporte TIC')) {
                                $query->where(function($subQuery) {
                                    $subQuery->where('assigned_user_id', Auth::user()->id)
                                             ->orWhere('user_created_id', Auth::user()->id);
                                });
                            }
                            return $query;
                        })
                        ->whereRaw($decodedParam)
                        ->get()->makeHidden(['priority_request_name', 'support_type_name', 'id_encrypted']));
                
            }

        }

        // Si la autenticación falla, retorna un error 401
        return response()->json(['error' => 'No autorizado'], 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
