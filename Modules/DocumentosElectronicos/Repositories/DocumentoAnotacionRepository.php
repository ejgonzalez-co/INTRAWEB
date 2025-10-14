<?php

namespace Modules\DocumentosElectronicos\Repositories;

use Modules\DocumentosElectronicos\Models\DocumentoAnotacion;
use App\Repositories\BaseRepository;

class DocumentoAnotacionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre_usuario',
        'contenido',
        'adjuntos',
        'leido_por',
        'de_documento_id',
        'users_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return DocumentoAnotacion::class;
    }
}
