<?php

namespace Modules\DocumentosElectronicos\Repositories;

use Modules\DocumentosElectronicos\Models\DocumentoDestinatario;
use App\Repositories\BaseRepository;

class DocumentoDestinatarioRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'tipo',
        'nombre',
        'users_id',
        'de_documentos_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return DocumentoDestinatario::class;
    }
}
