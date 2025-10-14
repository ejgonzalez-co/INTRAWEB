<?php

namespace Modules\ExpedientesElectronicos\Repositories;

use Modules\ExpedientesElectronicos\Models\DocumentosExpediente;
use App\Repositories\BaseRepository;

class DocumentosExpedienteRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ee_expediente_id',
        'users_id',
        'ee_tipos_documentales_id',
        'user_name',
        'nombre_expediente',
        'nombre_tipo_documental',
        'origen_creacion',
        'estado_doc',
        'nombre_documento',
        'fecha_documento',
        'descripcion',
        'pagina_inicio',
        'pagina_fin',
        'adjunto',
        'modulo_intraweb',
        'consecutivo',
        'hash_value',
        'hash_algoritmo'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return DocumentosExpediente::class;
    }
}
