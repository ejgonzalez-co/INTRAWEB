<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class UtilController {
    public static function convertXlsxToPdf(Spreadsheet $archiveXlsx){
        $writer = new Html($archiveXlsx);
        ob_start();
        $writer->save('php://output');
        $html = ob_get_clean();

        // Convertir el HTML a PDF
        $mpdf = new Mpdf();

        // Establece las contraseñas al momento de abrir el documento
        $mpdf->SetProtection(
            ['print', 'copy'],
            session("dni"),
            env("PDF_PROTECTION_PASS")
        );
        $mpdf->WriteHTML($html);
        $pdf = $mpdf->Output('', 'S');

        return $pdf;
    }

    public static function generateTemporaryFile($archiveContent, string $extension = ".pdf") : string{
        // Crea la carpeta temporal si no existe
        $temporaryFolderPath = storage_path('app/temp');
        if (!is_dir($temporaryFolderPath)) {
            mkdir($temporaryFolderPath, 0755, true);
        }

        // Generar un nombre único para el archivo
        $temporaryFileName = 'temp_' . uniqid() . $extension;
        
        // Guardar en el disco local
        Storage::disk('local')->put('temp/' . $temporaryFileName, $archiveContent);
        
        $temporaryFilePath = "temp/$temporaryFileName";

        return $temporaryFilePath;
    }
}
