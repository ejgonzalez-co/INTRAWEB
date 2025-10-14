<?php

namespace Modules\DocumentosElectronicos\Repositories;

use Modules\DocumentosElectronicos\Models\TipoDocumento;
use App\Repositories\BaseRepository;

class TipoDocumentoRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre',
        'prefijo',
        'version',
        'codigo_formato',
        'formato_consecutivo',
        'separador_consecutivo',
        'prefijo_incrementan_consecutivo',
        'variables_plantilla',
        'variables_plantilla_requeridas',
        'permiso_crear_documentos_todas',
        'permiso_consultar_documentos_todas',
        'plantilla',
        'estado',
        'sub_estados',
        'sub_estados_requerido',
        'vigencia',
        'users_id',
        'es_borrable',
        'es_editable'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TipoDocumento::class;
    }
}
