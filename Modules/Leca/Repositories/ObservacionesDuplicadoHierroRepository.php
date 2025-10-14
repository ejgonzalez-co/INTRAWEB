<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoHierro;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoHierroRepository
 * @package Modules\Leca\Repositories
 * @version June 16, 2022, 11:22 am -05
*/

class ObservacionesDuplicadoHierroRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_ensayo_hierro_id',
        'users_id',
        'name_user',
        'observation'
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
        return ObservacionesDuplicadoHierro::class;
    }
}
