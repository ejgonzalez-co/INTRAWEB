<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\Dependency;
use App\Repositories\BaseRepository;

/**
 * Class DependencyRepository
 * @package Modules\Intranet\Repositories
 * @version June 17, 2020, 4:30 pm UTC
*/

class DependencyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_sede',
        'codigo',
        'nombre',
        'codigo_oficina_productora'
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
        return Dependency::class;
    }
}
