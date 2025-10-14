<?php

namespace Modules\DocumentosElectronicos\Repositories;

use Modules\DocumentosElectronicos\Models\Documento;
use App\Repositories\BaseRepository;

class DocumentoRepository extends BaseRepository
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
        'observacion',
        'codigo_acceso_documento',
        'origen_documento',
        'vigencia',
        'users_id',
        'users_name',
        'de_tipos_documentos_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Documento::class;
    }
}
