<?php

namespace Modules\Calidad\Repositories;

use Modules\Calidad\Models\DocumentoSolicitudDocumental;
use App\Repositories\BaseRepository;

class DocumentoSolicitudDocumentalRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre_solicitante',
        'cargo',
        'tipo_solicitud',
        'tipo_documento',
        'codigo',
        'nombre_documento',
        'version',
        'justificacion_solicitud',
        'adjunto',
        'funcionario_responsable',
        'cargo_responsable',
        'estado',
        'observaciones',
        'users_id_solicitante',
        'users_id_responsable',
        'calidad_macroproceso_id',
        'calidad_proceso_id',
        'calidad_documento_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return DocumentoSolicitudDocumental::class;
    }
}
