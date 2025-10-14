<?php

namespace Modules\Configuracion\Repositories;

use Modules\Configuracion\Models\ConfigurationGeneral;
use App\Repositories\BaseRepository;

class ConfigurationGeneralRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'logo',
        'color_barra',
        'color_modal',
        'imagen_fondo',
        'nombre_entidad'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigurationGeneral::class;
    }
}
