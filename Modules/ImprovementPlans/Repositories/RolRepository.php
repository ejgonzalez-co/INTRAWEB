<?php

namespace Modules\ImprovementPlans\Repositories;

use Modules\ImprovementPlans\Models\Rol;
use App\Repositories\BaseRepository;

/**
 * Class RolRepository
 * @package Modules\ImprovementPlans\Repositories
 * @version August 23, 2023, 4:22 pm -05
*/

class RolRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'guard_name',
        'description'
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
        return Rol::class;
    }
}
