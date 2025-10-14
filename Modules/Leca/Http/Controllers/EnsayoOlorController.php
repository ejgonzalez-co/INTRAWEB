<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\BlancoGeneral;
use Modules\Leca\Models\Blancos;
use Modules\Leca\Models\DprprOlor;
use Modules\Leca\Models\Ensayo;
use Modules\Leca\Models\EnsayoOlor;
use Modules\Leca\Models\HistorySampleTaking;
use Modules\Leca\Models\ListTrials;
use Modules\Leca\Models\Olor;
use Modules\Leca\Models\Patron;
use Modules\Leca\Models\PatronGeneral;
use Modules\Leca\Models\CriticalEquipment;
use Modules\Leca\Models\SampleTaking;
use Modules\Leca\Models\TendidoMuestras;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class EnsayoOlorController extends AppBaseController
{

    /**
     * Muestra la vista para el CRUD de SampleTaking.
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 07 - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index()
    {

        return view('leca::EnsayoOlor.index');

    }

    public function getDatosBlanco()
    {

        $datosBlanco = Olor::orderBy('id', 'desc')->first();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    public function getCartaDatosBlanco()
    {

        $datosBlanco = Blancos::where('lc_list_trials_id', 33)->get()->last();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    public function getCartaDatosPatron()
    {

        $datosBlanco = Patron::where('lc_list_trials_id', 33)->get()->last();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param EnsayoOlor $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request['tipo'] == 'Blanco') {

            $datosBlanco = Blancos::where('lc_list_trials_id', 33)->get()->last();

            $result1 = floatval($datosBlanco->lcm);
            $result2 = floatval($request['conductividad']);

            // if ($result1 > $result2) {

                $user = Auth::user();
                $cantidadBlancos = EnsayoOlor::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Blanco')->count();
                $ensayo = new EnsayoOlor();
                $ensayo->estado = 'Si';
                $ensayo->temperatura_con = $request['temperatura_con'];
                $ensayo->aprobacion_usuario = 'Pendiente';
                $ensayo->olor = $request['olor'];

                $ensayo->id_muestra = '';
                if ($cantidadBlancos == 0) {
                    $ensayo->consecutivo = 'BK';
                } else {
                    $ensayo->consecutivo = 'BK' . $cantidadBlancos;
                }
                $ensayo->users_id = Auth::user()->id;
                $ensayo->user_name = Auth::user()->name;
                $ensayo->tipo = $request['tipo'];
                $ensayo->save();

                return $this->sendResponse($ensayo->toArray(), trans('data_obtained_successfully'));

            // } else {

                // $user = Auth::user();
                // $cantidadBlancos = EnsayoOlor::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Blanco')->count();
                // $ensayo = new EnsayoOlor();
                // $ensayo->estado = 'No';
                // $ensayo->temperatura_con = $request['temperatura_con'];
                // $ensayo->olor = $request['olor'];
                // $ensayo->id_muestra = '';
                // if ($cantidadBlancos == 0) {
                //     $ensayo->consecutivo = 'BK';
                // } else {
                //     $ensayo->consecutivo = 'BK' . $cantidadBlancos;
                // }
                // $ensayo->users_id = Auth::user()->id;
                // $ensayo->user_name = Auth::user()->name;
                // $ensayo->tipo = $request['tipo'];

                // $ensayo->save();

                // return $this->sendResponse($ensayo->toArray(), trans('data_obtained_successfully'));

            // }
        } else {

            if ($request['tipo'] == 'Patrón') {

                $datosPatron = Patron::where('lc_list_trials_id', 33)->get()->last();
                $result1 = floatval($datosPatron->lcs);
                $result2 = floatval($datosPatron->lci);
                $datosPrim = Olor::where('lc_list_trials_id', 33)->get()->last();
                $result3 = floatval($request['olor'])/$datosPrim->valor_esperador_tres*100;

                // if ($result3 > $result2 && $result3 < $result1) {

                    $user = Auth::user();
                    $cantidadPatrones = EnsayoOlor::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();
                    $ensayo = new EnsayoOlor();
                    $ensayo->estado = 'Si';
                    $ensayo->temperatura_con = $request['temperatura_con'];
                    $ensayo->aprobacion_usuario = 'Pendiente';
                    $ensayo->olor = $request['olor'];
                    $ensayo->id_muestra = '';
                    if ($cantidadPatrones == 0) {
                        if ($request['cons_concentracion']) {
                            $ensayo->consecutivo = 'STD' . ' - ' . $request['cons_concentracion'];
                        } else {
                            $ensayo->consecutivo = 'STD';
                        }

                    } else {
                        if ($request['cons_concentracion']) {
                            $ensayo->consecutivo = 'STD' . $cantidadPatrones . ' - ' . $request['cons_concentracion'];
                        } else {
                            $ensayo->consecutivo = 'STD' . $cantidadPatrones;
                        }
                    }
                    $ensayo->users_id = Auth::user()->id;
                    $ensayo->user_name = Auth::user()->name;
                    $ensayo->tipo = $request['tipo'];

                    $ensayo->save();

                    return $this->sendResponse($ensayo->toArray(), trans('data_obtained_successfully'));

                // } else {

                    // $user = Auth::user();
                    // $cantidadPatrones = EnsayoOlor::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();
                    // $ensayo = new EnsayoOlor();
                    // $ensayo->estado = 'No';
                    // $ensayo->temperatura_con = $request['temperatura_con'];
                    // $ensayo->olor = $request['olor'];
                    // $ensayo->id_muestra = '';
                    // if ($cantidadPatrones == 0) {
                    //     if ($request['cons_concentracion']) {
                    //         $ensayo->consecutivo = 'STD' . ' - ' . $request['cons_concentracion'];
                    //     } else {
                    //         $ensayo->consecutivo = 'STD';
                    //     }
                    // } else {
                    //     if ($request['cons_concentracion']) {
                    //         $ensayo->consecutivo = 'STD' . $cantidadPatrones . ' - ' . $request['cons_concentracion'];
                    //     } else {
                    //         $ensayo->consecutivo = 'STD' . $cantidadPatrones;
                    //     }
                    // }
                    // $ensayo->users_id = Auth::user()->id;
                    // $ensayo->user_name = Auth::user()->name;
                    // $ensayo->tipo = $request['tipo'];

                    // $ensayo->save();

                    // return $this->sendResponse($ensayo->toArray(), trans('data_obtained_successfully'));

                // }
            } else {

                if ($request['tipo'] == 'Ensayo') {
                    $user = Auth::user();

                    $blanco = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                    if ($blanco) {

                        $patron = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

                        if ($patron) {

                            $arrayTotal['ensayo'] = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

                            $blancoGeneral = BlancoGeneral::get()->last();
                            $patronGeneral = PatronGeneral::get()->last();

                            if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                                $centinela = false;
                                $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->where('lc_list_trials_id', "!=", 33)->get();
                                foreach ($verifica as $key => $value) {
                                    if ($value->estado == 'Ejecutado') {
                                        $centinela = true;
                                    } else {
                                        $centinela = false;
                                        break;
                                    }
                                }

                                $muestraActualizar = SampleTaking::where('id', $request['idMuestra']['id'])->get();

                                if ($centinela == true) {
                                    $history = new HistorySampleTaking();
                                    $history->action = "Análisis finalizado";
                                    $history->users_id = Auth::user()->id;
                                    $history->user_name = Auth::user()->name;
                                    $history->consecutivo = $muestraActualizar[0]->sample_reception_code;
                                    $history->observation = "Se realizo el analisis de la fórmula de Olor";

                                    $history->lc_sample_taking_id = $request['idMuestra']['id'];
                                    $history->save();

                                    $muestraActualizar[0]->estado_analisis = 'Análisis finalizado';
                                    $muestraActualizar[0]->save();
                                } else {
                                    $history = new HistorySampleTaking();
                                    $history->action = "Análisis en ejecución";
                                    $history->users_id = Auth::user()->id;
                                    $history->user_name = Auth::user()->name;
                                    $history->consecutivo = $muestraActualizar[0]->sample_reception_code;
                                    $history->observation = "Se realizo el analisis de la fórmula de Olor";

                                    $history->lc_sample_taking_id = $request['idMuestra']['id'];

                                    $history->save();
                                    $muestraActualizar[0]->estado_analisis = 'Análisis en ejecución';
                                    $muestraActualizar[0]->save();
                                }
                                $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->where('lc_list_trials_id', 33)->get();
                                $verifica[0]->estado = 'Ejecutado';
                                $verifica[0]->save();

                                $buscaEnsayo = Ensayo::where('lc_list_trials_id', 33)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();
                                $año = date('Y');
                                $array = str_split($año);

                                $ensayo = new EnsayoOlor();
                                $ensayo->estado = '';
                                $ensayo->temperatura_con = $request['temperatura_con'];
                                $ensayo->olor = $request['olor'];
                                $ensayo->resultado = $request['resultado'];
                                $ensayo->id_muestra = $request['idMuestra']['id'];
                                $ensayo->lc_sample_taking_has_lc_list_trials_id = $buscaEnsayo->id;
                                $ensayo->duplicado = $request['idMuestra']['duplicado'];
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code;

                                $ensayo->users_id = Auth::user()->id;
                                $ensayo->user_name = Auth::user()->name;

                                $ensayo->tipo = $request['tipo'];
                                $ensayo->save();

                                $muestras = TendidoMuestras::where('users_id', $user->id)->where('lc_sample_taking_id', $request['idMuestra']['id'])->where('estado', 'Activo')->first();

                                $muestras->estado = "Finalizado";
                                $muestras->fecha_finalizado = Carbon::now()->format('Y-m-d');

                                $muestras->save();

                                return $this->sendResponse('Si', trans('data_obtained_successfully'));

                            } else {

                                return $this->sendResponse('Error ensayo', trans('data_obtained_successfully'));

                            }

                        } else {

                            return $this->sendResponse('Error patron', trans('data_obtained_successfully'));

                        }
                    } else {
                        return $this->sendResponse('Error blanco', trans('data_obtained_successfully'));
                    }
                } else {

                    if ($request['tipo'] == 'Duplicado') {
                        $user = Auth::user();

                        // $blanco = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                        // if ($blanco) {

                        //     $patron = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

                        //     if ($patron) {

                        //         $arrayTotal['ensayo'] = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

                        //     $blancoGeneral=BlancoGeneral::get()->last();
                        //     $patronGeneral=PatronGeneral::get()->last();

                        // if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                        $buscaEnsayo = Ensayo::where('lc_list_trials_id', 33)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();

                        $año = date('Y');

                        $array = str_split($año);
                        $ensayo = new EnsayoOlor();
                        $ensayo->estado = '';
                        $ensayo->temperatura_con = $request['temperatura_con'];
                        $ensayo->olor = $request['olor'];
                        $ensayo->duplicado = $request['idMuestra']['duplicado'];
                        $ensayo->id_muestra = $request['idMuestra']['id_muestra'];
                        $ensayo->consecutivo_ensayo = $request['idMuestra']['consecutivo'];
                        $muestraActualizar = SampleTaking::where('id', $request['idMuestra']['id_muestra'])->get();
                        $ensayo->lc_sample_taking_has_lc_list_trials_id = $request['idMuestra']['id'];

                        if ($request['metodo'] == "Duplicado") {

                            $duplicado = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Duplicado')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();

                            if ($duplicado >= 1) {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D' . $duplicado;
                            } else {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D';
                            }
                            $ensayo->tipo = $request['tipo'];
                            $ensayo->metodo = "Duplicado";

                        } else {
                            if ($request['metodo'] == "Repetición") {
                                $duplicado = EnsayoOlor::where('users_id', $user->id)->where('metodo', 'Repetición')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();
                                if ($duplicado >= 1) {
                                    $duplicado = $duplicado + 1;
                                    $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-' . $duplicado;
                                } else {
                                    $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-' . 1;
                                }
                                $ensayo->tipo = "Ensayo - Repetición";
                                $ensayo->metodo = "Repetición";
                            } else {
                                if ($request['metodo'] == "Regla de decisión") {

                                    $duplicado = EnsayoOlor::where('users_id', $user->id)->where('metodo', 'Regla de decisión')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();

                                    if ($duplicado >= 1) {
                                        $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-R' . $duplicado;
                                    } else {
                                        $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-R';
                                    }
                                    $ensayo->tipo = "Ensayo - Regla de decisión";
                                    $ensayo->metodo = "Regla de decisión";

                                }
                            }
                        }

                        $ensayo->users_id = Auth::user()->id;
                        $ensayo->user_name = Auth::user()->name;

                        $ensayo->save();

                        if ($request['metodo'] == "Duplicado") {

                            // dd( $request['idMuestra']);
                            $dpr = new DprprOlor();
                            // $dpr->real = $request['real'];
                            $dpr->add1 = $request['resultado'];
                            $dpr->add2 = $request['idMuestra']['resultado'];
                            $dpr->tipo = 'Promedio';
                            $dpr->consecutivo = $request['idMuestra']['consecutivo'];
                            $dpr->resultado_completo = $request['resultado_completo'];
                            $dpr->user_name = $user->name;
                            $dpr->user_id = $user->id;
                            $dpr->id_ensayo = $ensayo->id;
                            $dpr->id_muestra = $request['idMuestra']['id_muestra'];
                            $dpr->resultado = ($request['idMuestra']['olor'] + $request['olor']) / 2;
                            $dpr->lc_ensayo_olor_id = $ensayo->id;
                            $dpr->save();

                        }

                        $history = new HistorySampleTaking();
                        $history->action = "Ejecuto duplicado";
                        $history->users_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->consecutivo = $ensayo->consecutivo;
                        $history->observation = "Se realizo duplicado a la muestra de Olor";
                        $history->lc_sample_taking_id = $request['idMuestra']['id'];
                        $history->save();

                        return $this->sendResponse('Si', trans('data_obtained_successfully'));

                        // } else {

                        //     return $this->sendResponse('Error ensayo', trans('data_obtained_successfully'));

                        // }

                        //     } else {

                        //         return $this->sendResponse('Error patron', trans('data_obtained_successfully'));

                        //     }
                        // } else {
                        //     return $this->sendResponse('Error blanco', trans('data_obtained_successfully'));
                        // }
                    }

                }

            }
        }

    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateListTrialsRequest $request
     *
     * @return Response
     */
    public function update($id, EnsayoOlor $request)
    {

    }

    /**
     * Elimina un ListTrials del almacenamiento
     *
     * @author Desarrollador Seven - 2022
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

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Desarrollador Seven - 2022
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
        $fileName = time() . '-' . trans('list_trials') . '.' . $fileType;

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
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz Peña. - Feb. 02 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAllOlor()
    {
        if (!Auth::user()->hasRole('Administrador Leca')) {
            $user = Auth::user();

            $allEnsayos = EnsayoOlor::where('users_id', $user->id)->latest()->get();
        } else {
            $allEnsayos = EnsayoOlor::latest()->get();

        }

        return $this->sendResponse($allEnsayos->toArray(), trans('data_obtained_successfully'));

    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz Peña. - Feb. 02 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCondicionEnsayos()
    {
        $user = Auth::user();
        $arrayTotal = null;

        $arrayTotal['ensayo'] = 0;
        $arrayTotal['pendienteb'] = true;
        $arrayTotal['pendientep'] = true;

        $blanco = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Blanco')->where('estado', 'Si')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where(function ($query) {
            $query->where('aprobacion_usuario', 'Pendiente')->orwhere('aprobacion_usuario', 'Si');
        })->get()->last();

        if ($blanco) {

            if ($blanco->aprobacion_usuario == 'Si') {

                $patron = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Patrón')->where('estado', 'Si')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where(function ($query) {
                    $query->where('aprobacion_usuario', 'Pendiente')->orwhere('aprobacion_usuario', 'Si');
                })->get()->last();
            } else {
                $patron = null;
            }

            if ($patron != null) {

                if ($patron->aprobacion_usuario == 'Si') {
                    $arrayTotal['ensayo'] = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();
                } else {
                    $arrayTotal['ensayo'] = null;
                }

                if ($arrayTotal['ensayo'] != null) {
                    $blancoGeneral = BlancoGeneral::get()->last();
                    $patronGeneral = PatronGeneral::get()->last();

                    if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad || $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                        if ($arrayTotal['ensayo'] > $blancoGeneral->periodicidad) {
                            $arrayTotal['blanco'] = false;
                        } else {
                            $arrayTotal['blanco'] = true;
                        }

                        if ($arrayTotal['ensayo'] > $patronGeneral->periodicidad) {
                            $arrayTotal['patron'] = false;
                        } else {
                            $arrayTotal['patron'] = true;
                        }

                        if ($patron->aprobacion_usuario == 'Pendiente') {
                            $arrayTotal['pendientep'] = true;
                            $arrayTotal['patron'] = false;
                        } else {
                            $arrayTotal['pendientep'] = false;
                        }

                        return $this->sendResponse($arrayTotal, trans('data_obtained_successfully'));

                    } else {

                        $arrayTotal['blanco'] = false;
                        $arrayTotal['patron'] = false;
                        $arrayTotal['ensayo'] = 0;

                        return $this->sendResponse($arrayTotal, trans('data_obtained_successfully'));

                    }
                } else {
                    if ($patron->aprobacion_usuario == 'Pendiente') {
                        $arrayTotal['pendientep'] = true;
                        $arrayTotal['patron'] = false;
                    } else {
                        $arrayTotal['pendientep'] = false;
                        if ($patron->estado == 'Si') {
                            $arrayTotal['patron'] = true;
                        }
                    }
                    $arrayTotal['pendienteb'] = false;
                    $arrayTotal['blanco'] = true;

                    return $this->sendResponse($arrayTotal, trans('data_obtained_successfully'));
                }

            } else {

                if ($blanco->aprobacion_usuario == 'Pendiente') {
                    $arrayTotal['pendienteb'] = true;
                    $arrayTotal['blanco'] = false;
                } else {
                    $arrayTotal['pendienteb'] = false;
                    if ($blanco->estado == 'Si') {
                        $arrayTotal['blanco'] = true;
                    }
                }
                $arrayTotal['pendientep'] = false;
                $arrayTotal['patron'] = false;

                return $this->sendResponse($arrayTotal, trans('data_obtained_successfully'));

            }

        } else {

            $arrayTotal['pendienteb'] = false;
            $arrayTotal['blanco'] = false;
            $arrayTotal['patron'] = false;

            return $this->sendResponse($arrayTotal, trans('data_obtained_successfully'));
        }
    }

    /**
     * Envia la muestra principal
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function estadoEnsayo(Request $request)
    {

        $ensayo = EnsayoOlor::find($request['id']);

        if ($request['esta'] == 'Si') {
            $ensayo->aprobacion_usuario = 'Si';
            $ensayo->observacion_analista = $request['observacion_analista'];
            $ensayo->save();
        } else {
            if ($request['esta'] == 'No') {
                $ensayo->aprobacion_usuario = 'No';
                $ensayo->observacion_analista = $request['observacion_analista'];
                $ensayo->save();
            }
        }

        return $this->sendResponse($ensayo, trans('data_obtained_successfully'));
    }

    /**
     * Muestra la vista para el CRUD de SampleTaking.
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 07 - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexEjecutar()
    {

        $user = Auth::user();
        $arrayTotal = null;

        $arrayTotal['ensayo'] = 0;

        $blanco = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

        if ($blanco) {

            $patron = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

            if ($patron) {

                $arrayTotal['ensayo'] = EnsayoOlor::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

                $blancoGeneral = BlancoGeneral::get()->last();
                $patronGeneral = PatronGeneral::get()->last();

                if ($arrayTotal['ensayo'] <= $blancoGeneral->periodicidad && $arrayTotal['ensayo'] <= $patronGeneral->periodicidad) {

                    if ($arrayTotal['ensayo'] > $blancoGeneral->periodicidad) {
                        $arrayTotal['blanco'] = false;
                    } else {
                        $arrayTotal['blanco'] = true;
                    }

                    if ($arrayTotal['ensayo'] > $patronGeneral->periodicidad) {
                        $arrayTotal['patron'] = false;
                    } else {
                        $arrayTotal['patron'] = true;
                    }

                    return view('leca::EnsayoOlor.ejecutar');

                } else {

                    $arrayTotal['blanco'] = false;
                    $arrayTotal['patron'] = false;
                    $arrayTotal['ensayo'] = 0;

                    return view('leca::EnsayoOlor.index');

                }

            } else {

                $arrayTotal['blanco'] = true;
                $arrayTotal['patron'] = false;

                return view('leca::EnsayoOlor.index');

            }

        } else {

            $arrayTotal['blanco'] = false;
            $arrayTotal['patron'] = false;

            return view('leca::EnsayoOlor.index');
        }

    }

    /**
     * Obtiene las muestras de la rutina
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function allMuestras()
    {
        $arrayTotal = [];
        $arraySegundo = [];
        $arrayTercero = [];
        $user = Auth::user();

        $ensayo = Ensayo::where('lc_list_trials_id', 33)->where('estado', 'Pendiente')->get();

        foreach ($ensayo as $keya => $item) {

            array_push($arrayTercero, $item->lc_sample_taking_id);

        }

        $sample_takings = SampleTaking::with(['lcListTrials'])->where('estado_analisis', '!=', '')->where('estado_analisis', '!=', 'Análisis finalizado')->whereIn('id', $arrayTercero)->get();

        for ($i = 0; $i < count($sample_takings); $i++) {
            foreach ($sample_takings[$i]->lcListTrials as $keya => $item) {
                if ($item->id == 1) {
                    array_push($arraySegundo, $sample_takings[$i]);
                }
            }
        }

        return $this->sendResponse($arraySegundo, trans('data_obtained_successfully'));

    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function storeTendido(Request $request)
    {

        $user = Auth::user();

        $muestras = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 33)->where('estado', 'Activo')->get();

        if (count($muestras) > 0) {

            foreach ($muestras as $key => $value) {

                $value->estado = "Inactivo";

                $value->save();

            }
        }

        foreach ($request->toArray() as $key => $value) {

            $tendido = new TendidoMuestras();
            $tendido->users_id = $user->id;
            $tendido->lc_list_trials_id = 33;
            $tendido->estado = "Activo";
            $tendido->lc_sample_taking_id = $value;
            $tendido->save();

        }

        return $this->sendResponse($tendido, trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function allTendido()
    {

        $user = Auth::user();
        $arrayTotal = [];
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 33)->where('estado', 'Activo')->get();

        foreach ($tendido as $key => $value) {
            # code...
            array_push($arrayTotal, $value->lc_sample_taking_id);
        }

        $muestras = SampleTaking::with(['lcListTrials'])->where('estado_analisis', '!=', '')->whereIn('id', $arrayTotal)->get();

        return $this->sendResponse($muestras, trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function allTendidoFinalizado()
    {

        $user = Auth::user();
        $arrayTotal = [];
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 33)->whereDate('fecha_finalizado', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Finalizado')->get();

        return $this->sendResponse($tendido, trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function consultaDecimalesBlanco()
    {

        $user = Auth::user();
        $blancoDecimales = Blancos::where('lc_list_trials_id', 33)->get()->last();
        return $this->sendResponse(intval($blancoDecimales->decimales), trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function consultaDecimalesPatron()
    {

        $user = Auth::user();
        $patronDecimales = Patron::where('lc_list_trials_id', 33)->get()->last();

        return $this->sendResponse(intval($patronDecimales->decimales), trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function consultaDecimalesEnsayo()
    {

        $user = Auth::user();
        $arrayTotal = [];
        $ensayoDecimales = Olor::where('lc_list_trials_id', 33)->get()->last();
        return $this->sendResponse(intval($ensayoDecimales->decimales), trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function storeDpr(Request $request)
    {
        $user = Auth::user();

        $dpr = new DprprOlor();
        $dpr->real = $request['real'];
        $dpr->add1 = $request['add1'];
        $dpr->add2 = $request['add2'];
        $dpr->tipo = $request['tipo'];
        $dpr->consecutivo = $request['consecutivo'];
        $dpr->user_name = $user->name;
        $dpr->user_id = $user->id;
        $dpr->volumen_adicionado = $request['volumenAdicionado'];
        $dpr->volumen_muestra = $request['volumenMuestra'];
        $dpr->concentracion = $request['concentracionSolucion'];
        $dpr->id_ensayo = $request['id_ensayo'];
        $dpr->lc_ensayo_olor_id = $request['id_ensayo'];
        $dpr->id_muestra = $request['id_muestra'];
        $dpr->resultado = $request['resultado'];
        $dpr->resultado_completo = $request['resultado_completo'];
        $dpr->save();
        return $this->sendResponse('si', trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function storeRelativa(Request $request)
    {
        $user = Auth::user();

        $dpr = new DprprOlor();
        // $dpr->real = $request['real'];
        $dpr->add1 = $request['add1'];
        $dpr->add2 = $request['add2'];
        $dpr->tipo = $request['tipo'];
        $dpr->consecutivo = $request['consecutivo'];
        $dpr->resultado_completo = $request['resultado_completo'];
        $dpr->user_name = $user->name;
        $dpr->user_id = $user->id;
        // $dpr->volumen_adicionado = $request['volumenAdicionado'];
        // $dpr->volumen_muestra = $request['volumenMuestra'];
        // $dpr->concentracion = $request['concentracionSolucion'];
        $dpr->id_ensayo = $request['id_ensayo'];
        $dpr->id_muestra = $request['id_muestra'];
        $dpr->resultado = $request['resultado'];
        $dpr->lc_ensayo_olor_id = $request['id_ensayo'];
        $dpr->save();
        return $this->sendResponse('si', trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDatosPromedio($id)
    {

        $dpr = DprprOlor::where('id_ensayo', $id)->where('tipo', 'Promedio')->get();

        if ($dpr) {
            return $this->sendResponse($dpr, trans('data_obtained_successfully'));

        } else {
            return $this->sendResponse('no', trans('data_obtained_successfully'));

        }

    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDatosRelativa($id)
    {

        $dpr = DprprOlor::where('id_ensayo', $id)->where('tipo', 'Diferencia porcentual relativa')->get();

        if ($dpr) {
            return $this->sendResponse($dpr, trans('data_obtained_successfully'));

        } else {
            return $this->sendResponse('no', trans('data_obtained_successfully'));

        }

    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAllShowEnsayo(Request $request)
    {
        $idEnsayo = $request['id_muestra'];

        $muestra = SampleTaking::with(['programacion', 'lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->where('id', $idEnsayo)->get()->last();

        $ensayo = EnsayoOlor::with(['lcDprprTurbidez', 'lcObservacionesDuplicadoTurbidez'])->where('id_muestra', $idEnsayo)->get();
        $ensayo->toArray();

        return $this->sendResponse($ensayo, trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAllShowPatronBlanco(Request $request)
    {
        $idEnsayo = $request['id_muestra'];

        $muestra = SampleTaking::with(['programacion', 'lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->where('id', $idEnsayo)->get()->last();

        $ensayo = [];

        $ensayoA = EnsayoOlor::where('id', $request['id'])->get()->last();

        $blanco = EnsayoOlor::whereDate('created_at', '>=', $ensayoA->created_at)->where('tipo', 'Blanco')->where('estado', 'Si')->get()->last();
        $patron = EnsayoOlor::whereDate('created_at', '>=', $ensayoA->created_at)->where('tipo', 'Patrón')->where('estado', 'Si')->get()->last();

        $ensayo['muestra'] = $muestra;
        $ensayo['blanco'] = $blanco;
        $ensayo['patron'] = $patron;

        return $this->sendResponse($ensayo, trans('data_obtained_successfully'));
    }

    /**
     * Exporta excel
     *
     * @author Manuel Marin. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function exporta(Request $request)
    {
        $input = $request['data'];

        $dateToday = date('m-d-Y h:i:s');

        $input = array_reverse($input);
        $inputFileType = 'Xlsx';
        $inputFileName = storage_path('app/public/leca/excel/Olor.xlsx');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);

        $date = strtotime($input[0]['created_at']);

        $datosPrimarios = Olor::whereDate('created_at', '<=', $input[0]['created_at'])->get()->last();

        $anio = date("y", $date);
        $mes = date("m", $date);

        $spreadsheet->getActiveSheet()->setCellValue('E9', 'Laboratorio de Ensayo de Calidad de Agua' ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('O10', $anio ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('Q10', $mes ?? 'N/A');
        
        $spreadsheet->getActiveSheet()->setCellValue('I18', $datosPrimarios->fecha_ajuste_curva_uno ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('I19', $datosPrimarios->documento_referencia_uno ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('I20', $datosPrimarios->nombre_patron_uno ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('I21', $datosPrimarios->valor_esperador_uno ?? 'N/A');


        $criticalEquip = CriticalEquipment::where('lc_list_trials_id', 33)->latest()->take(1)->get();

        $letra = "E";
        for ($i = 0; $i < count($criticalEquip); $i++) {

            $spreadsheet->getActiveSheet()->setCellValue($letra . '16', $criticalEquip[$i]->equipo_critico ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue($letra . '17', $criticalEquip[$i]->identificacion ?? 'N/A');
            $letra++;
            $letra++;
        }
        $cont = 0;
        $numTotal = 28 + count($input);
        $totalCeldas = count($input);
        if ($totalCeldas == 1) {

        } else {

            $spreadsheet->getActiveSheet()->insertNewRowBefore(29, $totalCeldas);

        }

        $arraydpr = array();
        for ($i = 28; $i < $numTotal; $i++) {

            $spreadsheet->getActiveSheet()->mergeCells('C' . $i . ':D' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('E' . $i . ':F' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('G' . $i . ':H' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('I' . $i . ':J' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('K' . $i . ':M' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('N' . $i . ':O' . $i);

            $date = strtotime($input[$cont]['created_at']);

            $dia = date("d", $date);
            $hora = date("H", $date);
            $minutos = date("i", $date);
            $segundos = date("s", $date);

            $user = User::find($input[$cont]['users_id']);

            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, $dia ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('B' . $i, $hora . ':' . $minutos . ':' . $segundos ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('C' . $i, $input[$cont]['consecutivo'] ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('P' . $i, $input[$cont]['temperatura_con'] ?? 'N/A');

            if($input[$cont]['olor'] == '0'){
                $spreadsheet->getActiveSheet()->setCellValue('N' . $i, 'X');
            } else if($input[$cont]['olor'] == '2'){
                $spreadsheet->getActiveSheet()->setCellValue('E' . $i, 'X');
            } else if($input[$cont]['olor'] == '4'){
                $spreadsheet->getActiveSheet()->setCellValue('G' . $i, 'X');
            } else if($input[$cont]['olor'] == '8'){
                $spreadsheet->getActiveSheet()->setCellValue('I' . $i, 'X');
            } else if($input[$cont]['olor'] == '12'){
                $spreadsheet->getActiveSheet()->setCellValue('K' . $i, 'X');
            }


            if ($input[$cont]['tipo'] == 'Duplicado') {

                $dprpr = DprprOlor::where('lc_ensayo_olor_id', $input[$cont]['id'])->get();

                if (count($dprpr) > 0) {

                    foreach ($dprpr as $key => $value) {
                        # code...
                        array_push($arraydpr, $value);

                    }

                }

            }

            if ($user->url_digital_signature) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setPath(storage_path('app/public' . '/' . $user->url_digital_signature)); /* put your path and image here */
                $drawing->setCoordinates('Q' . $i);
                $drawing->setWorksheet($spreadsheet->getActiveSheet());
                $drawing->setHeight(25);
                $drawing->setResizeProportional(true);
                $drawing->setOffsetX(1); // this is how
                $drawing->setOffsetY(2);
            }

            // $spreadsheet->getActiveSheet()->addImage('L'.$i, asset('storage').'/'. $user->url_digital_signature);
            $spreadsheet->getActiveSheet()->setCellValue('R' . $i, $user->name);
            $cont++;
        }

        if (count($arraydpr) > 0) {

            $contPorcentaje = 0;
            $contDpr = 0;

            $arraydpr = array_reverse($arraydpr);
            $letraO = "O";
            $letraK = "K";
            $letraP = "P";
            for ($u = 0; $u < count($arraydpr); $u++) {
                $var1 = explode(":", $arraydpr[$u]->created_at);
                $hora = $var1[2].":".$var1[1];

                if ($arraydpr[$u]->tipo == 'Diferencia porcentual relativa' && $contDpr < 2) {
                    $spreadsheet->getActiveSheet()->setCellValue('E23', $arraydpr[$u]->consecutivo ?? 'N/A');
                    $spreadsheet->getActiveSheet()->setCellValue($letraK . '24', $arraydpr[$u]->resultado . '%' ?? 'N/A');
                    $spreadsheet->getActiveSheet()->setCellValue($letraK . '23', ($arraydpr[$u]->add1 + $arraydpr[$u]->add2)/2 ?? 'N/A');
                    $spreadsheet->getActiveSheet()->setCellValue($letraO . '24', $dia ?? 'N/A');
                    $spreadsheet->getActiveSheet()->setCellValue($letraP . '24', $hora ?? 'N/A');
                }

            }

        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte Excel.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

        return $this->sendResponse($writer, trans('data_obtained_successfully'));

    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz Peña. - Feb. 02 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUltimoBlanco()
    {

        $ensayoA = EnsayoOlor::where('tipo', 'Blanco')->get()->last();

        return $this->sendResponse($ensayoA, trans('data_obtained_successfully'));

    }

    public function getDatoPrimario()
    {

        $datosBlanco = Olor::orderBy('id', 'desc')->first();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Envia la muestra principal
     *
     * @author Manuel Marin. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getEnsayoPrincipal($id)
    {   

        $dprs = $id - 1;
        $contador = 0;

        $ensayo = EnsayoOlor::where('id', $dprs)->first();
        if($ensayo->tipo != "Duplicado" && $ensayo->tipo != "Ensayo"){
            $ensayo = EnsayoOlor::where('tipo', "Duplicado")->latest()->get();
            foreach($ensayo as $ensa){
                if($ensa->id == $id){
                    $contador ++;
                } else if($contador == 1){
                    $ensayo = $ensa;
                    $contador ++;
                }
            }
        }

        return $this->sendResponse( $ensayo, trans('data_obtained_successfully'));

    }
}