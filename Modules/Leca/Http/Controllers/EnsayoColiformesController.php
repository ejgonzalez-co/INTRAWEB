<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\Aluminio;
use Modules\Leca\Models\BlancoGeneral;
use Modules\Leca\Models\Blancos;
use Modules\Leca\Models\ColiformesTotales;
use Modules\Leca\Models\CriticalEquipment;
use Modules\Leca\Models\Dprpr;
use Modules\Leca\Models\Ensayo;
use Modules\Leca\Models\EnsayoAluminio;
use Modules\Leca\Models\EnsayoMicro;
use Modules\Leca\Models\HistorySampleTaking;
use Modules\Leca\Models\ListTrials;
use Modules\Leca\Models\Patron;
use App\Http\Controllers\GoogleController;
use Modules\Leca\Models\PatronGeneral;
use Modules\Leca\Models\PozosMicro;
use Modules\Leca\Models\SampleTaking;
use Modules\Leca\Models\TendidoMuestras;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class EnsayoColiformesController extends AppBaseController
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

        return view('leca::EnsayoColiformes.index');

    }

    public function getDatosBlanco()
    {

        $datosBlanco = ColiformesTotales::orderBy('id', 'desc')->first();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    public function getDatosSiembra()
    {
        if (!Auth::user()->hasRole('Administrador Leca')) {
            $user = Auth::user();

            $allEnsayos = EnsayoMicro::where('users_id', $user->id)->where('tipo', 'Ensayo')->where('ensayo', 'Coliformes')->where('estado', 'Pendiente')->latest()->get();

        } else {
            $allEnsayos = EnsayoMicro::where('tipo', 'Ensayo')->where('ensayo', 'Coliformes')->where('estado', 'Pendiente')->first();
        }

        return $this->sendResponse($allEnsayos->toArray(), trans('data_obtained_successfully'));
    }

    public function getDatosUsers()
    {

        $datosUsuario = Auth::user();

        return $this->sendResponse($datosUsuario, trans('data_obtained_successfully'));
    }

    public function getResulPozos(Request $request)
    {
        $datosPozos = PozosMicro::select('resultado')->where('p_grandes', $request['p_grandes'])->where('p_pequeños', $request['p_pequeños'])->get()->toArray();

        return $this->sendResponse($datosPozos, trans('data_obtained_successfully'));
    }

    public function getResulPozosUfc(Request $request)
    {
        $datosPozos = PozosMicro::select('resultado')->where('p_grandes', $request['p_grandes'])->where('p_pequeños', $request['p_pequeños'])->get()->toArray();

        return $this->sendResponse($datosPozos, trans('data_obtained_successfully'));
    }

    public function getCartaDatosBlanco()
    {

        $datosBlanco = Blancos::where('lc_list_trials_id', 21)->get()->last();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    public function getCartaDatosPatron()
    {

        $datosBlanco = Patron::where('lc_list_trials_id', 15)->get()->last();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
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
    public function update($id, Request $request)
    {
        $user = Auth::user();
        $Year = date("Y");
        $coliformeGeneral = ColiformesTotales::orderBy('id', 'desc')->first();
        $user = Auth::user();

        $blanco = EnsayoMicro::where('users_id', $user->id)->where('ensayo', 'Coliformes')->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

        if ($blanco) {


            if ($blanco) {

                $arrayTotal['ensayo'] = EnsayoMicro::where('users_id', $user->id)->where('ensayo', 'Coliformes')->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->get()->count();

                $blancoGeneral = BlancoGeneral::get()->last();
                $patronGeneral = PatronGeneral::get()->last();

                if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                    $centinela = false;
                    $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->where('lc_list_trials_id', "!=", 21)->get();
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
                        $history->observation = "Se realizo el analisis de la fórmula de coliformes";

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
                        $history->observation = "Se realizo el analisis de la fórmula de coliformes";

                        $history->lc_sample_taking_id = $request['idMuestra']['id'];

                        $history->save();
                        $muestraActualizar[0]->estado_analisis = 'Análisis en ejecución';
                        $muestraActualizar[0]->save();
                    }
                    $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->where('lc_list_trials_id', 21)->get();
                    $verifica[0]->estado = 'Ejecutado';
                    $verifica[0]->save();

                    $buscaEnsayo = Ensayo::where('lc_list_trials_id', 21)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();
                    $año = date('Y');
                    $array = str_split($año);

                    $ensayo = EnsayoMicro::where('id_muestra', $id)->where('estado', 'Pendiente')->get();

                    $ensayo[0]->estado = 'No aplica';
                    $ensayo[0]->ensayo = 'Coliformes';
                    $ensayo[0]->aprobacion_usuario = 'No aplica';
                    $ensayo[0]->resultado = $request['resultado'];
                    $ensayo[0]->hora_lectura = $request['hora_lectura'];
                    $ensayo[0]->resultado_general = $request['resultado_general'];
                    $ensayo[0]->resultado_general_ufc = $request['resultado_general_ufc'];
                    $ensayo[0]->fecha_lectura = $request['fecha_lectura'];
                    $ensayo[0]->pozos_grandes = $request['p_grandes'];
                    $ensayo[0]->pozos_pequeños = $request['p_pequeños'];
                    $ensayo[0]->convertir_ufc = $request['convertir_ufc'];
                    $ensayo[0]->vigencia = $Year;
                    $ensayo[0]->id_muestra = $request['idMuestra']['id'];
                    $ensayo[0]->lc_sample_taking_has_lc_list_trials_id = $buscaEnsayo->id;
                    $ensayo[0]->duplicado = $request['idMuestra']['duplicado'];
                    $ensayo[0]->consecutivo = $muestraActualizar[0]->sample_reception_code;

                    $ensayo[0]->users_id = Auth::user()->id;
                    $ensayo[0]->user_name = Auth::user()->name;

                    $ensayo[0]->tipo = $request['tipo'];
                    $ensayo[0]->save();

                    $muestras = TendidoMuestras::where('users_id', $user->id)->where('lc_sample_taking_id', $request['idMuestra']['id'])->where('estado', 'Activo')->where('lc_list_trials_id', 21)->first();

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
        
        // $actEnsayo = EnsayoMicro::where('id_muestra', $id)->where('estado', 'Pendiente')->first();
        // $actEnsayo->tecnica = $request['dilucion_utilizada'];

        // $actEnsayo->save();
        return $this->sendResponse('Si', trans('data_obtained_successfully'));
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
    public function getAllColiformes()
    {

        if (!Auth::user()->hasRole('Administrador Leca')) {
            $user = Auth::user();

            // $allEnsayos = EnsayoMicro::where('users_id', $user->id)->where('tipo', 'Ensayo')->where('ensayo', 'Coliformes')->where('estado', "!=", 'Pendiente')->orWhere('tipo', 'Blanco')->orWhere('tipo', 'Duplicado')->latest()->get();

            $allEnsayos = EnsayoMicro::where('users_id', $user->id)->where('ensayo', 'Coliformes')->latest()->get();

        } else {
            // $allEnsayos = EnsayoMicro::where('tipo', 'Ensayo')->where('ensayo', 'Coliformes')->where('estado', "!=", 'Pendiente')->orWhere('tipo', 'Blanco')->orWhere('tipo', 'Duplicado')->latest()->get();

            $allEnsayos = EnsayoMicro::where('ensayo', 'Coliformes')->latest()->get();
        }

        return $this->sendResponse($allEnsayos->toArray(), trans('data_obtained_successfully'));

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

        $blanco = EnsayoMicro::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('aprobacion_usuario', 'Si')->get()->last();

        if ($blanco) {

            $patron = EnsayoMicro::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('aprobacion_usuario', 'Si')->get()->last();

            if ($blanco) {

                $arrayTotal['ensayo'] = EnsayoMicro::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->get()->count();


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

                    return view('leca::EnsayoColiformes.ejecutar');

                } else {

                    $arrayTotal['blanco'] = false;
                    $arrayTotal['patron'] = false;
                    $arrayTotal['ensayo'] = 0;

                    return view('leca::EnsayoColiformes.index');

                }

            } else {

                $arrayTotal['blanco'] = true;
                $arrayTotal['patron'] = false;

                return view('leca::EnsayoColiformes.index');

            }

        } else {

            $arrayTotal['blanco'] = false;
            $arrayTotal['patron'] = false;

            return view('leca::EnsayoColiformes.index');
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

        $ensayo = Ensayo::where('lc_list_trials_id', 21)->where('estado', 'Pendiente')->get();

        foreach ($ensayo as $keya => $item) {

            array_push($arrayTercero, $item->lc_sample_taking_id);

        }
        
        $sample_takings = SampleTaking::with(['lcListTrials'])->where('estado_analisis', '!=', '')->where('estado_analisis', '!=', 'Análisis finalizado')->whereIn('id', $arrayTercero)->get();

        for ($i = 0; $i < count($sample_takings); $i++) {
            foreach ($sample_takings[$i]->lcListTrials as $keya => $item) {
                if ($item->id == 21) {
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

        $muestras = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 21)->where('estado', 'Activo')->get();

        if (count($muestras) > 0) {

            foreach ($muestras as $key => $value) {

                $value->estado = "Inactivo";

                $value->save();

            }
        }

        foreach ($request->toArray() as $key => $value) {

            $tendido = new TendidoMuestras();
            $tendido->users_id = $user->id;
            $tendido->lc_list_trials_id = 21;
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
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 21)->where('estado', 'Activo')->get();

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
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 21)->whereDate('fecha_finalizado', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Finalizado')->get();

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
        $blancoDecimales = Blancos::where('lc_list_trials_id', 21)->get()->last();
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
        $patronDecimales = Patron::where('lc_list_trials_id', 21)->get()->last();

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
        $ensayoDecimales = ColiformesTotales::where('lc_list_trials_id', 21)->get()->last();
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

        $dpr = new Dprpr();
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
        $dpr->lc_ensayo_aluminio_id = $request['id_ensayo'];
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

        $dpr = new Dprpr();
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
        $dpr->lc_ensayo_aluminio_id = $request['id_ensayo'];
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
    public function getDatosPr($id)
    {

        $dpr = Dprpr::where('id_ensayo', $id)->where('tipo', 'Porcentaje de recuperacion')->get();

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

        $dpr = Dprpr::where('id_ensayo', $id)->where('tipo', 'Diferencia porcentual relativa')->get();

        if ($dpr) {
            return $this->sendResponse($dpr, trans('data_obtained_successfully'));

        } else {
            return $this->sendResponse('no', trans('data_obtained_successfully'));

        }

    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Manuel Marin Londoño. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAllShowEnsayo(Request $request)
    {
        $idEnsayo = $request['id_muestra'];

        $muestra = SampleTaking::with(['programacion', 'lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->where('id', $idEnsayo)->get()->last();

        $ensayo = EnsayoMicro::with(['lcObservacionesDuplicadoMicros'])->where('id_muestra', $idEnsayo)->get();
        $ensayo->toArray();

        return $this->sendResponse($ensayo, trans('data_obtained_successfully'));
    }

    /**
     * Gyuarda el tendido de muestras
     *
     * @author Manuel Marin Londoño. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAllShowPatronBlanco(Request $request)
    {
        $idEnsayo = $request['id_muestra'];

        $muestra = SampleTaking::with(['programacion', 'lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->where('id', $idEnsayo)->get()->last();

        $ensayo = [];

        $ensayoA = EnsayoMicro::where('id', $request['id'])->get()->last();

        $date = Carbon::parse($ensayoA->created_at)->format('Y-m-d');
        $time = Carbon::parse($ensayoA->created_at)->toTimeString();

        $blanco = EnsayoMicro::whereDate('created_at', '<=', $date)->whereTime('created_at', '<', $time)->where('tipo', 'Blanco')->where('estado', 'Si')->get()->last();
        // $patron = EnsayoAluminio::whereDate('created_at', '<=', $date)->whereTime('created_at', '<', $time)->where('tipo', 'Patrón')->where('estado', 'Si')->get()->last();

        $ensayo['muestra'] = $muestra;
        $ensayo['blanco'] = $blanco;
        // $ensayo['patron'] = $patron;

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
    public function exportaExcelGoogle(Request $request)
    {
        dd($request);

        return $this->sendResponse($ensayo, trans('data_obtained_successfully'));
    }

    /**
     * Exporta excel
     *
     * @author Manuel Marin. - Enero. 04 - 2022
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
        $inputFileName = storage_path('app/public/leca/excel/Coliformes.xlsx');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);

        $date = strtotime($input[0]['created_at']);

        $datosPrimarios = ColiformesTotales::whereDate('created_at', '<=', $input[0]['created_at'])->get()->last();

        $anio = date("y", $date);
        $mes = date("m", $date);

        $spreadsheet->getActiveSheet()->setCellValue('J10', $datosPrimarios->ensayo ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('D10', $datosPrimarios->tecnica ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('B10', $datosPrimarios->año ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('C10', $datosPrimarios->mes ?? 'N/A');

        $cont = 0;
        $numTotal = 15 + count($input);
        $totalCeldas = count($input);

        if ($totalCeldas == 1) {

        } else {

            $spreadsheet->getActiveSheet()->insertNewRowBefore(16, $totalCeldas - 1);

        }

        for ($i = 15; $i < $numTotal; $i++) {

            $fechaSiembra = $input[$cont]['fecha_siembra'];

            if($fechaSiembra == null){
                $fechaSiembra = "N/A";
            } else {
                $variable = explode('-', $input[$cont]['fecha_siembra']);
                $varTres = explode(' ', $variable[2]);
                $fechaSiembra = $varTres[0];
            }

            $horaSiembra = $input[$cont]['hora_siembra'];
            if($horaSiembra == null){
                $horaSiembra = "N/A";
            }

            $fechaIncubacion = $input[$cont]['fecha_incubacion'];
            if($fechaIncubacion == null){
                $fechaIncubacion = "N/A";
            } else {
                $variable = explode('-', $input[$cont]['fecha_incubacion']);
                $varTres = explode(' ', $variable[2]);
                $fechaIncubacion = $varTres[0];
            }

            $horaIncubacion = $input[$cont]['hora_incubacion'];
            if($horaIncubacion == null){
                $horaIncubacion = "N/A";
            }

            $dilucionUtulizada = $input[$cont]['dilucion_utilizada'];
            if($dilucionUtulizada == null){
                $dilucionUtulizada = "N/A";
            }

            $consecutivo = $input[$cont]['consecutivo'];
            if($consecutivo == null){
                $consecutivo = "N/A";
            }
            $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':C' . $i);
            // $spreadsheet->getActiveSheet()->mergeCells('H' . $i . ':I' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('P' . $i . ':Q' . $i);

            $date = strtotime($input[$cont]['created_at']);

            $dia = date("d", $date);
            $hora = date("H", $date);
            $minutos = date("i", $date);
            $segundos = date("s", $date);

            $user = User::find($input[$cont]['users_id']);

            $spreadsheet->getActiveSheet()->setCellValue('D' . $i, $fechaSiembra ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, $consecutivo ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('E' . $i, $horaSiembra ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('F' . $i, $fechaIncubacion ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('G' . $i, $horaIncubacion ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('J' . $i, $dilucionUtulizada ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('K' . $i, $dia ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('B' . $i, $hora . ':' . $minutos . ':' . $segundos ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('L' . $i, $hora . ':' . $minutos . ':' . $segundos ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('M' . $i, 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('N' . $i, 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('O' . $i, $input[$cont]['resultado'] ?? 'N/A');
            if ($input[$cont]['estado'] == 'No') {

                $spreadsheet
                    ->getActiveSheet()
                    ->getStyle('O' . $i)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('dbafaf');

            } else {
                if ($input[$cont]['estado'] == 'Si') {
                    $spreadsheet
                        ->getActiveSheet()
                        ->getStyle('O' . $i)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('b1dbad');
                } else {
                    $spreadsheet
                        ->getActiveSheet()
                        ->getStyle('O' . $i)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ffffff');
                }
            }
            if ($user->url_digital_signature) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setPath(storage_path('app/public' . '/' . $user->url_digital_signature)); /* put your path and image here */
                $drawing->setCoordinates('H' . $i);
                $drawing->setWorksheet($spreadsheet->getActiveSheet());
                $drawing->setHeight(36);
                $drawing->setResizeProportional(true);
                $drawing->setOffsetX(2); // this is how
                $drawing->setOffsetY(2);
            } else {
                $spreadsheet->getActiveSheet()->setCellValue('H' . $i, 'N/A');
            }
            // $spreadsheet->getActiveSheet()->addImage('L'.$i, asset('storage').'/'. $user->url_digital_signature);
            if($user->name){
                $spreadsheet->getActiveSheet()->setCellValue('I' . $i, $user->name ?? 'N/A');
            } else {
                $spreadsheet->getActiveSheet()->setCellValue('I' . $i, 'N/A');
            }

            if ($user->url_digital_signature) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setPath(storage_path('app/public' . '/' . $user->url_digital_signature)); /* put your path and image here */
                $drawing->setCoordinates('P' . $i);
                $drawing->setWorksheet($spreadsheet->getActiveSheet());
                $drawing->setHeight(36);
                $drawing->setResizeProportional(true);
                $drawing->setOffsetX(2); // this is how
                $drawing->setOffsetY(2);
            } else {
                $spreadsheet->getActiveSheet()->setCellValue('P' . $i, 'N/A');
            }

            $cont++;

            // if ($user->url_digital_signature) {
            //     $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            //     $drawing->setPath(storage_path('app/public' . '/' . $user->url_digital_signature)); /* put your path and image here */
            //     $drawing->setCoordinates('P' . $i);
            //     $drawing->setWorksheet($spreadsheet->getActiveSheet());
            //     $drawing->setHeight(36);
            //     $drawing->setResizeProportional(true);
            //     $drawing->setOffsetX(2); // this is how
            //     $drawing->setOffsetY(2);
            // }
            // $spreadsheet->getActiveSheet()->setCellValue('Q' . $i, $user->name);
            // $cont++;
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
     * Envia la muestra principal
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getEnsayoPrincipal($id)
    {

        $ensayo = EnsayoAluminio::where('id_muestra', $id)->where('tipo', 'Ensayo')->first();

        return $this->sendResponse($ensayo, trans('data_obtained_successfully'));

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

        $ensayo = EnsayoMicro::find($request['id']);

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

        $blanco = EnsayoMicro::where('users_id', $user->id)->where('tipo', 'Blanco')->where('ensayo', 'Coliformes')->where('estado', 'Si')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where(function ($query) {
            $query->where('aprobacion_usuario', 'Pendiente')->orwhere('aprobacion_usuario', 'Si');
        })->get()->last();

        if ($blanco) {

            if ($blanco->aprobacion_usuario == 'Si') {

                $patron = EnsayoMicro::where('users_id', $user->id)->where('tipo', 'Blanco')->where('ensayo', 'Coliformes')->where('ensayo', 'Coliformes')->where('estado', 'Si')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where(function ($query) {
                    $query->where('aprobacion_usuario', 'Pendiente')->orwhere('aprobacion_usuario', 'Si');
                })->get()->last();
            } else {
                $patron = null;
            }

            if ($patron != null) {
                

                if ($patron->aprobacion_usuario == 'Si') {
                    $arrayTotal['ensayo'] = EnsayoMicro::where('users_id', $user->id)->where('ensayo', 'Coliformes')->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();
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
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param EnsayoMicro $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $Year = date("Y");
        $coliformeGeneral = ColiformesTotales::orderBy('id', 'desc')->first();

        if ($request['tipo'] == 'Blanco') {

            $datosBlanco = Blancos::where('lc_list_trials_id', 21)->get()->last();
            $result1 = floatval($datosBlanco->lcm);
            $result2 = floatval($request['resultado_general']);

            if ($result1 >= $result2) {
                $user = Auth::user();
                $cantidadBlancos = EnsayoMicro::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('ensayo', 'Coliformes')->where('tipo', 'Blanco')->count();
                $ensayo = new EnsayoMicro();
                $ensayo->estado = 'Si';
                $ensayo->aprobacion_usuario = 'Pendiente';
                $ensayo->ensayo = 'Coliformes';
                $ensayo->resultado = $request['resultado'];
                $ensayo->pozos_grandes = $request['p_grandes'];
                $ensayo->pozos_pequeños = $request['p_pequeños'];
                $ensayo->convertir_ufc = $request['convertir_ufc'];
                $ensayo->resultado_general = $request['resultado_general'];
                $ensayo->resultado_general_ufc = $request['resultado_general_ufc'];
                $ensayo->vigencia = $Year;
                $ensayo->lc_sample_taking_has_lc_list_trials_id = 21;
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
                $cantidadBlancos = EnsayoMicro::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('ensayo', 'Coliformes')->where('tipo', 'Blanco')->count();
                $ensayo = new EnsayoMicro();
                $ensayo->tecnica = $request['tecnica'];
                $ensayo->dilucion_utilizada = $request['dilucion_utilizada'];
                $ensayo->estado = 'No';
                $ensayo->aprobacion_usuario = 'No';
                $ensayo->ensayo = 'Coliformes';
                $ensayo->resultado = $request['resultado'];
                $ensayo->pozos_grandes = $request['p_grandes'];
                $ensayo->pozos_pequeños = $request['p_pequeños'];
                $ensayo->convertir_ufc = $request['convertir_ufc'];
                $ensayo->resultado_general = $request['resultado_general'];
                $ensayo->resultado_general_ufc = $request['resultado_general_ufc'];
                $ensayo->vigencia = $Year;
                $ensayo->lc_sample_taking_has_lc_list_trials_id = 21;
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

                return $this->sendResponse($ensayo->toArray(), trans('data_obtainedcoliformeGeneral_successfully'));

            }
        } else {

            if ($request['tipo'] == 'Patrón') {

                $datosPatron = Patron::where('lc_list_trials_id', 15)->get()->last();
                $result1 = floatval($datosPatron->lcs);
                $result2 = floatval($datosPatron->lci);
                $result3 = floatval($request['concentracion']);

                if ($result3 > $result2 && $result3 < $result1) {

                    $user = Auth::user();
                    $cantidadPatrones = EnsayoAluminio::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();
                    $ensayo = new EnsayoAluminio();
                    $ensayo->volumen = $request['volumen'];
                    $ensayo->curva = $request['curva'];

                    if ($request['concentracion'] > 0) {
                        $resta = $request['concentracion'] - $coliformeGeneral->valor_esperado;
                        if ($resta < 0) {
                            $resta = $resta * -1;
                        }
                        $porcentajeRecuperacion = ($resta / $coliformeGeneral->valor_esperado) * 100;
                    }

                    $ensayo->pr_inicio = $porcentajeRecuperacion;
                    $ensayo->estado = 'Si';
                    $ensayo->aprobacion_usuario = 'Pendiente';
                    $ensayo->vigencia = $Year;
                    $ensayo->pendiente = $request['pendiente'];
                    $ensayo->lc_aluminio_id = $coliformeGeneral->id;
                    $ensayo->intercepto = $request['intercepto'];
                    $ensayo->factor_disolucion = $request['fd'];
                    $ensayo->absorbancia = $request['absorbancia'];
                    $ensayo->concentracion = $request['concentracion'];
                    $ensayo->resultado = $request['resultado'];
                    $ensayo->observacion_analista = $request['observacion_analista'];
                    $ensayo->id_muestra = '';
                    if ($cantidadPatrones == 0) {
                        if ($request['addconsecutivo']) {
                            $ensayo->consecutivo = 'STD' . ' - ' . $request['addconsecutivo'];
                        } else {
                            $ensayo->consecutivo = 'STD';
                        }

                    } else {
                        if ($request['addconsecutivo']) {
                            $ensayo->consecutivo = 'STD' . $cantidadPatrones . ' - ' . $request['addconsecutivo'];
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
                    $cantidadPatrones = EnsayoAluminio::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();
                    $ensayo = new EnsayoAluminio();

                    if ($request['concentracion'] > 0) {
                        $resta = $request['concentracion'] - $coliformeGeneral->valor_esperado;
                        if ($resta < 0) {
                            $resta = $resta * -1;
                        }
                        $porcentajeRecuperacion = ($resta / $coliformeGeneral->valor_esperado) * 100;
                    }

                    $ensayo->pr_inicio = $porcentajeRecuperacion;

                    $ensayo->volumen = $request['volumen'];
                    $ensayo->curva = $request['curva'];
                    $ensayo->estado = 'No';
                    $ensayo->aprobacion_usuario = 'No';
                    $ensayo->vigencia = $Year;
                    $ensayo->pendiente = $request['pendiente'];
                    $ensayo->intercepto = $request['intercepto'];
                    $ensayo->factor_disolucion = $request['fd'];
                    $ensayo->lc_aluminio_id = $coliformeGeneral->id;
                    $ensayo->absorbancia = $request['absorbancia'];
                    $ensayo->concentracion = $request['concentracion'];
                    $ensayo->resultado = $request['resultado'];
                    $ensayo->observacion_analista = $request['observacion_analista'];
                    $ensayo->id_muestra = '';
                    if ($cantidadPatrones == 0) {
                        if ($request['addconsecutivo']) {
                            $ensayo->consecutivo = 'STD' . ' - ' . $request['addconsecutivo'];
                        } else {
                            $ensayo->consecutivo = 'STD';
                        }
                    } else {
                        if ($request['addconsecutivo']) {
                            $ensayo->consecutivo = 'STD' . $cantidadPatrones . ' - ' . $request['addconsecutivo'];
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

                    $blanco = EnsayoMicro::where('users_id', $user->id)->where('ensayo', 'Coliformes')->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                    if ($blanco) {

                        if ($blanco) {

                            $arrayTotal['ensayo'] = EnsayoMicro::where('users_id', $user->id)->where('ensayo', 'Coliformes')->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->get()->count();

                            $blancoGeneral = BlancoGeneral::get()->last();
                            $patronGeneral = PatronGeneral::get()->last();

                            if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                                $buscaEnsayo = Ensayo::where('lc_list_trials_id', 21)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();
                                $año = date('Y');
                                $array = str_split($año);

                                $ensayo = new EnsayoMicro();
                                $ensayo->ensayo = 'Coliformes';
                                $ensayo->hora_siembra = $request['hora_siembra'];
                                $ensayo->estado = 'Pendiente';
                                $ensayo->resultado = $request['resultado'];
                                $ensayo->hora_incubacion = $request['hora_incubacion'];
                                $ensayo->fecha_siembra = $request['fecha_siembra'];
                                $ensayo->fecha_incubacion = $request['fecha_incubacion'];
                                $ensayo->dilucion_utilizada = $request['dilucion_utilizada'];
                                $ensayo->hora_lectura = $request['hora_lectura'];
                                $ensayo->resultado_general = $request['resultado_general'];
                                $ensayo->resultado_general_ufc = $request['resultado_general_ufc'];
                                $ensayo->fecha_lectura = $request['fecha_lectura'];
                                $ensayo->convertir_ufc = $request['convertir_ufc'];
                                $ensayo->vigencia = $Year;
                                $ensayo->id_muestra = $request['idMuestra']['id'];
                                $ensayo->lc_sample_taking_has_lc_list_trials_id = $buscaEnsayo->id;
                                $ensayo->duplicado = $request['idMuestra']['duplicado'];

                                $ensayo->users_id = Auth::user()->id;
                                $ensayo->user_name = Auth::user()->name;

                                $ensayo->tipo = $request['tipo'];
                                $ensayo->save();

                                // $muestras = TendidoMuestras::where('users_id', $user->id)->where('lc_sample_taking_id', $request['idMuestra']['id'])->where('estado', 'Activo')->first();

                                // $muestras->estado = "Finalizado";
                                // $muestras->fecha_finalizado = Carbon::now()->format('Y-m-d');

                                // $muestras->save();

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

                        // $blanco = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                        // if ($blanco) {

                        //     $patron = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

                        //     if ($patron) {

                        //         $arrayTotal['ensayo'] = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

                        //     $blancoGeneral=BlancoGeneral::get()->last();
                        //     $patronGeneral=PatronGeneral::get()->last();

                        // if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                        $buscaEnsayo = Ensayo::where('lc_list_trials_id', 21)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();

                        $año = date('Y');

                        $array = str_split($año);
                        $ensayo = new EnsayoMicro();
                        $ensayo->fecha_siembra = $request['fecha_siembra'];
                        $ensayo->hora_siembra = $request['hora_siembra'];
                        $ensayo->lc_micro_id = $coliformeGeneral->id;
                        $ensayo->hora_siembra = $request['hora_siembra'];
                        $ensayo->estado = 'No aplica';
                        $ensayo->aprobacion_usuario = 'No aplica';
                        $ensayo->vigencia = $Year;
                        $ensayo->ensayo = 'Coliformes';
                        $ensayo->fecha_incubacion = $request['fecha_incubacion'];
                        $ensayo->hora_incubacion = $request['hora_incubacion'];
                        $ensayo->dilucion_utilizada = $request['dilucion_utilizada'];
                        $ensayo->fecha_lectura = $request['fecha_lectura'];
                        $ensayo->hora_lectura = $request['hora_lectura'];
                        $ensayo->resultado = $request['resultadoGFinal'];
                        $ensayo->pozos_grandes = $request['p_grandes'];
                        $ensayo->convertir_ufc = $request['convertir_ufc'];
                        $ensayo->pozos_pequeños = $request['p_pequeños'];
                        $ensayo->resultado_general = $request['resultado_general'];
                        $ensayo->resultado_general_ufc = $request['resultadoUfcB'];
                        $ensayo->duplicado = $request['idMuestra']['duplicado'];
                        $ensayo->id_muestra = $request['idMuestra']['id_muestra'];
                        $ensayo->consecutivo_ensayo = $request['idMuestra']['consecutivo'];
                        $muestraActualizar = SampleTaking::where('id', $request['idMuestra']['id_muestra'])->get();
                        $ensayo->lc_sample_taking_has_lc_list_trials_id = $request['idMuestra']['id'];

                        if ($request['metodo'] == "Duplicado") {
                            $duplicado = EnsayoMicro::where('users_id', $user->id)->where('tipo', 'Duplicado')->where('ensayo', 'Coliformes')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();
                            if ($duplicado >= 1) {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D' . $duplicado;
                            } else {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D';
                            }
                            $ensayo->tipo = $request['tipo'];
                            $ensayo->metodo = "Duplicado";

                        } else {
                            if ($request['metodo'] == "Repetición") {
                                $duplicado = EnsayoMicro::where('users_id', $user->id)->where('metodo', 'Repetición')->where('ensayo', 'Coliformes')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();
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

                                    $duplicado = EnsayoMicro::where('users_id', $user->id)->where('ensayo', 'Coliformes')->where('metodo', 'Regla de decisión')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();

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

                        $history = new HistorySampleTaking();
                        $history->action = "Ejecuto duplicado";
                        $history->users_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->consecutivo = $ensayo->consecutivo;
                        $history->observation = "Se realizo duplicado a la muestra de coliformes";
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
     * Exporta grafico
     *
     * @author Manuel Marin Londoño - 2022
     * @version 1.0.0
     *
     * @param EnsayoMicro $request
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
        $contBk=0;

        //Este es el titular del ensayo
        $data[] = ["range" => "Coli. Totales!B7", "values" => [["Coliformes totales"]]];


        $fechaActual = date('d-m-Y');
        $anio = date("y", strtotime($fechaActual));
        $mes = date("m", strtotime($fechaActual));

        $data[] = ["range" => "Coli. Totales!V8", "values" => [[$mes]]];
        $data[] = ["range" => "Coli. Totales!V7", "values" => [[$anio]]];

        //Aqui se recorre la data que llega desde la vista de cada ensayo
        foreach ($request->data as $keya => $item){ 
            //Se valida si el dato es blanco
            if($item["tipo"]=="Blanco") {

                $contBk++;

                //Aqui se llena el arreglo para los primeros 31 registros
                if($contBk<=31){
                    if($item["resultado_general"] == null){
                        $item["resultado_general"] = 0;
                    }
                    array_push($arrayBk, $item["resultado_general"]);
                    $dia = date("d", strtotime($item["created_at"]));
                    array_push($arrayBkF,  $dia);
                    array_push($arrayBkC, $item["consecutivo"]." / ".$item["users_id"]);
                }else{
                    //Aqui se llena el arreglo para los otros 31 registros
                    if($contBk<=62 && $contBk>31){
                        if($item["resultado_general"] == null){
                            $item["resultado_general"] = 0;
                        }
                        array_push($arrayBk2,  $item["resultado_general"]);
                        $dia = date("d", strtotime($item["created_at"]));
                        array_push($arrayBk2F,  $dia);
                        array_push($arrayBk2C, $item["consecutivo"]." / ".$item["users_id"]);
                    }
                }
            }
            
        }

        if(count($arrayBk)>0){
            $data[] = ["range" => "Coli. Totales!B28", "values" => [$arrayBk]];
            $data[] = ["range" => "Coli. Totales!B29", "values" => [$arrayBkF]];
            $data[] = ["range" => "Coli. Totales!B30", "values" => [$arrayBkC]];
        }

        if(count($arrayBk2)>0){
            $data[] = ["range" => "Coli. Totales!B33", "values" => [$arrayBk2]];
            $data[] = ["range" => "Coli. Totales!B34", "values" => [$arrayBk2F]];
            $data[] = ["range" => "Coli. Totales!B35", "values" => [$arrayBk2C]];
        }

        $fileIdCopy = $e->editFileExcelBatch("1mIUzhxh5bLzQnz7cxjsxQkfDnXzQm9o-T-UN9qtbs-E", $data, true);

        $e->downloadFileGoogleDrive($fileIdCopy, "Ensayo Coliformes Totales", "excel", true);
    }



    /**
     * Exporta grafico
     *
     * @author Manuel Marin Londoño - 2022
     * @version 1.0.0
     *
     * @param EnsayoMicro $request
     *
     * @return Response
     */
    public function getIp(Request $request)
    {
        $e = new GoogleController();
        //Array de blanco
        $arrayDprA = [];
        $arrayDprA2 = [];
        $arrayDprB = [];
        $arrayDprB2 = [];
        $arrayDprF = [];
        $arrayDprF2 = [];
        $arrayDprC = [];
        $arrayDprC2 = [];

        //Contadores
        $contDpr=0;
        $contador = 0;
        $contaultimo = 0;

        //Este es el titular del ensayo
        $data[] = ["range" => "MARZO!B7", "values" => [["Coliformes Totales"]]];

        $fechaActual = date('d-m-Y');
        $anio = date("y", strtotime($fechaActual));
        $mes = date("m", strtotime($fechaActual));

        $data[] = ["range" => "MARZO!V8", "values" => [[$mes]]];
        $data[] = ["range" => "MARZO!V7", "values" => [[$anio]]];

        //Aqui se recorre la data que llega desde la vista de cada ensayo
        foreach ($request->data as $keya => $item){ 
            //Se valida si el dato es blanco
            if($item["tipo"]=="Duplicado"){
                $contaultimo++;
                if($contador == 0) {
    
                    $contDpr++;
                    if($contDpr<=31){
                        $infoEnsayo = EnsayoMicro::where('consecutivo', $item['consecutivo_ensayo'])->where('tipo', 'Ensayo')->where('ensayo', 'Coliformes')->get()->first();
                        array_push($arrayDprC, $item["consecutivo_ensayo"]." / ".$item["users_id"]);
                        $dia = date("d", strtotime($item["created_at"]));
                        array_push($arrayDprF, $dia);
                        array_push($arrayDprA, $infoEnsayo['resultado_general']);
                        array_push($arrayDprB, $item['resultado_general']);
                    }else{
                        $infoEnsayo = EnsayoMicro::where('consecutivo', $item['consecutivo_ensayo'])->where('tipo', 'Ensayo')->where('ensayo', 'Coliformes')->get()->first();
                        array_push($arrayDprC2, $item["consecutivo_ensayo"]." / ".$item["users_id"]);
                        $dia = date("d", strtotime($item["created_at"]));
                        array_push($arrayDprF2, $dia);
                        array_push($arrayDprA2, $infoEnsayo['resultado_general']);
                        array_push($arrayDprB2, $item['resultado_general']);
                    }
                $contador++;
                } else {
                    if (!(in_array($item['consecutivo_ensayo']." / ".$item["users_id"], $arrayDprC) || in_array($item['consecutivo_ensayo']." / ".$item["users_id"], $arrayDprC2))) {
        
                        $contDpr++;
                        if($contDpr<=31){
                            $infoEnsayo = EnsayoMicro::where('consecutivo', $item['consecutivo_ensayo'])->where('tipo', 'Ensayo')->where('ensayo', 'Coliformes')->get()->first();
                            array_push($arrayDprC, $item["consecutivo_ensayo"]." / ".$item["users_id"]);
                            $dia = date("d", strtotime($item["created_at"]));
                            array_push($arrayDprF, $dia);
                            array_push($arrayDprA, $infoEnsayo['resultado_general']);
                            array_push($arrayDprB, $item['resultado_general']);
                        }else{
                            $infoEnsayo = EnsayoMicro::where('consecutivo', $item['consecutivo_ensayo'])->where('tipo', 'Ensayo')->where('ensayo', 'Coliformes')->get()->first();
                            array_push($arrayDprC2, $item["consecutivo_ensayo"]." / ".$item["users_id"]);
                            $dia = date("d", strtotime($item["created_at"]));
                            array_push($arrayDprF2, $dia);
                            array_push($arrayDprA2, $infoEnsayo['resultado_general']);
                            array_push($arrayDprB2, $item['resultado_general']);
                        }
                    }
                }
            }
        }

        if(count($arrayDprA)>0){
            $data[] = ["range" => "MARZO!B28", "values" => [$arrayDprA]];
            $data[] = ["range" => "MARZO!B29", "values" => [$arrayDprB]];
            $data[] = ["range" => "MARZO!B30", "values" => [$arrayDprF]];
            $data[] = ["range" => "MARZO!B31", "values" => [$arrayDprC]];
        }

        if(count($arrayDprA2)>0){
            $data[] = ["range" => "MARZO!B34", "values" => [$arrayDprA2]];
            $data[] = ["range" => "MARZO!B35", "values" => [$arrayDprB2]];
            $data[] = ["range" => "MARZO!B36", "values" => [$arrayDprF2]];
            $data[] = ["range" => "MARZO!B37", "values" => [$arrayDprC2]];
        }
        // dd($data);

        $fileIdCopy = $e->editFileExcelBatch("1tc9aa2ETNAcBOYYiQhTSU4R9cf7tofah8M3VAG9Cfaw", $data, true);

        $e->downloadFileGoogleDrive($fileIdCopy, "Ensayo Coliformes", "excel", true);
    }

}