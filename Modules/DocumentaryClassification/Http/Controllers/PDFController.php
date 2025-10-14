<?php

namespace Modules\DocumentaryClassification\Http\Controllers;

use App\Exports\documentary\GenericExport;
use Modules\DocumentaryClassification\Http\Requests\CreatedependenciasRequest;
use Modules\DocumentaryClassification\Http\Requests\UpdatedependenciasRequest;
use Modules\DocumentaryClassification\Repositories\dependenciasRepository;
use Modules\DocumentaryClassification\Models\dependenciasSerieSubseries;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
use Modules\DocumentaryClassification\Models\documentarySerieSubseries;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\documentary\RequestExport;
use Modules\DocumentaryClassification\Models\dependencias;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\DocumentaryClassification\Models\inventoryDocuments;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class PDFController extends AppBaseController {

    public function showPdf($id)
    {
        $document = inventoryDocuments::find($id);
        $metadata = $document->metadata; // Obtén los metadatos si existe una relación
    
        // dd($document->url_document_digital);
        return view('documentaryclassification::pdf.show', compact('document', 'metadata'));
    }
    

    public function getPdf($id)
    {
        $document = inventoryDocuments::find($id);
        $metadata = $document->metadata; // Suponiendo que tengas una relación definida en el modelo

        return view('documentaryclassification::pdf.show', compact('document', 'metadata'));
    }

    public function updateMetadata(Request $request, $id)
    {
        $document = inventoryDocuments::find($id);
        $metadata = $document->metadata; // Suponiendo que tengas una relación definida en el modelo

        $metadataData = $request->all(); // Obtiene los datos del formulario

        // Actualiza los metadatos del documento
        $metadata->update($metadataData);

        // Actualiza los metadatos en el PDF
        $pdf = PDF::loadView('pdf.pdf-view', compact('document', 'metadata'));
        $pdf->setOptions([
            'title' => $metadata->title,
            'author' => $metadata->author,
            'subject' => $metadata->subject,
            // Otros metadatos
        ]);

        // Guarda el PDF actualizado en el servidor o en otro destino, según tus necesidades
        $pdf->save('ruta/del/pdf/' . $document->filename);

        return redirect()->route('get-pdf', $id)
            ->with('success', 'Metadatos actualizados con éxito');
    }

}