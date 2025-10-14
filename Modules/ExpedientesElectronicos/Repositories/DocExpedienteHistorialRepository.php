<?php

namespace Modules\ExpedientesElectronicos\Repositories;

use Modules\ExpedientesElectronicos\Models\DocExpedienteHistorial;
use App\Repositories\BaseRepository;

class DocExpedienteHistorialRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ee_documentos_expediente_id',
        'users_id',
        'user_name',
        'nombre_expediente',
        'nombre_tipo_documental',
        'origen_creacion',
        'nombre_documento',
        'fecha_documento',
        'descripcion',
        'pagina_inicio',
        'pagina_fin',
        'adjunto',
        'modulo_intraweb',
        'consecutivo',
        'accion',
        'justificacion',
        'vigencia',
        'modulo_consecutivo',
        'hash_value',
        'hash_algoritmo'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return DocExpedienteHistorial::class;
    }
}
