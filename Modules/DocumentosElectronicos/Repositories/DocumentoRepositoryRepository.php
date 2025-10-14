<?php

namespace Modules\DocumentosElectronicos\Repositories;

use Modules\DocumentosElectronicos\Models\DocumentoRepository;
use App\Repositories\BaseRepository;

class DocumentoRepositoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'consecutivo',
        'titulo_asunto',
        'plantilla',
        'destinatarios',
        'copias',
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
        'elaboro_nombres',
        'reviso_nombres',
        'users_id_actual',
        'users_name_actual',
        'observacion',
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
        return DocumentoRepository::class;
    }
}
