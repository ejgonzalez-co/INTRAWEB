<?php

namespace Modules\DocumentosElectronicos\Repositories;

use Modules\DocumentosElectronicos\Models\DocumentoHistorial;
use App\Repositories\BaseRepository;

class DocumentoHistorialRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'consecutivo',
        'consecutivo_prefijo',
        'titulo_asunto',
        'plantilla',
        'documento_adjunto',
        'compartidos',
        'document_pdf',
        'estado',
        'subestado_documento',
        'adjuntos',
        'classification_production_office',
        'classification_serie',
        'classification_subserie',
        'documentos_asociados',
        'formato_publicacion',
        'hash_document_pdf',
        'validation_code',
        'elaboro_id',
        'reviso_id',
        'tipo_usuario',
        'correo_usuario',
        'elaboro_nombres',
        'reviso_nombres',
        'users_id_actual',
        'users_name_actual',
        'observacion',
        'observacion_historial',
        'codigo_acceso_documento',
        'vigencia',
        'users_id',
        'users_name',
        'de_tipos_documentos_id',
        'de_documento_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return DocumentoHistorial::class;
    }
}
