<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\State;
use App\Models\City;
use App\User;
use Modules\Intranet\Models\Citizen;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;
use Illuminate\Support\Facades\Response;
use DB;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
class UtilController extends AppBaseController {

    /**
     * Obtiene todos los elementos existentes por pais
     *
     * @author Carlos Moises Garcia T. - Jun. 08 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getStatesByCountry($countryID) {
        $states = State::where('country_id', $countryID)->orderBy("name")->get();
        return $this->sendResponse($states->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes por estado
     *
     * @author Carlos Moises Garcia T. - Jun. 08 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCitiesByState($stateID) {
        $cities = City::where('state_id', $stateID)->get();
        return $this->sendResponse($cities->toArray(), trans('data_obtained_successfully'));
    }

    public function cancelSubscriptionView(){
        return view('cancel_subscription', [
            'appName' => env('APP_NAME'),
        ]);
    }

    public function cancelSubscriptionProcess(Request $request)
    {
        try {
            $input = $request->toArray();

            $email = decrypt($input['email']);

            $user = User::where('email', $email)->update([
                'sendEmail' => 0
            ]);

            return response()->json(['success' => true, 'message' => 'Email notifications have been cancelled.']);

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Controllers', 'app\Http\Controllers\UtilController - Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Controllers', 'app\Http\Controllers\UtilController - Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    public function trackMail(Request $request) {

        $input = $request->toArray();
        $ip = $this->detectIP();

        // Se realiza conteo de notificación
        $existeRegistro = NotificacionesMailIntraweb::where('id_mail', $input['c'])->exists();

        if ($existeRegistro) {
            // Primero verificamos si el registro tiene estado_notificacion igual a 'Entregado'
            $esEntregado = NotificacionesMailIntraweb::where('id_mail', $input['c'])
                            ->where('estado_notificacion', 'Entregado')
                            ->exists();
            
            if ($esEntregado) {
                // Obtenemos el valor actual de intento
                $intento = NotificacionesMailIntraweb::where('id_mail', $input['c'])->pluck('intento')->first();
                
                // Incrementamos el valor de intento
                NotificacionesMailIntraweb::where('id_mail', $input['c'])->update([
                    'intento' => $intento + 1
                ]);

                // Una vez se envía se actualiza el campo nuevamente
                NotificacionesMailIntraweb::where('id_mail', $input['c'])
                    ->where('intento', $intento + 1) // Usamos el nuevo valor
                    ->update([
                        'leido' => 'Si',
                        'fecha_hora_leido' => date('Y-m-d H:i:s'),
                        'ip_leido' => $ip,
                        'agente_navegador' => 'Google'
                    ]);
            } else {
                $this->generateSevenLog('UtilController', 'No se actualizó el registro porque estado_notificacion no es Entregado: ' . $input['c']);
            }
        } else {
            $this->generateSevenLog('UtilController', 'Error al actualizar el ingreso del correo desde el pixel de seguimiento: ' . $input['c']);
        }

        $this->generateSevenLog('SnSController', 'app\Http\Controllers\SnSController - . JsonData: ' . $input['c']);
        // Crear una imagen en gris de 1x1 píxeles
        $image = imagecreatetruecolor(1, 1);
        $gray = imagecolorallocate($image, 192, 192, 192); // Color gris
        imagefill($image, 0, 0, $gray);

        // Devolver la imagen como respuesta
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();

        // Liberar memoria
        imagedestroy($image);

        // Devolver la imagen con cabeceras de tipo 'image/png'
        return Response::make($imageData, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);

    }

     /**
     * Genera un reporte en formato Excel basado en los datos de proveedores obtenidos
     * desde una base de datos Joomla, dependiendo del tipo de contrato especificado.
     *
     * @param string $tc Tipo de contrato para filtrar los datos. Puede ser:
     *                   - 'Contrato de prestacion de servicios'
     *                   - Otro tipo de contrato
     *
     * @return void Este método genera y descarga un archivo Excel como respuesta HTTP.
     *
     * Funcionalidad:
     * - Carga una plantilla de Excel específica según el tipo de contrato.
     * - Obtiene los datos de la base de datos Joomla relacionados con el tipo de contrato.
     * - Llena la plantilla de Excel con los datos obtenidos.
     * - Calcula promedios de diferentes métricas y los inserta en celdas específicas.
     * - Aplica estilos a las celdas para mejorar la presentación.
     * - Genera y envía el archivo Excel como respuesta para su descarga.
     *
     * Notas:
     * - Utiliza la biblioteca PhpSpreadsheet para manipular archivos Excel.
     * - La conexión a la base de datos Joomla debe estar configurada previamente.
     * - Asegúrese de que las plantillas de Excel existan en las rutas especificadas.
     * - Este método termina la ejecución del script con `exit` después de enviar el archivo.
     */
    public function reportExcelProovedoresJoomla($tc) {

        $fileType = 'Xlsx';

        if($tc == 'Contrato de prestacion de servicios') {
            $storagePath = public_path('assets/formatos/reporte-provedores-1.xlsx');

        }else{
            $storagePath = public_path('assets/formatos/reporte-provedores-2.xlsx');
        }
        $cellValue = 6;


        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        $reader->setIncludeCharts(true);
        $spreadsheet = $reader->load($storagePath);
        $spreadsheet->setActiveSheetIndex(0);

        $querys = DB::connection('joomla')->table('epaprov_contrato')->where('tipo_contrato',$tc)->get()->toArray();

        $spreadsheet->getActiveSheet()->setCellValue('A1', "Calificación de ". $tc);

        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 24
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $letraContador = 'C';
        $contadorHorizontal = 1;

        if ($tc == 'Contrato de prestacion de servicios') {
            $calidadTotal = 0;
            $cumplimientoTotal = 0;
            $atencionClienteTotal = 0;
            $seguridadTotal = 0;
            $gestionAmbientalTotal = 0;

            foreach ($querys as $key => $value) {

                $celdasContador = $letraContador . '5';
                $celdasContadorConsecutivo = $letraContador . '6';


                $spreadsheet->getActiveSheet()->setCellValue($celdasContador, $contadorHorizontal);
                $spreadsheet->getActiveSheet()->setCellValue($celdasContadorConsecutivo, $value->contrato);

                $spreadsheet->getActiveSheet()->setCellValue($letraContador.'8', $value->calidad_total.'%');
                $spreadsheet->getActiveSheet()->setCellValue($letraContador.'9', $value->cumplimiento_total.'%');
                $spreadsheet->getActiveSheet()->setCellValue($letraContador.'10', $value->atencion_cliente_total.'%');
                $spreadsheet->getActiveSheet()->setCellValue($letraContador.'11', $value->seguridad_total.'%');
                $spreadsheet->getActiveSheet()->setCellValue($letraContador.'12', $value->gestion_ambiental_total.'%');

                $calidadTotal += round($value->calidad_total, 2);
                $cumplimientoTotal += round($value->cumplimiento_total, 2);
                $atencionClienteTotal += round($value->atencion_cliente_total, 2);
                $seguridadTotal += round($value->seguridad_total, 2);
                $gestionAmbientalTotal += round($value->gestion_ambiental_total, 2);


                $spreadsheet->getActiveSheet()->getStyle($celdasContador)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $spreadsheet->getActiveSheet()->getStyle($celdasContadorConsecutivo)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $letraContador++;
                $contadorHorizontal++;
            }

            $contadorHorizontal--;


            $spreadsheet->getActiveSheet()->setCellValue('B15', ($calidadTotal / $contadorHorizontal) / 100);
            $spreadsheet->getActiveSheet()->getStyle('B15')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

            $spreadsheet->getActiveSheet()->setCellValue('B16', ($cumplimientoTotal / $contadorHorizontal) / 100);
            $spreadsheet->getActiveSheet()->getStyle('B16')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

            $spreadsheet->getActiveSheet()->setCellValue('B17', ($atencionClienteTotal / $contadorHorizontal) / 100);
            $spreadsheet->getActiveSheet()->getStyle('B17')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

            $spreadsheet->getActiveSheet()->setCellValue('B18', ($seguridadTotal / $contadorHorizontal) / 100);
            $spreadsheet->getActiveSheet()->getStyle('B18')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

            $spreadsheet->getActiveSheet()->setCellValue('B19', ($gestionAmbientalTotal / $contadorHorizontal) / 100);
            $spreadsheet->getActiveSheet()->getStyle('B19')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);


        }else{

            $calidadTotal = 0;
            $cumplimientoTotal = 0;
            $atencionClienteTotal = 0;
            $precioTotal = 0;

            foreach ($querys as $key => $value) {

                $celdasContador = $letraContador . '5';
                $celdasContadorConsecutivo = $letraContador . '6';


                $spreadsheet->getActiveSheet()->setCellValue($celdasContador, $contadorHorizontal);
                $spreadsheet->getActiveSheet()->setCellValue($celdasContadorConsecutivo, $value->contrato);

                $spreadsheet->getActiveSheet()->setCellValue($letraContador.'8', $value->calidad_2_total.'%');
                $spreadsheet->getActiveSheet()->setCellValue($letraContador.'9', $value->cumplimiento_2_total.'%');
                $spreadsheet->getActiveSheet()->setCellValue($letraContador.'10', $value->atencion_cliente_2_total.'%');
                $spreadsheet->getActiveSheet()->setCellValue($letraContador.'11', $value->precio_2_total.'%');

                $calidadTotal += $value->calidad_2_total;
                $cumplimientoTotal += $value->cumplimiento_2_total;
                $atencionClienteTotal += $value->atencion_cliente_2_total;
                $precioTotal += $value->precio_2_total;


                $spreadsheet->getActiveSheet()->getStyle($celdasContador)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $spreadsheet->getActiveSheet()->getStyle($celdasContadorConsecutivo)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $letraContador++;
                $contadorHorizontal++;
            }
            $contadorHorizontal--;


            $spreadsheet->getActiveSheet()->setCellValue('B14', ($calidadTotal / $contadorHorizontal) / 100);
            $spreadsheet->getActiveSheet()->getStyle('B14')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
            $spreadsheet->getActiveSheet()->setCellValue('B15', ($cumplimientoTotal / $contadorHorizontal) / 100);
            $spreadsheet->getActiveSheet()->getStyle('B15')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
            $spreadsheet->getActiveSheet()->setCellValue('B16', ($atencionClienteTotal / $contadorHorizontal) / 100);
            $spreadsheet->getActiveSheet()->getStyle('B16')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
            $spreadsheet->getActiveSheet()->setCellValue('B17', ($precioTotal / $contadorHorizontal) / 100);
            $spreadsheet->getActiveSheet()->getStyle('B17')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);


        }


                // Configuraciones de los encabezados del archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Excel inventario documental digital'.'.xlsx"');
        header('Cache-Control: max-age=0');

        // Exportacion del archivo
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $fileType);
        $writer->setIncludeCharts(true);
        $writer->save('php://output');
        exit;

        return 'excel generado';

    }

}
