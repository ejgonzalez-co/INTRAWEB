<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\BlancoGeneral;
use Modules\Leca\Models\Blancos;
use Modules\Leca\Models\DprprSustanciasFlotantes;
use Modules\Leca\Models\Ensayo;
use Modules\Leca\Models\EnsayoSustanciasFlotantes;
use Modules\Leca\Models\HistorySampleTaking;
use Modules\Leca\Models\ListTrials;
use Modules\Leca\Models\Patron;
use Modules\Leca\Models\PatronGeneral;
use Modules\Leca\Models\SampleTaking;
use Modules\Leca\Models\SustanciasFlotantes;
use Modules\Leca\Models\TendidoMuestras;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class EnsayoSustanciasFlotantesController extends AppBaseController
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

        return view('leca::EnsayoSustanciasFlotantes.index');

    }

    public function getDatosBlanco()
    {

        $datosBlanco = SustanciasFlotantes::orderBy('id', 'desc')->first();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    public function getCartaDatosBlanco()
    {

        $datosBlanco = Blancos::where('lc_list_trials_id', 35)->get()->last();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    public function getCartaDatosPatron()
    {

        $datosBlanco = Patron::where('lc_list_trials_id', 35)->get()->last();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param EnsayoSustanciasFlotantes $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request['tipo'] == 'Blanco') {

            $datosBlanco = Blancos::where('lc_list_trials_id', 35)->get()->last();

            $result1 = floatval($datosBlanco->lcm);
            $result2 = floatval($request['conductividad']);

            if ($result1 > $result2) {

                $user = Auth::user();
                $cantidadBlancos = EnsayoSustanciasFlotantes::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Blanco')->count();
                $ensayo = new EnsayoSustanciasFlotantes();
                $ensayo->estado = 'Si';

                $ensayo->sustanciasFlotantes = $request['sustanciasFlotantes'];

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

            } else {

                $user = Auth::user();
                $cantidadBlancos = EnsayoSustanciasFlotantes::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Blanco')->count();
                $ensayo = new EnsayoSustanciasFlotantes();
                $ensayo->estado = 'No';

                $ensayo->sustanciasFlotantes = $request['sustanciasFlotantes'];
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

            }
        } else {

            if ($request['tipo'] == 'Patrón') {

                $datosPatron = Patron::where('lc_list_trials_id', 35)->get()->last();
                $result1 = floatval($datosPatron->lcs);
                $result2 = floatval($datosPatron->lci);
                $datosPrim = SustanciasFlotantes::where('lc_list_trials_id', 35)->get()->last();
                $result3 = floatval($request['conductividad'])/$datosPrim->valor_esperador_uno*100;

                if ($result3 > $result2 && $result3 < $result1) {

                    $user = Auth::user();
                    $cantidadPatrones = EnsayoSustanciasFlotantes::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();
                    $ensayo = new EnsayoSustanciasFlotantes();
                    $ensayo->estado = 'Si';

                    $ensayo->sustanciasFlotantes = $request['sustanciasFlotantes'];
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

                } else {

                    $user = Auth::user();
                    $cantidadPatrones = EnsayoSustanciasFlotantes::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();
                    $ensayo = new EnsayoSustanciasFlotantes();
                    $ensayo->estado = 'No';

                    $ensayo->sustanciasFlotantes = $request['sustanciasFlotantes'];
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

                }
            } else {

                if ($request['tipo'] == 'Ensayo') {
                    $user = Auth::user();

                    $blanco = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                    if ($blanco || $blanco == null) {

                        // $patron = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();
                        $patron = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                        if ($patron || $patron == null) {

                            $arrayTotal['ensayo'] = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->get()->count();

                            $blancoGeneral = BlancoGeneral::get()->last();
                            $patronGeneral = PatronGeneral::get()->last();

                            if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                                $centinela = false;
                                $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->where('lc_list_trials_id', "!=", 35)->get();
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
                                    $history->observation = "Se realizo el analisis de la fórmula de SustanciasFlotantes";

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
                                    $history->observation = "Se realizo el analisis de la fórmula de SustanciasFlotantes";

                                    $history->lc_sample_taking_id = $request['idMuestra']['id'];

                                    $history->save();
                                    $muestraActualizar[0]->estado_analisis = 'Análisis en ejecución';
                                    $muestraActualizar[0]->save();
                                }
                                $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->where('lc_list_trials_id', 35)->get();
                                $verifica[0]->estado = 'Ejecutado';
                                $verifica[0]->save();

                                $buscaEnsayo = Ensayo::where('lc_list_trials_id', 35)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();
                                $año = date('Y');
                                $array = str_split($año);

                                $ensayo = new EnsayoSustanciasFlotantes();
                                $ensayo->estado = '';

                                $ensayo->sustanciasFlotantes = $request['sustanciasFlotantes'];
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

                        // $blanco = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                        // if ($blanco) {

                        //     $patron = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

                        //     if ($patron) {

                        //         $arrayTotal['ensayo'] = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

                        //     $blancoGeneral=BlancoGeneral::get()->last();
                        //     $patronGeneral=PatronGeneral::get()->last();

                        // if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                        $buscaEnsayo = Ensayo::where('lc_list_trials_id', 35)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();

                        $año = date('Y');

                        $array = str_split($año);
                        $ensayo = new EnsayoSustanciasFlotantes();
                        $ensayo->estado = '';

                        $ensayo->sustanciasFlotantes = $request['sustanciasFlotantes'];
                        $ensayo->duplicado = $request['idMuestra']['duplicado'];
                        $ensayo->id_muestra = $request['idMuestra']['id_muestra'];
                        $ensayo->consecutivo_ensayo = $request['idMuestra']['consecutivo'];
                        $muestraActualizar = SampleTaking::where('id', $request['idMuestra']['id_muestra'])->get();
                        $ensayo->lc_sample_taking_has_lc_list_trials_id = $request['idMuestra']['id'];

                        if ($request['metodo'] == "Duplicado") {

                            $duplicado = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Duplicado')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();

                            if ($duplicado >= 1) {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D' . $duplicado;
                            } else {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D';
                            }
                            $ensayo->tipo = $request['tipo'];
                            $ensayo->metodo = "Duplicado";

                        } else {
                            if ($request['metodo'] == "Repetición") {
                                $duplicado = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('metodo', 'Repetición')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();
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

                                    $duplicado = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('metodo', 'Regla de decisión')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();

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
                            $dpr = new DprprSustanciasFlotantes();
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
                            $dpr->resultado = ($request['resultado'] + $request['idMuestra']['resultado']) / 2;
                            $dpr->lc_ensayo_turbidez_id = $ensayo->id;
                            $dpr->save();

                        }

                        $history = new HistorySampleTaking();
                        $history->action = "Ejecuto duplicado";
                        $history->users_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->consecutivo = $ensayo->consecutivo;
                        $history->observation = "Se realizo duplicado a la muestra de SustanciasFlotantes";
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
    public function update($id, EnsayoSustanciasFlotantes $request)
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
    public function getAllSustanciasFlotantes()
    {
        if (!Auth::user()->hasRole('Administrador Leca')) {
            $user = Auth::user();

            $allEnsayos = EnsayoSustanciasFlotantes::where('users_id', $user->id)->latest()->get();
        } else {
            $allEnsayos = EnsayoSustanciasFlotantes::latest()->get();

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

        $blanco = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

        if ($blanco) {

            $patron = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

            if ($patron) {

                $arrayTotal['ensayo'] = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

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

                    return $this->sendResponse($arrayTotal, trans('data_obtained_successfully'));

                } else {

                    $arrayTotal['blanco'] = false;
                    $arrayTotal['patron'] = false;
                    $arrayTotal['ensayo'] = 0;

                    return $this->sendResponse($arrayTotal, trans('data_obtained_successfully'));

                }

            } else {

                $arrayTotal['blanco'] = true;
                $arrayTotal['patron'] = false;

                return $this->sendResponse($arrayTotal, trans('data_obtained_successfully'));

            }

        } else {

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

        $blanco = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

        if ($blanco) {

            $patron = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

            if ($patron) {

                $arrayTotal['ensayo'] = EnsayoSustanciasFlotantes::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

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

                    return view('leca::EnsayoSustanciasFlotantes.ejecutar');

                } else {

                    $arrayTotal['blanco'] = false;
                    $arrayTotal['patron'] = false;
                    $arrayTotal['ensayo'] = 0;

                    return view('leca::EnsayoSustanciasFlotantes.ejecutar');

                }

            } else {

                $arrayTotal['blanco'] = true;
                $arrayTotal['patron'] = false;

                return view('leca::EnsayoSustanciasFlotantes.index');

            }

        } else {

            $arrayTotal['blanco'] = false;
            $arrayTotal['patron'] = false;

            return view('leca::EnsayoSustanciasFlotantes.ejecutar');
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

        $ensayo = Ensayo::where('lc_list_trials_id', 35)->where('estado', 'Pendiente')->get();

        foreach ($ensayo as $keya => $item) {
            array_push($arrayTercero, $item->lc_sample_taking_id);

        }
        $sample_takings = SampleTaking::with(['lcListTrials'])->where('estado_analisis', '!=', '')->where('estado_analisis', '!=', 'Análisis finalizado')->whereIn('id', $arrayTercero)->get();

        for ($i = 0; $i < count($sample_takings); $i++) {
            foreach ($sample_takings[$i]->lcListTrials as $keya => $item) {
                if ($item->id == 35) {
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

        $muestras = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 35)->where('estado', 'Activo')->get();

        if (count($muestras) > 0) {

            foreach ($muestras as $key => $value) {

                $value->estado = "Inactivo";

                $value->save();

            }
        }

        foreach ($request->toArray() as $key => $value) {

            $tendido = new TendidoMuestras();
            $tendido->users_id = $user->id;
            $tendido->lc_list_trials_id = 35;
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
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 35)->where('estado', 'Activo')->get();

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
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 35)->whereDate('fecha_finalizado', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Finalizado')->get();

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
        $blancoDecimales = Blancos::where('lc_list_trials_id', 35)->get()->last();
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
        $patronDecimales = Patron::where('lc_list_trials_id', 35)->get()->last();

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
        $ensayoDecimales = SustanciasFlotantes::where('lc_list_trials_id', 35)->get()->last();
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

        $dpr = new DprprSustanciasFlotantes();
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
        $dpr->lc_ensayo_turbidez_id = $request['id_ensayo'];
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

        $dpr = new DprprSustanciasFlotantes();
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
        $dpr->lc_ensayo_turbidez_id = $request['id_ensayo'];
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

        $dpr = DprprSustanciasFlotantes::where('id_ensayo', $id)->where('tipo', 'Promedio')->get();

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

        $dpr = DprprSustanciasFlotantes::where('id_ensayo', $id)->where('tipo', 'Diferencia porcentual relativa')->get();

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

        $ensayo = EnsayoSustanciasFlotantes::with(['lcDprprTurbidez', 'lcObservacionesDuplicadoTurbidez'])->where('id_muestra', $idEnsayo)->get();
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

        $ensayoA = EnsayoSustanciasFlotantes::where('id', $request['id'])->get()->last();

        $blanco = EnsayoSustanciasFlotantes::whereDate('created_at', '>=', $ensayoA->created_at)->where('tipo', 'Blanco')->where('estado', 'Si')->get()->last();
        $patron = EnsayoSustanciasFlotantes::whereDate('created_at', '>=', $ensayoA->created_at)->where('tipo', 'Patrón')->where('estado', 'Si')->get()->last();

        $ensayo['muestra'] = $muestra;
        $ensayo['blanco'] = $blanco;
        $ensayo['patron'] = $patron;

        return $this->sendResponse($ensayo, trans('data_obtained_successfully'));
    }

    /**
     * Exporta excel
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 01 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function exporta(Request $request)
    {

        $input = $request['data'];
        $inputFileType = 'Xlsx';
        $inputFileName = storage_path('app/public/leca/excel/SustanciasFlotantes.xlsx');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);

        $date = strtotime($input[0]['created_at']);

        $anio = date("y", $date);
        $mes = date("m", $date);

        $spreadsheet->getActiveSheet()->setCellValue('H11', $input[0]['pendiente'] ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('J11', $input[0]['intercepto'] ?? 'N/A');

        $spreadsheet->getActiveSheet()->setCellValue('K7', $anio ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('M7', $mes ?? 'N/A');

        // for ($i=25; $i <30 ; $i++) {
        //     $spreadsheet->getActiveSheet()->setCellValue('C'.$i, "erika");

        // }
        $cont = 0;
        $numTotal = 25 + count($input);
        for ($i = 25; $i < $numTotal; $i++) {

            $date = strtotime($input[$cont]['created_at']);

            $dia = date("d", $date);
            $hora = date("H", $date);
            $minutos = date("i", $date);
            $segundos = date("s", $date);

            $user = User::find($input[$cont]['users_id']);

            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, $dia ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('B' . $i, $hora . ':' . $minutos . ':' . $segundos ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('C' . $i, $input[$cont]['consecutivo'] ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('E' . $i, $input[$cont]['volumen'] ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('F' . $i, $input[$cont]['factor_disolucion'] ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('G' . $i, $input[$cont]['absorbancia'] ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('J' . $i, $input[$cont]['resultado'] ?? 'N/A');
            if ($user->url_digital_signature) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setPath(storage_path('app/public' . '/' . $user->url_digital_signature)); /* put your path and image here */
                $drawing->setCoordinates('L' . $i);
                $drawing->setWorksheet($spreadsheet->getActiveSheet());
                $drawing->setHeight(36);
                $drawing->setResizeProportional(true);
                $drawing->setOffsetX(2); // this is how
                $drawing->setOffsetY(2);
            }
            // $spreadsheet->getActiveSheet()->addImage('L'.$i, asset('storage').'/'. $user->url_digital_signature);
            $spreadsheet->getActiveSheet()->setCellValue('M' . $i, $user->name ?? 'N/A');
            $cont++;

        }

        // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        // $writer->save( storage_path('app/public/leca/excel/Erika.xlsx'));

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

        $ensayoA = EnsayoSustanciasFlotantes::where('tipo', 'Blanco')->get()->last();

        return $this->sendResponse($ensayoA, trans('data_obtained_successfully'));

    }

    public function getDatoPrimario()
    {

        $datosBlanco = SustanciasFlotantes::orderBy('id', 'desc')->first();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }
}