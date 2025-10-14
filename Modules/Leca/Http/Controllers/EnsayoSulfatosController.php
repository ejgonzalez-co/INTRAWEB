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
use Modules\Leca\Models\DprprSulfato;
use Modules\Leca\Models\Ensayo;
use Modules\Leca\Models\Patron;
use Modules\Leca\Models\Blancos;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Leca\Models\Aluminio;
use Modules\Leca\Models\Fluoruros;
use Modules\Leca\Models\Sulfatos;
use Modules\Leca\Models\ListTrials;
use Modules\Leca\Models\SampleTaking;
use App\Http\Controllers\GoogleController;
use Modules\Leca\Models\BlancoGeneral;
use Modules\Leca\Models\PatronGeneral;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\EnsayoAluminio;
use Modules\Leca\Models\EnsayoSulfatos;
use Modules\Leca\Models\TendidoMuestras;
use Modules\Leca\Models\CriticalEquipment;
use Modules\Leca\Models\HistorySampleTaking;

class EnsayoSulfatosController extends AppBaseController
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

        return view('leca::EnsayoSulfatos.index');

    }

    public function getDatosBlanco()
    {

        $datosBlanco = Sulfatos::orderBy('id', 'desc')->first();

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
     * @author Manuel Marín Londoño. - Feb. 02 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getAllSulfatos()
    {
        if (!Auth::user()->hasRole('Administrador Leca')) {
            $user = Auth::user();

            $allEnsayos = EnsayoSulfatos::with("lcDprPrSulfatos")->where('users_id', $user->id)->latest()->get();
        } else {
            $allEnsayos = EnsayoSulfatos::with("lcDprPrSulfatos")->latest()->get();
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

        $blanco = EnsayoSulfatos::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('aprobacion_usuario', 'Si')->get()->last();

        if ($blanco) {

            $patron = EnsayoSulfatos::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('aprobacion_usuario', 'Si')->get()->last();

            if ($patron) {

                $arrayTotal['ensayo'] = EnsayoSulfatos::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

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

                    return view('leca::EnsayoSulfatos.ejecutar');

                } else {

                    $arrayTotal['blanco'] = false;
                    $arrayTotal['patron'] = false;
                    $arrayTotal['ensayo'] = 0;

                    return view('leca::EnsayoSulfatos.index');

                }

            } else {

                $arrayTotal['blanco'] = true;
                $arrayTotal['patron'] = false;

                return view('leca::EnsayoSulfatos.index');

            }

        } else {

            $arrayTotal['blanco'] = false;
            $arrayTotal['patron'] = false;

            return view('leca::EnsayoSulfatos.index');
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

        $ensayo = Ensayo::where('lc_list_trials_id', 9)->where('estado', 'Pendiente')->get();

        foreach ($ensayo as $keya => $item) {

            array_push($arrayTercero, $item->lc_sample_taking_id);

        }

        $sample_takings = SampleTaking::with(['lcListTrials'])->where('estado_analisis', '!=', '')->where('estado_analisis', '!=', 'Análisis finalizado')->whereIn('id', $arrayTercero)->get();

        for ($i = 0; $i < count($sample_takings); $i++) {
            foreach ($sample_takings[$i]->lcListTrials as $keya => $item) {
                if ($item->id == 9) {
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

        $muestras = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 9)->where('estado', 'Activo')->get();

        if (count($muestras) > 0) {

            foreach ($muestras as $key => $value) {

                $value->estado = "Inactivo";

                $value->save();

            }
        }

        foreach ($request->toArray() as $key => $value) {

            $tendido = new TendidoMuestras();
            $tendido->users_id = $user->id;
            $tendido->lc_list_trials_id = 9;
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
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 9)->where('estado', 'Activo')->get();

        foreach ($tendido as $key => $value) {
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
        $tendido = TendidoMuestras::where('users_id', $user->id)->where('lc_list_trials_id', 9)->whereDate('fecha_finalizado', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Finalizado')->get();

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
        $blancoDecimales = Blancos::where('lc_list_trials_id', 9)->get()->last();
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
        $patronDecimales = Patron::where('lc_list_trials_id', 9)->get()->last();

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
        $ensayoDecimales = Sulfatos::where('lc_list_trials_id', 9)->get()->last();
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

        $dpr = new DprprSulfato();
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
        $dpr->lc_ensayo_sulfatos_id  = $request['id_ensayo'];
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
        $dpr = DprprSulfato::where('id_ensayo', $id)->where('tipo', 'Diferencia porcentual relativa')->get();

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
        $inputFileName = storage_path('app/public/leca/excel/Sulfatos.xlsx');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);

        $date = strtotime($input[0]['created_at']);

        $datosPrimarios = Sulfatos::whereDate('created_at', '<=', $input[0]['created_at'])->get()->last();

        $anio = date("y", $date);
        $mes = date("m", $date);

        $spreadsheet->getActiveSheet()->setCellValue('A11', 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('J11', 'Y='.$datosPrimarios->pendiente_uno.'X='.$datosPrimarios->intercepto_uno ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('L11', 'Y='.$datosPrimarios->pendiente_dos.'X='.$datosPrimarios->intercepto_dos ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('N11', 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('E7', $datosPrimarios->proceso ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('G16', $datosPrimarios->documento_referencia ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('G17', $datosPrimarios->nombre_patron ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('G18', $datosPrimarios->valor_esperado ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('M8', $anio ?? 'N/A');
        $spreadsheet->getActiveSheet()->setCellValue('N8', $mes ?? 'N/A');

        $criticalEquip = CriticalEquipment::where('lc_list_trials_id', 9)->latest()->take(4)->get();

        $letra = "G";
        for ($i = 0; $i < count($criticalEquip); $i++) {

            $spreadsheet->getActiveSheet()->setCellValue($letra . '13', $criticalEquip[$i]->equipo_critico ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue($letra . '14', $criticalEquip[$i]->identificacion ?? 'N/A');
            $letra++;
            $letra++;
        }

        $cont = 0;
        $numTotal = 23 + count($input);
        $totalCeldas = count($input);
        if ($totalCeldas == 1) {

        } else {

            $spreadsheet->getActiveSheet()->insertNewRowBefore(24, $totalCeldas - 1);

        }

        $arraydpr = array();
        for ($i = 23; $i < $numTotal; $i++) {

            $spreadsheet->getActiveSheet()->mergeCells('C' . $i . ':F' . $i);

            $date = strtotime($input[$cont]['created_at']);

            $dia = date("d", $date);
            $hora = date("H", $date);
            $minutos = date("i", $date);
            $segundos = date("s", $date);

            $user = User::find($input[$cont]['users_id']);

            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, $dia ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('B' . $i, $hora . ':' . $minutos . ':' . $segundos ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('C' . $i, $input[$cont]['consecutivo'] ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('G' . $i, $input[$cont]['volumen_muestra'] ?? 'N/A');
            if($input[$cont]['buffer'] == "A"){
                $spreadsheet->getActiveSheet()->setCellValue('H' . $i, 'X');
            } else {
                $spreadsheet->getActiveSheet()->setCellValue('I' . $i, 'X');
            }
            $spreadsheet->getActiveSheet()->setCellValue('J' . $i, $input[$cont]['formula'] ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('K' . $i, $input[$cont]['valor_turb_uno'] ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('L' . $i, $input[$cont]['valor_turb_dos'] ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('M' . $i, $input[$cont]['sulfato_resultado_a'] ?? 'N/A');



            // if ($input[$cont]['estado'] == 'No') {

            //     $spreadsheet
            //         ->getActiveSheet()
            //         ->getStyle('J' . $i)
            //         ->getFill()
            //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            //         ->getStartColor()
            //         ->setARGB('dbafaf');

            // } else {
            //     if ($input[$cont]['estado'] == 'Si') {
            //         $spreadsheet
            //             ->getActiveSheet()
            //             ->getStyle('J' . $i)
            //             ->getFill()
            //             ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            //             ->getStartColor()
            //             ->setARGB('b1dbad');

            //     } else {
            //         $spreadsheet
            //             ->getActiveSheet()
            //             ->getStyle('J' . $i)
            //             ->getFill()
            //             ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            //             ->getStartColor()
            //             ->setARGB('ffffff');

            //     }
            // }

            // if ($input[$cont]['tipo'] == 'Duplicado') {

            //     $dprpr = DprprColor::where('lc_ensayo_color_id', $input[$cont]['id'])->get();

            //     if (count($dprpr) > 0) {

            //         foreach ($dprpr as $key => $value) {
            //             # code...
            //             array_push($arraydpr, $value);

            //         }

            //     }

            // }

            if ($user->url_digital_signature) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setPath(storage_path('app/public' . '/' . $user->url_digital_signature)); /* put your path and image here */
                $drawing->setCoordinates('N' . $i);
                $drawing->setWorksheet($spreadsheet->getActiveSheet());
                $drawing->setHeight(36);
                $drawing->setResizeProportional(true);
                $drawing->setOffsetX(2); // this is how
                $drawing->setOffsetY(2);
            }

            // $spreadsheet->getActiveSheet()->addImage('L'.$i, asset('storage').'/'. $user->url_digital_signature);
            $spreadsheet->getActiveSheet()->setCellValue('N' . $i, $user->name ?? 'N/A');
            $cont++;
        }

        // if (count($arraydpr) > 0) {

        //     $contPorcentaje = 0;
        //     $contDpr = 0;

        //     $arraydpr = array_reverse($arraydpr);
        //     $letraP = "O";
        //     $letraD = "J";
        //     $letraP = "P";
        //     for ($u = 0; $u < count($arraydpr); $u++) {
        //         $var1 = explode(":", $arraydpr[$u]->created_at);
        //         $hora = $var1[2].":".$var1[1];

        //         if ($arraydpr[$u]->tipo == 'Diferencia porcentual relativa' && $contDpr < 2) {
        //             $spreadsheet->getActiveSheet()->setCellValue('D26', $arraydpr[$u]->consecutivo);
        //             $spreadsheet->getActiveSheet()->setCellValue($letraD . '26', $arraydpr[$u]->resultado . '%');
        //             $spreadsheet->getActiveSheet()->setCellValue($letraD . '25', ($arraydpr[$u]->add1 + $arraydpr[$u]->add2)/2);
        //             $spreadsheet->getActiveSheet()->setCellValue($letraP . '26', $dia);
        //             $spreadsheet->getActiveSheet()->setCellValue($letraP . '28', $hora);
        //             // $letraD++;
        //         }
        //         // else {
        //         //     if ($arraydpr[$u]->tipo == 'Porcentaje de recuperacion' && $contPorcentaje < 4) {
        //         //         $spreadsheet->getActiveSheet()->setCellValue($letraP . '18', $arraydpr[$u]->resultado . '%');
        //         //         $spreadsheet->getActiveSheet()->setCellValue($letraP . '23', $dia);
        //         //         $letraP++;
        //         //     }
        //         // }

        //     }

        // }

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

        $ensayo = EnsayoSulfatos::where('id', $dprs)->first();
        if($ensayo->tipo != "Duplicado" && $ensayo->tipo != "Ensayo"){
            $ensayo = EnsayoSulfatos::where('tipo', "Duplicado")->latest()->get();
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

        $ensayo = EnsayoSulfatos::find($request['id']);

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

        $blanco = EnsayoSulfatos::where('users_id', $user->id)->where('tipo', 'Blanco')->where('estado', 'Si')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where(function ($query) {
            $query->where('aprobacion_usuario', 'Pendiente')->orwhere('aprobacion_usuario', 'Si');
        })->get()->last();

        if ($blanco) {

            if ($blanco->aprobacion_usuario == 'Si') {

                $patron = EnsayoSulfatos::where('users_id', $user->id)->where('tipo', 'Patrón')->where('estado', 'Si')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where(function ($query) {
                    $query->where('aprobacion_usuario', 'Pendiente')->orwhere('aprobacion_usuario', 'Si');
                })->get()->last();
            } else {
                $patron = null;
            }

            if ($patron != null) {

                if ($patron->aprobacion_usuario == 'Si') {
                    $arrayTotal['ensayo'] = EnsayoSulfatos::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();
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
     * @param EnsayoSulfatos $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $Year = date("Y");
        $aluminioGeneral = Sulfatos::orderBy('id', 'desc')->first();

        if ($request['tipo'] == 'Blanco') {

            $datosBlanco = Blancos::where('lc_list_trials_id', 9)->get()->last();
            $result1 = floatval($datosBlanco->lcm);
            $result2 = floatval($request['concentracion']);

            if ($result1 > $result2) {

                $user = Auth::user();
                $cantidadBlancos = EnsayoSulfatos::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Blanco')->count();
                $ensayo = new EnsayoSulfatos();
                $ensayo->volumen_muestra = $request['volumen_muestra'];
                $ensayo->buffer = $request['buffer'];
                $ensayo->formula = $request['formula'];
                if($request['sulfato_resultado_a'] == null){
                    $resultadoFinal = $request['sulfato_resultado_b'];
                } else {
                    $resultadoFinal = $request['sulfato_resultado_a'];
                }
                $ensayo->sulfato_resultado_a = $resultadoFinal;
                $ensayo->valor_turb_uno = $request['valor_turb_uno'];
                $ensayo->valor_turb_dos = $request['valor_turb_dos'];
                $ensayo->estado = 'Si';
                $ensayo->id_carta = $datosBlanco->id;
                $ensayo->aprobacion_usuario = 'Pendiente';
                $ensayo->vigencia = $Year;
                $ensayo->lc_sulfatos_id  = $aluminioGeneral->id;
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
                $cantidadBlancos = EnsayoSulfatos::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Blanco')->count();
                $ensayo = new EnsayoSulfatos();
                $ensayo->volumen_muestra = $request['volumen_muestra'];
                $ensayo->buffer = $request['buffer'];
                $ensayo->formula = $request['formula'];
                if($request['sulfato_resultado_a'] == null){
                    $resultadoFinal = $request['sulfato_resultado_b'];
                } else {
                    $resultadoFinal = $request['sulfato_resultado_a'];
                }
                $ensayo->sulfato_resultado_a = $resultadoFinal;
                $ensayo->valor_turb_uno = $request['valor_turb_uno'];
                $ensayo->valor_turb_dos = $request['valor_turb_dos'];
                $ensayo->estado = 'No';
                $ensayo->aprobacion_usuario = 'No';
                $ensayo->vigencia = $Year;
                $ensayo->id_carta = $datosBlanco->id;
                $ensayo->lc_sulfatos_id  = $aluminioGeneral->id;
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

                $datosPatron = Patron::where('lc_list_trials_id', 9)->get()->last();
                $result1 = floatval($datosPatron->lcs);
                $result2 = floatval($datosPatron->lci);

                if ($result1 > $result2) {

                    $user = Auth::user();
                        $cantidadPatrones = EnsayoSulfatos::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();

                    $ensayo = new EnsayoSulfatos();
                    $ensayo->volumen_muestra = $request['volumen_muestra'];
                    $ensayo->buffer = $request['buffer'];
                    $ensayo->formula = $request['formula'];
                    if($request['sulfato_resultado_a'] == null){
                        $resultadoFinal = $request['sulfato_resultado_b'];
                    } else {
                        $resultadoFinal = $request['sulfato_resultado_a'];
                    }
                    $ensayo->sulfato_resultado_a = $resultadoFinal;
                    $ensayo->valor_turb_uno = $request['valor_turb_uno'];
                    $ensayo->valor_turb_dos = $request['valor_turb_dos'];
                    $ensayo->id_carta = $datosPatron->id;
                    $ensayo->estado = 'Si';
                    $ensayo->aprobacion_usuario = 'Pendiente';
                    $ensayo->vigencia = $Year;
                    $ensayo->lc_sulfatos_id = $aluminioGeneral->id;
                    $ensayo->id_muestra = '';

                    if ($cantidadPatrones == 0) {
                            $ensayo->consecutivo = 'STD' . '-' . $request['concentracion'];
                    } else {
                        $ensayo->consecutivo = 'STD' . $cantidadPatrones . '-' . $request['concentracion'];
                    }

                    $ensayo->users_id = Auth::user()->id;
                    $ensayo->user_name = Auth::user()->name;
                    $ensayo->tipo = $request['tipo'];

                    $ensayo->save();

                    return $this->sendResponse($ensayo->toArray(), trans('data_obtained_successfully'));

                } else {

                    $user = Auth::user();

                    $cantidadPatrones = EnsayoSulfatos::where('users_id', $user->id)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('tipo', 'Patrón')->count();


                    $ensayo = new EnsayoSulfatos();
                    $ensayo->volumen_muestra = $request['volumen_muestra'];
                    $ensayo->buffer = $request['buffer'];
                    $ensayo->formula = $request['formula'];
                    if($request['sulfato_resultado_a'] == null){
                        $resultadoFinal = $request['sulfato_resultado_b'];
                    } else {
                        $resultadoFinal = $request['sulfato_resultado_a'];
                    }
                    $ensayo->sulfato_resultado_a = $resultadoFinal;
                    $ensayo->valor_turb_uno = $request['valor_turb_uno'];
                    $ensayo->valor_turb_dos = $request['valor_turb_dos'];
                    $ensayo->estado = 'No';
                    $ensayo->aprobacion_usuario = 'No';
                    $ensayo->vigencia = $Year;
                    $ensayo->id_carta = $datosPatron->id;
                    $ensayo->lc_sulfatos_id = $aluminioGeneral->id;
                    $ensayo->id_muestra = '';

                    if ($cantidadPatrones == 0) {
                        $ensayo->consecutivo = 'STD' . '-' . $request['concentracion'];
                } else {
                    $ensayo->consecutivo = 'STD' . $cantidadPatrones . '-' . $request['concentracion'];
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

                    $blanco = EnsayoSulfatos::where('users_id', $user->id)->where('tipo', 'Blanco')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->where('estado', 'Si')->get()->last();

                    if ($blanco) {

                        $patron = EnsayoSulfatos::where('users_id', $user->id)->where('tipo', 'Patrón')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $blanco->created_at)->where('estado', 'Si')->get()->last();

                        if ($patron) {

                            $arrayTotal['ensayo'] = EnsayoSulfatos::where('users_id', $user->id)->where('tipo', 'Ensayo')->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->whereTime('created_at', '>=', $patron->created_at)->get()->count();

                            $blancoGeneral = BlancoGeneral::get()->last();
                            $patronGeneral = PatronGeneral::get()->last();

                            if ($arrayTotal['ensayo'] < $blancoGeneral->periodicidad && $arrayTotal['ensayo'] < $patronGeneral->periodicidad) {

                                $centinela = false;
                                $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->where('lc_list_trials_id', "!=", 9)->get();
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
                                    $history->observation = "Se realizo el analisis de la fórmula de sulfatos";

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
                                    $history->observation = "Se realizo el analisis de la fórmula de sulfatos";

                                    $history->lc_sample_taking_id = $request['idMuestra']['id'];

                                    $history->save();
                                    $muestraActualizar[0]->estado_analisis = 'Análisis en ejecución';
                                    $muestraActualizar[0]->save();
                                }
                                $verifica = Ensayo::where('lc_sample_taking_id', $request['idMuestra']['id'])->where('lc_list_trials_id', 9)->get();
                                $verifica[0]->estado = 'Ejecutado';
                                $verifica[0]->save();

                                $buscaEnsayo = Ensayo::where('lc_list_trials_id', 9)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();
                                $año = date('Y');
                                $array = str_split($año);

                                $ensayo = new EnsayoSulfatos();
                                $ensayo->volumen_muestra = $request['volumen_muestra'];
                                $ensayo->buffer = $request['buffer'];
                                $ensayo->formula = $request['formula'];
                                if($request['sulfato_resultado_a'] == null){
                                    $resultadoFinal = $request['sulfato_resultado_b'];
                                } else {
                                    $resultadoFinal = $request['sulfato_resultado_a'];
                                }
                                $ensayo->sulfato_resultado_a = $resultadoFinal;
                                $ensayo->valor_turb_uno = $request['valor_turb_uno'];
                                $ensayo->valor_turb_dos = $request['valor_turb_dos'];
                                $ensayo->estado = 'No aplica';
                                $ensayo->lc_sulfatos_id  = $aluminioGeneral->id;
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

                        $buscaEnsayo = Ensayo::where('lc_list_trials_id', 9)->where('lc_sample_taking_id', $request['idMuestra']['id'])->first();
                        if($request['sulfato_resultado_a'] == null){
                            $resultadoFinal = $request['sulfato_resultado_b'];
                        } else {
                            $resultadoFinal = $request['sulfato_resultado_a'];
                        }

                        $total_promedio = ($request['idMuestra']['sulfato_resultado_a'] + $resultadoFinal)/2;

                        $año = date('Y');
                        $array = str_split($año);
                        $ensayo = new EnsayoSulfatos();
                        $ensayo->resultado_promedio = $total_promedio;
                        $ensayo->volumen_muestra = $request['volumen_muestra'];
                        $ensayo->buffer = $request['buffer'];
                        $ensayo->formula = $request['formula'];
                        $ensayo->sulfato_resultado_a = $resultadoFinal;
                        $ensayo->valor_turb_uno = $request['valor_turb_uno'];
                        $ensayo->valor_turb_dos = $request['valor_turb_dos'];
                        $ensayo->lc_sulfatos_id  = $aluminioGeneral->id;
                        $ensayo->estado = 'No aplica';
                        $ensayo->vigencia = $Year;
                        $ensayo->duplicado = $request['idMuestra']['duplicado'];
                        $ensayo->id_muestra = $request['idMuestra']['id_muestra'];
                        $ensayo->consecutivo_ensayo = $request['idMuestra']['consecutivo'];
                        $muestraActualizar = SampleTaking::where('id', $request['idMuestra']['id_muestra'])->get();
                        // $ensayo->lc_sample_taking_has_lc_list_trials_id = $request['idMuestra']['id'];

                        if ($request['metodo'] == "Duplicado") {
                            $duplicado = EnsayoSulfatos::where('users_id', $user->id)->where('tipo', 'Duplicado')->where('id_muestra', $request['idMuestra']['id_muestra'])->get()->count();
                            if ($duplicado >= 1) {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D' . $duplicado;
                            } else {
                                $ensayo->consecutivo = $muestraActualizar[0]->sample_reception_code . '-D';
                            }
                            $ensayo->tipo = $request['tipo'];
                            $ensayo->metodo = "Duplicado";

                        }

                        $ensayo->users_id = Auth::user()->id;
                        $ensayo->user_name = Auth::user()->name;
                        $ensayo->save();

                        $history = new HistorySampleTaking();
                        $history->action = "Ejecuto duplicado";
                        $history->users_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->consecutivo = $ensayo->consecutivo;
                        $history->observation = "Se realizo duplicado a la muestra de sulfatos";
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
     * @param EnsayoSulfatos $request
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

        //Contadores
        $contBk=0;
        $contStd=0;
        $contLcm=0;
        $contDpr=0;

        //Este es el titular del ensayo
        $data[] = ["range" => "BK!B7", "values" => [["Sulfatos"]]];
        $data[] = ["range" => "STD!E7", "values" => [["Sulfatos"]]];
        $data[] = ["range" => "% S std!E7", "values" => [["Sulfatos"]]];
        $data[] = ["range" => "LCM!E7", "values" => [["Sulfatos"]]];
        $data[] = ["range" => "%SLCM!E7", "values" => [["Sulfatos"]]];
        $data[] = ["range" => "DPR!B7", "values" => [["Sulfatos"]]];

        //Se valida la fecha de expedicion
        $fechaActual = date('d-m-Y');
        $anio = date("y", strtotime($fechaActual));
        $mes = date("m", strtotime($fechaActual));

        $blanco = Blancos::where('lc_list_trials_id', 9)->get()->last();

        $data[] = ["range" => "BK!V8", "values" => [[$mes]]];
        $data[] = ["range" => "BK!V7", "values" => [[$anio]]];
        $data[] = ["range" => "BK!B37", "values" => [[$blanco->ldm]]];
        $data[] = ["range" => "BK!B38", "values" => [[$blanco->lcm]]];


        $ensayo = Sulfatos::orderBy('id', 'desc')->first();

        $data[] = ["range" => "STD!P8", "values" => [[$mes]]];
        $data[] = ["range" => "STD!V8", "values" => [[$anio]]];
        $data[] = ["range" => "STD!E8", "values" => [[$ensayo->valor_esperado]]];

        $data[] = ["range" => "% S std!P8", "values" => [[$mes]]];
        $data[] = ["range" => "% S std!V8", "values" => [[$anio]]];
        $data[] = ["range" => "% S std!E8", "values" => [[$ensayo->valor_esperado]]];
        
        
        $patron = Patron::where('lc_list_trials_id', 9)->get()->last();
        //se validan los datos de la carta anterior
        $data[] = ["range" => "STD!AJ14", "values" => [[$patron->n_anterior]]];
        $data[] = ["range" => "STD!AJ15", "values" => [[$patron->x_anterior]]];
        $data[] = ["range" => "STD!AJ16", "values" => [[$patron->s_anterior]]];

        $data[] = ["range" => "% S std!AJ14", "values" => [[$patron->n_ant_porc_std]]];
        $data[] = ["range" => "% S std!AJ15", "values" => [[$patron->x_ant_porc_std]]];
        $data[] = ["range" => "% S std!AJ16", "values" => [[$patron->s_ant_porc_std]]];
        $data[] = ["range" => "% S std!AI17", "values" => [[$patron->lcs_porc_std]]];
        $data[] = ["range" => "% S std!AI18", "values" => [[$patron->lci_porc_std]]];
        $data[] = ["range" => "% S std!AI19", "values" => [[$patron->las_porc_std]]];
        $data[] = ["range" => "% S std!AI20", "values" => [[$patron->lai_porc_std]]];
        $data[] = ["range" => "% S std!AI21", "values" => [[$patron->x_mas_porc_std]]];
        $data[] = ["range" => "% S std!AI22", "values" => [[$patron->x_menos_porc_std]]];


        $data[] = ["range" => "LCM!P8", "values" => [[$mes]]];
        $data[] = ["range" => "LCM!V8", "values" => [[$anio]]];
        $data[] = ["range" => "LCM!E8", "values" => [[$ensayo->valor_esperado_lcm]]];

        $data[] = ["range" => "%SLCM!P8", "values" => [[$mes]]];
        $data[] = ["range" => "%SLCM!V8", "values" => [[$anio]]];
        $data[] = ["range" => "%SLCM!E8", "values" => [[$ensayo->valor_esperado_lcm]]];

        
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

        $data[] = ["range" => "%SLCM!AJ14", "values" => [[$patron->n_anterior_lcm]]];
        $data[] = ["range" => "%SLCM!AJ15", "values" => [[$patron->x_anterior_lcm]]];
        $data[] = ["range" => "%SLCM!AJ16", "values" => [[$patron->s_anterior_lcm]]];
        $data[] = ["range" => "%SLCM!AI17", "values" => [[$patron->lcs_porc_lcm]]];
        $data[] = ["range" => "%SLCM!AI18", "values" => [[$patron->lci_porc_lcm]]];
        $data[] = ["range" => "%SLCM!AI19", "values" => [[$patron->las_porc_lcm]]];
        $data[] = ["range" => "%SLCM!AI20", "values" => [[$patron->lai_porc_lcm]]];
        $data[] = ["range" => "%SLCM!AI21", "values" => [[$patron->x_mas_porc_lcm]]];
        $data[] = ["range" => "%SLCM!AI22", "values" => [[$patron->x_menos_porc_lcm]]];

        
        $data[] = ["range" => "DPR!V8", "values" => [[$mes]]];
        $data[] = ["range" => "DPR!V7", "values" => [[$anio]]];
        $data[] = ["range" => "DPR!AH14", "values" => [[$patron->n_ant_dpr]]];
        $data[] = ["range" => "DPR!AH15", "values" => [[$patron->x_ant_dpr]]];
        $data[] = ["range" => "DPR!AH16", "values" => [[$patron->s_ant_dpr]]];

        //Aqui se recorre la data que llega desde la vista de cada ensayo
        foreach ($request->data as $keya => $item){ 
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
            }else{
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
                }else{
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
                                if($item["lc_dpr_pr_sulfatos"]){
                                    foreach ($item["lc_dpr_pr_sulfatos"] as $value){

                                        if($value['tipo']=="Diferencia porcentual relativa"){
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
                                    if($item["lc_dpr_pr_sulfatos"]){
                                        foreach ($item["lc_dpr_pr_sulfatos"] as $value){
    
                                            if($value['tipo']=="Diferencia porcentual relativa"){
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
            $data[] = ["range" => "STD!B34", "values" => [$arrayStd]];
            $data[] = ["range" => "STD!B35", "values" => [$arrayStdF]];
            $data[] = ["range" => "STD!B36", "values" => [$arrayStdC]];

            $data[] = ["range" => "% S std!B34", "values" => [$arrayStd]];
            $data[] = ["range" => "% S std!B35", "values" => [$arrayStdF]];
            $data[] = ["range" => "% S std!B36", "values" => [$arrayStdC]];
        }

        if(count($arrayStd2)>0){
            $data[] = ["range" => "STD!B39", "values" => [$arrayStd2]];
            $data[] = ["range" => "STD!B40", "values" => [$arrayStd2F]];
            $data[] = ["range" => "STD!B41", "values" => [$arrayStd2C]];

            $data[] = ["range" => "% S std!B39", "values" => [$arrayStd2]];
            $data[] = ["range" => "% S std!B40", "values" => [$arrayStd2F]];
            $data[] = ["range" => "% S std!B41", "values" => [$arrayStd2C]];
        }

        if(count($arrayLcm)>0){
            $data[] = ["range" => "LCM!B34", "values" => [$arrayLcm]];
            $data[] = ["range" => "LCM!B35", "values" => [$arrayLcmF]];
            $data[] = ["range" => "LCM!B36", "values" => [$arrayLcmC]];

            $data[] = ["range" => "%SLCM!B34", "values" => [$arrayLcm]];
            $data[] = ["range" => "%SLCM!B35", "values" => [$arrayLcmF]];
            $data[] = ["range" => "%SLCM!B36", "values" => [$arrayLcmC]];
        }

        if(count($arrayLcm2)>0){
            $data[] = ["range" => "LCM!B39", "values" => [$arrayLcm2]];
            $data[] = ["range" => "LCM!B40", "values" => [$arrayLcm2F]];
            $data[] = ["range" => "LCM!B41", "values" => [$arrayLcm2C]];

            $data[] = ["range" => "%SLCM!B39", "values" => [$arrayLcm2]];
            $data[] = ["range" => "%SLCM!B40", "values" => [$arrayLcm2F]];
            $data[] = ["range" => "%SLCM!B41", "values" => [$arrayLcm2C]];
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

        $fileIdCopy = $e->editFileExcelBatch("1PHY-UVWJB0IpbeyAq_K2DgvClLXCkutkmaTrdO9Gm-Q", $data, true);

        $e->downloadFileGoogleDrive($fileIdCopy, "Ensayo Sulfatos", "excel", true);
    }

    public function getDatosFormulas()
    {

        $datosFormulas =Sulfatos::orderBy('id', 'desc')->first();

        return $this->sendResponse($datosFormulas->toArray(), trans('data_obtained_successfully'));
    }

}