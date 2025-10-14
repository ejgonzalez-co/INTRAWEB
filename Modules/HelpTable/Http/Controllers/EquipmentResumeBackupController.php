<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\HelpTable\RequestExport;
use Modules\HelpTable\Http\Requests\CreateEquipmentResumeBackupRequest;
use Modules\HelpTable\Http\Requests\UpdateEquipmentResumeBackupRequest;
use Modules\HelpTable\Repositories\EquipmentResumeBackupRepository;
use Modules\HelpTable\Models\EquipmentResume;
use Modules\HelpTable\Models\EquipmentResumeBackup;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Kleverman Salazar Florez. - Ene. 24 - 2023
 * @version 1.0.0
 */
class EquipmentResumeBackupController extends AppBaseController {

    /** @var  EquipmentResumeBackupRepository */
    private $equipmentResumeBackupRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     */
    public function __construct(EquipmentResumeBackupRepository $equipmentResumeBackupRepo) {
        $this->equipmentResumeBackupRepository = $equipmentResumeBackupRepo;
    }

    /**
     * Muestra la vista para el CRUD de EquipmentResumeBackup.
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $equipment_resume = EquipmentResume::select('id')->where('id',$request['equipment_resume_id'])->get()->first();
        return view('help_table::equipment_resume_backups.index')->with('equipment_resume_id',$equipment_resume['id']);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $equipment_resume_backups = EquipmentResumeBackup::where('ht_tic_equipment_resume_id',$request['equipment_resume_id'])->with(['EquipmentResume','EquipmentPurchaseDetailsBackups','OtherEquipmentBackups'])->latest()->get();
        return $this->sendResponse($equipment_resume_backups->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param CreateEquipmentResumeBackupRequest $request
     *
     * @return Response
     */
    public function store(CreateEquipmentResumeBackupRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $equipmentResumeBackup = $this->equipmentResumeBackupRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($equipmentResumeBackup->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeBackupController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeBackupController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateEquipmentResumeBackupRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEquipmentResumeBackupRequest $request) {

        $input = $request->all();

        /** @var EquipmentResumeBackup $equipmentResumeBackup */
        $equipmentResumeBackup = $this->equipmentResumeBackupRepository->find($id);

        if (empty($equipmentResumeBackup)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $equipmentResumeBackup = $this->equipmentResumeBackupRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($equipmentResumeBackup->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeBackupController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeBackupController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un EquipmentResumeBackup del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var EquipmentResumeBackup $equipmentResumeBackup */
        $equipmentResumeBackup = $this->equipmentResumeBackupRepository->find($id);

        if (empty($equipmentResumeBackup)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $equipmentResumeBackup->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeBackupController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeBackupController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('equipment_resume_backups').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $equipmentResume = EquipmentResume::where("id",$input["data"][0]["ht_tic_equipment_resume_id"])->get()->first();
            $equipmentResumeBackups = EquipmentResumeBackup::where("ht_tic_equipment_resume_id",$input["data"][0]["ht_tic_equipment_resume_id"])->get();
            $equipmentResumeBackups[count($equipmentResumeBackups) + 1] = $equipmentResume;
            return Excel::download(new RequestExport('help_table::equipment_resume_backups.exports.xlsx',$equipmentResumeBackups,'CR'), 'Listado de backups de la hoja de vida del equipo.xlsx');
        }
    }

    public function exportResumeHistoryInExcelFile(int $equipmentResumeHistoryId){
        $equipmentResumeHistory = EquipmentResumeBackup::where('id',$equipmentResumeHistoryId)->with(['EquipmentResume','EquipmentPurchaseDetailsBackups','OtherEquipmentBackups'])->get()->first();

        $fileType = 'Xlsx';
        $storagePath = storage_path('app/public/help_table/format_equipment_resume_history2.xlsx');
        $cellValue = 58;

        // Lee el archivo del storage enviado
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        $spreadsheet = $reader->load($storagePath);
        $spreadsheet->setActiveSheetIndex(0);

        // Inserta los datos en lo campos que no son incrementales
        $spreadsheet->getActiveSheet()->setCellValue('B7', $equipmentResumeHistory['domain_user']);
        $spreadsheet->getActiveSheet()->setCellValue('D7', $equipmentResumeHistory['officer']);
        $spreadsheet->getActiveSheet()->setCellValue('B8', $equipmentResumeHistory['contract_type']);
        $spreadsheet->getActiveSheet()->setCellValue('D8', $equipmentResumeHistory['charge']);
        $spreadsheet->getActiveSheet()->setCellValue('B9', $equipmentResumeHistory['dependece']);
        $spreadsheet->getActiveSheet()->setCellValue('D9', $equipmentResumeHistory['area']);
        $spreadsheet->getActiveSheet()->setCellValue('B10', $equipmentResumeHistory['site']);
        $spreadsheet->getActiveSheet()->setCellValue('D10', $equipmentResumeHistory['service_manager']);
        $spreadsheet->getActiveSheet()->setCellValue('B12', $equipmentResumeHistory['maintenance_type']);
        $spreadsheet->getActiveSheet()->setCellValue('D12', $equipmentResumeHistory['cycle']);
        $spreadsheet->getActiveSheet()->setCellValue('B13', $equipmentResumeHistory['contract_number']);
        $spreadsheet->getActiveSheet()->setCellValue('D13', $equipmentResumeHistory['contract_date']);
        $spreadsheet->getActiveSheet()->setCellValue('B14', $equipmentResumeHistory['maintenance_date']);
        $spreadsheet->getActiveSheet()->setCellValue('B15', $equipmentResumeHistory['provider']);
        $spreadsheet->getActiveSheet()->setCellValue('D15', $equipmentResumeHistory['contract_value']);
        $spreadsheet->getActiveSheet()->setCellValue('C17', $equipmentResumeHistory['has_internal_and_external_hardware_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('D17', $equipmentResumeHistory['observation_internal_and_external_hardware_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('C18', $equipmentResumeHistory['has_ram_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('D18', $equipmentResumeHistory['observation_ram_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('C19', $equipmentResumeHistory['has_board_memory_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('D19', $equipmentResumeHistory['observation_board_memory_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('C20', $equipmentResumeHistory['has_power_supply_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('D20', $equipmentResumeHistory['observation_power_supply_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('C21', $equipmentResumeHistory['has_dvd_drive_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('D21', $equipmentResumeHistory['observation_dvd_drive_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('C22', $equipmentResumeHistory['has_monitor_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('D22', $equipmentResumeHistory['observation_monitor_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('C23', $equipmentResumeHistory['has_keyboard_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('D23', $equipmentResumeHistory['observation_keyboard_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('C24', $equipmentResumeHistory['has_mouse_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('D24', $equipmentResumeHistory['observation_mouse_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('C25', $equipmentResumeHistory['has_thermal_paste_change']);
        $spreadsheet->getActiveSheet()->setCellValue('D25', $equipmentResumeHistory['observation_thermal_paste_change']);
        $spreadsheet->getActiveSheet()->setCellValue('C26', $equipmentResumeHistory['has_heatsink_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('D26', $equipmentResumeHistory['observation_heatsink_cleaning']);
        $spreadsheet->getActiveSheet()->setCellValue('D27', "REPORTE TECNICO: ".$equipmentResumeHistory['technical_report']);
        $spreadsheet->getActiveSheet()->setCellValue('D28', "OBSERVACIONES: ".$equipmentResumeHistory['observation']);
        $spreadsheet->getActiveSheet()->setCellValue('D30', $equipmentResumeHistory['asset_type']);
        $spreadsheet->getActiveSheet()->setCellValue('B31', $equipmentResumeHistory['tower_inventory_number']);
        $spreadsheet->getActiveSheet()->setCellValue('D31', $equipmentResumeHistory['tower']);
        $spreadsheet->getActiveSheet()->setCellValue('B32', $equipmentResumeHistory['tower_model']);
        $spreadsheet->getActiveSheet()->setCellValue('D32', $equipmentResumeHistory['tower_series']);
        $spreadsheet->getActiveSheet()->setCellValue('B33', $equipmentResumeHistory['tower_processor']);
        $spreadsheet->getActiveSheet()->setCellValue('D33', $equipmentResumeHistory['tower_host']);
        $spreadsheet->getActiveSheet()->setCellValue('B34', $equipmentResumeHistory['tower_ram_gb']);
        $spreadsheet->getActiveSheet()->setCellValue('D34', $equipmentResumeHistory['tower_ram_gb_mark']);
        $spreadsheet->getActiveSheet()->setCellValue('B35', $equipmentResumeHistory['tower_number_ram_modules']);
        $spreadsheet->getActiveSheet()->setCellValue('D35', $equipmentResumeHistory['tower_mac_address']);
        $spreadsheet->getActiveSheet()->setCellValue('B36', $equipmentResumeHistory['tower_mainboard']);
        $spreadsheet->getActiveSheet()->setCellValue('D36', $equipmentResumeHistory['tower_mainboard_mark']);
        $spreadsheet->getActiveSheet()->setCellValue('B37', $equipmentResumeHistory['tower_ipv4_address']);
        $spreadsheet->getActiveSheet()->setCellValue('D37', $equipmentResumeHistory['tower_ipv6_address']);
        $spreadsheet->getActiveSheet()->setCellValue('B38', $equipmentResumeHistory['tower_ddh_capacity_gb']);
        $spreadsheet->getActiveSheet()->setCellValue('D38', $equipmentResumeHistory['tower_ddh_capacity_gb_mark']);
        $spreadsheet->getActiveSheet()->setCellValue('B39', $equipmentResumeHistory['tower_ssd_capacity_gb']);
        $spreadsheet->getActiveSheet()->setCellValue('D39', $equipmentResumeHistory['tower_ssd_capacity_gb_mark']);
        $spreadsheet->getActiveSheet()->setCellValue('B40', $equipmentResumeHistory['tower_video_card']);
        $spreadsheet->getActiveSheet()->setCellValue('D40', $equipmentResumeHistory['tower_video_card_mark']);
        $spreadsheet->getActiveSheet()->setCellValue('B41', $equipmentResumeHistory['tower_sound_card']);
        $spreadsheet->getActiveSheet()->setCellValue('D41', $equipmentResumeHistory['tower_sound_card_mark']);
        $spreadsheet->getActiveSheet()->setCellValue('B42', $equipmentResumeHistory['tower_network_card']);
        $spreadsheet->getActiveSheet()->setCellValue('D42', $equipmentResumeHistory['tower_network_card_mark']);
        $spreadsheet->getActiveSheet()->setCellValue('B43', $equipmentResumeHistory['faceplate']);
        $spreadsheet->getActiveSheet()->setCellValue('D43', $equipmentResumeHistory['faceplate_patch_panel']);
        $spreadsheet->getActiveSheet()->setCellValue('B44', $equipmentResumeHistory['tower_value']);
        $spreadsheet->getActiveSheet()->setCellValue('D44', $equipmentResumeHistory['tower_contract_number']);
        $spreadsheet->getActiveSheet()->setCellValue('B46', $equipmentResumeHistory['monitor_number_inventory']);
        $spreadsheet->getActiveSheet()->setCellValue('D46', $equipmentResumeHistory['monitor']);
        $spreadsheet->getActiveSheet()->setCellValue('B47', $equipmentResumeHistory['monitor_model']);
        $spreadsheet->getActiveSheet()->setCellValue('D47', $equipmentResumeHistory['monitor_serial']);
        $spreadsheet->getActiveSheet()->setCellValue('B48', $equipmentResumeHistory['monitor_value']);
        $spreadsheet->getActiveSheet()->setCellValue('D48', $equipmentResumeHistory['monitor_contract_number']);
        $spreadsheet->getActiveSheet()->setCellValue('B50', $equipmentResumeHistory['keyboard_number_inventory']);
        $spreadsheet->getActiveSheet()->setCellValue('D50', $equipmentResumeHistory['keyboard']);
        $spreadsheet->getActiveSheet()->setCellValue('B51', $equipmentResumeHistory['keyboard_model']);
        $spreadsheet->getActiveSheet()->setCellValue('D51', $equipmentResumeHistory['keyboard_serial']);
        $spreadsheet->getActiveSheet()->setCellValue('B52', $equipmentResumeHistory['keyboard_value']);
        $spreadsheet->getActiveSheet()->setCellValue('D52', $equipmentResumeHistory['keyboard_contract_number']);
        $spreadsheet->getActiveSheet()->setCellValue('B54', $equipmentResumeHistory['mouse_number_inventory']);
        $spreadsheet->getActiveSheet()->setCellValue('D54', $equipmentResumeHistory['mouse']);
        $spreadsheet->getActiveSheet()->setCellValue('B55', $equipmentResumeHistory['mouse_model']);
        $spreadsheet->getActiveSheet()->setCellValue('D55', $equipmentResumeHistory['mouse_serial']);
        $spreadsheet->getActiveSheet()->setCellValue('B56', $equipmentResumeHistory['mouse_value']);
        $spreadsheet->getActiveSheet()->setCellValue('D56', $equipmentResumeHistory['mouse_contract_number']);

        foreach ($equipmentResumeHistory['OtherEquipmentBackups'] as $otherEquipment) {
            $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue, "No. INVENTARIO");
            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $otherEquipment['inventory_number']);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue, "MARCA");
            $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $otherEquipment['mark']);
            $cellValue++;
            $spreadsheet->getActiveSheet()->insertNewRowBefore($cellValue);

            $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue, "MODELO");
            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $otherEquipment['model']);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue, "SERIAL");
            $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $otherEquipment['serial']);
            $cellValue++;
            $spreadsheet->getActiveSheet()->insertNewRowBefore($cellValue);


            $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue, "VALOR DISPOSITIVO");
            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $otherEquipment['monitor_value']);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue, "No. CONTRATO");
            $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $otherEquipment['contract_number']);
            $cellValue++;
        }
        $cellValue++;
        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $equipmentResumeHistory['operating_system']);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $equipmentResumeHistory['operating_system_version']);
        $cellValue++;
        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $equipmentResumeHistory['operating_system_license']);
        $cellValue++;
        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $equipmentResumeHistory['office_automation']);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $equipmentResumeHistory['office_automation_version']);
        $cellValue++;
        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $equipmentResumeHistory['office_automation_license']);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $equipmentResumeHistory['antivirus']);
        $cellValue++;
        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $equipmentResumeHistory['installed_product']);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $equipmentResumeHistory['installed_product_version']);
        $cellValue++;
        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $equipmentResumeHistory['browser']);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $equipmentResumeHistory['browser_version']);
        $cellValue++;
        $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $equipmentResumeHistory['teamviewer']);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $equipmentResumeHistory['other']);

        $cellValue = $cellValue+2;

        foreach ($equipmentResumeHistory['EquipmentPurchaseDetailsBackups'] as $equipmentPurchaseDetail) {
            $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue, "No. CONTRATO");
            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $equipmentPurchaseDetail['contract_number']);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue, "FECHA");
            $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $equipmentPurchaseDetail['date']);
            $cellValue++;

            $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue, "PROVEEDOR");
            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $equipmentPurchaseDetail['provider']);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue, "GARANTIA AÑOS");
            $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $equipmentPurchaseDetail['warranty_in_years']);
            $cellValue++;

            $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue, "VALOR TOTAL CONTRATO");
            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $equipmentPurchaseDetail['contract_total_value']);
            $cellValue++;
            $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue, "FECHA TERMINACION GARANTIA");
            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $equipmentPurchaseDetail['warranty_termination_date']);
            $cellValue++;
        }

        // Configuraciones de los encabezados del archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="HOJA DE VIDA PARA LA MESA DE AYUDA EPA.xlsx"');
        header('Cache-Control: max-age=0');

        // Exportacion del archivo
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $fileType);
        $writer->save('php://output');
        exit;

        return $this->sendResponse($writer, trans('msg_success_update'));


    }
}
