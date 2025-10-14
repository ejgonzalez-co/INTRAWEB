<?php

namespace Modules\Calidad\Repositories;

use Modules\Calidad\Models\DocumentoTipoDocumento;
use App\Repositories\BaseRepository;

class DocumentoTipoDocumentoRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre',
        'prefijo',
        'estado',
        'orden',
        'calidad_tipo_sistema_id',
        'users_id',
        'usuario_creador'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return DocumentoTipoDocumento::class;
    }
}
