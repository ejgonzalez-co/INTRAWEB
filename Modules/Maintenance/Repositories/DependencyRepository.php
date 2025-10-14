<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\Dependency;
use App\Repositories\BaseRepository;

/**
 * Class DependencyRepository
 * @package Modules\Maintenance\Repositories
 * @version August 27, 2021, 3:45 pm -05
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
        return Dependency::class;
    }
}
