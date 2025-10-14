<?php

namespace Modules\Leca\Http\Controllers;

use Auth;
use App\User;
use Response;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Exports\GenericExport;
use Illuminate\Support\Carbon;
use Modules\Leca\Models\Ensayo;
use Modules\Leca\Models\Patron;
use Modules\Leca\Models\Blancos;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Leca\Models\ListTrials;
use Modules\Leca\Models\Alcalinidad;
use Modules\Leca\Models\SampleTaking;
use Modules\Leca\Models\BlancoGeneral;
use Modules\Leca\Models\PatronGeneral;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\TendidoMuestras;
use Modules\Leca\Models\DprprAlcalinidad;
use Modules\Leca\Models\CriticalEquipment;
use Modules\Leca\Models\EnsayoAlcalinidad;
use Modules\Leca\Models\HistorySampleTaking;

use App\Http\Controllers\GoogleController;

class EnsayoAlcalinidadController extends AppBaseController
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

        return view('leca::EnsayoAlcalinidad.index');

    }

    public function getDatosBlanco()
    {

        $datosBlanco = Alcalinidad::orderBy('id', 'desc')->first();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    public function getCartaDatosBlanco()
    {

        $datosBlanco = Blancos::where('lc_list_trials_id', 7)->get()->last();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    public function getCartaDatosPatron()
    {

        $datosBlanco = Patron::where('lc_list_trials_id', 7)->get()->last();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param EnsayoAlcalinidad $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
    
        $user = Auth::user();
        $Year = date("Y");
        $alcalinidadGeneral = Alcalinidad::orderBy('id', 'desc')->first();

        if ($request['tipo'] == 'Blanco') {

            $datosBlanco = Blancos::where('lc_list_trials_id', 7)->get()->last();
            $result1 = floatval($datosBlanco->lcm);
            $result2 = floatval($request['resultado']);
            
            if ($result1 > $result2) {

                $user = Auth::user();
                $cantidadBlancos = EnsayoAlcalinidad::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Blanco')->count();
                $ensayo = new EnsayoAlcalinidad();
                $ensayo->volumen_muestra = $request['volumen_muestra'];
                $ensayo->estado = 'Si';
                $ensayo->alcalinidad_total = $request['alcalinidad_total'];
                $ensayo->resultado = $request['resultado'];
                $ensayo->normalidad_solucion = $request['normalidad_solucion'];
                $ensayo->volumen_f = $request['volumen_f'];
                $ensayo->ph = $request['ph'];
                $ensayo->aprobacion_usuario = 'Pendiente';
                $ensayo->volumen_m = $request['volumen_m'];
                $ensayo->alcalinidad_select = $request['alcalinidad_select'];
                $ensayo->id_muestra = '';

                $ensayo->vigencia = $Year;
                $ensayo->lc_alcalinidad_id = $alcalinidadGeneral->id;
                $ensayo->id_carta = $datosBlanco->id;

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

            } else {

                $user = Auth::user();
                $cantidadBlancos = EnsayoAlcalinidad::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Blanco')->count();
                $ensayo = new EnsayoAlcalinidad();
                $ensayo->volumen_muestra = $request['volumen_muestra'];
                $ensayo->estado = 'No';
                $ensayo->aprobacion_usuario = 'No';
                $ensayo->alcalinidad_total = $request['alcalinidad_total'];
                $ensayo->resultado = $request['resultado'];
                $ensayo->ph = $request['ph'];
                $ensayo->normalidad_solucion = $request['normalidad_solucion'];
                $ensayo->volumen_f = $request['volumen_f'];
                $ensayo->volumen_m = $request['volumen_m'];
                $ensayo->alcalinidad_select = $request['alcalinidad_select'];
                $ensayo->id_muestra = '';

                $ensayo->vigencia = $Year;
                $ensayo->lc_alcalinidad_id = $alcalinidadGeneral->id;
                $ensayo->id_carta = $datosBlanco->id;

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

            }
        } else {

            if ($request['tipo'] == 'Patrón') {

                $alcalinidad_total = 0;
                $bk = DB::  select(" select * from lc_ensayo_alcalinidad where tipo = 'Blanco' and lc_ensayo_alcalinidad.deleted_at is null order by created_at desc limit 1");

                    

                if($request['tipo_patron']  == 'Patrón de 10'){
                    $tipoPatron = '10 mg/L';
                } else {
                    $tipoPatron = '50 mg/L';
                }

                $datosPatron = Patron::where('lc_list_trials_id', 7)->get()->last();
                $result1 = floatval($datosPatron->lcs);
                $result2 = floatval($datosPatron->lci);
                $result3 = floatval($request['resultado']);
                $resulFinal = 0;

                $consulPesperado = Alcalinidad::get()->last();
                if($request['tipo_patron'] == "Patrón de 50"){
                    $resulFinal = $request['resultado']/$consulPesperado->patron_esperado_dos*100;
                    
                } else {
                        $resulFinal = ($request['resultado']/$consulPesperado->patron_esperado*100);
                }


                if ($resulFinal > $result2 && $resulFinal < $result1) {

                    if ($request['tipo_patron'] == 'Patrón de 50') {
                        $cantidadPatrones = EnsayoAlcalinidad::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón de 50')->count();
                    } else {
                        if ($request['tipo_patron'] == 'Patrón') {
                            $cantidadPatrones = EnsayoAlcalinidad::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón de 10')->count();
                        } else {
                            if ($request['tipo_patron'] == 'LCM') {
                                $cantidadPatrones = EnsayoAlcalinidad::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'LCM')->count();
                            } 
                        }
                    }

                    $user = Auth::user();
                    $cantidadPatrones = EnsayoAlcalinidad::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();
                    $ensayo = new EnsayoAlcalinidad();
                    $ensayo->volumen_muestra = $request['volumen_muestra'];
                    $ensayo->estado = 'Si';

              
                    if ($bk[0]->alcalinidad_total == 0) {
                        $ensayo->alcalinidad_total = $request['resultado'];
                       } else {
                           $ensayo->alcalinidad_total = floatval($request['resultado']-$bk[0]->alcalinidad_total) ;
                       }
                    //    dd($bk[0]->alcalinidad_total,floatval($request['resultado']-$bk[0]->alcalinidad_total));
                    //    dd($request);
                    $ensayo->resultado = $request['resultado'];
                    $ensayo->normalidad_solucion = $request['normalidad_solucion'];
                    $ensayo->volumen_f = $request['volumen_f'];
                    $ensayo->ph = $request['ph'];
                    $ensayo->tipo_patron = $request['tipo_patron'];
                    $ensayo->aprobacion_usuario = 'Pendiente';
                    $ensayo->patron_pendiente = 'Pendiente';
                    $ensayo->volumen_m = $request['volumen_m'];
                    $ensayo->alcalinidad_select = $request['alcalinidad_select'];
                    $ensayo->id_muestra = '';

                    $ensayo->vigencia = $Year;
                    $ensayo->lc_alcalinidad_id = $alcalinidadGeneral->id;
                    $ensayo->id_carta = $datosPatron->id;

                    if ($cantidadPatrones == 0) {
                        if ($request['addconsecutivo']) {
                            $ensayo->consecutivo = 'STD' . ' - ' . $request['addconsecutivo'] . ' ' . $tipoPatron;
                        } else {
                            $ensayo->consecutivo = 'STD' . ' ' . $tipoPatron;
                        }

                    } else {
                        if ($request['addconsecutivo']) {
                            $ensayo->consecutivo = 'STD' . $cantidadPatrones . ' - ' . $request['addconsecutivo'] . ' ' . $tipoPatron;
                        } else {
                            $ensayo->consecutivo = 'STD' . $cantidadPatrones . ' ' . $tipoPatron;
                        }
                    }
                    $ensayo->users_id = Auth::user()->id;
                    $ensayo->user_name = Auth::user()->name;
                    if ($request['tipo_patron'] == 'Patrón de 50') {
                        $ensayo->tipo = 'Patrón';
                    } else {
                        if ($request['tipo_patron'] == 'Patrón de 10') {
                            $ensayo->tipo = 'Patrón';
                        } else {
                            if ($request['tipo_patron'] == 'LCM') {
                                $ensayo->tipo = 'LCM';
                            } 
                        }
                    }


                    $ensayo->save();

                    return $this->sendResponse($ensayo->toArray(), trans('data_obtained_successfully'));

                } else {

                    $user = Auth::user();
                    $cantidadPatrones = EnsayoAlcalinidad::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();
                    $ensayo = new EnsayoAlcalinidad();
                    $ensayo->volumen_muestra = $request['volumen_muestra'];
                    $ensayo->estado = 'No';
                    $ensayo->aprobacion_usuario = 'No';
                    $ensayo->alcalinidad_total = $request['alcalinidad_total'];
                    $ensayo->resultado = $request['resultado'];
                    $ensayo->normalidad_solucion = $request['normalidad_solucion'];
                    $ensayo->volumen_f = $request['volumen_f'];
                    $ensayo->ph = $request['ph'];
                    $ensayo->tipo_patron = $request['tipo_patron'];
                    $ensayo->volumen_m = $request['volumen_m'];
                    $ensayo->alcalinidad_select = $request['alcalinidad_select'];
                    $ensayo->id_muestra = '';

                    $ensayo->vigencia = $Year;
                    $ensayo->lc_alcalinidad_id = $alcalinidadGeneral->id;
                    $ensayo->id_carta = $datosPatron->id;

                    if ($cantidadPatrones == 0) {
                        if ($request['addconsecutivo']) {
                            $ensayo->consecutivo = 'STD' . ' - ' . $request['addconsecutivo'] . ' ' . $tipoPatron;
                        } else {
                            $ensayo->consecutivo = 'STD' . ' ' . $tipoPatron;
                        }
                    } else {
                        if ($request['addconsecutivo']) {
                            $ensayo->consecutivo = 'STD' . $cantidadPatrones . ' - ' . $request['addconsecutivo'] . ' ' . $tipoPatron;
                        } else {
                            $ensayo->consecutivo = 'STD' . $cantidadPatrones . ' ' . $tipoPatron;
                        }
                    }
                    $ensayo->users_id = Auth::user()->id;
                    $ensayo->user_name = Auth::user()->name;
                    $ensayo->tipo = $request['tipo'];

                    $ensayo->save();

                    return $this->sendResponse($ensayo->toArray(), trans('data_obtained_successfully'));

                }
            } else {

                if ($request['tipo'] == 'Ensayo') {
                    $user = Auth::user();

                    $blanco = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                    if ($blanco) {

                        $patron = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

                        if ($patron) {

                            $arrayTotal['ensayo'] = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

                            $blancoGeneral = BlancoGeneral::get()->last();
                            $patronGeneral = PatronGeneral::get()->last();

                            if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                                $centinela = false;
                                $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->where('lc_list_trials_id', "!=", 7)->get();
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
                                    $history->observation = "Se realizo el analisis de la fórmula de alcalinidad";

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
                                    $history->observation = "Se realizo el analisis de la fórmula de alcalinidad";

                                    $history->lc_sample_taking_id = $request['idMuestra']['id'];

                                    $history->save();
                                    $muestraActualizar[0]->estado_analisis = 'Análisis en ejecución';
                                    $muestraActualizar[0]->save();
                                }
                                $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->where('lc_list_trials_id', 7)->get();
                                $verifica[0]->estado = 'Ejecutado';
                                $verifica[0]->save();

                                $buscaEnsayo = Ensayo::where('lc_list_trials_id', 7)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();
                                $año = date('Y');
                                $array = str_split($año);

                                $ensayo = new EnsayoAlcalinidad();
                                
                                $ensayo->volumen_muestra = $request['volumen_muestra'];
                                $ensayo->estado = '';
                                $ensayo->alcalinidad_total = $request['alcalinidad_total'];
                                $ensayo->resultado = $request['resultado'];
                                $ensayo->normalidad_solucion = $request['normalidad_solucion'];
                                $ensayo->volumen_f = $request['volumen_f'];
                                $ensayo->ph = $request['ph'];
                                $ensayo->alcalinidad_select = $request['alcalinidad_select'];
                                $ensayo->volumen_m = $request['volumen_m'];
                                $ensayo->resultado = $request['resultado'];
                                $ensayo->id_muestra = $request['idMuestra']['id'];
                                $ensayo->lc_sample_taking_has_lc_list_trials_id = $buscaEnsayo->id;
                                $ensayo->duplicado = $request['idMuestra']['duplicado'];
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code;

                                $ensayo->users_id = Auth::user()->id;
                                $ensayo->user_name = Auth::user()->name;

                                $ensayo->tipo = $request['tipo'];

                                $ensayo->vigencia = $Year;
                                $ensayo->lc_alcalinidad_id = $alcalinidadGeneral->id;

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

                        // $blanco = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                        // if ($blanco) {

                        //     $patron = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

                        //     if ($patron) {

                        //         $arrayTotal['ensayo'] = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

                        //     $blancoGeneral=BlancoGeneral::get()->last();
                        //     $patronGeneral=PatronGeneral::get()->last();

                        // if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                        $buscaEnsayo = Ensayo::where('lc_list_trials_id', 7)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();

                        $año = date('Y');

                        $array = str_split($año);
                        $ensayo = new EnsayoAlcalinidad();
                        $ensayo->volumen_muestra = $request['volumen_muestra'];
                        $ensayo->estado = '';
                        $ensayo->alcalinidad_total = $request['alcalinidad_total'];
                        $ensayo->resultado = $request['resultado'];
                        $ensayo->normalidad_solucion = $request['normalidad_solucion'];
                        $ensayo->volumen_f = $request['volumen_f'];
                        $ensayo->ph = $request['ph'];
                        $ensayo->volumen_m = $request['volumen_m'];
                        $ensayo->alcalinidad_select = $request['alcalinidad_select'];
                        $ensayo->resultado = $request['resultado'];
                        $ensayo->duplicado = $request['idMuestra']['duplicado'];
                        $ensayo->id_muestra = $request['idMuestra']['id_muestra'];
                        $ensayo->consecutivo_ensayo = $request['idMuestra']['consecutivo'];
                        $muestraActualizar = SampleTaking::where('id', $request['idMuestra']['id_muestra'])->get();
                        $ensayo->lc_sample_taking_has_lc_list_trials_id = $request['idMuestra']['id'];

                        $ensayo->vigencia = $Year;
                        $ensayo->lc_alcalinidad_id = $alcalinidadGeneral->id;

                        if ($request['metodo'] == "Duplicado") {

                            $duplicado = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Duplicado')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();

                            if ($duplicado >= 1) {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D' . $duplicado;
                            } else {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D';
                            }
                            $ensayo->tipo = $request['tipo'];
                            $ensayo->metodo = "Duplicado";

                        } else {
                            if ($request['metodo'] == "Repetición") {
                                $duplicado = EnsayoAlcalinidad::where('users_id', $user->id)->where('metodo', 'Repetición')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();
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

                                    $duplicado = EnsayoAlcalinidad::where('users_id', $user->id)->where('metodo', 'Regla de decisión')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();

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

                            $dpr = new DprprAlcalinidad();
                            // $dpr->real = $request['real'];
                            $dpr->add1 =  $request['resultado'];
                            $dpr->add2 = $request['idMuestra']['resultado'];
                            $dpr->tipo = 'Promedio';
                            $dpr->consecutivo = $request['idMuestra']['consecutivo'];
                            $dpr->resultado_completo = $request['resultado_completo'];
                            $dpr->user_name = $user->name;
                            $dpr->user_id = $user->id;
                            $dpr->id_ensayo = $ensayo->id;
                            $dpr->id_muestra = $request['idMuestra']['id_muestra'];
                            $dpr->resultado =  ($request['resultado']+ $request['idMuestra']['resultado'])/2;
                            $dpr->lc_ensayo_alcalinidad_id = $ensayo->id;
                            $dpr->save();
                            
                        }

                        $history = new HistorySampleTaking();
                        $history->action = "Ejecuto duplicado";
                        $history->users_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->consecutivo = $ensayo->consecutivo;
                        $history->observation = "Se realizo duplicado a la muestra de alcalinidad";
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
    public function update($id, EnsayoAlcalinidad $request)
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
    public function getAllAlcalinidad()
    {
        if (!Auth::user()->hasRole('Administrador Leca')) {
            $user = Auth::user();

            $allEnsayos = EnsayoAlcalinidad::with("DprPrAlcalinidad")->where('users_id', $user->id)->latest()->get();
        } else {
            $allEnsayos = EnsayoAlcalinidad::with("DprPrAlcalinidad")->latest()->get();
        }

        return $this->sendResponse($allEnsayos->toArray(), trans('data_obtained_successfully'));
    }



        /**
     * Aqui se configura la condicion del blanco y del patron
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

        $blanco = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Blanco')->where('estado', 'Si')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where(function ($query) {
            $query->where('aprobacion_usuario', 'Pendiente')->orwhere('aprobacion_usuario', 'Si');
        })->get()->last();

        if ($blanco) {

            if ($blanco->aprobacion_usuario == 'Si') {

                $patron = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Patrón')->where('estado', 'Si')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where(function ($query) {
                    $query->where('aprobacion_usuario', 'Pendiente')->orwhere('aprobacion_usuario', 'Si');
                })->get()->last();
            } else {
                $patron = null;
            }

            if ($patron != null) {

                if ($patron->aprobacion_usuario == 'Si') {
                    $arrayTotal['ensayo'] = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();
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
                            // if(){

                            // }
                            $arrayTotal['patronotro'] = true;
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

        $blanco = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

        if ($blanco) {

            $patron = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

            if ($patron) {

                $arrayTotal['ensayo'] = EnsayoAlcalinidad::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

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

                    return view('leca::EnsayoAlcalinidad.ejecutar');

                } else {

                    $arrayTotal['blanco'] = false;
                    $arrayTotal['patron'] = false;
                    $arrayTotal['ensayo'] = 0;

                    return view('leca::EnsayoAlcalinidad.index');

                }

            } else {

                $arrayTotal['blanco'] = true;
                $arrayTotal['patron'] = false;

                return view('leca::EnsayoAlcalinidad.index');

            }

        } else {

            $arrayTotal['blanco'] = false;
            $arrayTotal['patron'] = false;

            return view('leca::EnsayoAlcalinidad.index');
        }

    }

    /**
     * Obtiene las muestras de la rutina
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
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

        $ensayo = Ensayo::where('lc_list_trials_id', 7)->where('estado', 'Pendiente')->get();

        foreach ($ensayo as $keya => $item) {

            array_push($arrayTercero, $item->lc_sample_taking_id);

        }

        $sample_takings = SampleTaking::with(['lcListTrials'])->where('estado_analisis', '!=', '')->where('estado_analisis', '!=', 'Análisis finalizado')->whereIn('id', $arrayTercero)->get();

        for ($i = 0; $i < count($sample_takings); $i++) {
            foreach ($sample_takings[$i]->lcListTrials as $keya => $item) {
                if ($item->id == 7) {
                    array_push($arraySegundo, $sample_takings[$i]);
                }
            }
        }

        return $this->sendResponse($arraySegundo, trans('data_obtained_successfully'));

    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function storeTendido(Request $request)
    {

        $user = Auth::user();

        $muestras = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 7)->where('estado', 'Activo')->get();

        if (count($muestras) > 0) {

            foreach ($muestras as $key => $value) {

                $value->estado = "Inactivo";

                $value->save();

            }
        }

        foreach ($request->toArray() as $key => $value) {

            $tendido = new TendidoMuestras();
            $tendido->users_id = $user->id;
            $tendido->lc_list_trials_id = 7;
            $tendido->estado = "Activo";
            $tendido->lc_sample_taking_id = $value;
            $tendido->save();

        }

        return $this->sendResponse($tendido, trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function allTendido()
    {

        $user = Auth::user();
        $arrayTotal = [];
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 7)->where('estado', 'Activo')->get();

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
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function allTendidoFinalizado()
    {

        $user = Auth::user();
        $arrayTotal = [];
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 7)->whereDate('fecha_finalizado', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Finalizado')->get();

        return $this->sendResponse($tendido, trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function consultaDecimalesBlanco()
    {

        $user = Auth::user();
        $blancoDecimales = Blancos::where('lc_list_trials_id', 7)->get()->last();
        return $this->sendResponse(intval($blancoDecimales->decimales), trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function consultaDecimalesPatron()
    {

        $user = Auth::user();
        $patronDecimales = Patron::where('lc_list_trials_id', 7)->get()->last();

        return $this->sendResponse(intval($patronDecimales->decimales), trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function consultaDecimalesEnsayo()
    {

        $user = Auth::user();
        $arrayTotal = [];
        $ensayoDecimales = Alcalinidad::where('lc_list_trials_id', 7)->get()->last();
        return $this->sendResponse(intval($ensayoDecimales->decimales), trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function storeDpr(Request $request)
    {
        $user = Auth::user();

        $dpr = new DprprAlcalinidad();
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
        $dpr->lc_ensayo_alcalinidad_id = $request['id_ensayo'];
        $dpr->id_muestra = $request['id_muestra'];
        $dpr->resultado = $request['resultado'];
        $dpr->resultado_completo = $request['resultado_completo'];
        $dpr->save();
        return $this->sendResponse('si', trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function storeRelativa(Request $request)
    {
        $user = Auth::user();

        $dpr = new DprprAlcalinidad();
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
        $dpr->lc_ensayo_alcalinidad_id = $request['id_ensayo'];
        $dpr->save();
        return $this->sendResponse('si', trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDatosPromedio($id)
    {

        $dpr = DprprAlcalinidad::where('id_ensayo', $id)->where('tipo', 'Promedio')->get();

        if ($dpr) {
            return $this->sendResponse($dpr, trans('data_obtained_successfully'));

        } else {
            return $this->sendResponse('no', trans('data_obtained_successfully'));

        }

    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDatosRelativa($id)
    {

        $dpr = DprprAlcalinidad::where('id_ensayo', $id)->where('tipo', 'Diferencia porcentual relativa')->get();

        if ($dpr) {
            return $this->sendResponse($dpr, trans('data_obtained_successfully'));

        } else {
            return $this->sendResponse('no', trans('data_obtained_successfully'));

        }

    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAllShowEnsayo(Request $request)
    {
        $idEnsayo = $request['id_muestra'];

        $muestra = SampleTaking::with(['programacion', 'lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->where('id', $idEnsayo)->get()->last();

        $ensayo = EnsayoAlcalinidad::with(['DprPrAlcalinidad', 'lcDprPrObservacionesDuplicadoAlcalinidads'])->where('id_muestra', $idEnsayo)->get();
        $ensayo->toArray();

        return $this->sendResponse($ensayo, trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAllShowPatronBlanco(Request $request)
    {
        $idEnsayo = $request['id_muestra'];

        $muestra = SampleTaking::with(['programacion', 'lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->where('id', $idEnsayo)->get()->last();

        $ensayo = [];

        $ensayoA = EnsayoAlcalinidad::where('id', $request['id'])->get()->last();

        $blanco = EnsayoAlcalinidad::whereDate('created_at', '>=', $ensayoA->created_at)->where('tipo', 'Blanco')->where('estado', 'Si')->get()->last();
        $patron = EnsayoAlcalinidad::whereDate('created_at', '>=', $ensayoA->created_at)->where('tipo', 'Patrón')->where('estado', 'Si')->get()->last();

        $ensayo['muestra'] = $muestra;
        $ensayo['blanco'] = $blanco;
        $ensayo['patron'] = $patron;

        return $this->sendResponse($ensayo, trans('data_obtained_successfully'));
    }

    /**
     * Exporta excel
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function exporta(Request $request)
    {
        $ss=0;
        $input = $request['data'];
        
        $dateToday = date('m-d-Y h:i:s');  

        $input=array_reverse($input);
        $inputFileType = 'Xlsx';
        $inputFileName = storage_path('app/public/leca/excel/Aluminio_LECA.xlsx');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);

        $date = strtotime($input[0]['created_at']); 

        $datosPrimarios=Alcalinidad::whereDate('created_at', '<=', $input[0]['created_at'])->get()->last();

        $anio = date("y", $date);
        $mes = date("m", $date);

        $spreadsheet->getActiveSheet()->setCellValue('G7', $datosPrimarios->documento_referencia ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('J14', $datosPrimarios->alcalinidad_alta ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('L14', $datosPrimarios->factor_alcal_alta ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('J16', $datosPrimarios->alcalinidad_baja ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('L16', $datosPrimarios->factor_alcal_baja ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('D15', $datosPrimarios->limite_cuantificacion ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('G15', $datosPrimarios->v_muestra ?? 'N/A');

        $spreadsheet->getActiveSheet()->setCellValue('H20', $datosPrimarios->curva ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('K20', $datosPrimarios->fecha_curva ?? 'N/A');

        $criticalEquip=CriticalEquipment::where('lc_list_trials_id', 7)->latest()->take(4)->get();

        $letra="B";
        for ($i=0; $i < count($criticalEquip) ; $i++) { 
            
            $spreadsheet->getActiveSheet()->setCellValue($letra.'13', $criticalEquip[$i]->equipo_critico ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue($letra.'14', $criticalEquip[$i]->identificacion ?? 'N/A');
            $letra++;
            $letra++;
        }
        
        $spreadsheet->getActiveSheet()->setCellValue('M8', $anio ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('N8', $mes ?? 'N/A');

        $cont=1;
        $numTotal=25+count($input);

        $arraydpr = array();
        for ($i=26; $i < $numTotal; $i++) { 

            $date = strtotime($input[$cont]['created_at']); 

            $dia = date("d", $date);
            $hora = date("H", $date);
            $minutos = date("i", $date);
            $segundos = date("s", $date);
            
            $user=User::find($input[$cont]['users_id']);
            
            $spreadsheet->getActiveSheet()->setCellValue('A'.$i, $dia ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('B'.$i, $hora.':'.$minutos.':'.$segundos ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('C'.$i, $input[$cont]['consecutivo'] ?? 'N/A');
            if($input[$cont]['alcalinidad_select']=='≥ 20 mg/L'){
                $spreadsheet->getActiveSheet()->setCellValue('D'.$i, 'NA');
                $spreadsheet->getActiveSheet()->setCellValue('E'.$i, 'NA');
                $spreadsheet->getActiveSheet()->setCellValue('F'.$i, $input[$cont]['volumen_f'] ?? 'N/A');
                $spreadsheet->getActiveSheet()->setCellValue('G'.$i, $input[$cont]['volumen_m'] ?? 'N/A');

            }else{
                if($input[$cont]['alcalinidad_select']=='≤ 20 mg/L'){


                $spreadsheet->getActiveSheet()->setCellValue('D'.$i, $input[$cont]['volumen_f'] ?? 'N/A');
                $spreadsheet->getActiveSheet()->setCellValue('E'.$i, $input[$cont]['volumen_m'] ?? 'N/A');
                $spreadsheet->getActiveSheet()->setCellValue('F'.$i, 'NA');
                $spreadsheet->getActiveSheet()->setCellValue('G'.$i, 'NA');

                }
            }

            
            $spreadsheet->getActiveSheet()->setCellValue('H'.$i, $input[$cont]['volumen_ph'] ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('J'.$i, $input[$cont]['resultado'] ?? 'N/A');

            if($input[$cont]['estado']=='No'){

                $spreadsheet
                ->getActiveSheet()
                ->getStyle('J'.$i)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('dbafaf');

            }else{
                if($input[$cont]['estado']=='Si'){
                    $spreadsheet
                    ->getActiveSheet()
                    ->getStyle('J'.$i)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('b1dbad');
                }else{
                    $spreadsheet
                    ->getActiveSheet()
                    ->getStyle('J'.$i)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('ffffff');
                }
            }

            // dd($input[4]['tipo']);
            if($input[$cont]['tipo']=='Duplicado'){

                $dprpr=DprprAlcalinidad::where('lc_ensayo_alcalinidad_id', $input[$cont]['id'])->get();
                
                if(count($dprpr)>0){

                    foreach ($dprpr as $key => $value) {
                        # code...
                        array_push($arraydpr, $value);
                        
                    }

                }

            }

            if($user->url_digital_signature){
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setPath(storage_path('app/public'.'/'. $user->url_digital_signature)); /* put your path and image here */
            $drawing->setCoordinates('L'.$i);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
            $drawing->setHeight(28);
            $drawing->setResizeProportional(true);
            $drawing->setOffsetX(1);    // this is how
            $drawing->setOffsetY(2);
            }  
            
            // $spreadsheet->getActiveSheet()->addImage('L'.$i, asset('storage').'/'. $user->url_digital_signature);
            $spreadsheet->getActiveSheet()->setCellValue('M'.$i, $user->name ?? 'N/A');
            $cont++;
            $ss = $cont;

        }
        // dd( $ss);
        if(count($arraydpr)>0){
            
            $contPorcentaje=0;
            $contDpr=0;

            $arraydpr=array_reverse($arraydpr);

            for ($u=0; $u < count($arraydpr) ; $u++) { 
                
                if($arraydpr[$u]->tipo=='Diferencia porcentual relativa' &&  $contDpr<3){
                    $spreadsheet->getActiveSheet()->setCellValue('F'. (20+$contDpr), $arraydpr[$u]->resultado.'%' ?? 'N/A');
                    $spreadsheet->getActiveSheet()->setCellValue('B'. (20+$contDpr), $dia ?? 'N/A');

                    $contDpr++;
                }else{
                    if($arraydpr[$u]->tipo=='Promedio' && $contPorcentaje<3){
                        $spreadsheet->getActiveSheet()->setCellValue('F'. (20+$contPorcentaje), $arraydpr[$u]->resultado.'%' ?? 'N/A');
                        $spreadsheet->getActiveSheet()->setCellValue('B'. (20+$contPorcentaje), $dia ?? 'N/A');

                        $contPorcentaje++;
                    }
                }
                
            }
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte Alcalinidad.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;


        return $this->sendResponse( $writer, trans('data_obtained_successfully'));

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

        $ensayo = EnsayoAlcalinidad::find($request['id']);

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
     * Exporta grafico
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param EnsayoAlcalinidad $request
     *
     * @return Response
     */
    public function getGrafico(Request $request)
    {
        $e = new GoogleController();
        //Array de blanco
        $arrayBk = [];
        $arrayBk2 = [];
        $arrayBkF= [];
        $arrayBk2F = [];
        $arrayBkC = [];
        $arrayBk2C = [];

        //Array de patron 10
        $arrayStd = [];
        $arrayStd2 = [];
        $arrayStdF = [];
        $arrayStd2F = [];
        $arrayStdC = [];
        $arrayStd2C = [];

        //Array de patron 50
        $arrayStd_50 = [];
        $arrayStd2_50 = [];
        $arrayStdF_50 = [];
        $arrayStd2F_50 = [];
        $arrayStdC_50 = [];
        $arrayStd2C_50 = [];

        //Array de LCM
        $arrayLcm = [];
        $arrayLcm2 = [];
        $arrayLcmF = [];
        $arrayLcm2F = [];
        $arrayLcmC = [];
        $arrayLcm2C = [];

        //Array de DPR
        $arrayDprA = [];
        $arrayDprA2 = [];
        $arrayDprB = [];
        $arrayDprB2 = [];
        $arrayDprF = [];
        $arrayDprF2 = [];
        $arrayDprC = [];
        $arrayDprC2 = [];
        $arrayDprCA = [];
        $arrayDprCA2 = [];

        //Contadores
        $contBk=0;
        $contStd=0;
        $contStd50=0;
        $contLcm=0;
        $contDpr=0;

        //Este es el titular del ensayo
        $data[] = ["range" => "BK!B7", "values" => [["Alcalinidad"]]];
        $data[] = ["range" => "STD10!E7", "values" => [["Alcalinidad"]]];
        $data[] = ["range" => "%S std 10!E7", "values" => [["Alcalinidad"]]];

        $data[] = ["range" => "STD50!E7", "values" => [["Alcalinidad"]]];
        $data[] = ["range" => "%S std 50!E7", "values" => [["Alcalinidad"]]];

        $data[] = ["range" => "LCM!E7", "values" => [["Alcalinidad"]]];
        $data[] = ["range" => "%S LCM!E7", "values" => [["Alcalinidad"]]];
        $data[] = ["range" => "DPR!B7", "values" => [["Alcalinidad"]]];

        //Se valida la fecha de expedicion
        $fechaActual = date('d-m-Y');
        $anio = date("y", strtotime($fechaActual));
        $mes = date("m", strtotime($fechaActual));


        $blanco = Blancos::where('lc_list_trials_id', 7)->get()->last();

        $data[] = ["range" => "BK!V8", "values" => [[$mes]]];
        $data[] = ["range" => "BK!V7", "values" => [[$anio]]];
        $data[] = ["range" => "BK!B37", "values" => [[$blanco->ldm]]];
        $data[] = ["range" => "BK!B38", "values" => [[$blanco->lcm]]];


        $ensayo = Alcalinidad::orderBy('id', 'desc')->first();

        $data[] = ["range" => "STD10!P8", "values" => [[$mes]]];
        $data[] = ["range" => "STD10!V8", "values" => [[$anio]]];
        $data[] = ["range" => "STD10!E8", "values" => [[$ensayo->patron_esperado]]];

        $data[] = ["range" => "%S std 10!P8", "values" => [[$mes]]];
        $data[] = ["range" => "%S std 10!V8", "values" => [[$anio]]];
        $data[] = ["range" => "%S std 10!E8", "values" => [[$ensayo->patron_esperado]]];


        $data[] = ["range" => "STD50!P8", "values" => [[$mes]]];
        $data[] = ["range" => "STD50!V8", "values" => [[$anio]]];
        $data[] = ["range" => "STD50!E8", "values" => [[$ensayo->patron_esperado_dos]]];

        $data[] = ["range" => "%S std 50!P8", "values" => [[$mes]]];
        $data[] = ["range" => "%S std 50!V8", "values" => [[$anio]]];
        $data[] = ["range" => "%S std 50!E8", "values" => [[$ensayo->patron_esperado_dos]]];


        $patron = Patron::where('lc_list_trials_id', 7)->get()->last();
        //se validan los datos de la carta anterior
        $data[] = ["range" => "STD10!AJ14", "values" => [[$patron->n_anterior]]];
        $data[] = ["range" => "STD10!AJ15", "values" => [[$patron->x_anterior]]];
        $data[] = ["range" => "STD10!AJ16", "values" => [[$patron->s_anterior]]];

        
        $data[] = ["range" => "%S std 10!AJ14", "values" => [[$patron->n_ant_porc_std]]];
        $data[] = ["range" => "%S std 10!AJ15", "values" => [[$patron->x_ant_porc_std]]];
        $data[] = ["range" => "%S std 10!AJ16", "values" => [[$patron->s_ant_porc_std]]];
        $data[] = ["range" => "%S std 10!AI17", "values" => [[$patron->lcs_porc_std]]];
        $data[] = ["range" => "%S std 10!AI18", "values" => [[$patron->lci_porc_std]]];
        $data[] = ["range" => "%S std 10!AI19", "values" => [[$patron->las_porc_std]]];
        $data[] = ["range" => "%S std 10!AI20", "values" => [[$patron->lai_porc_std]]];
        $data[] = ["range" => "%S std 10!AI21", "values" => [[$patron->x_mas_porc_std]]];
        $data[] = ["range" => "%S std 10!AI22", "values" => [[$patron->x_menos_porc_std]]];
        
        
        //se validan los datos de la carta anterior
        $data[] = ["range" => "STD50!AJ14", "values" => [[$patron->n_anterior_50]]];
        $data[] = ["range" => "STD50!AJ15", "values" => [[$patron->x_anterior_50]]];
        $data[] = ["range" => "STD50!AJ16", "values" => [[$patron->s_anterior_50]]];

        $data[] = ["range" => "%S std 50!AJ14", "values" => [[$patron->n_anterior_50]]];
        $data[] = ["range" => "%S std 50!AJ15", "values" => [[$patron->x_anterior_50]]];
        $data[] = ["range" => "%S std 50!AJ16", "values" => [[$patron->s_anterior_50]]];


        $data[] = ["range" => "LCM!P8", "values" => [[$mes]]];
        $data[] = ["range" => "LCM!V8", "values" => [[$anio]]];
        $data[] = ["range" => "LCM!E8", "values" => [[$ensayo->valor_esperado_lcm]]];

        $data[] = ["range" => "%S LCM!P8", "values" => [[$mes]]];
        $data[] = ["range" => "%S LCM!V8", "values" => [[$anio]]];
        $data[] = ["range" => "%S LCM!E8", "values" => [[$ensayo->valor_esperado_lcm]]];

        
        //Aqui se completan los datos de la carta anterior para lcm y adicionado
        $data[] = ["range" => "LCM!AJ14", "values" => [[$patron->n_anterior_lcm]]];
        $data[] = ["range" => "LCM!AJ15", "values" => [[$patron->x_anterior_lcm]]];
        $data[] = ["range" => "LCM!AJ16", "values" => [[$patron->s_anterior_lcm]]];
        $data[] = ["range" => "LCM!AI17", "values" => [[$patron->lcs_lcm]]];
        $data[] = ["range" => "LCM!AI18", "values" => [[$patron->lci_lcm]]];
        $data[] = ["range" => "LCM!AI19", "values" => [[$patron->las_lcm]]];
        $data[] = ["range" => "LCM!AI20", "values" => [[$patron->lai_lcm]]];
        $data[] = ["range" => "LCM!AI21", "values" => [[$patron->x_mas_lcm]]];
        $data[] = ["range" => "LCM!AI22", "values" => [[$patron->x_menos_lcm]]];

        $data[] = ["range" => "%S LCM!AJ14", "values" => [[$patron->n_anterior_lcm]]];
        $data[] = ["range" => "%S LCM!AJ15", "values" => [[$patron->x_anterior_lcm]]];
        $data[] = ["range" => "%S LCM!AJ16", "values" => [[$patron->s_anterior_lcm]]];
        $data[] = ["range" => "%S LCM!AI17", "values" => [[$patron->lcs_porc_lcm]]];
        $data[] = ["range" => "%S LCM!AI18", "values" => [[$patron->lci_porc_lcm]]];
        $data[] = ["range" => "%S LCM!AI19", "values" => [[$patron->las_porc_lcm]]];
        $data[] = ["range" => "%S LCM!AI20", "values" => [[$patron->lai_porc_lcm]]];
        $data[] = ["range" => "%S LCM!AI21", "values" => [[$patron->x_mas_porc_lcm]]];
        $data[] = ["range" => "%S LCM!AI22", "values" => [[$patron->x_menos_porc_lcm]]];


        $data[] = ["range" => "DPR!V8", "values" => [[$mes]]];
        $data[] = ["range" => "DPR!V7", "values" => [[$anio]]];
        $data[] = ["range" => "DPR!AH14", "values" => [[$patron->n_ant_dpr]]];
        $data[] = ["range" => "DPR!AH15", "values" => [[$patron->x_ant_dpr]]];
        $data[] = ["range" => "DPR!AH16", "values" => [[$patron->s_ant_dpr]]];

        //Aqui se recorre la data que llega desde la vista de cada ensayo
        foreach ($request->data as $keya => $item) {
            //Se valida si el dato es blanco
            if($item["tipo"]=="Blanco") {

                $contBk++;

                //Aqui se llena el arreglo para los primeros 31 registros
                if($contBk<=31){
                    array_push($arrayBk, $item["resultado"]);
                    $dia = date("d", strtotime($item["created_at"]));
                    array_push($arrayBkF,  $dia);
                    array_push($arrayBkC, $item["consecutivo"]." / ".$item["users_id"]);
                }else{
                    //Aqui se llena el arreglo para los otros 31 registros
                    if($contBk<=62 && $contBk>31){
                        array_push($arrayBk2,  $item["resultado"]);
                        $dia = date("d", strtotime($item["created_at"]));
                        array_push($arrayBk2F,  $dia);
                        array_push($arrayBk2C, $item["consecutivo"]." / ".$item["users_id"]);
                    }
                }
            } else {
                //Se valida si es patron 10
                if($item["tipo"] == "Patrón" && $item["alcalinidad_select"] == "≤ 20 mg/L") {

                    $contStd++;
                    //Aqui se llena el arreglo para los primeros 31 registros
                    if($contStd<=31){
                        array_push($arrayStd, $item["resultado"]);
                        $dia = date("d", strtotime($item["created_at"]));
                        array_push($arrayStdF,  $dia);
                        array_push($arrayStdC, $item["users_id"]);
                    }else{
                        //Aqui se llena el arreglo para los otros 31 registros
                        if($contStd<=62 && $contStd>31){
                            array_push($arrayStd2, $item["resultado"]);
                            $dia = date("d", strtotime($item["created_at"]));
                            array_push($arrayStd2F,  $dia);
                            array_push($arrayStd2C, $item["users_id"]);
                        }
                    }
                } else if($item["tipo"] == "Patrón" && $item["alcalinidad_select"] == "≥ 20 mg/L") {

                    $contStd50++;
                    //Aqui se llena el arreglo para los primeros 31 registros
                    if($contStd50<=31){
                        array_push($arrayStd_50, $item["resultado"]);
                        $dia = date("d", strtotime($item["created_at"]));
                        array_push($arrayStdF_50,  $dia);
                        array_push($arrayStdC_50, $item["consecutivo"]);
                    }else{
                        //Aqui se llena el arreglo para los otros 31 registros
                        if($contStd50<=62 && $contStd50>31){
                            array_push($arrayStd2_50, $item["resultado"]);
                            $dia = date("d", strtotime($item["created_at"]));
                            array_push($arrayStd2F_50,  $dia);
                            array_push($arrayStd2C_50, $item["consecutivo"]);
                        }
                    }
                } else {
                    if($item["tipo"]=="LCM") {

                        $contLcm++;
                        if($contLcm<=31){
                            array_push($arrayLcm,  $item["resultado"]);
                            $dia = date("d", strtotime($item["created_at"]));
                            array_push($arrayLcmF,  $dia);
                            array_push($arrayLcmC, $item["users_id"]);
                        }else{
                            if($contLcm<=62 && $contLcm>31){
                                array_push($arrayLcm2, $item["resultado"]);
                                $dia = date("d", strtotime($item["created_at"]));
                                array_push($arrayLcm2F,  $dia);
                                array_push($arrayLcm2C, $item["users_id"]);
                            }
                        }
                    }else{
                        if($item["tipo"]=="Duplicado") {

                            $contDpr++;
                            if($contDpr<=31){
                                if($item["dpr_pr_alcalinidad"]){
                                    foreach ($item["dpr_pr_alcalinidad"] as $value){

                                        if($value['tipo']=="Promedio"){
                                            array_push($arrayDprA, $value['add1']);
                                            array_push($arrayDprB, $value['add2']);
                                            $dia = date("d", strtotime($item["created_at"]));
                                            array_push($arrayDprF, $dia);
                                            array_push($arrayDprC, $item["consecutivo"]);
                                            array_push($arrayDprCA, $item["users_id"]);
                                        }
                                    }
                                }
                            }else{
                                if($contDpr<=62 && $contDpr>31){
                                    if($item["dpr_pr_alcalinidad"]){
                                        foreach ($item["dpr_pr_alcalinidad"] as $value){
    
                                            if($value['tipo']=="Promedio"){
                                                array_push($arrayDprA2, $value['add1']);
                                                array_push($arrayDprB2, $value['add2']);
                                                $dia = date("d", strtotime($item["created_at"]));
                                                array_push($arrayDprF2, $dia);
                                                array_push($arrayDprC2, $item["consecutivo"]);
                                                array_push($arrayDprCA2, $item["users_id"]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }        

        if(count($arrayBk)>0){
            $data[] = ["range" => "BK!B28", "values" => [$arrayBk]];
            $data[] = ["range" => "BK!B29", "values" => [$arrayBkF]];
            $data[] = ["range" => "BK!B30", "values" => [$arrayBkC]];
        }

        if(count($arrayBk2)>0){
            $data[] = ["range" => "BK!B33", "values" => [$arrayBk2]];
            $data[] = ["range" => "BK!B34", "values" => [$arrayBk2F]];
            $data[] = ["range" => "BK!B35", "values" => [$arrayBk2C]];
        }

        if(count($arrayStd)>0){

            $data[] = ["range" => "STD10!B34", "values" => [$arrayStd]];
            $data[] = ["range" => "STD10!B35", "values" => [$arrayStdF]];
            $data[] = ["range" => "STD10!B36", "values" => [$arrayStdC]];

            $data[] = ["range" => "%S std 10!B34", "values" => [$arrayStd]];
            $data[] = ["range" => "%S std 10!B35", "values" => [$arrayStdF]];
            $data[] = ["range" => "%S std 10!B36", "values" => [$arrayStdC]];
        }

        if(count($arrayStd2)>0){
            $data[] = ["range" => "STD10!B39", "values" => [$arrayStd2]];
            $data[] = ["range" => "STD10!B40", "values" => [$arrayStd2F]];
            $data[] = ["range" => "STD10!B41", "values" => [$arrayStd2C]];

            $data[] = ["range" => "%S std 10!B39", "values" => [$arrayStd2]];
            $data[] = ["range" => "%S std 10!B40", "values" => [$arrayStd2F]];
            $data[] = ["range" => "%S std 10!B41", "values" => [$arrayStd2C]];
        }

        if(count($arrayStd_50)>0){

            $data[] = ["range" => "STD50!B34", "values" => [$arrayStd_50]];
            $data[] = ["range" => "STD50!B35", "values" => [$arrayStdF_50]];
            $data[] = ["range" => "STD50!B36", "values" => [$arrayStdC_50]];

            $data[] = ["range" => "%S std 50!B34", "values" => [$arrayStd_50]];
            $data[] = ["range" => "%S std 50!B35", "values" => [$arrayStdF_50]];
            $data[] = ["range" => "%S std 50!B36", "values" => [$arrayStdC_50]];
        }

        if(count($arrayStd2_50)>0){
            $data[] = ["range" => "STD50!B39", "values" => [$arrayStd2_50]];
            $data[] = ["range" => "STD50!B40", "values" => [$arrayStd2F_50]];
            $data[] = ["range" => "STD50!B41", "values" => [$arrayStd2C_50]];

            $data[] = ["range" => "%S std 50!B39", "values" => [$arrayStd2_50]];
            $data[] = ["range" => "%S std 50!B40", "values" => [$arrayStd2F_50]];
            $data[] = ["range" => "%S std 50!B41", "values" => [$arrayStd2C_50]];
        }

        if(count($arrayLcm)>0){
            $data[] = ["range" => "LCM!B34", "values" => [$arrayLcm]];
            $data[] = ["range" => "LCM!B35", "values" => [$arrayLcmF]];
            $data[] = ["range" => "LCM!B36", "values" => [$arrayLcmC]];

            $data[] = ["range" => "%S LCM!B34", "values" => [$arrayLcm]];
            $data[] = ["range" => "%S LCM!B35", "values" => [$arrayLcmF]];
            $data[] = ["range" => "%S LCM!B36", "values" => [$arrayLcmC]];
        }
        if(count($arrayLcm2)>0){
            $data[] = ["range" => "LCM!B39", "values" => [$arrayLcm2]];
            $data[] = ["range" => "LCM!B40", "values" => [$arrayLcm2F]];
            $data[] = ["range" => "LCM!B41", "values" => [$arrayLcm2C]];

            $data[] = ["range" => "%S LCM!B39", "values" => [$arrayLcm2]];
            $data[] = ["range" => "%S LCM!B40", "values" => [$arrayLcm2F]];
            $data[] = ["range" => "%S LCM!B41", "values" => [$arrayLcm2C]];
        }

        if(count($arrayDprA)>0){
            $data[] = ["range" => "DPR!B28", "values" => [$arrayDprA]];
            $data[] = ["range" => "DPR!B29", "values" => [$arrayDprB]];
            $data[] = ["range" => "DPR!B31", "values" => [$arrayDprF]];
            $data[] = ["range" => "DPR!B32", "values" => [$arrayDprC]];
            $data[] = ["range" => "DPR!B33", "values" => [$arrayDprCA]];
        }

        if(count($arrayDprA2)>0){
            $data[] = ["range" => "DPR!B36", "values" => [$arrayDprA2]];
            $data[] = ["range" => "DPR!B37", "values" => [$arrayDprB2]];
            $data[] = ["range" => "DPR!B39", "values" => [$arrayDprF2]];
            $data[] = ["range" => "DPR!B40", "values" => [$arrayDprC2]];
            $data[] = ["range" => "DPR!B41", "values" => [$arrayDprCA2]];
        }

        $fileIdCopy = $e->editFileExcelBatch("1cCiYw2l5tUgAIHseJxZY8NQlqlc_6vuL5iyGG9-fNMI", $data, true);

        $e->downloadFileGoogleDrive($fileIdCopy, "Ensayo Alcalinidad", "excel", true);
    }


    /**
     * calcula el dpr
     *
     * @author Leonardo Herrera - marzo. 31 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDprPromedio($id)
    {

    // se separa el consecutivo de entrada
    $array =  explode('-',$id);
    //Consecutivo de entrada sin el prefijo de duplicado
    $sample_reception_code = $array[0].'-'.$array[1];
    //Se instancia donde se guardara el consecutivo anterior
    $consecutivoAnterior = '';

        //Si el último prefijo es D, es por que es el primer duplicado
        if ($array[2] == 'D') {
            //El anterior consecutivo, seria la toma de muestra.
            $consecutivoAnterior = $sample_reception_code;

            // Si el ultimo prefijo es diferente de D es por que es un duplicado diferente al primero
        }else {
            
            // Se Guarda el número del consecutivo actual menos  1, esl que acompaña la D.
            $numeroConsecutivo = intval(substr( $array[2], 1))-1;

            // Si el resultado del numero del consecutivo anterior es cero, es por que es el primer duplicado, se consruye el consecutivo del primer duplicado.
            if ($numeroConsecutivo == 0) {
                $consecutivoAnterior = $sample_reception_code.'-D';
                // De lo contrario se arma el consecutivo anterior al que se recibe por parametro
            }else{
                $consecutivoAnterior = $sample_reception_code.'-D'.$numeroConsecutivo;
            }
        
        }

    



        $total = 0;
        $consecutivo = '';
        // $startSampling = SampleTaking::with('lcResidualChlorineLists')->where('sample_reception_code', $sample_reception_code)->get()->toArray();
        $dprEnsayo = EnsayoAlcalinidad::where('consecutivo', $consecutivoAnterior)->get()->first();
        $dprEnsayoF = $dprEnsayo->resultado;

        $dprEnsayoDos = EnsayoAlcalinidad::where('consecutivo', $id)->get()->first();
        $dprEnsayoFDos = $dprEnsayoDos->resultado;

        $variableUno = $dprEnsayoF - $dprEnsayoFDos;
        $variableDos = ($dprEnsayoF + $dprEnsayoFDos)/2;

        $total =abs($variableUno/$variableDos)*100 ;

        


        return $this->sendResponse(floatval($total), trans('data_obtained_successfully'));
    }


      /**
     * Envia la muestra principal
     *
     * @author Leonardo Herrera - Marzo. 30 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getEnsayoPrincipal($id)
    {   
        $ensayo=EnsayoAlcalinidad::where('id',$id )->first();
        $consecutivo =   $ensayo->consecutivo;

        if ($ensayo->consecutivo_ensayo.'-D' == $consecutivo) {
            $ensayo = EnsayoAlcalinidad::where('consecutivo', $ensayo->consecutivo_ensayo)->first();
        }
        else {
            $array =  explode('-',$consecutivo);
            $numeroDuplicado = substr( $array[2], 1);
            $dprs = intval($numeroDuplicado)-1;

            if ( $dprs == 0) {
                $ensayo = EnsayoAlcalinidad::where('consecutivo', $ensayo->consecutivo_ensayo. '-D')->latest()->first();
            }else{
                $ensayo = EnsayoAlcalinidad::where('consecutivo', $ensayo->consecutivo_ensayo. '-'. $dprs)->latest()->first();
            }
            
        }
        return $this->sendResponse( $ensayo, trans('data_obtained_successfully'));

    }

}
