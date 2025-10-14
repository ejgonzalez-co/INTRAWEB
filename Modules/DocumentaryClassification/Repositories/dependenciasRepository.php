<?php

namespace Modules\DocumentaryClassification\Repositories;

use Modules\DocumentaryClassification\Models\dependencias;
use App\Repositories\BaseRepository;

/**
 * Class dependenciasRepository
 * @package Modules\DocumentaryClassification\Repositories
 * @version March 31, 2023, 3:08 am -05
*/

class dependenciasRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_sede',
        'codigo',
        'nombre',
        'codigo_oficina_productora',
        'cf_user_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return dependencias::class;
    }
}
