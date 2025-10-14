<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoCloruro;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoCloruroRepository
 * @package Modules\Leca\Repositories
 * @version July 29, 2022, 10:46 am -05
*/

class ObservacionesDuplicadoCloruroRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_cloruro_id'
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
        return ObservacionesDuplicadoCloruro::class;
    }
}
