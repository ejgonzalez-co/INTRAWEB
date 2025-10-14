<?php

namespace Modules\Leca\Http\Controllers;

use Auth;
use App\User;
use Response;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Exports\GenericExport;
use Illuminate\Support\Carbon;
use Modules\Leca\Models\Dprpr;
use Modules\Leca\Models\Ensayo;
use Modules\Leca\Models\Patron;
use Modules\Leca\Models\Blancos;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Leca\Models\Aluminio;
use Modules\Leca\Models\ListTrials;
use Modules\Leca\Models\SampleTaking;
use App\Http\Controllers\GoogleController;
use Modules\Leca\Models\BlancoGeneral;
use Modules\Leca\Models\PatronGeneral;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\EnsayoAluminio;
use Modules\Leca\Models\TendidoMuestras;
use Modules\Leca\Models\CriticalEquipment;
use Modules\Leca\Models\HistorySampleTaking;

class EnsayoAluminioController extends AppBaseController
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

        return view('leca::EnsayoAluminio.index');

    }

    public function getDatosBlanco()
    {

        $datosBlanco = Aluminio::orderBy('id', 'desc')->first();

        return $this->sendResponse($datosBlanco->toArray(), trans('data_obtained_successfully'));
    }

    public function getCartaDatosBlanco()
    {

        $datosBlanco = Blancos::where('lc_list_trials_id', 15)->get()->last();

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
    public function update($id, EnsayoAluminio $request)
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
    public function getAllAluminio()
    {
        if (!Auth::user()->hasRole('Administrador Leca')) {
            $user = Auth::user();

            $allEnsayos = EnsayoAluminio::with("dprPr")->where('users_id', $user->id)->latest()->get();
        } else {
            $allEnsayos = EnsayoAluminio::with("dprPr")->latest()->get();
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

        $blanco = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('aprobacion_usuario', 'Si')->get()->last();

        if ($blanco) {

            $patron = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('aprobacion_usuario', 'Si')->get()->last();

            if ($patron) {

                $arrayTotal['ensayo'] = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

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

                    return view('leca::EnsayoAluminio.ejecutar');

                } else {

                    $arrayTotal['blanco'] = false;
                    $arrayTotal['patron'] = false;
                    $arrayTotal['ensayo'] = 0;

                    return view('leca::EnsayoAluminio.index');

                }

            } else {

                $arrayTotal['blanco'] = true;
                $arrayTotal['patron'] = false;

                return view('leca::EnsayoAluminio.index');

            }

        } else {

            $arrayTotal['blanco'] = false;
            $arrayTotal['patron'] = false;

            return view('leca::EnsayoAluminio.index');
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

        $ensayo = Ensayo::where('lc_list_trials_id', 15)->where('estado', 'Pendiente')->get();

        foreach ($ensayo as $keya => $item) {

            array_push($arrayTercero, $item->lc_sample_taking_id);

        }

        $sample_takings = SampleTaking::with(['lcListTrials'])->where('estado_analisis', '!=', '')->where('estado_analisis', '!=', 'Análisis finalizado')->whereIn('id', $arrayTercero)->get();

        for ($i = 0; $i < count($sample_takings); $i++) {
            foreach ($sample_takings[$i]->lcListTrials as $keya => $item) {
                if ($item->id == 15) {
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

        $muestras = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 15)->where('estado', 'Activo')->get();

        if (count($muestras) > 0) {

            foreach ($muestras as $key => $value) {

                $value->estado = "Inactivo";

                $value->save();

            }
        }

        foreach ($request->toArray() as $key => $value) {

            $tendido = new TendidoMuestras();
            $tendido->users_id = $user->id;
            $tendido->lc_list_trials_id = 15;
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
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 15)->where('estado', 'Activo')->get();

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
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 15)->whereDate('fecha_finalizado', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Finalizado')->get();

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
        $blancoDecimales = Blancos::where('lc_list_trials_id', 15)->get()->last();
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
        $patronDecimales = Patron::where('lc_list_trials_id', 15)->get()->last();

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
        $ensayoDecimales = Aluminio::where('lc_list_trials_id', 15)->get()->last();
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
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAllShowEnsayo(Request $request)
    {
        $idEnsayo = $request['id_muestra'];

        $muestra = SampleTaking::with(['programacion', 'lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->where('id', $idEnsayo)->get()->last();

        $ensayo = EnsayoAluminio::with(['dprPr', 'observaciones'])->where('id_muestra', $idEnsayo)->get();
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

        $ensayoA = EnsayoAluminio::where('id', $request['id'])->get()->last();

        $date = Carbon::parse($ensayoA->created_at)->format('Y-m-d');
        $time = Carbon::parse($ensayoA->created_at)->toTimeString();

        $blanco = EnsayoAluminio::whereDate('created_at', '<=', $date)->whereTime('created_at', '<', $time)->where('tipo', 'Blanco')->where('estado', 'Si')->get()->last();
        $patron = EnsayoAluminio::whereDate('created_at', '<=', $date)->whereTime('created_at', '<', $time)->where('tipo', 'Patrón')->where('estado', 'Si')->get()->last();

        $ensayo['muestra'] = $muestra;
        $ensayo['blanco'] = $blanco;
        $ensayo['patron'] = $patron;

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
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
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
        $inputFileName = storage_path('app/public/leca/excel/Aluminio.xlsx');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);

        $date = strtotime($input[0]['created_at']);

        $datosPrimarios = Aluminio::whereDate('created_at', '<=', $input[0]['created_at'])->get()->last();

        $anio = date("y", $date);
        $mes = date("m", $date);

        $spreadsheet->getActiveSheet()->setCellValue('D9', $datosPrimarios->ensayo ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('D7', $datosPrimarios->processo ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('H11', $datosPrimarios->k_pendiente ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('J11', $datosPrimarios->b_intercepto ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('D17', $datosPrimarios->nombre_patron ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('D19', $datosPrimarios->valor_esperado.'mg/L' ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('A23', $datosPrimarios->limite_cuantificacion ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('F23', $datosPrimarios->curva_numero ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('H23', $datosPrimarios->fecha_curva ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('I9', $datosPrimarios->documento_referencia ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('K7', $datosPrimarios->año ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('M7', $datosPrimarios->mes ?? 'N/A');

        $criticalEquip = CriticalEquipment::where('lc_list_trials_id', 15)->latest()->take(4)->get();

        $letra = "D";
        for ($i = 0; $i < count($criticalEquip); $i++) {

            $spreadsheet->getActiveSheet()->setCellValue($letra . '14', $criticalEquip[$i]->equipo_critico ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue($letra . '15', $criticalEquip[$i]->identificacion ?? 'N/A');
            $letra++;
            $letra++;
        }

        $cont = 0;
        $numTotal = 25 + count($input);
        $totalCeldas = count($input);
        if ($totalCeldas == 1) {

        } else {

            $spreadsheet->getActiveSheet()->insertNewRowBefore(26, $totalCeldas - 1);

        }

        $arraydpr = array();
        for ($i = 25; $i < $numTotal; $i++) {

            $spreadsheet->getActiveSheet()->mergeCells('C' . $i . ':D' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('G' . $i . ':I' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('J' . $i . ':K' . $i);

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
            if ($input[$cont]['estado'] == 'No') {

                $spreadsheet
                    ->getActiveSheet()
                    ->getStyle('J' . $i)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('dbafaf');

            } else {
                if ($input[$cont]['estado'] == 'Si') {
                    $spreadsheet
                        ->getActiveSheet()
                        ->getStyle('J' . $i)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('b1dbad');
                } else {
                    $spreadsheet
                        ->getActiveSheet()
                        ->getStyle('J' . $i)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ffffff');
                }
            }

            if ($input[$cont]['tipo'] == 'Duplicado') {

                $dprpr = Dprpr::where('lc_ensayo_aluminio_id', $input[$cont]['id'])->get();

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

        if (count($arraydpr) > 0) {

            $contPorcentaje = 0;
            $contDpr = 0;

            $arraydpr = array_reverse($arraydpr);
            $letraP = "J";
            $letraD = "J";
            $letraG = "J";
            for ($u = 0; $u < count($arraydpr); $u++) {

                if ($arraydpr[$u]->tipo == 'Diferencia porcentual relativa' && $contDpr < 4) {
                    $spreadsheet->getActiveSheet()->setCellValue($letraD . '21', $arraydpr[$u]->resultado . '%' ?? 'N/A');
                    $spreadsheet->getActiveSheet()->setCellValue($letraD . '23', $dia ?? 'N/A');
                    $letraD++;
                } else {
                    if ($arraydpr[$u]->tipo == 'Porcentaje de recuperacion' && $contPorcentaje < 4) {
                        $spreadsheet->getActiveSheet()->setCellValue($letraP . '18', $arraydpr[$u]->resultado . '%' ?? 'N/A');
                        $spreadsheet->getActiveSheet()->setCellValue($letraP . '23', $dia ?? 'N/A');
                        $letraP++;
                    }
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
     * Envia la muestra principal
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getEnsayoPrincipal($id)
    {

        $dprs = $id - 1;
        $contador = 0;

        $ensayo = EnsayoAluminio::where('id', $dprs)->first();
        if($ensayo->tipo != "Duplicado" && $ensayo->tipo != "Ensayo"){
            $ensayo = EnsayoAluminio::where('tipo', "Duplicado")->latest()->get();
            foreach($ensayo as $ensa){
                if($ensa->id == $id){
                    $contador ++;
                } else if($contador == 1){
                    $ensayo = $ensa;
                    $contador ++;
                }
            }
        }

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

        $ensayo = EnsayoAluminio::find($request['id']);

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

        $blanco = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Blanco')->where('estado', 'Si')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where(function ($query) {
            $query->where('aprobacion_usuario', 'Pendiente')->orwhere('aprobacion_usuario', 'Si');
        })->get()->last();

        if ($blanco) {

            if ($blanco->aprobacion_usuario == 'Si') {

                $patron = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Patrón')->where('estado', 'Si')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where(function ($query) {
                    $query->where('aprobacion_usuario', 'Pendiente')->orwhere('aprobacion_usuario', 'Si');
                })->get()->last();
            } else {
                $patron = null;
            }

            if ($patron != null) {

                if ($patron->aprobacion_usuario == 'Si') {
                    $arrayTotal['ensayo'] = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();
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
     * @param EnsayoAluminio $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $Year = date("Y");
        $aluminioGeneral = Aluminio::orderBy('id', 'desc')->first();

        if ($request['tipo'] == 'Blanco') {

            $datosBlanco = Blancos::where('lc_list_trials_id', 15)->get()->last();
            $result1 = floatval($datosBlanco->lcm);
            $result2 = floatval($request['concentracion']);

            if ($result1 > $result2) {

                $user = Auth::user();
                $cantidadBlancos = EnsayoAluminio::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Blanco')->count();
                $ensayo = new EnsayoAluminio();
                $ensayo->volumen = $request['volumen'];
                $ensayo->curva = $request['curva'];
                $ensayo->estado = 'Si';
                $ensayo->id_carta = $datosBlanco->id;
                $ensayo->aprobacion_usuario = 'Pendiente';
                $ensayo->pendiente = $request['pendiente'];
                $ensayo->intercepto = $request['intercepto'];
                $ensayo->vigencia = $Year;
                $ensayo->factor_disolucion = $request['fd'];
                $ensayo->lc_aluminio_id = $aluminioGeneral->id;
                $ensayo->absorbancia = $request['absorbancia'];
                $ensayo->concentracion = $request['concentracion'];
                $ensayo->resultado = $request['resultado'];
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
                $cantidadBlancos = EnsayoAluminio::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Blanco')->count();
                $ensayo = new EnsayoAluminio();
                $ensayo->volumen = $request['volumen'];
                $ensayo->curva = $request['curva'];
                $ensayo->estado = 'No';
                $ensayo->aprobacion_usuario = 'No';
                $ensayo->pendiente = $request['pendiente'];
                $ensayo->intercepto = $request['intercepto'];
                $ensayo->vigencia = $Year;
                $ensayo->id_carta = $datosBlanco->id;
                $ensayo->lc_aluminio_id = $aluminioGeneral->id;
                $ensayo->factor_disolucion = $request['fd'];
                $ensayo->absorbancia = $request['absorbancia'];
                $ensayo->concentracion = $request['concentracion'];
                $ensayo->resultado = $request['resultado'];
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

                $datosPatron = Patron::where('lc_list_trials_id', 15)->get()->last();
                $result1 = floatval($datosPatron->lcs);
                $result2 = floatval($datosPatron->lci);
                $datosPrim = Aluminio::where('lc_list_trials_id', 15)->get()->last();
                $result3 = floatval($request['concentracion'])/$datosPrim->valor_esperado*100;

                if ($result3 > $result2 && $result3 < $result1) {

                    $user = Auth::user();
                    if ($request['tipo_patron'] == 'Patrón') {
                        $cantidadPatrones = EnsayoAluminio::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();
                    } else {
                        $cantidadPatrones = EnsayoAluminio::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'LCM')->count();
                    }

                    $ensayo = new EnsayoAluminio();
                    $ensayo->volumen = $request['volumen'];
                    $ensayo->curva = $request['curva'];

                    if ($request['concentracion'] > 0) {
                        $resta = $request['concentracion'] - $aluminioGeneral->valor_esperado;
                        if ($resta < 0) {
                            $resta = $resta * -1;
                        }
                        $porcentajeRecuperacion = ($resta / $aluminioGeneral->valor_esperado) * 100;
                    }
                    $ensayo->id_carta = $datosPatron->id;
                    $ensayo->pr_inicio = $porcentajeRecuperacion;
                    $ensayo->estado = 'Si';
                    $ensayo->aprobacion_usuario = 'Pendiente';
                    $ensayo->vigencia = $Year;
                    $ensayo->pendiente = $request['pendiente'];
                    $ensayo->lc_aluminio_id = $aluminioGeneral->id;
                    $ensayo->intercepto = $request['intercepto'];
                    $ensayo->cant_concentracion = $request['addconsecutivo'];
                    $ensayo->tipo_patron = $request['tipo_patron'];
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
                    if ($request['tipo_patron'] == 'Patrón') {
                        $ensayo->tipo = 'Patrón';
                    } else {
                        $ensayo->tipo = 'LCM';
                    }

                    $ensayo->save();

                    return $this->sendResponse($ensayo->toArray(), trans('data_obtained_successfully'));

                } else {

                    $user = Auth::user();

                    if ($request['tipo_patron'] == 'Patrón') {
                        $cantidadPatrones = EnsayoAluminio::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();
                    } else {
                        $cantidadPatrones = EnsayoAluminio::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'LCM')->count();
                    }

                    if ($request['concentracion'] > 0) {
                        $resta = $request['concentracion'] - $aluminioGeneral->valor_esperado;
                        if ($resta < 0) {
                            $resta = $resta * -1;
                        }
                        $porcentajeRecuperacion = ($resta / $aluminioGeneral->valor_esperado) * 100;
                    }
                    $ensayo = new EnsayoAluminio();
                    $ensayo->pr_inicio = $porcentajeRecuperacion;

                    $ensayo->volumen = $request['volumen'];
                    $ensayo->curva = $request['curva'];
                    $ensayo->estado = 'No';
                    $ensayo->aprobacion_usuario = 'No';
                    $ensayo->vigencia = $Year;
                    $ensayo->id_carta = $datosPatron->id;
                    $ensayo->pendiente = $request['pendiente'];
                    $ensayo->intercepto = $request['intercepto'];
                    $ensayo->tipo_patron = $request['tipo_patron'];
                    $ensayo->cant_concentracion = $request['addconsecutivo'];
                    $ensayo->factor_disolucion = $request['fd'];
                    $ensayo->lc_aluminio_id = $aluminioGeneral->id;
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

                    if ($request['tipo_patron'] == 'Patrón') {
                        $ensayo->tipo = 'Patrón';
                    } else {
                        $ensayo->tipo = 'LCM';
                    }

                    $ensayo->save();

                    return $this->sendResponse($ensayo->toArray(), trans('data_obtained_successfully'));

                }
            } else {

                if ($request['tipo'] == 'Ensayo') {
                    $user = Auth::user();

                    $blanco = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                    if ($blanco) {

                        $patron = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

                        if ($patron) {

                            $arrayTotal['ensayo'] = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

                            $blancoGeneral = BlancoGeneral::get()->last();
                            $patronGeneral = PatronGeneral::get()->last();

                            if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                                $centinela = false;
                                $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->get();
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
                                    $history->observation = "Se realizo el analisis de la fórmula de aluminio";

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
                                    $history->observation = "Se realizo el analisis de la fórmula de aluminio";

                                    $history->lc_sample_taking_id = $request['idMuestra']['id'];

                                    $history->save();
                                    $muestraActualizar[0]->estado_analisis = 'Análisis en ejecución';
                                    $muestraActualizar[0]->save();
                                }
                                $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->where('lc_list_trials_id', 15)->get();
                                $verifica[0]->estado = 'Ejecutado';
                                $verifica[0]->save();

                                $buscaEnsayo = Ensayo::where('lc_list_trials_id', 15)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();
                                $año = date('Y');
                                $array = str_split($año);

                                $ensayo = new EnsayoAluminio();
                                $ensayo->volumen = $request['volumen'];
                                $ensayo->curva = $request['curva'];
                                $ensayo->estado = 'No aplica';
                                $ensayo->pendiente = $request['pendiente'];
                                $ensayo->intercepto = $request['intercepto'];
                                $ensayo->factor_disolucion = $request['fd'];
                                $ensayo->lc_aluminio_id = $aluminioGeneral->id;
                                $ensayo->absorbancia = $request['absorbancia'];
                                $ensayo->concentracion = $request['concentracion'];
                                $ensayo->resultado = $request['resultado'];
                                $ensayo->vigencia = $Year;
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

                        // $blanco = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                        // if ($blanco) {

                        //     $patron = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

                        //     if ($patron) {

                        //         $arrayTotal['ensayo'] = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

                        //     $blancoGeneral=BlancoGeneral::get()->last();
                        //     $patronGeneral=PatronGeneral::get()->last();

                        // if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                        $buscaEnsayo = Ensayo::where('lc_list_trials_id', 15)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();

                        $año = date('Y');

                        $array = str_split($año);
                        $ensayo = new EnsayoAluminio();
                        $ensayo->volumen = $request['volumen'];
                        $ensayo->curva = $request['curva'];
                        $ensayo->lc_aluminio_id = $aluminioGeneral->id;
                        $ensayo->estado = 'No aplica';
                        $ensayo->pendiente = $request['pendiente'];
                        $ensayo->intercepto = $request['intercepto'];
                        $ensayo->factor_disolucion = $request['fd'];
                        $ensayo->absorbancia = $request['absorbancia'];
                        $ensayo->vigencia = $Year;
                        $ensayo->concentracion = $request['concentracion'];
                        $ensayo->resultado = $request['resultado'];
                        $ensayo->duplicado = $request['idMuestra']['duplicado'];
                        $ensayo->id_muestra = $request['idMuestra']['id_muestra'];
                        $ensayo->consecutivo_ensayo = $request['idMuestra']['consecutivo'];
                        $muestraActualizar = SampleTaking::where('id', $request['idMuestra']['id_muestra'])->get();
                        $ensayo->lc_sample_taking_has_lc_list_trials_id = $request['idMuestra']['id'];

                        if ($request['metodo'] == "Duplicado") {
                            $duplicado = EnsayoAluminio::where('users_id', $user->id)->where('tipo', 'Duplicado')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();
                            if ($duplicado >= 1) {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D' . $duplicado;
                            } else {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D';
                            }
                            $ensayo->tipo = $request['tipo'];
                            $ensayo->metodo = "Duplicado";

                        } else {
                            if ($request['metodo'] == "Repetición") {
                                $duplicado = EnsayoAluminio::where('users_id', $user->id)->where('metodo', 'Repetición')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();
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

                                    $duplicado = EnsayoAluminio::where('users_id', $user->id)->where('metodo', 'Regla de decisión')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();

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
                        $history->observation = "Se realizo duplicado a la muestra de aluminio";
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
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param EnsayoAluminio $request
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
        //Array de patron
        $arrayStd = [];
        $arrayStd2 = [];
        $arrayStdF = [];
        $arrayStd2F = [];
        $arrayStdC = [];
        $arrayStd2C = [];
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

        //Array de adicionado
        $arrayAdd = [];
        $arrayAdd2 = [];
        $arrayAddF = [];
        $arrayAdd2F = [];
        $arrayAddC = [];
        $arrayAdd2C = [];
        //Contadores
        $contBk=0;
        $contStd=0;
        $contLcm=0;
        $contDpr=0;
        $contAdicionado=0;

        //Este es el titular del ensayo
        $data[] = ["range" => "BK!B7", "values" => [["Aluminio"]]];
        $data[] = ["range" => "STD!E7", "values" => [["Aluminio"]]];
        $data[] = ["range" => "%S std 0,20!E7", "values" => [["Aluminio"]]];
        $data[] = ["range" => "LCM!E7", "values" => [["Aluminio"]]];
        $data[] = ["range" => "DPR!B7", "values" => [["Aluminio"]]];
        $data[] = ["range" => "Adicionado!E7", "values" => [["Aluminio"]]];

        //Se valida la fecha de expedicion
        $fechaActual = date('d-m-Y');
        $anio = date("y", strtotime($fechaActual));
        $mes = date("m", strtotime($fechaActual));


        $blanco = Blancos::where('lc_list_trials_id', 15)->get()->last();

        $data[] = ["range" => "BK!V8", "values" => [[$mes]]];
        $data[] = ["range" => "BK!V7", "values" => [[$anio]]];
        $data[] = ["range" => "BK!B37", "values" => [[$blanco->ldm]]];
        $data[] = ["range" => "BK!B38", "values" => [[$blanco->lcm]]];


        $ensayo = Aluminio::orderBy('id', 'desc')->first();

        $data[] = ["range" => "STD!P8", "values" => [[$mes]]];
        $data[] = ["range" => "STD!V8", "values" => [[$anio]]];
        $data[] = ["range" => "STD!E8", "values" => [[$ensayo->valor_esperado]]];

        $data[] = ["range" => "%S std 0,20!P8", "values" => [[$mes]]];
        $data[] = ["range" => "%S std 0,20!V8", "values" => [[$anio]]];
        $data[] = ["range" => "%S std 0,20!E8", "values" => [[$ensayo->valor_esperado]]];
        
        
        $patron = Patron::where('lc_list_trials_id', 15)->get()->last();
        //se validan los datos de la carta anterior
        $data[] = ["range" => "STD!AJ14", "values" => [[$patron->n_anterior]]];
        $data[] = ["range" => "STD!AJ15", "values" => [[$patron->x_anterior]]];
        $data[] = ["range" => "STD!AJ16", "values" => [[$patron->s_anterior]]];

        $data[] = ["range" => "%S std 0,20!AJ14", "values" => [[$patron->n_ant_porc_std]]];
        $data[] = ["range" => "%S std 0,20!AJ15", "values" => [[$patron->x_ant_porc_std]]];
        $data[] = ["range" => "%S std 0,20!AJ16", "values" => [[$patron->s_ant_porc_std]]];
        $data[] = ["range" => "%S std 0,20!AI17", "values" => [[$patron->lcs_porc_std]]];
        $data[] = ["range" => "%S std 0,20!AI18", "values" => [[$patron->lci_porc_std]]];
        $data[] = ["range" => "%S std 0,20!AI19", "values" => [[$patron->las_porc_std]]];
        $data[] = ["range" => "%S std 0,20!AI20", "values" => [[$patron->lai_porc_std]]];
        $data[] = ["range" => "%S std 0,20!AI21", "values" => [[$patron->x_mas_porc_std]]];
        $data[] = ["range" => "%S std 0,20!AI22", "values" => [[$patron->x_menos_porc_std]]];


        $data[] = ["range" => "LCM!P8", "values" => [[$mes]]];
        $data[] = ["range" => "LCM!V8", "values" => [[$anio]]];
        $data[] = ["range" => "LCM!E8", "values" => [[$ensayo->valor_esperado_lcm]]];

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

        
        $data[] = ["range" => "Adicionado!AJ14", "values" => [[$patron->n_ant_adicionado]]];
        $data[] = ["range" => "Adicionado!AJ15", "values" => [[$patron->x_ant_adicionado]]];
        $data[] = ["range" => "Adicionado!AJ16", "values" => [[$patron->s_ant_adicionado]]];

        $data[] = ["range" => "Adicionado!P8", "values" => [[$mes]]];
        $data[] = ["range" => "Adicionado!V8", "values" => [[$anio]]];
        $data[] = ["range" => "Adicionado!E8", "values" => [[$ensayo->valor_esperado_adc]]];


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
                    array_push($arrayBk, $item["concentracion"]);
                    $dia = date("d", strtotime($item["created_at"]));
                    array_push($arrayBkF,  $dia);
                    array_push($arrayBkC, $item["consecutivo"]." / ".$item["users_id"]);
                }else{
                    //Aqui se llena el arreglo para los otros 31 registros
                    if($contBk<=62 && $contBk>31){
                        array_push($arrayBk2,  $item["concentracion"]);
                        $dia = date("d", strtotime($item["created_at"]));
                        array_push($arrayBk2F,  $dia);
                        array_push($arrayBk2C, $item["consecutivo"]." / ".$item["users_id"]);
                    }
                }
            } else {
                //Se valida si es patron
                if($item["tipo"]=="Patrón") {

                    $contStd++;
                    //Aqui se llena el arreglo para los primeros 31 registros
                    if($contStd<=31){
                        array_push($arrayStd, $item["concentracion"]);
                        $dia = date("d", strtotime($item["created_at"]));
                        array_push($arrayStdF,  $dia);
                        array_push($arrayStdC, $item["users_id"]);
                    }else{
                        //Aqui se llena el arreglo para los otros 31 registros
                        if($contStd<=62 && $contStd>31){
                            array_push($arrayStd2, $item["concentracion"]);
                            $dia = date("d", strtotime($item["created_at"]));
                            array_push($arrayStd2F,  $dia);
                            array_push($arrayStd2C, $item["users_id"]);
                        }
                    }
                } else {
                    if($item["tipo"]=="LCM") {

                        $contLcm++;
                        if($contLcm<=31){
                            array_push($arrayLcm,  $item["concentracion"]);
                            $dia = date("d", strtotime($item["created_at"]));
                            array_push($arrayLcmF,  $dia);
                            array_push($arrayLcmC, $item["users_id"]);
                        }else{
                            if($contLcm<=62 && $contLcm>31){
                                array_push($arrayLcm2, $item["concentracion"]);
                                $dia = date("d", strtotime($item["created_at"]));
                                array_push($arrayLcm2F,  $dia);
                                array_push($arrayLcm2C, $item["users_id"]);
                            }
                        }
                    }else{
                        if($item["tipo"]=="Duplicado") {

                            $contDpr++;
                            if($contDpr<=31){
                                if($item["dpr_pr"]){
                                    foreach ($item["dpr_pr"] as $value){

                                        if($value['tipo']=="Diferencia porcentual relativa"){
                                            array_push($arrayDprA, $value['add1']);
                                            array_push($arrayDprB, $value['add2']);
                                            $dia = date("d", strtotime($item["created_at"]));
                                            array_push($arrayDprF, $dia);
                                            array_push($arrayDprC, $item["consecutivo"]);
                                            array_push($arrayDprCA, $item["users_id"]);
                                        } else if($value['tipo']=="Porcentaje de recuperacion") {
                                            array_push($arrayAdd, $value["resultado"]);
                                            $dia = date("d", strtotime($item["created_at"]));
                                            array_push($arrayAddF,  $dia);
                                            array_push($arrayAddC, $item["users_id"]);
                                        }
                                    }
                                }
                            }else{
                                if($contDpr<=62 && $contDpr>31){
                                    if($item["dpr_pr"]){
                                        foreach ($item["dpr_pr"] as $value){
    
                                            if($value['tipo']=="Diferencia porcentual relativa"){
                                                array_push($arrayDprA2, $value['add1']);
                                                array_push($arrayDprB2, $value['add2']);
                                                $dia = date("d", strtotime($item["created_at"]));
                                                array_push($arrayDprF2, $dia);
                                                array_push($arrayDprC2, $item["consecutivo"]);
                                                array_push($arrayDprCA2, $item["users_id"]);
                                            } else if($value['tipo']=="Porcentaje de recuperacion") {
                                                array_push($arrayAdd2, $value["resultado"]);
                                                $dia = date("d", strtotime($item["created_at"]));
                                                array_push($arrayAddF2,  $dia);
                                                array_push($arrayAddC2, $item["users_id"]);
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
            $data[] = ["range" => "STD!B34", "values" => [$arrayStd]];
            $data[] = ["range" => "STD!B35", "values" => [$arrayStdF]];
            $data[] = ["range" => "STD!B36", "values" => [$arrayStdC]];

            $data[] = ["range" => "%S std 0,20!B34", "values" => [$arrayStd]];
            $data[] = ["range" => "%S std 0,20!B35", "values" => [$arrayStdF]];
            $data[] = ["range" => "%S std 0,20!B36", "values" => [$arrayStdC]];
        }

        if(count($arrayStd2)>0){
            $data[] = ["range" => "STD!B39", "values" => [$arrayStd2]];
            $data[] = ["range" => "STD!B40", "values" => [$arrayStd2F]];
            $data[] = ["range" => "STD!B41", "values" => [$arrayStd2C]];

            $data[] = ["range" => "%S std 0,20!B39", "values" => [$arrayStd2]];
            $data[] = ["range" => "%S std 0,20!B40", "values" => [$arrayStd2F]];
            $data[] = ["range" => "%S std 0,20!B41", "values" => [$arrayStd2C]];
        }

        if(count($arrayLcm)>0){
            $data[] = ["range" => "LCM!B34", "values" => [$arrayLcm]];
            $data[] = ["range" => "LCM!B35", "values" => [$arrayLcmF]];
            $data[] = ["range" => "LCM!B36", "values" => [$arrayLcmC]];
        }

        if(count($arrayLcm2)>0){
            $data[] = ["range" => "LCM!B39", "values" => [$arrayLcm2]];
            $data[] = ["range" => "LCM!B40", "values" => [$arrayLcm2F]];
            $data[] = ["range" => "LCM!B41", "values" => [$arrayLcm2C]];
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

        if(count($arrayAdd)>0){

            $data[] = ["range" => "Adicionado!B34", "values" => [$arrayAdd]];
            $data[] = ["range" => "Adicionado!B35", "values" => [$arrayAddF]];
            $data[] = ["range" => "Adicionado!B36", "values" => [$arrayAddC]];
        }

        if(count($arrayAdd2)>0){
            $data[] = ["range" => "Adicionado!B39", "values" => [$arrayAdd2]];
            $data[] = ["range" => "Adicionado!B40", "values" => [$arrayAdd2F]];
            $data[] = ["range" => "Adicionado!B41", "values" => [$arrayAdd2C]];
        }

        $fileIdCopy = $e->editFileExcelBatch("1P7RlWPzUxVg4qa4-w5Kn9DbKkOOXKxXu1b3coh8myLE", $data, true);

        $e->downloadFileGoogleDrive($fileIdCopy, "Ensayo Aluminio", "excel", true);
    }

}