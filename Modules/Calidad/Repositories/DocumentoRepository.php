<?php

namespace Modules\Calidad\Repositories;

use Modules\Calidad\Models\Documento;
use App\Repositories\BaseRepository;

class DocumentoRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'titulo',
        'version',
        'estado',
        'orden',
        'consecutivo',
        'consecutivo_prefijo',
        'separador_consecutivo',
        'plantilla',
        'documento_adjunto',
        'document_pdf',
        'clase',
        'visibilidad_documento',
        'classification_production_office',
        'classification_serie',
        'classification_subserie',
        'formato_publicacion',
        'hash_document_pdf',
        'validation_code',
        'elaboro_id',
        'reviso_id',
        'aprobo_id',
        'publico_id',
        'elaboro_nombres',
        'reviso_nombres',
        'aprobo_nombres',
        'publico_nombres',
        'elaboro_cargos',
        'reviso_cargos',
        'aprobo_cargos',
        'publico_cargos',
        'fechas_elaboro',
        'fechas_reviso',
        'fechas_aprobo',
        'fecha_publico',
        'distribucion',
        'users_id_actual',
        'users_name_actual',
        'tipo_documento',
        'proceso',
        'codigo_formato',
        'observacion',
        'origen_documento',
        'formato_archivo',
        'documento_id_procedente',
        'vigencia',
        'users_id',
        'users_name',
        'calidad_documento_tipo_documento_id',
        'calidad_proceso_id',
        'calidad_documento_solicitud_documental_id',
        'calidad_tipo_sistema_id'
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
